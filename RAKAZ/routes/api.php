<?php

use App\Http\Controllers\Api\LazyLoadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Lazy Load API Routes
Route::get('/lazy-load/featured-section', [LazyLoadController::class, 'getFeaturedSection']);
Route::get('/lazy-load/perfect-gift-section', [LazyLoadController::class, 'getPerfectGiftSection']);
Route::get('/lazy-load/footer', [LazyLoadController::class, 'getFooter']);
Route::get('/lazy-load/related-products/{productId}', [LazyLoadController::class, 'getRelatedProducts']);
Route::get('/lazy-load/brand-products/{productId}', [LazyLoadController::class, 'getBrandProducts']);

// Product API for Wishlist Modal
Route::get('/products/{id}', function ($id) {
    try {
        $product = \App\Models\Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // جلب الأحجام
        $productSizes = collect([]);
        if ($product->productSizes && $product->productSizes->count() > 0) {
            $productSizes = $product->productSizes->map(function($ps) {
                $sizeName = $ps->size_id; // القيمة الافتراضية

                // محاولة الحصول على اسم المقاس من جدول sizes
                $size = \App\Models\Size::find($ps->size_id);
                if ($size && $size->name) {
                    $sizeName = $size->name;
                }

                return [
                    'size_id' => $ps->size_id,
                    'name' => $sizeName
                ];
            });
        }

        // جلب أحجام الأحذية
        $productShoeSizes = collect([]);
        if ($product->productShoeSizes && $product->productShoeSizes->count() > 0) {
            $productShoeSizes = $product->productShoeSizes->map(function($ps) {
                $shoeSize = $ps->shoe_size_id; // القيمة الافتراضية

                // محاولة الحصول على حجم الحذاء من جدول shoe_sizes
                $shoeSizeModel = \App\Models\ShoeSize::find($ps->shoe_size_id);
                if ($shoeSizeModel && $shoeSizeModel->size) {
                    $shoeSize = $shoeSizeModel->size;
                }

                return [
                    'shoe_size_id' => $ps->shoe_size_id,
                    'size' => $shoeSize
                ];
            });
        }

        // جلب الألوان
        $productColors = collect([]);
        if ($product->productColors && $product->productColors->count() > 0) {
            $productColors = $product->productColors->map(function($pc) {
                $colorName = $pc->color_id; // القيمة الافتراضية

                // محاولة الحصول على اسم اللون من جدول colors
                $color = \App\Models\Color::find($pc->color_id);
                if ($color && $color->name) {
                    $colorName = $color->name;
                }

                return [
                    'color_id' => $pc->color_id,
                    'name' => $colorName
                ];
            });
        }

        // بناء قائمة الصور الكاملة (الصورة الرئيسية + صور المعرض)
        $allImages = [];
        if ($product->main_image) {
            $allImages[] = asset('storage/' . $product->main_image);
        }
        if ($product->gallery_images && is_array($product->gallery_images)) {
            foreach ($product->gallery_images as $galleryImg) {
                $allImages[] = asset('storage/' . $galleryImg);
            }
        }

        // استخراج أسماء المقاسات فقط
        $sizes = [];
        if ($product->sizes && is_array($product->sizes)) {
            $sizes = $product->sizes;
        } elseif ($productSizes->count() > 0) {
            $sizes = $productSizes->pluck('name')->toArray();
        } elseif ($productShoeSizes->count() > 0) {
            $sizes = $productShoeSizes->pluck('size')->toArray();
        }

        // جلب صور الألوان
        $colorImages = [];
        if ($product->colorImages && $product->colorImages->count() > 0) {
            $colorImages = $product->colorImages->map(function($colorImage) {
                $color = \App\Models\Color::find($colorImage->color_id);
                $colorNameAr = $color && isset($color->name['ar']) ? $color->name['ar'] : '';
                $colorNameEn = $color && isset($color->name['en']) ? $color->name['en'] : '';

                return [
                    'color_id' => $colorImage->color_id,
                    'color_ar' => $colorNameAr,
                    'color_en' => $colorNameEn,
                    'image' => $colorImage->image ? asset('storage/' . $colorImage->image) : null,
                    'sort_order' => $colorImage->sort_order ?? 0
                ];
            })->sortBy('sort_order')->values()->toArray();
        }

        return response()->json([
            'id' => $product->id,
            'name' => is_array($product->name)
                ? ($product->name[app()->getLocale()] ?? $product->name['ar'] ?? '')
                : $product->name,
            'brand' => is_array($product->brand)
                ? ($product->brand[app()->getLocale()] ?? $product->brand['ar'] ?? '')
                : $product->brand,
            'price' => number_format($product->price, 0) . ' ' . (app()->getLocale() == 'ar' ? 'د.إ' : 'AED'),
            'sale_price' => $product->sale_price
                ? number_format($product->sale_price, 0) . ' ' . (app()->getLocale() == 'ar' ? 'د.إ' : 'AED')
                : null,
            'is_on_sale' => $product->is_on_sale ?? false,
            'images' => $allImages,
            'image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
            'sizes' => $sizes,
            'description' => is_array($product->description)
                ? ($product->description[app()->getLocale()] ?? $product->description['ar'] ?? '')
                : ($product->description ?? ''),
            'sizing_info' => is_array($product->sizing_info)
                ? ($product->sizing_info[app()->getLocale()] ?? $product->sizing_info['ar'] ?? '')
                : ($product->sizing_info ?? ''),
            'design_details' => is_array($product->design_details)
                ? ($product->design_details[app()->getLocale()] ?? $product->design_details['ar'] ?? '')
                : ($product->design_details ?? ''),
            'is_new' => $product->is_new ?? false,
            'is_featured' => $product->is_featured ?? false,
            'productSizes' => $productSizes,
            'productShoeSizes' => $productShoeSizes,
            'productColors' => $productColors,
            'colorImages' => $colorImages
        ]);
    } catch (\Exception $e) {
        \Log::error('Product API Error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'error' => 'Internal server error',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Health Check Route for Testing
Route::get('/health-check', function () {
    try {
        Log::info('🏥 Health check endpoint called');

        $dbConnection = DB::connection()->getPdo();
        $dbName = DB::connection()->getDatabaseName();

        $featuredSection = \App\Models\FeaturedSection::first();
        $productCount = \App\Models\Product::count();

        return response()->json([
            'status' => 'ok',
            'database' => [
                'connected' => true,
                'name' => $dbName,
                'driver' => config('database.default')
            ],
            'featured_section' => [
                'exists' => $featuredSection ? true : false,
                'id' => $featuredSection->id ?? null,
                'title_ar' => $featuredSection->title['ar'] ?? null,
                'products_count' => $featuredSection->products()->count() ?? 0
            ],
            'products' => [
                'total' => $productCount
            ],
            'timestamp' => now()->toDateTimeString()
        ]);
    } catch (Exception $e) {
        Log::error('❌ Health check failed: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
