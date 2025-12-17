<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['toggle']);
    }

    public function toggle(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() == 'ar' ? 'يجب تسجيل الدخول أولاً' : 'Please login first',
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $isAdded = Wishlist::toggle(auth()->id(), $request->product_id);

        return response()->json([
            'success' => true,
            'isAdded' => $isAdded,
            'message' => $isAdded
                ? (app()->getLocale() == 'ar' ? 'تمت الإضافة للمفضلة' : 'Added to wishlist')
                : (app()->getLocale() == 'ar' ? 'تم الحذف من المفضلة' : 'Removed from wishlist'),
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $isInWishlist = Wishlist::isInWishlist(auth()->id(), $request->product_id);

        return response()->json([
            'isInWishlist' => $isInWishlist,
        ]);
    }

    public function remove($id)
    {
        $wishlistItem = Wishlist::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() == 'ar' ? 'تم الحذف من المفضلة' : 'Removed from wishlist',
        ]);
    }
}
