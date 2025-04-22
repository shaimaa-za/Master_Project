import faiss
import os
os.environ["KMP_DUPLICATE_LIB_OK"] = "TRUE"
import torch
import torchvision.transforms as transforms
import torchvision.models as models
import numpy as np
from flask import Flask, request, jsonify
from PIL import Image

# إعداد Flask
app = Flask(__name__)

# إعداد الجهاز
device = torch.device("cuda" if torch.cuda.is_available() else "cpu")

# تحميل ResNet-50 المدرب مسبقًا
model = models.resnet50()
num_features = model.fc.in_features
model.fc = torch.nn.Sequential(
    torch.nn.Linear(num_features, 512),
    torch.nn.ReLU(),
    torch.nn.Dropout(0.5),
    torch.nn.Linear(512, 4),  # النموذج المدرب كان يستخدم 4 فئات
    torch.nn.LogSoftmax(dim=1)
)
model.load_state_dict(torch.load("resnet50_jewelry.pth", map_location=device))
model.to(device)
model.eval()

# تحويل الصورة إلى تنسيق مناسب للنموذج
transform = transforms.Compose([
    transforms.Resize((224, 224)),
    transforms.ToTensor(),
    transforms.Normalize(mean=[0.485, 0.456, 0.406], std=[0.229, 0.224, 0.225]),
])

# إعداد FAISS
feature_dimension = 512  # نحتاج لفضاء ميزات بُعده 512
faiss_index_path = "faiss_resnet50.bin"
image_id_file = "image_id.txt"

# تحميل أو إنشاء FAISS Index
if os.path.exists(faiss_index_path):
    faiss_index = faiss.read_index(faiss_index_path)
    print("FAISS Index loaded from file.")
else:
    faiss_index = faiss.IndexIDMap(faiss.IndexFlatL2(feature_dimension))
    print("New FAISS Index created.")

# تحميل معرف الصور من الملف
if os.path.exists(image_id_file):
    with open(image_id_file, "r") as f:
        image_id_counter = int(f.read().strip())
else:
    image_id_counter = 0

# قائمة تخزين معرفات الصور
image_ids = [{"id": i, "path": f"uploaded_{i}.jpg"} for i in range(image_id_counter)]

# دالة لاستخراج الميزات من صورة باستخدام ResNet-50 (باستخدام المخرجات من الطبقة المخفية)
def extract_features(image_path):
    image = Image.open(image_path).convert("RGB")
    image = transform(image).unsqueeze(0).to(device)
    with torch.no_grad():
        # المرور عبر الشبكة حتى الطبقة قبل التصنيف:
        # الحصول على المخرجات من جميع الطبقات حتى avgpool
        x = model.conv1(image)
        x = model.bn1(x)
        x = model.relu(x)
        x = model.maxpool(x)
        x = model.layer1(x)
        x = model.layer2(x)
        x = model.layer3(x)
        x = model.layer4(x)
        x = model.avgpool(x)
        x = torch.flatten(x, 1)  # الشكل: (1, num_features) حيث num_features عادةً 2048
        
        # تمرير x عبر أول 3 طبقات من model.fc لإنتاج تمثيل 512 بُعد
        features = model.fc[:3](x)  # الطبقات 0: Linear, 1: ReLU, 2: Dropout
    features = features.cpu().numpy().astype('float32').reshape(1, -1)
    print(f"Extracted features shape: {features.shape}")  # يجب أن يكون (1, 512)
    return features
UPLOAD_FOLDER = r"C:\xampp\htdocs\app\storage\app\public\uploaded_img"
os.makedirs(UPLOAD_FOLDER, exist_ok=True)  # إنشاء المجلد إذا لم يكن موجودًا
# دالة تحميل الصورة وإضافتها إلى FAISS
@app.route('/upload', methods=['POST'])
def upload_image():
    global image_id_counter, faiss_index
    """
     # تفريغ FAISS بشكل دائم عند رفع كل صورة
    faiss_index.reset()
    if os.path.exists(faiss_index_path):
        os.remove(faiss_index_path)
    image_ids = []
    image_id_counter = 0
    with open(image_id_file, "w") as f:
        f.write(str(image_id_counter))
    print("FAISS تم تفريغه وإعادة تعيينه.")
    """
    file = request.files['image']
    image_path = os.path.join(UPLOAD_FOLDER, f"uploaded_{image_id_counter}.jpg")
    file.save(image_path)
    
    try:
        # استخراج الميزات لاستخدامها مع FAISS
        features = extract_features(image_path)
        
        # استخدام الصورة الأصلية للتصنيف عبر تمريرها عبر النموذج الكامل
        from PIL import Image
        image = Image.open(image_path).convert("RGB")
        img_tensor = transform(image).unsqueeze(0).to(device)
        with torch.no_grad():
            output = model(img_tensor)
            predicted_class = torch.argmax(output, dim=1).item()
        class_mapping = {0: "Bracelet", 1: "Earring", 2: "Necklace", 3: "Ring"}
        predicted_label = class_mapping[predicted_class]
        
    except Exception as e:
        return jsonify({"message": str(e)}), 400
    
    # إضافة الصورة إلى FAISS
    image_id = image_id_counter
    faiss_index.add_with_ids(features, np.array([image_id], dtype=np.int64))
    image_ids.append({"id": image_id, "path": image_path})
    image_id_counter += 1
    
    with open(image_id_file, "w") as f:
        f.write(str(image_id_counter))
    
    faiss.write_index(faiss_index, faiss_index_path)
    
    return jsonify({"message": "Image saved successfully!", "image_id": image_id, "product_type": predicted_label})

# دالة البحث عن الصور المشابهة
@app.route('/search', methods=['POST'])
def search_images():
    if faiss_index.ntotal == 0:
        return jsonify({"message": "There is no data in FAISS! Please add some images first."}), 400
    file = request.files['image']
    query_image_path = "query_image.jpg"
    file.save(query_image_path)
    try:
        query_features = extract_features(query_image_path)
    except Exception as e:
        return jsonify({"message": str(e)}), 400
    num_images = faiss_index.ntotal
    distances, indices = faiss_index.search(query_features, k=min(10, num_images))
    similarity_threshold = 13.5  # يمكن تعديله حسب دقة النتائج
    results = []
    for i, index in enumerate(indices[0]):
        if index != -1 and distances[0][i] < similarity_threshold:
            matched_image = next((img for img in image_ids if img["id"] == index), None)
            if matched_image:
                results.append({
                    "image_id": int(index),
                    "image_path": matched_image["path"],
                    "distance": float(distances[0][i])
                })
    if not results:
        return jsonify({"message": "Not enough similar images were found."})
    return jsonify({"message": "Search results", "results": results})

if __name__ == '__main__':
    app.run(debug=True)
