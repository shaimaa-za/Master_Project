import torch
import torchvision.models as models
import torchvision.transforms as transforms
from torchvision.datasets import ImageFolder
from torch.utils.data import DataLoader
import torch.nn as nn
import torch.optim as optim
import os

# إعداد الجهاز
device = torch.device("cuda" if torch.cuda.is_available() else "cpu")

# تحميل البيانات وتحويلها
transform = transforms.Compose([
    transforms.Resize((224, 224)),
    transforms.RandomHorizontalFlip(p=0.5),
    transforms.RandomRotation(10),
    transforms.ColorJitter(brightness=0.2, contrast=0.2, saturation=0.2, hue=0.1),
    transforms.ToTensor(),
    transforms.Normalize(mean=[0.485, 0.456, 0.406], std=[0.229, 0.224, 0.225]),
])

data_dir = "C:\\Users\\User\\Downloads\\Jewellery_sorted"  # استبدل بمسار قاعدة بياناتك
train_data = ImageFolder(os.path.join(data_dir, "train"), transform=transform)
valid_data = ImageFolder(os.path.join(data_dir, "valid"), transform=transform)

train_loader = DataLoader(train_data, batch_size=32, shuffle=True)
valid_loader = DataLoader(valid_data, batch_size=32, shuffle=False)

# تحميل ResNet-50 وتعديله
model = models.resnet50(weights="DEFAULT")
num_features = model.fc.in_features
model.fc = nn.Sequential(
    nn.Linear(num_features, 512),
    nn.ReLU(),
    nn.Dropout(0.5),
    nn.Linear(512, len(train_data.classes)),  # عدد الفئات بناءً على بيانات التدريب
    nn.LogSoftmax(dim=1)
)
model = model.to(device)

# إعداد دالة الفقد والمُحسِّن
criterion = nn.CrossEntropyLoss()
optimizer = optim.Adam(model.parameters(), lr=0.0001)

def train_model(model, train_loader, valid_loader, epochs=7):
    for epoch in range(epochs):
        model.train()
        running_loss = 0.0
        for images, labels in train_loader:
            images, labels = images.to(device), labels.to(device)
            optimizer.zero_grad()
            outputs = model(images)
            loss = criterion(outputs, labels)
            loss.backward()
            optimizer.step()
            running_loss += loss.item()
        
        # تقييم على بيانات التحقق
        model.eval()
        correct = 0
        total = 0
        with torch.no_grad():
            for images, labels in valid_loader:
                images, labels = images.to(device), labels.to(device)
                outputs = model(images)
                _, predicted = torch.max(outputs, 1)
                total += labels.size(0)
                correct += (predicted == labels).sum().item()
        
        accuracy = 100 * correct / total
        print(f"Epoch {epoch+1}/{epochs} - Loss: {running_loss/len(train_loader):.4f}, Accuracy: {accuracy:.2f}%")
    
    # حفظ النموذج
    torch.save(model.state_dict(), "resnet50_jewelry.pth")
    print("The form has been saved successfully!")

# تشغيل التدريب مع تقليل عدد مرات التدريب إلى 7
train_model(model, train_loader, valid_loader, epochs=7)
