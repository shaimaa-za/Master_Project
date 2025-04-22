<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // التحقق من صحة المدخلات
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // التأكد من أن الطلب تم تسليمه قبل التقييم
        $order = Order::findOrFail($request->order_id);
        if (strtolower($order->shipping->status) !== 'delivered') {
            return redirect()->back()->with('error', 'You can only review delivered orders.');
        }

        // التأكد من عدم تكرار التقييم لنفس المنتج في الطلب
        if (Review::where('order_id', $request->order_id)
            ->where('product_id', $request->product_id)
            ->exists()) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        // إنشاء التقييم
        Review::create([
            'user_id' => auth()->id(),
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully.');
    }
}
