<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\PrivacyPolicyController;
use App\Http\Controllers\Admin\ContactPageController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Admin\SectionTitleController;
use App\Http\Controllers\Admin\DiscoverItemController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Frontend Routes
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/shop', [FrontendController::class, 'shop'])->name('shop');
Route::get('/category/{slug}', [FrontendController::class, 'category'])->name('category.show');
Route::get('/product/{slug}', [FrontendController::class, 'productDetails'])->name('product.details');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact/submit', [FrontendController::class, 'submitContact'])->name('contact.submit');
Route::get('/wishlist', [FrontendController::class, 'wishlist'])->name('wishlist')->middleware(['auth', \App\Http\Middleware\SaveIntendedUrl::class]);
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::post('/wishlist/check', [WishlistController::class, 'check'])->name('wishlist.check');
Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacy.policy');

// Test Wishlist Routes
Route::get('/test-wishlist', function () {
    return view('test-wishlist');
})->name('test.wishlist');

Route::get('/wishlist-diagnostic', function () {
    return view('wishlist-diagnostic');
})->name('wishlist.diagnostic');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart')->middleware(['auth']);
Route::get('/cart/index', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/api/cart', [CartController::class, 'apiIndex'])->name('cart.api');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

// Order Routes (Frontend)
Route::get('/orders', [FrontendController::class, 'orders'])->name('orders.index')->middleware(['auth', \App\Http\Middleware\SaveIntendedUrl::class]);
Route::get('/order/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::match(['get', 'post'], '/orders/track', [OrderController::class, 'track'])->name('orders.track');

// Language Test Route
Route::get('/test-language', function () {
    return view('test-language');
});

// Language Switch Route
Route::post('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return response()->json(['success' => true]);
})->name('locale.switch');

// User Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/user/login', [UserAuthController::class, 'showLogin'])->name('user.login');
    Route::post('/user/login', [UserAuthController::class, 'login'])->name('user.login.submit');
    Route::post('/user/register', [UserAuthController::class, 'register'])->name('user.register.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/user/logout', [UserAuthController::class, 'logout'])->name('user.logout');
});

// Dashboard Routes (Breeze) - For Admin Only
Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Admin Routes - Pages Management
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('pages', PageController::class);

    // Home Page Editor
    Route::get('home/edit', [HomePageController::class, 'edit'])->name('home.edit');
    Route::post('home/update', [HomePageController::class, 'update'])->name('home.update');

    // About Page Editor
    Route::get('about/edit', [AboutPageController::class, 'edit'])->name('about.edit');
    Route::post('about/update', [AboutPageController::class, 'update'])->name('about.update');

    // Privacy Policy Page Editor
    Route::get('privacy/edit', [PrivacyPolicyController::class, 'edit'])->name('privacy.edit');
    Route::post('privacy/update', [PrivacyPolicyController::class, 'update'])->name('privacy.update');

    // Contact Page Editor
    Route::get('contact/edit', [ContactPageController::class, 'edit'])->name('contact.edit');
    Route::post('contact/update', [ContactPageController::class, 'update'])->name('contact.update');

    // Section Titles Management
    Route::get('section-titles/edit', [SectionTitleController::class, 'edit'])->name('section-titles.edit');
    Route::post('section-titles/update', [SectionTitleController::class, 'update'])->name('section-titles.update');
    Route::get('section-titles/get/{key}/{locale?}', [SectionTitleController::class, 'getByKey'])->name('section-titles.get');

    // Discover Items Management
    Route::delete('discover-items/{id}', [DiscoverItemController::class, 'destroy'])->name('discover-items.destroy');

    // Categories Management
    Route::resource('categories', CategoryController::class);
    Route::get('categories/subcategories', [CategoryController::class, 'getSubcategories'])->name('categories.subcategories');

    // Sizes Management
    Route::resource('sizes', \App\Http\Controllers\Admin\SizeController::class)->except(['show', 'create', 'edit']);

    // Colors Management
    Route::resource('colors', \App\Http\Controllers\Admin\ColorController::class)->except(['show', 'create', 'edit']);

    // Shoe Sizes Management
    Route::resource('shoe-sizes', \App\Http\Controllers\Admin\ShoeSizeController::class)->except(['show', 'create', 'edit']);

    // Menus Management
    Route::resource('menus', MenuController::class);
    Route::get('menus/{menu}/columns', [MenuController::class, 'manageColumns'])->name('menus.columns');
    Route::post('menus/{menu}/columns', [MenuController::class, 'storeColumn'])->name('menus.columns.store');
    Route::put('menu-columns/{column}', [MenuController::class, 'updateColumn'])->name('menus.columns.update');
    Route::delete('menu-columns/{column}', [MenuController::class, 'destroyColumn'])->name('menus.columns.destroy');
    Route::post('menu-columns/{column}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
    Route::delete('menu-items/{item}', [MenuController::class, 'destroyItem'])->name('menus.items.destroy');

    // Products Management
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

    // Customer Management - Contact Messages
    Route::get('customers/messages', [ContactMessageController::class, 'index'])->name('customers.messages.index');
    Route::get('customers/messages/{id}', [ContactMessageController::class, 'show'])->name('customers.messages.show');
    Route::post('customers/messages/{id}/status', [ContactMessageController::class, 'updateStatus'])->name('customers.messages.status');
    Route::post('customers/messages/{id}/reply', [ContactMessageController::class, 'sendReply'])->name('customers.messages.reply');
    Route::delete('customers/messages/{id}', [ContactMessageController::class, 'destroy'])->name('customers.messages.destroy');

    // Orders Management
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{id}/payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.payment');
    Route::get('orders/{id}/print', [AdminOrderController::class, 'print'])->name('orders.print');
    Route::delete('orders/{id}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
});

require __DIR__.'/auth.php';
