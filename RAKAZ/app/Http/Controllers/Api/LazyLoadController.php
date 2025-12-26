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
            $query->with('productColors');
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
            $query->with('productColors');
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
    public function getFooter()
    {
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
}
