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
use Illuminate\Http\Request;

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

        return view('frontend.index', compact('homePage', 'giftsTitle', 'discoverItems'));
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
            ->with(['category', 'productSizes', 'productShoeSizes', 'productColors']);

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
            ->with(['category', 'productSizes', 'productShoeSizes', 'productColors']);

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
            ->with('category')
            ->firstOrFail();

        // Increment views count
        $product->increment('views_count');

        // Get related products from same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Get more products from same brand if available
        $brandProducts = [];
        if ($product->brand) {
            $brandProducts = Product::where('brand', $product->brand)
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->inRandomOrder()
                ->limit(8)
                ->get();
        }

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

            $response = [
                'id' => $product->id,
                'name' => $product->getName(),
                'brand' => $product->brand,
                'price' => number_format($product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price, 0) . ' ' . (app()->getLocale() == 'ar' ? 'د.إ' : 'AED'),
                'images' => $allImages,
                'hasNewSeason' => $product->is_new,
                'sizes' => $product->sizes ?? [],
                'description' => $product->getDescription(),
                'sizing_info' => $sizingInfo,
                'design_details' => $designDetails,
                'sku' => $product->sku
            ];

            \Log::info('Product API Response', ['product_id' => $product->id, 'sizes' => $product->sizes]);

            return response()->json($response);
        }

        return view('frontend.product-details', compact('product', 'relatedProducts', 'brandProducts'));
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
            ? \App\Models\Wishlist::with('product')->where('user_id', auth()->id())->get()
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

    public function privacyPolicy()
    {
        $page = \App\Models\PrivacyPolicyPage::first();
        return view('frontend.privacy-policy', compact('page'));
    }
}
