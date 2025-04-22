import torch
import torchvision.transforms as transforms
from torchvision import models, datasets
from torch.utils.data import DataLoader
import torch.nn as nn
import json
import os

# إعداد الجهاز
device = torch.device("cuda" if torch.cuda.is_available() else "cpu")

# تحميل النموذج المدرب ResNet-50
model = models.resnet50()
num_features = model.fc.in_features
model.fc = torch.nn.Sequential(
    torch.nn.Linear(num_features, 512),
    torch.nn.ReLU(),
    torch.nn.Dropout(0.5),
    torch.nn.Linear(512, 4),  # عدّل إذا تغير عدد الفئات
    torch.nn.LogSoftmax(dim=1)
)

# تحميل أوزان النموذج
model.load_state_dict(torch.load("resnet50_model2.pth", map_location=device))
model.to(device)
model.eval()

# تحميل أسماء الفئات
with open("C:/xampp/htdocs/app/searchbyimg/class_labels.json", "r") as f:
    class_labels = json.load(f)

# تحويل الصور
transform = transforms.Compose([
    transforms.Resize((224, 224)),
    transforms.ToTensor(),
    transforms.Normalize(mean=[0.485, 0.456, 0.406], std=[0.229, 0.224, 0.225]),
])

# مسار مجلد الاختبار
test_folder = "C:\\Users\\User\\Downloads\\Jewellery_sorted\\test"

# تحميل بيانات الاختبار
test_dataset = datasets.ImageFolder(root=test_folder, transform=transform)
test_loader = DataLoader(test_dataset, batch_size=32, shuffle=False)

# دالة الخسارة
criterion = nn.NLLLoss()

# التقييم
test_loss = 0.0
correct = 0
total = 0
results = {}
category_errors = {class_name: {"total": 0, "incorrect": 0} for class_name in test_dataset.classes}

with torch.no_grad():
    for images, labels in test_loader:
        images, labels = images.to(device), labels.to(device)
        outputs = model(images)
        loss = criterion(outputs, labels)
        test_loss += loss.item() * images.size(0)
        _, predicted = torch.max(outputs, 1)
        correct += (predicted == labels).sum().item()
        total += labels.size(0)

        # حفظ النتائج لكل صورة
        for i in range(images.size(0)):
            img_path, true_label_idx = test_dataset.samples[total - images.size(0) + i]
            img_name = os.path.basename(img_path)
            predicted_label = predicted[i].item()
            true_class = test_dataset.classes[true_label_idx]
            predicted_class = class_labels[str(predicted_label)]
            
            # حفظ النتيجة في القاموس
            results[img_name] = {
                "true_class": true_class,
                "predicted_class": predicted_class
            }

            # تحديث عداد الأخطاء حسب الفئة
            category_errors[true_class]["total"] += 1
            if true_class != predicted_class:
                category_errors[true_class]["incorrect"] += 1

# حساب الدقة والخسارة
avg_test_loss = test_loss / total
test_accuracy = correct / total * 100

# طباعة النتائج فقط مرة واحدة
print(f"\nTest Loss: {avg_test_loss:.4f}")
print(f"Test Accuracy: {test_accuracy:.2f}%")

# طباعة عدد الصور الخاطئة في كل فئة
print("\nClassification Errors per Category:")
for category, errors in category_errors.items():
    incorrect = errors["incorrect"]
    total_images = errors["total"]
    print(f"{category}: {incorrect} incorrect out of {total_images} images")

# حفظ النتائج في ملف JSON
with open("test_results.json", "w", encoding="utf-8") as f:
    json.dump(results, f, indent=4, ensure_ascii=False)

print("\nThe detailed results have been saved in test_results.json")
