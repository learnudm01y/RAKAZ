<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\HomePage;
use App\Models\SectionTitle;
use App\Models\DiscoverItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\ShoeSize;
use App\Models\Color;
use App\Models\FeaturedSection;
use App\Models\PerfectGiftSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{
    public function index()
    {
        $homePage = HomePage::getActive();

        if (!$homePage) {
            // Create default home page if not exists
            $homePage = HomePage::create([
                'locale' => app()->getLocale(),
                'is_active' => true,
                'hero_slides' => [],
                'gifts_items' => [],
                'discover_items' => [],
            ]);
        }

        // Get section titles from section_titles table
        $giftsTitle = SectionTitle::getByKey('gifts_section');

        // Get discover items from discover_items table
        $discoverItems = DiscoverItem::active()->ordered()->get();

        // Get featured section with products
        $featuredSection = FeaturedSection::where('is_active', true)
            ->with(['products' => function($query) {
                $query->where('is_active', true)
                      ->with(['brand', 'productSizes', 'productColors', 'productShoeSizes']);
            }])
            ->first();

        // Get perfect gift section with products
        $perfectGiftSection = PerfectGiftSection::where('is_active', true)
            ->with(['products' => function($query) {
                $query->where('is_active', true)
                      ->with(['brand', 'productSizes', 'productColors', 'productShoeSizes']);
            }])
            ->first();

        return view('frontend.index', compact('homePage', 'giftsTitle', 'discoverItems', 'featuredSection', 'perfectGiftSection'));
    }

    public function about()
    {
        $page = Page::where('slug', 'about-us')->where('status', 'active')->firstOrFail();

        // Get statistics settings
        $stats = SiteSetting::where('group', 'stats')->orderBy('order')->get();

        // Get services settings
        $services = SiteSetting::where('group', 'services')->orderBy('order')->get();

        return view('frontend.about', compact('page', 'stats', 'services'));
    }

    public function shop(Request $request)
    {
        $query = Product::where('is_active', true)
            ->with(['category', 'brand']); // Removed productSizes, productShoeSizes, productColors for lazy loading

        // Filter by categories
        if ($request->has('categories') && is_array($request->categories) && count($request->categories) > 0) {
            $categoryIds = array_filter($request->categories);
            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Filter by brands
        if ($request->has('brands') && is_array($request->brands) && count($request->brands) > 0) {
            $brandIds = array_filter($request->brands);
            if (!empty($brandIds)) {
                $query->whereIn('brand_id', $brandIds);
            }
        }

        // Filter by sizes (clothing)
        if ($request->has('sizes') && is_array($request->sizes) && count($request->sizes) > 0) {
            $sizeNames = array_filter($request->sizes);
            if (!empty($sizeNames)) {
                $query->whereHas('productSizes', function($q) use ($sizeNames) {
                    $q->whereIn('sizes.name', $sizeNames);
                });
            }
        }

        // Filter by shoe sizes
        if ($request->has('shoe_sizes') && is_array($request->shoe_sizes) && count($request->shoe_sizes) > 0) {
            $shoeSizeValues = array_filter($request->shoe_sizes);
            if (!empty($shoeSizeValues)) {
                $query->whereHas('productShoeSizes', function($q) use ($shoeSizeValues) {
                    $q->whereIn('shoe_sizes.size', $shoeSizeValues);
                });
            }
        }

        // Filter by colors
        if ($request->has('colors') && is_array($request->colors) && count($request->colors) > 0) {
            $colorNames = array_filter($request->colors);
            if (!empty($colorNames)) {
                $colorIds = [];

                // Get all active colors and filter by name (handles Arabic properly)
                $allColors = Color::where('is_active', true)->get();
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

        // Filter by price range
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

        // For AJAX pagination requests (when clicking on page numbers)
        if ($request->ajax() || $request->wantsJson()) {
            $products = $query->paginate(10);

            // Append filter parameters to pagination links
            $products->appends($request->only([
                'categories',
                'brands',
                'sizes',
                'shoe_sizes',
                'colors',
                'min_price',
                'max_price'
            ]));

            $productsHtml = view('frontend.partials.shop-products-grid', compact('products'))->render();
            $paginationHtml = view('frontend.partials.shop-pagination', compact('products'))->render();

            return response()->json([
                'success' => true,
                'productsHtml' => $productsHtml,
                'paginationHtml' => $paginationHtml,
                'currentPage' => $products->currentPage(),
                'lastPage' => $products->lastPage(),
                'total' => $products->total()
            ]);
        }

        // For initial page load: get only 10 products WITHOUT expensive count query
        $products = $query->limit(10)->get();
        // Get filters data with product counts
        $sizes = Size::where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        $shoeSizes = ShoeSize::where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        $colors = Color::where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        // Get price range
        $minPrice = Product::where('is_active', true)
            ->selectRaw('MIN(COALESCE(sale_price, price)) as min_price')
            ->value('min_price') ?? 0;

        $maxPrice = Product::where('is_active', true)
            ->selectRaw('MAX(COALESCE(sale_price, price)) as max_price')
            ->value('max_price') ?? 30000;

        return view('frontend.shop', compact('products', 'sizes', 'shoeSizes', 'colors', 'minPrice', 'maxPrice'));
    }

    public function category(Request $request, $slug)
    {
        $locale = app()->getLocale();
        $category = Category::where('is_active', true)
            ->whereRaw("JSON_EXTRACT(slug, '$.{$locale}') = ?", [$slug])
            ->firstOrFail();

        $query = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->with(['category', 'brand', 'productSizes', 'productShoeSizes', 'productColors']);

        // Filter by sizes (clothing)
        if ($request->has('sizes') && is_array($request->sizes) && count($request->sizes) > 0) {
            $sizeNames = array_filter($request->sizes);
            if (!empty($sizeNames)) {
                $query->whereHas('productSizes', function($q) use ($sizeNames) {
                    $q->whereIn('sizes.name', $sizeNames);
                });
            }
        }

        // Filter by shoe sizes
        if ($request->has('shoe_sizes') && is_array($request->shoe_sizes) && count($request->shoe_sizes) > 0) {
            $shoeSizeValues = array_filter($request->shoe_sizes);
            if (!empty($shoeSizeValues)) {
                $query->whereHas('productShoeSizes', function($q) use ($shoeSizeValues) {
                    $q->whereIn('shoe_sizes.size', $shoeSizeValues);
                });
            }
        }

        // Filter by colors
        if ($request->has('colors') && is_array($request->colors) && count($request->colors) > 0) {
            $colorNames = array_filter($request->colors);
            if (!empty($colorNames)) {
                $colorIds = [];

                // Get all active colors and filter by name (handles Arabic properly)
                $allColors = Color::where('is_active', true)->get();
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

        // Filter by price range
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

        $products = $query->paginate(10);

        // Append filter parameters to pagination links
        $products->appends($request->only([
            'sizes',
            'shoe_sizes',
            'colors',
            'min_price',
            'max_price'
        ]));

        // Get filters data with product counts for this category
        $sizes = Size::where('is_active', true)
            ->withCount(['products' => function($q) use ($category) {
                $q->where('is_active', true)
                  ->where('category_id', $category->id);
            }])
            ->orderBy('sort_order')
            ->get();

        $shoeSizes = ShoeSize::where('is_active', true)
            ->withCount(['products' => function($q) use ($category) {
                $q->where('is_active', true)
                  ->where('category_id', $category->id);
            }])
            ->orderBy('sort_order')
            ->get();

        $colors = Color::where('is_active', true)
            ->withCount(['products' => function($q) use ($category) {
                $q->where('is_active', true)
                  ->where('category_id', $category->id);
            }])
            ->orderBy('sort_order')
            ->get();

        // Get price range for this category
        $minPrice = Product::where('is_active', true)
            ->where('category_id', $category->id)
            ->selectRaw('MIN(COALESCE(sale_price, price)) as min_price')
            ->value('min_price') ?? 0;

        $maxPrice = Product::where('is_active', true)
            ->where('category_id', $category->id)
            ->selectRaw('MAX(COALESCE(sale_price, price)) as max_price')
            ->value('max_price') ?? 30000;

        return view('frontend.shop', compact('category', 'products', 'sizes', 'shoeSizes', 'colors', 'minPrice', 'maxPrice'));
    }

    public function productDetails($slug)
    {
        $locale = app()->getLocale();

        // Find product by slug in current locale
        $product = Product::where('slug->' . $locale, $slug)
            ->orWhere('slug->ar', $slug)
            ->orWhere('slug->en', $slug)
            ->with(['category', 'brand', 'productColors', 'colorImages'])
            ->firstOrFail();

        // Increment views count
        $product->increment('views_count');

        // Note: relatedProducts and brandProducts are now loaded via AJAX
        // for better performance (see LazyLoadController)

        // If Ajax request, return JSON with all images
        if (request()->wantsJson() || request()->ajax()) {
            $allImages = [];

            // Add main image
            if ($product->main_image) {
                $allImages[] = asset('storage/' . $product->main_image);
            }

            // Add all gallery images
            if ($product->gallery_images && is_array($product->gallery_images)) {
                foreach ($product->gallery_images as $image) {
                    $allImages[] = asset('storage/' . $image);
                }
            }

            // Get sizing info
            $sizingInfo = null;
            if ($product->sizing_info && is_array($product->sizing_info)) {
                $sizingInfo = app()->getLocale() == 'ar'
                    ? ($product->sizing_info['ar'] ?? '')
                    : ($product->sizing_info['en'] ?? $product->sizing_info['ar'] ?? '');
            }

            // Get design details
            $designDetails = null;
            if ($product->design_details && is_array($product->design_details)) {
                $designDetails = app()->getLocale() == 'ar'
                    ? ($product->design_details['ar'] ?? '')
                    : ($product->design_details['en'] ?? $product->design_details['ar'] ?? '');
            }

            // Prepare color images data
            $colorImagesData = [];
            if ($product->colorImages && $product->colorImages->count() > 0) {
                foreach ($product->colorImages as $colorImage) {
                    $color = $colorImage->color;
                    if ($color) {
                        $colorImagesData[] = [
                            'color_id' => $colorImage->color_id,
                            'color_ar' => $color->name['ar'] ?? $color->translated_name,
                            'color_en' => $color->name['en'] ?? $color->translated_name,
                            'image' => $colorImage->image_url,
                            'is_primary' => $colorImage->is_primary
                        ];
                    }
                }
            }

            $response = [
                'id' => $product->id,
                'name' => $product->getName(),
                'brand' => $product->brand,
                'price' => number_format($product->price, 0) . ' ' . (app()->getLocale() == 'ar' ? 'د.إ' : 'AED'),
                'sale_price' => $product->sale_price ? number_format($product->sale_price, 0) . ' ' . (app()->getLocale() == 'ar' ? 'د.إ' : 'AED') : null,
                'is_on_sale' => $product->is_on_sale,
                'images' => $allImages,
                'hasNewSeason' => $product->is_new,
                'sizes' => $product->sizes ?? [],
                'colorImages' => $colorImagesData,
                'description' => $product->getDescription(),
                'sizing_info' => $sizingInfo,
                'design_details' => $designDetails,
                'sku' => $product->sku
            ];

            Log::info('Product API Response', ['product_id' => $product->id, 'sizes' => $product->sizes]);

            return response()->json($response);
        }

        return view('frontend.product-details', compact('product'));
    }

    public function cart()
    {
        return view('frontend.cart');
    }

    public function checkout()
    {
        return view('frontend.checkout');
    }

    public function contact()
    {
        $page = \App\Models\ContactPage::first();
        return view('frontend.contact', compact('page'));
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        \App\Models\ContactMessage::create([
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() == 'ar'
                ? 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.'
                : 'Your message has been sent successfully! We will contact you soon.'
        ]);
    }

    public function wishlist()
    {
        $wishlistItems = auth()->check()
            ? \App\Models\Wishlist::with([
                'product.brand',
                'product.category',
                'product.colorImages'
            ])->where('user_id', auth()->id())->get()
            : collect();

        return view('frontend.wishlist', compact('wishlistItems'));
    }

    public function orders()
    {
        $orders = \App\Models\Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.orders', compact('orders'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('frontend.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success',
            app()->getLocale() == 'ar'
                ? 'تم تحديث الملف الشخصي بنجاح'
                : 'Profile updated successfully'
        );
    }

    public function privacyPolicy()
    {
        $page = \App\Models\PrivacyPolicyPage::first();
        return view('frontend.privacy-policy', compact('page'));
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('q', '');
        $products = collect();
        $categories = collect();

        if (!empty($searchQuery)) {
            // Search in products (by name in both languages)
            $products = Product::where('is_active', true)
                ->where(function($query) use ($searchQuery) {
                    $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar')) LIKE ?", ["%{$searchQuery}%"])
                          ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$searchQuery}%"])
                          ->orWhere('brand', 'LIKE', "%{$searchQuery}%")
                          ->orWhere('sku', 'LIKE', "%{$searchQuery}%");
                })
                ->with(['category', 'brand', 'productSizes', 'productShoeSizes', 'productColors'])
                ->paginate(24);

            // Search in categories (by name in both languages)
            $categories = Category::where('is_active', true)
                ->where(function($query) use ($searchQuery) {
                    $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar')) LIKE ?", ["%{$searchQuery}%"])
                          ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$searchQuery}%"]);
                })
                ->get();
        }

        return view('frontend.search', compact('searchQuery', 'products', 'categories'));
    }

    public function searchSuggestions(Request $request)
    {
        $searchQuery = $request->input('q', '');
        $locale = app()->getLocale();

        if (empty($searchQuery) || strlen($searchQuery) < 2) {
            return response()->json([
                'products' => [],
                'categories' => []
            ]);
        }

        // Search products (limit to 5)
        $products = Product::where('is_active', true)
            ->where(function($query) use ($searchQuery) {
                $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar')) LIKE ?", ["%{$searchQuery}%"])
                      ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$searchQuery}%"])
                      ->orWhere('brand', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('sku', 'LIKE', "%{$searchQuery}%");
            })
            ->limit(5)
            ->get()
            ->map(function($product) use ($locale) {
                return [
                    'name' => $product->getName($locale),
                    'url' => route('product.details', $product->getSlug($locale)),
                    'image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                    'price' => $product->sale_price ?
                        number_format($product->sale_price, 0) . ' ' . ($locale == 'ar' ? 'د.إ' : 'AED') :
                        number_format($product->price, 0) . ' ' . ($locale == 'ar' ? 'د.إ' : 'AED')
                ];
            });

        // Search categories (limit to 3)
        $categories = Category::where('is_active', true)
            ->where(function($query) use ($searchQuery) {
                $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar')) LIKE ?", ["%{$searchQuery}%"])
                      ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$searchQuery}%"]);
            })
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->limit(3)
            ->get()
            ->map(function($category) use ($locale) {
                return [
                    'name' => $category->getName($locale),
                    'url' => route('category.show', $category->getSlug($locale)),
                    'products_count' => $category->products_count
                ];
            });

        return response()->json([
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function loadMoreCategories(Request $request)
    {
        $skip = $request->input('skip', 10);
        $take = $request->input('take', 10);

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->whereHas('products', function($query) {
                $query->where('is_active', true);
            })
            ->orderBy('sort_order')
            ->skip($skip)
            ->take($take)
            ->get();

        $html = '';
        foreach ($categories as $category) {
            $productsCount = $category->products()->where('is_active', true)->count();
            $categoryName = $category->getName(app()->getLocale());
            $categorySlug = $category->getSlug(app()->getLocale());

            $html .= '<label class="category-checkbox-item">';
            $html .= '<span class="custom-checkbox">';
            $html .= '<input type="checkbox" name="category" value="' . $category->id . '" data-slug="' . $categorySlug . '">';
            $html .= '<span class="checkbox-mark">';
            $html .= '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">';
            $html .= '<polyline points="20 6 9 17 4 12"></polyline>';
            $html .= '</svg>';
            $html .= '</span>';
            $html .= '</span>';
            $html .= '<span class="category-label">';
            $html .= '<span class="category-text">' . htmlspecialchars($categoryName) . '</span>';
            $html .= '<span class="category-count">(' . $productsCount . ')</span>';
            $html .= '</span>';
            $html .= '</label>';
        }

        return response()->json([
            'html' => $html,
            'hasMore' => Category::where('is_active', true)
                ->whereNull('parent_id')
                ->count() > ($skip + $take)
        ]);
    }

    public function loadMoreBrands(Request $request)
    {
        $skip = $request->input('skip', 10);
        $take = $request->input('take', 10);

        $brands = \App\Models\Brand::where('is_active', true)
            ->whereHas('products', function($query) {
                $query->where('is_active', true);
            })
            ->orderBy('name_ar')
            ->skip($skip)
            ->take($take)
            ->get();

        $html = '';
        foreach ($brands as $brand) {
            $productsCount = $brand->products()->where('is_active', true)->count();

            $html .= '<label class="category-checkbox-item">';
            $html .= '<span class="custom-checkbox">';
            $html .= '<input type="checkbox" name="brand" value="' . $brand->id . '" data-slug="' . $brand->slug . '">';
            $html .= '<span class="checkbox-mark">';
            $html .= '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">';
            $html .= '<polyline points="20 6 9 17 4 12"></polyline>';
            $html .= '</svg>';
            $html .= '</span>';
            $html .= '</span>';
            $html .= '<span class="category-label">';
            $html .= '<span class="category-text">' . htmlspecialchars($brand->getName()) . '</span>';
            $html .= '<span class="category-count">(' . $productsCount . ')</span>';
            $html .= '</span>';
            $html .= '</label>';
        }

        return response()->json([
            'html' => $html,
            'hasMore' => \App\Models\Brand::where('is_active', true)
                ->whereHas('products', function($query) {
                    $query->where('is_active', true);
                })
                ->count() > ($skip + $take)
        ]);
    }

    public function loadMoreMobileMenuItems(Request $request)
    {
        $menuId = $request->input('menu_id');
        $offset = $request->input('offset', 5);
        $limit = $request->input('limit', 5);

        Log::info('Load More Request', [
            'menu_id' => $menuId,
            'offset' => $offset,
            'limit' => $limit
        ]);

        // الحصول على القائمة
        $menu = \App\Models\Menu::find($menuId);

        if (!$menu) {
            Log::warning('Menu not found in database', ['menu_id' => $menuId]);
            return response()->json([
                'success' => false,
                'message' => 'القائمة غير موجودة في قاعدة البيانات'
            ]);
        }

        if (!$menu->menu_data) {
            Log::warning('Menu has no menu_data', ['menu_id' => $menuId, 'menu_name' => $menu->name]);
            return response()->json([
                'success' => false,
                'message' => 'القاإمة لا تحتوي على بيانات (menu_data فارغ)'
            ]);
        }

        $menuData = json_decode($menu->menu_data, true);
        if (!$menuData) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات القائمة غير صحيحة'
            ]);
        }

        // تجميع كل العناصر من جميع الأعمدة
        $allItems = [];
        foreach ($menuData as $column) {
            if (isset($column['items']) && is_array($column['items'])) {
                foreach ($column['items'] as $item) {
                    $allItems[] = [
                        'column_title_ar' => $column['title_ar'] ?? '',
                        'column_title_en' => $column['title_en'] ?? '',
                        'item' => $item
                    ];
                }
            }
        }

        // جلب العناصر المطلوبة فقط
        $items = array_slice($allItems, $offset, $limit);
        $totalItems = count($allItems);
        $remainingItems = $totalItems - ($offset + count($items));

        return response()->json([
            'success' => true,
            'items' => $items,
            'hasMore' => $remainingItems > 0,
            'remaining' => $remainingItems,
            'loaded' => count($items),
            'total' => $totalItems
        ]);
    }

    public function allMenus(Request $request)
    {
        // استخدام pagination مع 3 قوائم في كل صفحة
        $menus = \App\Models\Menu::where('is_active', true)
            ->with(['activeColumns' => function ($query) {
                $query->with(['items' => function ($q) {
                    $q->where('is_active', true)
                      ->orderBy('sort_order')
                      ->with(['category' => function ($categoryQuery) {
                          $categoryQuery->with(['children' => function ($childQuery) {
                              $childQuery->where('is_active', true)
                                        ->orderBy('sort_order');
                          }]);
                      }]);
                }]);
            }])
            ->orderBy('sort_order')
            ->paginate(2); // قائمتين فقط في كل صفحة

        // إذا كان الطلب AJAX، نرجع JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('partials.menu-items', compact('menus'))->render(),
                'hasMore' => $menus->hasMorePages(),
                'nextPage' => $menus->currentPage() + 1
            ]);
        }

        return view('all-menus', compact('menus'));
    }

    public function loadDesktopMenuItems(Request $request)
    {
        $menuId = $request->input('menu_id');

        Log::info('Load Desktop Menu Items Request', [
            'menu_id' => $menuId
        ]);

        $menu = \App\Models\Menu::find($menuId);

        if (!$menu || !$menu->menu_data) {
            return response()->json([
                'success' => false,
                'message' => 'القائمة غير موجودة'
            ]);
        }

        $allMenuData = json_decode($menu->menu_data, true);

        // Prepare desktop menu data with 13 rows limit per column
        $desktopMenuData = [];

        foreach ($allMenuData as $column) {
            $maxRows = 13;
            $rowCount = 0;
            $limitedItems = [];

            foreach ($column['items'] as $item) {
                // Calculate how many rows this item will take
                $itemRows = 1 + (isset($item['children']) ? count($item['children']) : 0);

                if ($rowCount + $itemRows <= $maxRows) {
                    $limitedItems[] = $item;
                    $rowCount += $itemRows;
                } else {
                    break;
                }
            }

            $desktopMenuData[] = [
                'title_ar' => $column['title_ar'],
                'title_en' => $column['title_en'],
                'items' => $limitedItems,
                'has_more' => count($column['items']) > count($limitedItems)
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $desktopMenuData
        ]);
    }
}
