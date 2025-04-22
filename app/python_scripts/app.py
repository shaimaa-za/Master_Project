import faiss
import os
os.environ["KMP_DUPLICATE_LIB_OK"] = "TRUE"
import torch
import torchvision.models as models
import torchvision.transforms as transforms
from flask import Flask, request, jsonify
import cv2
import numpy as np

# إعداد Flask
app = Flask(__name__)

# تحميل النموذج
model = models.mobilenet_v2(weights="DEFAULT")
model.eval()

# تحويل الصورة إلى التنسيق المناسب للنموذج
transform = transforms.Compose([
    transforms.ToPILImage(),
    transforms.Resize((224, 224)),
    transforms.ToTensor(),
    transforms.Normalize(mean=[0.485, 0.456, 0.406], std=[0.229, 0.224, 0.225]),
])

feature_dimension = 1280  # عدد الأبعاد المستخرجة من MobileNetV2

# مسارات الملفات
faiss_index_path = "faiss_index.bin"
image_id_file = "image_id.txt"

# تحميل معرف الصور من الملف أو البدء من الصفر
if os.path.exists(image_id_file):
    with open(image_id_file, "r") as f:
        image_id_counter = int(f.read().strip())
else:
    image_id_counter = 0

# تحميل أو إنشاء FAISS Index
if os.path.exists(faiss_index_path):
    faiss_index = faiss.read_index(faiss_index_path)
    print("✅ FAISS Index loaded from file.")
else:
    faiss_index = faiss.IndexIDMap(faiss.IndexFlatL2(feature_dimension))
    print("📢 New FAISS Index created.")

# قائمة لتخزين معرفات الصور
image_ids = [{"id": i, "path": f"uploaded_{i}.jpg"} for i in range(image_id_counter)]

# دالة تطبيع الميزات باستخدام Min-Max
def normalize_features(features):
    min_val = np.min(features)
    max_val = np.max(features)
    return (features - min_val) / (max_val - min_val)  # تطبيع باستخدام Min-Max

# دالة لاستخراج الميزات من الصورة
def extract_features(image_path):
    print(f"Loading image from: {image_path}")
    
    # تحميل الصورة باستخدام OpenCV
    image = cv2.imread(image_path)
    if image is None:
        raise ValueError(f"❌ Cannot load image: {image_path}")

    # تحويل الصورة من BGR إلى RGB
    image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    image = transform(image).unsqueeze(0)  # تحويل الصورة إلى Tensor مناسب للنموذج

    with torch.no_grad():
        features = model.features(image)
        features = torch.nn.functional.adaptive_avg_pool2d(features, (1, 1))  # استخدام التكييف المتوسط
        features = features.view(-1).numpy()  # تحويل الميزات إلى مصفوفة 1D

        # تطبيع الميزات باستخدام Min-Max Normalization
        features = normalize_features(features)

    return features.astype('float32')

# دالة لتحميل الصورة إلى FAISS
@app.route('/upload', methods=['POST'])
def upload_image():
    global image_id_counter
    global faiss_index

    file = request.files['image']
    image_path = f"uploaded_{image_id_counter}.jpg"
    file.save(image_path)

    try:
        features = extract_features(image_path)
    except ValueError as e:
        return jsonify({"message": str(e)}), 400

    # إضافة الميزات إلى FAISS مع معرف الصورة
    image_id = image_id_counter
    faiss_index.add_with_ids(np.array([features]), np.array([image_id], dtype=np.int64))
    image_ids.append({"id": image_id, "path": image_path})

    # تحديث معرف الصورة
    image_id_counter += 1

    # حفظ معرف الصورة في الملف حتى لا يتم فقدانه عند إعادة التشغيل
    with open(image_id_file, "w") as f:
        f.write(str(image_id_counter))

    # حفظ FAISS Index بعد الإضافة
    faiss.write_index(faiss_index, faiss_index_path)

    return jsonify({
        "message": "✅ تم حفظ الصورة وإضافتها إلى FAISS!",
        "image_id": image_id
    })

# دالة البحث عن الصور المشابهة جدًا فقط
@app.route('/search', methods=['POST'])
def search_images():
    if faiss_index.ntotal == 0:
        return jsonify({"message": "❌ No data in FAISS! Please add some images first."}), 400

    file = request.files['image']
    query_image_path = "query_image.jpg"
    file.save(query_image_path)

    try:
        query_features = extract_features(query_image_path)
    except ValueError as e:
        return jsonify({"message": str(e)}), 400

    # البحث عن الصور في FAISS
    num_images = faiss_index.ntotal  # عدد الصور المخزنة
    distances, indices = faiss_index.search(np.array([query_features]), k=num_images)  # البحث في جميع الصور
    print(f"🔍 Total images stored in FAISS: {num_images}")

    # حد أدنى للتشابه (تحديد المسافة القصوى المسموح بها)
    similarity_threshold = 2.15 

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
                
    print(f"🔎 Total similar products found: {len(results)}")

    if not results:
        return jsonify({"message": "❌ لم يتم العثور على صور مشابهة كفاية."})

    return jsonify({
        "message": "🔍 نتائج البحث",
        "results": results
    })

if __name__ == '__main__':
    app.run(debug=True)
