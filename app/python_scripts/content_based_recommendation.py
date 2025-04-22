import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import sys
import os

# طباعة المسار الحالي
print("Current working directory:", os.getcwd())

# تحميل بيانات المنتجات مع الفئات
df_products = pd.read_csv('C:/xampp/htdocs/app/storage/app/products.csv', on_bad_lines='skip')

# التأكد من أن الأعمدة المهمة لا تحتوي على قيم NaN
df_products.fillna({'name': '', 'description': '', 'category': ''}, inplace=True)

# دمج الوصف مع أسماء المنتجات وإضافة الفئة لتوليد الميزات
df_products['features'] = df_products['name'] + " " + df_products['description'] + " " + df_products['category']

# تحويل النصوص إلى متجهات TF-IDF
vectorizer = TfidfVectorizer(stop_words='english')
feature_matrix = vectorizer.fit_transform(df_products['features'])

# حساب التشابه بين المنتجات باستخدام Cosine Similarity
similarity_matrix = cosine_similarity(feature_matrix)

# تحميل بيانات التفاعلات
df_interactions = pd.read_csv('C:/xampp/htdocs/app/storage/app/interactions.csv')

# الحصول على معرف المستخدم من المعامل
user_id = int(sys.argv[1])  
print(f"Processing recommendations for User ID: {user_id}")

# دالة لاستخراج التوصيات بناءً على آخر 7 تفاعلات
def get_recommendations(user_id, top_n=5):
    # العثور على أحدث 7 تفاعلات خاصة بالمستخدم
    interacted_products = df_interactions[df_interactions['user_id'] == user_id].sort_values(by='timestamp', ascending=False).head(7)

    if interacted_products.empty:
        print("No recent interactions found for user", user_id)
        return

    product_ids = interacted_products['product_id'].unique()

    recommended_product_ids = []

    for product_id in product_ids:
        product_row = df_products[df_products['id'] == product_id]

        if product_row.empty:
            continue

        product_index = product_row.index[0]
        product_category = product_row.iloc[0]['category']

        similarity_scores = list(enumerate(similarity_matrix[product_index]))
        sorted_scores = sorted(similarity_scores, key=lambda x: x[1], reverse=True)[1:top_n+5]

        similar_products = [df_products.loc[idx, 'id'] for idx, _ in sorted_scores]
        similar_products_in_category = [
            df_products.loc[idx, 'id']
            for idx, _ in sorted_scores
            if df_products.loc[idx, 'category'] == product_category
        ]

        final_recommendations = similar_products_in_category[:top_n]
        if len(final_recommendations) < top_n:
            remaining_slots = top_n - len(final_recommendations)
            final_recommendations.extend(similar_products[:remaining_slots])

        recommended_product_ids.extend(final_recommendations)

    recommended_product_ids = list(set(recommended_product_ids))[:top_n]
    
    print("Recommended products:", ",".join(map(str, recommended_product_ids)))

# استدعاء الدالة للحصول على التوصيات
get_recommendations(user_id)
