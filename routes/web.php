<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;

// Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham', [ShopController::class, 'index'])->name('shop');
Route::get('/san-pham/{slug}', [ShopController::class, 'show'])->name('product.show');
Route::get('/gio-hang', [CartController::class, 'index'])->name('cart');
Route::post('/gio-hang/them', [CartController::class, 'add'])->name('cart.add');
Route::post('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
Route::get('/gio-hang/xoa/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/gio-hang/ma-giam-gia', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::get('/gio-hang/ma-giam-gia/xoa', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
Route::get('/thanh-toan', [CartController::class, 'checkout'])->name('checkout');
Route::post('/dat-hang', [CartController::class, 'placeOrder'])->name('order.place');
Route::get('/don-hang/{orderCode}', [CartController::class, 'orderSuccess'])->name('order.success');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/thuong-hieu', [BrandController::class, 'index'])->name('brands.index');

Route::post('/san-pham/{product:slug}/danh-gia', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.store');

Route::get('/payment/vnpay/return', [PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');
Route::post('/payment/vnpay/ipn', [PaymentController::class, 'vnpayIpn'])->name('payment.vnpay.ipn');

// Auth
Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login');
Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login.post');
Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register');
Route::post('/dang-ky', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/tai-khoan', [AuthController::class, 'account'])->middleware('auth')->name('account');

// Admin
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('brands', App\Http\Controllers\Admin\BrandController::class);
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);
    Route::resource('posts', App\Http\Controllers\Admin\PostController::class);
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
    Route::get('reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
});
