<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\ImgPro;
use App\Models\Product;
use App\Models\Protype;

class ProductController extends Controller
{
    // عرض صفحة رفع الصور
    public function showUploadForm()
    {
        return view('products.upload');
    }

    // عرض صفحة البحث
    public function searchForm()
    {
        return view('products.search');
    }

    public function searchByName(Request $request)
    {
        $request->validate([
            'search' => 'required|string|max:355'
        ]);

        // البحث عن المنتجات التي تحتوي على النص المدخل
        $products = Product::where('name', 'LIKE', '%' . $request->search . '%')->get();

        return view('products.search', compact('products'));
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'images.*' => 'required|image|max:5000'
        ]);
    
        $uploadedImages = [];
        $client = new Client();
    
        foreach ($request->file('images') as $image) {
            $imagePath = $image->store('product_img', 'public');
            $fullImagePath = storage_path("app/public/" . $imagePath);
    
            try {
                $response = $client->post('http://127.0.0.1:5000/upload', [
                    'multipart' => [
                        [
                            'name' => 'image',
                            'contents' => fopen($fullImagePath, 'r'),
                            'filename' => basename($fullImagePath)
                        ]
                    ]
                ]);
    
                $data = json_decode($response->getBody()->getContents(), true);
                
                if (!isset($data['image_id']) || !isset($data['product_type'])) {
                    return response()->json(['message' => 'Failed to save the image!'], 400);
                }
    
                // الحصول على المنتج الموجود
                $product = Product::find($request->product_id);
    
                // إنشاء أو تحديث سجل النوع في جدول protypes باستخدام product_id
                $protype = Protype::updateOrCreate(
                    ['product_id' => $product->id],
                    ['name' => $data['product_type']]
                );
    
                // حفظ بيانات الصورة في جدول ImgPro
                $imgPro = ImgPro::create([
                    'product_id' => $product->id,
                    'image_url' => $imagePath,
                    'faiss_id' => $data['image_id']
                ]);
    
                $uploadedImages[] = $imgPro;
            } catch (\Exception $e) {
                return response()->json(['message' => 'An error occurred while uploading the image!', 'error' => $e->getMessage()], 500);
            }
        }
    
        return response()->json(['message' => 'Images have been saved and the product has been categorized successfully!', 'data' => $uploadedImages]);
    }
    
    public function searchByImage(Request $request)
    {
        $request->validate(['image' => 'required|image']);
    
        // حفظ الصورة مؤقتاً لعملية البحث
        $imagePath = $request->file('image')->store('search_images', 'public');
        $fullImagePath = storage_path("app/public/" . $imagePath);
    
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/search', [
            'multipart' => [
                [
                    'name' => 'image',
                    'contents' => fopen($fullImagePath, 'r'),
                    'filename' => basename($fullImagePath)
                ]
            ]
        ]);
    
        $data = json_decode($response->getBody()->getContents(), true);
    
        // التأكد من وجود نتائج البحث
        if (!isset($data['results']) || count($data['results']) == 0) {
            return view('products.search')->with('message', 'No sufficiently similar images were found.');
        }
    
        // الحصول على الصورة المشابهة (أول نتيجة)
        $firstResult = ImgPro::where('faiss_id', $data['results'][0]['image_id'])->first();
        if (!$firstResult) {
            return view('products.search')->with('message', 'No matching record found for the image.');
        }
    
        // الحصول على المنتج الخاص بالصورة المشابهة
        $similarProduct = $firstResult->product;
       
        // التأكد من وجود نوع المنتج المرتبط
        if (!$similarProduct || !$similarProduct->protype) {
            return view('products.search')->with('message', 'Product type not found.');
        }
    
        // الحصول على اسم النوع من جدول protypes
        $productType = $similarProduct->protype->name;
        
        // جلب باقي المنتجات التي تحمل نفس النوع (مع استثناء المنتج المشابه نفسه)
        $otherProducts = Product::whereHas('protype', function($q) use ($productType) {
            $q->where('name', $productType);
        })->where('id', '!=', $similarProduct->id)->get();
       
        // إعادة عرض الصفحة مع المنتج المشابه وباقي المنتجات
        
        $products = collect([$similarProduct])->merge($otherProducts);

    // إعادة عرض الصفحة مع المتغير الموحد
    return view('products.search', compact('products'));
    }
}
