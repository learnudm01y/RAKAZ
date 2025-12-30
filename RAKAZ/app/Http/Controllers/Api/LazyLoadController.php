<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeaturedSection;
use App\Models\PerfectGiftSection;
use Illuminate\Http\Request;

class LazyLoadController extends Controller
{
    /**
     * Get Featured Section data
     */
    public function getFeaturedSection()
    {
        $featuredSection = FeaturedSection::with(['products' => function($query) {
            $query->with(['brand', 'productColors', 'productSizes', 'productShoeSizes']);
        }])->first();

        if (!$featuredSection || $featuredSection->products->count() === 0) {
            return response()->json(['html' => '']);
        }

        $html = view('frontend.partials.featured-section-content', [
            'featuredSection' => $featuredSection
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Get Perfect Gift Section data
     */
    public function getPerfectGiftSection()
    {
        $perfectGiftSection = PerfectGiftSection::with(['products' => function($query) {
            $query->with(['brand', 'productColors', 'productSizes', 'productShoeSizes']);
        }])->first();

        if (!$perfectGiftSection || $perfectGiftSection->products->count() === 0) {
            return response()->json(['html' => '']);
        }

        $html = view('frontend.partials.perfect-gift-section-content', [
            'perfectGiftSection' => $perfectGiftSection
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Get Footer data
     */
    public function getFooter(Request $request)
    {
        // Set locale from request
        $locale = $request->get('locale', $request->header('Accept-Language', 'ar'));
        if (in_array($locale, ['ar', 'en'])) {
            app()->setLocale($locale);
        }

        $homePage = \App\Models\HomePage::getActive();

        $html = view('frontend.partials.footer-content', compact('homePage'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Get Related Products (You May Also Like)
     */
    public function getRelatedProducts($productId)
    {
        $product = \App\Models\Product::find($productId);

        if (!$product) {
            return response()->json(['html' => '']);
        }

        $relatedProducts = \App\Models\Product::where('id', '!=', $productId)
            ->where('category_id', $product->category_id)
            ->where('is_active', true)
            ->with(['productColors', 'productSizes', 'productShoeSizes', 'brand'])
            ->inRandomOrder()
            ->limit(8)
            ->get();

        if ($relatedProducts->count() === 0) {
            return response()->json(['html' => '']);
        }

        $html = view('frontend.partials.related-products-content', [
            'relatedProducts' => $relatedProducts,
            'title' => app()->getLocale() == 'ar' ? 'قد يعجبك أيضاً' : 'You May Also Like'
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Get Brand Products (Top Rated from Brand)
     */
    public function getBrandProducts($productId)
    {
        $product = \App\Models\Product::find($productId);

        if (!$product) {
            return response()->json(['html' => '']);
        }

        // Try to get products from featured section first
        $brandProducts = collect([]);
        $featuredSection = \App\Models\FeaturedSection::where('is_active', true)->first();

        if ($featuredSection && $featuredSection->products->count() > 0) {
            $brandProducts = $featuredSection->products()
                ->where('products.id', '!=', $productId)
                ->where('products.is_active', true)
                ->with(['productColors', 'productSizes', 'productShoeSizes', 'brand'])
                ->limit(8)
                ->get();
        }

        // Fallback to highest rated products if no featured products
        if ($brandProducts->isEmpty()) {
            $brandProducts = \App\Models\Product::where('id', '!=', $productId)
                ->where('is_active', true)
                ->where('rating_average', '>', 0)
                ->with(['productColors', 'productSizes', 'productShoeSizes', 'brand'])
                ->orderByDesc('rating_average')
                ->orderByDesc('rating_count')
                ->limit(8)
                ->get();
        }

        if ($brandProducts->count() === 0) {
            return response()->json(['html' => '']);
        }

        $html = view('frontend.partials.related-products-content', [
            'relatedProducts' => $brandProducts,
            'title' => app()->getLocale() == 'ar' ? 'الأكثر مبيعاً من ركاز' : 'Top Rated from Rakaz'
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Get Product Hover Content
     */
    public function getProductHoverContent($productId)
    {
        $product = \App\Models\Product::with(['productColors', 'colorImages.color', 'productSizes', 'productShoeSizes'])
            ->find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Prepare product colors
        $productColors = [];
        if($product->productColors && $product->productColors->count() > 0) {
            $productColors = $product->productColors;
        } elseif($product->colors && is_array($product->colors) && count($product->colors) > 0) {
            $productColors = collect($product->colors);
        }

        $hasSizes = $product->sizes && is_array($product->sizes) && count($product->sizes) > 0;

        // Get hover image
        $hoverImage = null;
        if ($product->hover_image) {
            $hoverImage = asset('storage/' . $product->hover_image);
        } elseif ($product->main_image) {
            $hoverImage = asset('storage/' . $product->main_image);
        }

        $html = view('frontend.partials.product-hover-content', compact('product', 'productColors', 'hasSizes', 'hoverImage'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'hoverImage' => $hoverImage
        ]);
    }

    /**
     * Get Shop Sidebar data
     */
    public function getShopSidebar(Request $request)
    {
        // Set locale from request
        $locale = $request->get('locale', $request->header('Accept-Language', 'ar'));
        if (in_array($locale, ['ar', 'en'])) {
            app()->setLocale($locale);
        }

        $sizes = \App\Models\Size::where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        $shoeSizes = \App\Models\ShoeSize::where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        $colors = \App\Models\Color::where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        $minPrice = \App\Models\Product::where('is_active', true)
            ->selectRaw('MIN(COALESCE(sale_price, price)) as min_price')
            ->value('min_price') ?? 0;

        $maxPrice = \App\Models\Product::where('is_active', true)
            ->selectRaw('MAX(COALESCE(sale_price, price)) as max_price')
            ->value('max_price') ?? 30000;

        $html = view('frontend.partials.shop-sidebar-content', compact('sizes', 'shoeSizes', 'colors', 'minPrice', 'maxPrice'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Get Shop Pagination (lazy loaded after 1.5 seconds)
     * This performs the expensive COUNT query separately
     */
    public function getShopPagination(Request $request)
    {
        $query = \App\Models\Product::where('is_active', true);

        // Apply all filters from request
        if ($request->has('categories') && is_array($request->categories) && count($request->categories) > 0) {
            $categoryIds = array_filter($request->categories);
            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($request->has('brands') && is_array($request->brands) && count($request->brands) > 0) {
            $brandIds = array_filter($request->brands);
            if (!empty($brandIds)) {
                $query->whereIn('brand_id', $brandIds);
            }
        }

        if ($request->has('sizes') && is_array($request->sizes) && count($request->sizes) > 0) {
            $sizeNames = array_filter($request->sizes);
            if (!empty($sizeNames)) {
                $query->whereHas('productSizes', function($q) use ($sizeNames) {
                    $q->whereIn('sizes.name', $sizeNames);
                });
            }
        }

        if ($request->has('shoe_sizes') && is_array($request->shoe_sizes) && count($request->shoe_sizes) > 0) {
            $shoeSizeValues = array_filter($request->shoe_sizes);
            if (!empty($shoeSizeValues)) {
                $query->whereHas('productShoeSizes', function($q) use ($shoeSizeValues) {
                    $q->whereIn('shoe_sizes.size', $shoeSizeValues);
                });
            }
        }

        if ($request->has('colors') && is_array($request->colors) && count($request->colors) > 0) {
            $colorNames = array_filter($request->colors);
            if (!empty($colorNames)) {
                $colorIds = [];
                $allColors = \App\Models\Color::where('is_active', true)->get();

                foreach ($allColors as $color) {
                    $arName = strtolower($color->name['ar'] ?? '');
                    $enName = strtolower($color->name['en'] ?? '');

                    foreach ($colorNames as $searchName) {
                        $searchLower = strtolower($searchName);
                        if ($arName === $searchLower || $enName === $searchLower) {
                            $colorIds[] = $color->id;
                            break;
                        }
                    }
                }

                if (!empty($colorIds)) {
                    $query->whereHas('productColors', function($q) use ($colorIds) {
                        $q->whereIn('colors.id', $colorIds);
                    });
                }
            }
        }

        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where(function($q) use ($request) {
                $q->where(function($subQ) use ($request) {
                    $subQ->whereNotNull('sale_price')
                         ->where('sale_price', '>=', $request->min_price);
                })->orWhere(function($subQ) use ($request) {
                    $subQ->whereNull('sale_price')
                         ->where('price', '>=', $request->min_price);
                });
            });
        }

        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where(function($q) use ($request) {
                $q->where(function($subQ) use ($request) {
                    $subQ->whereNotNull('sale_price')
                         ->where('sale_price', '<=', $request->max_price);
                })->orWhere(function($subQ) use ($request) {
                    $subQ->whereNull('sale_price')
                         ->where('price', '<=', $request->max_price);
                });
            });
        }

        // Paginate products - THIS is the expensive COUNT query
        $products = $query->paginate(10);

        $products->appends($request->only([
            'categories',
            'brands',
            'sizes',
            'shoe_sizes',
            'colors',
            'min_price',
            'max_price'
        ]));

        // Set the path to /shop so pagination links go to the correct route
        $products->setPath('/shop');

        $html = view('frontend.partials.shop-pagination', compact('products'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'currentPage' => $products->currentPage(),
            'lastPage' => $products->lastPage(),
            'total' => $products->total()
        ]);
    }

    /**
     * Get Home Product Overlay Content (for featured & perfect gift sections)
     */
    public function getHomeProductOverlay($productId)
    {
        $product = \App\Models\Product::with(['productColors', 'productSizes', 'productShoeSizes', 'brand'])
            ->find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Get secondary image (hover image)
        $secondaryImage = null;
        if ($product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0) {
            $secondaryImage = asset('storage/' . $product->gallery_images[0]);
        } elseif ($product->main_image) {
            $secondaryImage = asset('storage/' . $product->main_image);
        }

        // Render overlay info content
        $overlayHtml = view('frontend.partials.home-overlay-content', compact('product'))->render();

        return response()->json([
            'success' => true,
            'overlayHtml' => $overlayHtml,
            'secondaryImage' => $secondaryImage
        ]);
    }

    /**
     * Get Discover Section data
     */
    public function getDiscoverSection(Request $request)
    {
        // Set locale from request
        $locale = $request->get('locale', $request->header('Accept-Language', 'ar'));
        if (in_array($locale, ['ar', 'en'])) {
            app()->setLocale($locale);
        }

        $discoverItems = \App\Models\DiscoverItem::active()->ordered()->get();

        if ($discoverItems->count() === 0) {
            return response()->json(['success' => false, 'html' => '']);
        }

        $html = view('frontend.partials.discover-section-content', [
            'discoverItems' => $discoverItems
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Get Hero Banner data
     */
    public function getHeroBanner()
    {
        $homePage = \App\Models\HomePage::getActive();

        if (!$homePage || !$homePage->hero_slides || count($homePage->hero_slides) === 0) {
            return response()->json(['success' => false, 'html' => '']);
        }

        $html = view('frontend.partials.hero-banner-content', [
            'homePage' => $homePage
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Get Currency Dropdown data
     */
    public function getCurrencyDropdown()
    {
        $html = view('partials.currency-dropdown-content')->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}

