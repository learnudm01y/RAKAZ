<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Auth\UserAuthController;
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
Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
Route::get('/checkout', [FrontendController::class, 'checkout'])->name('checkout');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact/submit', [FrontendController::class, 'submitContact'])->name('contact.submit');
Route::get('/wishlist', [FrontendController::class, 'wishlist'])->name('wishlist')->middleware(['auth', \App\Http\Middleware\SaveIntendedUrl::class]);
Route::get('/orders', [FrontendController::class, 'orders'])->name('orders')->middleware(['auth', \App\Http\Middleware\SaveIntendedUrl::class]);
Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacy.policy');

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
Route::view('dashboard', 'dashboard')
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
});

require __DIR__.'/auth.php';
