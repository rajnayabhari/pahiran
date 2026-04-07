<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Seller\AuthController as SellerAuthController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\CategoryController as SellerCategoryController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Storefront Routes (Public)
|--------------------------------------------------------------------------
*/
Route::get('/', [StorefrontController::class, 'index'])->name('home');
Route::get('/test-image', function() {
    return view('test-image');
})->name('test.image');
Route::get('/product/{slug}', [StorefrontController::class, 'show'])->name('product.show');
Route::get('/category/{slug}', [StorefrontController::class, 'category'])->name('category.show');
Route::get('/search', [StorefrontController::class, 'search'])->name('search');

/*
|--------------------------------------------------------------------------
| Customer Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Cart Routes (Public — session-based)
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{key}', [CartController::class, 'remove'])->name('cart.remove');

/*
|--------------------------------------------------------------------------
| Checkout Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process'); // Modified this line
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// Khalti callback (no auth — Khalti redirects here)
Route::get('/payment/khalti/callback', [CheckoutController::class, 'callback'])->name('payment.khalti.callback');

/*
|--------------------------------------------------------------------------
| Seller Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('seller')->group(function () {
    Route::get('/login', [SellerAuthController::class, 'showLoginForm'])->name('seller.login');
    Route::post('/login', [SellerAuthController::class, 'login'])->name('seller.login.submit');
    Route::get('/register', [SellerAuthController::class, 'showRegisterForm'])->name('seller.register');
    Route::post('/register', [SellerAuthController::class, 'register'])->name('seller.register.submit');
    Route::post('/logout', [SellerAuthController::class, 'logout'])->name('seller.logout');
});

/*
|--------------------------------------------------------------------------
| Seller Dashboard Routes (Seller Guard)
|--------------------------------------------------------------------------
*/
Route::prefix('seller')->middleware('seller')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');

    Route::get('/categories', [SellerCategoryController::class, 'index'])->name('seller.categories.index');
    Route::get('/categories/create', [SellerCategoryController::class, 'create'])->name('seller.categories.create');
    Route::post('/categories', [SellerCategoryController::class, 'store'])->name('seller.categories.store');
    Route::get('/categories/{category}/edit', [SellerCategoryController::class, 'edit'])->name('seller.categories.edit');
    Route::put('/categories/{category}', [SellerCategoryController::class, 'update'])->name('seller.categories.update');
    Route::delete('/categories/{category}', [SellerCategoryController::class, 'destroy'])->name('seller.categories.destroy');

    Route::get('/products', [SellerProductController::class, 'index'])->name('seller.products.index');
    Route::get('/products/create', [SellerProductController::class, 'create'])->name('seller.products.create');
    Route::post('/products', [SellerProductController::class, 'store'])->name('seller.products.store');
    Route::get('/products/{product}/edit', [SellerProductController::class, 'edit'])->name('seller.products.edit');
    Route::put('/products/{product}', [SellerProductController::class, 'update'])->name('seller.products.update');
    Route::delete('/products/{product}', [SellerProductController::class, 'destroy'])->name('seller.products.destroy');

    Route::get('/orders', [SellerOrderController::class, 'index'])->name('seller.orders.index');
    Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('seller.orders.show');
    Route::patch('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('seller.orders.status');
});

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes (Admin Guard)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/sellers', [AdminSellerController::class, 'index'])->name('admin.sellers.index');
    Route::get('/sellers/{seller}', [AdminSellerController::class, 'show'])->name('admin.sellers.show');
    Route::patch('/sellers/{seller}/toggle', [AdminSellerController::class, 'toggleStatus'])->name('admin.sellers.toggle');
    Route::patch('/sellers/{seller}/commission', [AdminSellerController::class, 'updateCommission'])->name('admin.sellers.commission');
    Route::delete('/sellers/{seller}', [AdminSellerController::class, 'destroy'])->name('admin.sellers.destroy');
    
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');

    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    Route::patch('/users/{user}/toggle', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('admin.users.toggle');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
});
