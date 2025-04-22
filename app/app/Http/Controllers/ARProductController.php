<?php

namespace App\Http\Controllers;
use App\Models\ARProduct;
use Illuminate\Http\Request;

class ARProductController extends Controller
{
    public function index()
    {
        $products = ARProduct::all();
        return view('AR_Products.index', compact('products'));
    }
    
    // عرض المنتج في الواقع المعزز
    public function showAR($id)
    {
        $product = ARProduct::findOrFail($id);
        return view('AR_Products.view', compact('product'));
    }

}
