<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\User;

class FavoritesController extends Controller
{   
    public function toggle(Request $request)
    {
        $user = Auth::user();
        $productId = $request->input('product_id');

        $favorite = Favorite::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            return response()->json(['status' => 'added']);
        }
    }
    public function toggledet(Product $product)
    {
        $user = auth()->user();

        if ($user->favorites()->where('product_id', $product->id)->exists()) {
            $user->favorites()->where('product_id', $product->id)->delete();
            return response()->json(['status' => 'removed']);
        } else {
            $user->favorites()->create(['product_id' => $product->id]);
            return response()->json(['status' => 'added']);
        }
    }

     /**
     * Display the list of favorite products.
     */
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('product.images')->get();
        return view('favorites.index', compact('favorites'));
    }

    public function addToCart($productId)
    {
        $user = Auth::user();
    
        // التحقق مما إذا كان المنتج موجودًا في السلة بالفعل
        $cartItem = Cart::where('user_id', $user->id)->where('product_id', $productId)->first();
    
        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }
    
        return redirect()->route('customer.dashboard')->with('success', 'تمت إضافة المنتج إلى السلة.');
    }
    
    public function removeFromFavorites($productId)
    {
        $user = Auth::user();
    
        // حذف المنتج من المفضلة
        Favorite::where('user_id', $user->id)->where('product_id', $productId)->delete();
    
        return redirect()->route('customer.dashboard')->with('success', 'تمت إزالة المنتج من المفضلة.');
    }
}
