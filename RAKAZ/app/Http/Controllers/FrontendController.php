<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\HomePage;
use App\Models\SectionTitle;
use App\Models\DiscoverItem;
use App\Models\Category;
use App\Models\Product;
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

    public function shop()
    {
        $products = Product::where('is_active', true)
            ->with('category')
            ->paginate(12);

        return view('frontend.shop', compact('products'));
    }

    public function category($slug)
    {
        $locale = app()->getLocale();
        $category = Category::where('is_active', true)
            ->whereRaw("JSON_EXTRACT(slug, '$.{$locale}') = ?", [$slug])
            ->firstOrFail();

        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->with('category')
            ->paginate(12);

        return view('frontend.shop', compact('category', 'products'));
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

            return response()->json([
                'name' => $product->getName(),
                'brand' => $product->brand,
                'price' => number_format($product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price, 0) . ' ' . (app()->getLocale() == 'ar' ? 'د.إ' : 'AED'),
                'images' => $allImages,
                'hasNewSeason' => $product->is_new,
                'sizes' => $product->sizes ?? [],
                'description' => $product->getDescription(),
                'sku' => $product->sku
            ]);
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
        return view('frontend.wishlist');
    }

    public function orders()
    {
        return view('frontend.orders');
    }

    public function privacyPolicy()
    {
        $page = \App\Models\PrivacyPolicyPage::first();
        return view('frontend.privacy-policy', compact('page'));
    }
}
