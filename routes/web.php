<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// PUBLIC STOREFRONT ROUTES
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/newsletter/subscribe', [HomeController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::get('/track-order', [HomeController::class, 'trackOrder'])->name('orders.track');
Route::get('/contact', [HomeController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('pages.contact.submit');
Route::get('/about', [HomeController::class, 'about'])->name('pages.about');

// Shop, Sneaker Vault & Product Catalog
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/sneakers', [ShopController::class, 'sneakers'])->name('shop.sneakers');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('shop.show');

// Shopping Bag / Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/items/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Checkout & Payment Verification
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/payment/gateway/{reference}', [CheckoutController::class, 'paymentGateway'])->name('payment.gateway');
Route::post('/payment/verify/{reference}', [CheckoutController::class, 'verifyPayment'])->name('payment.verify');
Route::get('/order-confirmation/{orderNumber}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

// =========================================================================
// AUTHENTICATION ROUTES
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth-attempts')->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:auth-attempts')->name('register.submit');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// =========================================================================
// CUSTOMER DASHBOARD & AUTHENTICATED INTERACTIONS
// =========================================================================
Route::middleware('auth')->group(function () {
    Route::post('/product/{product}/reviews', [ShopController::class, 'storeReview'])->name('shop.reviews.store');
    Route::post('/wishlist/toggle/{product}', [CustomerDashboardController::class, 'toggleWishlist'])->name('wishlist.toggle');

    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
        Route::get('/orders/{orderNumber}', [CustomerDashboardController::class, 'orderDetail'])->name('orders.show');
        Route::get('/wishlist', [CustomerDashboardController::class, 'wishlist'])->name('wishlist');
        Route::get('/addresses', [CustomerDashboardController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [CustomerDashboardController::class, 'storeAddress'])->name('addresses.store');
        Route::patch('/addresses/{address}/default', [CustomerDashboardController::class, 'setDefaultAddress'])->name('addresses.default');
        Route::delete('/addresses/{address}', [CustomerDashboardController::class, 'deleteAddress'])->name('addresses.delete');
        Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [CustomerDashboardController::class, 'updatePassword'])->name('profile.password');
    });
});

// =========================================================================
// ADMIN DASHBOARD ROUTES (ROLE-BASED ACCESS CONTROL)
// =========================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Products & Inventory
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::patch('/product-images/{image}', [AdminController::class, 'updateProductImage'])->name('products.images.update');
    Route::delete('/product-images/{image}', [AdminController::class, 'deleteProductImage'])->name('products.images.delete');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');

    // Sneaker & Fashion Brands
    Route::get('/brands', [AdminController::class, 'brands'])->name('brands');
    Route::post('/brands', [AdminController::class, 'storeBrand'])->name('brands.store');
    Route::delete('/brands/{brand}', [AdminController::class, 'deleteBrand'])->name('brands.delete');

    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');

    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    // Customers
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::patch('/customers/{user}/toggle-status', [AdminController::class, 'toggleCustomerStatus'])->name('customers.toggle');

    // Discount Coupons
    Route::get('/coupons', [AdminController::class, 'coupons'])->name('coupons');
    Route::post('/coupons', [AdminController::class, 'storeCoupon'])->name('coupons.store');
    Route::patch('/coupons/{coupon}/toggle', [AdminController::class, 'toggleCoupon'])->name('coupons.toggle');
    Route::delete('/coupons/{coupon}', [AdminController::class, 'deleteCoupon'])->name('coupons.delete');

    // Customer Reviews
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    Route::patch('/reviews/{review}/toggle', [AdminController::class, 'toggleReviewApproval'])->name('reviews.toggle');
    Route::delete('/reviews/{review}', [AdminController::class, 'deleteReview'])->name('reviews.delete');

    // Homepage Banners & Shipping Settings
    Route::get('/banners', [AdminController::class, 'banners'])->name('banners');
    Route::post('/banners', [AdminController::class, 'storeBanner'])->name('banners.store');
    Route::patch('/banners/{banner}/toggle', [AdminController::class, 'toggleBanner'])->name('banners.toggle');
    Route::delete('/banners/{banner}', [AdminController::class, 'deleteBanner'])->name('banners.delete');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});
