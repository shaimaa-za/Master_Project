<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductView;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductViewController extends Controller
{
  // دالة لتصدير المنتجات إلى CSV مع الفئة
    public function exportProducts()
    {
            // استخراج بيانات المنتجات مع الفئات من قاعدة البيانات
            $products = Product::with('category')->select('id', 'name', 'description', 'category_id')->get();

            // تنظيف البيانات والتأكد من خلو الأوصاف من مسافات زائدة أو مشاكل أخرى
            $productsArray = $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => trim($product->name),  // إزالة المسافات الزائدة
                    'description' => trim($product->description),  // إزالة المسافات الزائدة
                    'category' => $product->category ? $product->category->name : 'غير معروف', // إضافة اسم الفئة أو "غير معروف"
                ];
            });

            // إنشاء ملف CSV
            $csvHeader = "id,name,description,category\n";
            $csvData = $csvHeader . implode("\n", array_map(function($product) {
                // إضافة علامات اقتباس حول القيم لضمان عدم وجود فواصل غير متوقعة
                return '"' . implode('","', $product) . '"';
            }, $productsArray->toArray()));

            // حفظ البيانات في ملف CSV داخل التخزين
            Storage::put('products.csv', $csvData);

            // تحميل الملف
            return response()->download(storage_path('app/products.csv'));
    }

    public function exportInteractions()
    {
        // استخراج التفاعلات من الجداول المختلفة (الزيارات، المفضلة، السلة) مع الطابع الزمني
        $interactions = DB::table('product_views')
            ->select('user_id', 'product_id', DB::raw('"view" as interaction_type'), 'created_at')
            ->union(
                DB::table('favorites')->select('user_id', 'product_id', DB::raw('"favorite" as interaction_type'), 'created_at')
            )
            ->union(
                DB::table('cart')->select('user_id', 'product_id', DB::raw('"cart" as interaction_type'), 'created_at')
            )
            ->orderBy('created_at', 'desc') // ترتيب من الأحدث إلى الأقدم
            ->get();

        // بناء بيانات CSV مع العناوين المناسبة
        $csvData = "user_id,product_id,interaction_type,timestamp\n";
        foreach ($interactions as $interaction) {
            $timestamp = $interaction->created_at ?? now(); // استخدم التاريخ الحالي إذا كان الحقل فارغًا
            $csvData .= "{$interaction->user_id},{$interaction->product_id},{$interaction->interaction_type},{$timestamp}\n";
        }

        // حفظ الملف في التخزين المحلي
        Storage::put('interactions.csv', $csvData);

        return response()->download(storage_path('app/interactions.csv'));
    }


    // دالة لاستخراج التوصيات بناءً على الفئة
    public function getRecommendationsByCategory($userId, $top_n = 5)
    {
        // العثور على المنتجات التي تفاعل معها المستخدم (مثل الزيارات أو المفضلات)
        $interactedProducts = DB::table('product_views')
                                ->where('user_id', $userId)
                                ->pluck('product_id');

        if ($interactedProducts->isEmpty()) {
            return []; // إذا لم تكن هناك تفاعلات، إرجاع مصفوفة فارغة
        }

        // استخراج المنتجات من نفس الفئة
        $productsInSameCategory = Product::whereIn('id', $interactedProducts)
                                        ->with('category')
                                        ->get()
                                        ->groupBy('category_id');

        // اختر المنتجات الموصى بها من نفس الفئة
        $recommendedProducts = [];
        foreach ($productsInSameCategory as $categoryId => $products) {
            // إضافة بعض المنتجات الموصى بها من نفس الفئة
            $recommendedProducts = array_merge($recommendedProducts, $products->pluck('id')->toArray());
        }

        // إرجاع أعلى 5 منتجات موصى بها
        return array_slice($recommendedProducts, 0, $top_n);
    }

    public function trackProductView($productId)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login'); // أو أي إجراء آخر
        }

        
        ProductView::create([
            'user_id' => $user->id,
            'product_id' => $productId
        ]);
            


         // تحديث الملفات بعد تسجيل الزيارة
         $this->exportProducts();       // تحديث ملف المنتجات
         $this->exportInteractions();   // تحديث ملف التفاعلات
         
        return back();
    }


}


