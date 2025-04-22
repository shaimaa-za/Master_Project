<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * عرض السلة.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access the cart.');
        }

        // الحصول على جميع المنتجات في السلة للمستخدم الحالي
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        // حساب إجمالي السلة مع التأكد من أن المنتج لا يزال موجودًا
        $total = $cartItems->sum(function ($item) {
            return optional($item->product)->price * $item->quantity;
        });

        // تحديد إذا كانت السلة فارغة
        $isCartEmpty = $cartItems->isEmpty();

        return view('cart.index', compact('cartItems', 'total', 'isCartEmpty'));
    }


    /**
     * إضافة منتج إلى السلة.
     */
    public function addToCart(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
        }

        $product = Product::findOrFail($productId);

        // التحقق من وجود المنتج في السلة
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();

        if ($cartItem) {
            // إذا كان المنتج موجودًا بالفعل في السلة، تحديث الكمية المطلوبة
            $cartItem->increment('quantity', $request->input('quantity', 1));
        } else {
            // إضافة المنتج إلى السلة مع الكمية المطلوبة
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $request->input('quantity', 1),
            ]);
        }

        // إضافة رسالة نجاح
        session()->put('success', 'The product has been added to the cart!');
        
        return back()->with('success', '  The product has been added to the cart!');
    }

    /**
     * إزالة منتج من السلة.
     */
    public function removeFromCart($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access the cart.');
        }
    
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();
    
        if ($cartItem) {
            $cartItem->delete();
        }
    
        return redirect()->back()->with('success', 'Product removed from cart successfully.');
    }

    /**
     * تحديث كمية المنتج في السلة.
     */
    public function updateQuantity(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access the cart.');        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $request->quantity]);
            return redirect()->back();
        }
        return redirect()->back();
    }

    /**
     * تفريغ السلة بالكامل.
     */
    public function clearCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access the cart.');        }

        Cart::where('user_id', Auth::id())->delete();

        //msg deleted
    }
}
