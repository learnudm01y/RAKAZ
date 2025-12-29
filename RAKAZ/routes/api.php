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
Route::get('/lazy-load/hero-banner', [LazyLoadController::class, 'getHeroBanner']);
Route::get('/lazy-load/currency-dropdown', [LazyLoadController::class, 'getCurrencyDropdown']);
Route::get('/lazy-load/featured-section', [LazyLoadController::class, 'getFeaturedSection']);
Route::get('/lazy-load/perfect-gift-section', [LazyLoadController::class, 'getPerfectGiftSection']);
Route::get('/lazy-load/footer', [LazyLoadController::class, 'getFooter']);
Route::get('/lazy-load/discover-section', [LazyLoadController::class, 'getDiscoverSection']);
Route::get('/lazy-load/related-products/{productId}', [LazyLoadController::class, 'getRelatedProducts']);
Route::get('/lazy-load/brand-products/{productId}', [LazyLoadController::class, 'getBrandProducts']);
Route::get('/lazy-load/shop-sidebar', [LazyLoadController::class, 'getShopSidebar']);
Route::get('/lazy-load/product-hover/{productId}', [LazyLoadController::class, 'getProductHoverContent']);
Route::get('/lazy-load/shop-pagination', [LazyLoadController::class, 'getShopPagination']);
Route::get('/lazy-load/home-product-overlay/{productId}', [LazyLoadController::class, 'getHomeProductOverlay']);

// Product API for Wishlist Modal
Route::get('/products/{id}', function ($id) {
    try {
        $product = \App\Models\Product::with(['productSizes', 'productShoeSizes'])->find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // جلب الأحجام - productSizes returns Size models directly (many-to-many)
        $sizes = [];
        $productSizesData = [];

        if ($product->productSizes && $product->productSizes->count() > 0) {
            foreach ($product->productSizes as $size) {
                // $size is a Size model object directly
                $sizeId = $size->id;
                $sizeName = $size->name; // e.g., "S", "M", "L"

                $sizes[] = $sizeName;
                $productSizesData[] = [
                    'size_id' => $sizeId,
                    'name' => $sizeName
                ];
            }
        }

        // جلب أحجام الأحذية - productShoeSizes returns ShoeSize models directly
        $productShoeSizesData = [];
        if ($product->productShoeSizes && $product->productShoeSizes->count() > 0) {
            foreach ($product->productShoeSizes as $shoeSize) {
                // $shoeSize is a ShoeSize model object directly
                $shoeSizeId = $shoeSize->id;
                $shoeSizeValue = $shoeSize->size; // e.g., "40", "41", "42"

                if (empty($sizes)) {
                    $sizes[] = $shoeSizeValue;
                }
                $productShoeSizesData[] = [
                    'shoe_size_id' => $shoeSizeId,
                    'size' => $shoeSizeValue
                ];
            }
        }

        // جلب الألوان
        $productColors = collect([]);
        if ($product->productColors && $product->productColors->count() > 0) {
            $productColors = $product->productColors->map(function($color) {
                // $color is a Color model object directly
                return [
                    'color_id' => $color->id,
                    'name' => $color->name
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

        // If no sizes from relations, check legacy sizes field
        if (empty($sizes) && $product->sizes && is_array($product->sizes)) {
            $sizes = $product->sizes;
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

        // Get brand name properly - brand can be a Brand model object or array
        $brandName = '';
        if ($product->brand_id && $product->brand) {
            // Brand is a relationship (Brand model)
            $brandModel = $product->brand;
            if (is_object($brandModel) && method_exists($brandModel, 'getName')) {
                $brandName = $brandModel->getName();
            } elseif (is_object($brandModel) && isset($brandModel->name)) {
                $brandName = is_array($brandModel->name)
                    ? ($brandModel->name[app()->getLocale()] ?? $brandModel->name['ar'] ?? '')
                    : $brandModel->name;
            }
        } elseif (is_array($product->getAttributes()['brand'] ?? null)) {
            // Legacy: brand is stored as array in product table
            $brandName = $product->getAttributes()['brand'][app()->getLocale()] ?? $product->getAttributes()['brand']['ar'] ?? '';
        } elseif (is_string($product->getAttributes()['brand'] ?? null)) {
            // Legacy: brand is stored as string in product table
            $brandName = $product->getAttributes()['brand'];
        }

        return response()->json([
            'id' => $product->id,
            'name' => is_array($product->name)
                ? ($product->name[app()->getLocale()] ?? $product->name['ar'] ?? '')
                : $product->name,
            'brand' => $brandName,
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
            'productSizes' => $productSizesData,
            'productShoeSizes' => $productShoeSizesData,
            'productColors' => $productColors,
            'colorImages' => $colorImages
        ]);
    } catch (Exception $e) {
        Log::error('Product API Error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
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

// Image Compression API for Admin Products
Route::post('/admin/compress-image', function (Request $request) {
    try {
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No image provided'], 400);
        }

        $file = $request->file('image');
        $type = $request->input('type', 'main'); // main, hover, gallery, color

        // Validate image
        if (!$file->isValid() || !in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])) {
            return response()->json(['error' => 'Invalid image file'], 400);
        }

        // Determine storage directory based on type
        $directories = [
            // Products
            'main' => 'products/temp',
            'hover' => 'products/temp',
            'gallery' => 'products/gallery/temp',
            'color' => 'products/colors/temp',
            // Home Page
            'hero' => 'home-page/hero/temp',
            'hero_tablet' => 'home-page/hero/tablet/temp',
            'hero_mobile' => 'home-page/hero/mobile/temp',
            'cyber_sale' => 'home-page/cyber-sale/temp',
            'cyber_sale_tablet' => 'home-page/cyber-sale/tablet/temp',
            'cyber_sale_mobile' => 'home-page/cyber-sale/mobile/temp',
            'gift' => 'home-page/gifts/temp',
            'dg_banner' => 'home-page/dg-banner/temp',
            'dg_banner_tablet' => 'home-page/dg-banner/tablet/temp',
            'dg_banner_mobile' => 'home-page/dg-banner/mobile/temp',
            'gucci_spotlight' => 'home-page/gucci-spotlight/temp',
            'gucci_spotlight_tablet' => 'home-page/gucci-spotlight/tablet/temp',
            'gucci_spotlight_mobile' => 'home-page/gucci-spotlight/mobile/temp',
            // About Page
            'about_story' => 'about/temp',
        ];
        $directory = $directories[$type] ?? 'uploads/temp';

        // Use ImageCompressionService
        $imageService = app(\App\Services\ImageCompressionService::class);
        $path = $imageService->compressAndStore($file, $directory);

        // Get file info
        $fullPath = storage_path('app/public/' . $path);
        $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
        $fileSizeKB = round($fileSize / 1024, 2);

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/' . $path),
            'size_bytes' => $fileSize,
            'size_kb' => $fileSizeKB,
            'message' => 'Image compressed successfully'
        ]);
    } catch (Exception $e) {
        Log::error('Image compression error: ' . $e->getMessage());
        return response()->json([
            'error' => 'Compression failed: ' . $e->getMessage()
        ], 500);
    }
})->middleware('web');
