<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class UserProductController extends Controller
{
    public function index()
    {
        // جلب جميع المنتجات
        $allProducts = Product::with(['images', 'attributes'])->where('stock', '>', 0)->get();

        // جلب جميع الفئات مع المنتجات المرتبطة
        $categories = Category::with(['products' => function ($query) {
            $query->where('stock', '>', 0)->with(['images', 'attributes']);
        }])->get();
        

        // عرض صفحة المنتجات مع تمرير البيانات
        return view('Home.userproducts', compact('allProducts', 'categories'));
    }

    // عرض تفاصيل المنتج
    public function details($id)
    {
        $product = Product::with(['images', 'attributes'])->findOrFail($id);

       // الحصول على التوصيات باستخدام السكربت Python
        $recommendedProductIds = $this->getRecommendedProducts(auth()->id());

        // استرجاع المنتجات الموصى بها من قاعدة البيانات
        $recommendedProducts = Product::whereIn('id', $recommendedProductIds)->get();

        // عرض صفحة تفاصيل المنتج مع تمرير المنتجات الموصى بها
        return view('Home.productdetails', compact('product', 'recommendedProducts'));
    }
    public function show($id)
    {
        $product = Product::with('reviews')->findOrFail($id);
        
        // الحصول على التوصيات باستخدام السكربت Python
        $recommendedProductIds = $this->getRecommendedProducts(auth()->id());
    
        // استرجاع المنتجات الموصى بها من قاعدة البيانات
        $recommendedProducts = Product::whereIn('id', $recommendedProductIds)->get();
        
       
        return view('Home.productdetails', compact('product', 'recommendedProducts'));
    }
    
    private function getRecommendedProducts($userId)
    {
        if (!$userId) {
            return collect();
        }
        $output = shell_exec("python C:/xampp/htdocs/app/python_scripts/content_based_recommendation.py $userId 2>&1");
        $recommendedProductIds = explode(',', trim($output));
        return $recommendedProductIds;
    }




}
