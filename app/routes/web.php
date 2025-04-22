<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PanelController;  
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayPalController;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Http\Controllers\UserProductController; 
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductViewController;



Route::get('/', function () {
    return view('Home');
});


use App\Http\Controllers\ARProductController;

Route::get('/AR_Products', [ARProductController::class, 'index'])->name('AR_Products.index');
Route::get('/AR_Products/ar/{id}', [ARProductController::class, 'showAR'])->name('AR_Products.view');



use App\Http\Controllers\ProductController;

Route::get('/products/upload-images', [ProductController::class, 'showUploadForm'])->name('product.uploadForm');
Route::post('/products/upload-images', [ProductController::class, 'uploadImages'])->name('product.uploadImages');

Route::get('/products/search-form', [ProductController::class, 'searchForm'])->name('product.searchForm');
Route::post('/products/search', [ProductController::class, 'searchByImage'])->name('product.searchByImage');
Route::get('/search', [ProductController::class, 'searchByName'])->name('product.searchByName');



Route::get('/track-product-view/{productId}', [ProductViewController::class, 'trackProductView']);

Route::get('/export-interactions', [ProductViewController::class, 'exportInteractions'])->name('exportInteractions');
Route::get('/export-products', [ProductViewController::class, 'exportProducts']);

// لوحة تحكم المستخدمين العاديين
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'not.admin'])->name('dashboard');


// المسارات المحمية للمستخدمين المسجلين فقط
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// إضافة المسار الخاص بلوحة التحكم للأدمن
use Filament\Facades\Filament;

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('/admin', [PanelController::class, 'index'])->name('filament.admin');
});


//customer dashboard
Route::get('/customer/dashboard', [\App\Http\Controllers\Customer\Dashboard\DashboardController::class, 'index'])
    ->name('customer.dashboard');


Route::middleware('auth')->group(function () {
    // عرض صفحة المفضلة
    Route::get('/favorites', [FavoritesController::class, 'index'])->name('favorites.index');
    // إضافة المنتج إلى السلة
    Route::post('/favorites/{productId}/add-to-cart', [FavoritesController::class, 'addToCart'])->name('favorites.addToCart');
    // حذف المنتج من المفضلة
    Route::delete('/favorites/{productId}/remove', [FavoritesController::class, 'removeFromFavorites'])->name('favorites.remove');
    // عرض تفاصيل المنتج
    Route::get('/detailsproducts/{id}', [UserProductController::class, 'details'])->name('userproducts.details');
});
    
    
Route::middleware('auth')->group(function () {
    // عرض السلة
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    // إضافة المنتج إلى السلة
    Route::post('/favorites/{productId}/add-to-cart', [FavoritesController::class, 'addToCart'])->name('favorites.addToCart');

    // إزالة المنتج من السلة
    Route::delete('/cart/{productId}/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');

    // تحديث كمية المنتج في السلة
    Route::post('/cart/{productId}/update', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
});

// Checkout Routes

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

use App\Http\Controllers\OrderController;

Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::put('/orders/{order}/return', [OrderController::class, 'returnOrder'])->name('orders.return');

use App\Http\Controllers\ReviewController;

Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');



//site pages
Route::get('/userproducts', [UserProductController::class, 'index'])->name('userproducts.index');

Route::get('/detailsproducts/{id}', [UserProductController::class, 'details'])
    ->name('userproducts.details');
Route::get('/showproduct/{id}', [UserProductController::class, 'show'])->name('userproducts.show');
    
    
Route::post('/detailsproducts/{productId}/add-to-cart', [CartController::class, 'addToCart'])->name('details.addToCart')->middleware('auth');

Route::post('/favorites/toggle', [FavoritesController::class, 'toggle'])->name('favorites.toggle')->middleware('auth');
Route::post('/favorites/toggle/{product}', [FavoritesController::class, 'toggledet'])->middleware('auth');


Route::post('paymentpro/{order_id}',[PayPalController::class, 'paymentpro'])->name('paymentpro');
Route::get('payment/cancel/{order_id}',[PayPalController::class, 'cancel'])->name('payment.cancel');
Route::get('payment/success/{order_id}', [PayPalController::class, 'success'])->name('payment.success');
Route::get('payment/{order_id}', [PayPalController::class, 'payment'])->name('payment');

use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function() {
    Mail::raw('This is a test email.', function ($message) {
        $message->to('shaimaazakzouk123@gmail.com')
                ->subject('Test Email');
    });

    return 'Email sent successfully';
});



Route::get('/help', function () {
    return view('Home.help');
})->name('help');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/about', function () {
    return view('about'); 
})->name('about');

Route::get('/blog', function () {
    return view('blog'); 
})->name('blog');

Route::get('/blog_Detail', function () {
    return view('blog_Detail'); 
})->name('blog_Detail');


Route::get('/filter1', function () {
    return view('AR_Products.filter1'); 
})->name('AR_Products.filter1');

Route::get('/filter2', function () {
    return view('AR_Products.filter2'); 
})->name('AR_Products.filter2');

Route::get('/filter3', function () {
    return view('AR_Products.filter3'); 
})->name('AR_Products.filter3');

require __DIR__.'/auth.php';
