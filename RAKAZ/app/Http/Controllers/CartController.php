<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Http\Controllers\Admin\GeneralSettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private function getIdentifier()
    {
        if (auth()->check()) {
            return ['user_id' => auth()->id(), 'session_id' => null];
        }

        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', uniqid('cart_', true));
        }

        return ['user_id' => null, 'session_id' => Session::get('cart_session_id')];
    }

    public function index()
    {
        $identifier = $this->getIdentifier();
        $cartItems = Cart::getCartItems($identifier['user_id'], $identifier['session_id']);
        $cartTotal = Cart::getCartTotal($identifier['user_id'], $identifier['session_id']);
        $taxPercentage = GeneralSettingsController::getTaxRatePercentage();
        $taxRate = GeneralSettingsController::getTaxRate();

        return view('frontend.cart', compact('cartItems', 'cartTotal', 'taxPercentage', 'taxRate'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'shoe_size' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $identifier = $this->getIdentifier();

        // Check if item already exists in cart with same details
        $query = Cart::where('product_id', $product->id)
            ->where($identifier['user_id'] ? 'user_id' : 'session_id', $identifier['user_id'] ?: $identifier['session_id']);

        // Handle nullable fields correctly
        if ($request->size) {
            $query->where('size', $request->size);
        } else {
            $query->whereNull('size');
        }

        if ($request->shoe_size) {
            $query->where('shoe_size', $request->shoe_size);
        } else {
            $query->whereNull('shoe_size');
        }

        if ($request->color) {
            $query->where('color', $request->color);
        } else {
            $query->whereNull('color');
        }

        $cartItem = $query->first();

        $price = $product->sale_price && $product->sale_price < $product->price
            ? $product->sale_price
            : $product->price;

        if ($cartItem) {
            // Item exists with same details - increase quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => $identifier['user_id'],
                'session_id' => $identifier['session_id'],
                'product_id' => $product->id,
                'size' => $request->size,
                'shoe_size' => $request->shoe_size,
                'color' => $request->color,
                'quantity' => $request->quantity,
                'price' => $price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() == 'ar' ? 'تم إضافة المنتج إلى السلة' : 'Product added to cart',
            'cartCount' => Cart::getCartCount($identifier['user_id'], $identifier['session_id'])
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $identifier = $this->getIdentifier();
        $cartItem = Cart::where('id', $id)
            ->where($identifier['user_id'] ? 'user_id' : 'session_id', $identifier['user_id'] ?: $identifier['session_id'])
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() == 'ar' ? 'تم تحديث الكمية' : 'Quantity updated',
            'subtotal' => $cartItem->subtotal,
            'cartTotal' => Cart::getCartTotal($identifier['user_id'], $identifier['session_id'])
        ]);
    }

    public function remove($id)
    {
        $identifier = $this->getIdentifier();
        $cartItem = Cart::where('id', $id)
            ->where($identifier['user_id'] ? 'user_id' : 'session_id', $identifier['user_id'] ?: $identifier['session_id'])
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() == 'ar' ? 'تم حذف المنتج من السلة' : 'Product removed from cart',
            'cartCount' => Cart::getCartCount($identifier['user_id'], $identifier['session_id']),
            'cartTotal' => Cart::getCartTotal($identifier['user_id'], $identifier['session_id'])
        ]);
    }

    public function clear()
    {
        $identifier = $this->getIdentifier();
        Cart::where($identifier['user_id'] ? 'user_id' : 'session_id', $identifier['user_id'] ?: $identifier['session_id'])->delete();

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() == 'ar' ? 'تم تفريغ السلة' : 'Cart cleared'
        ]);
    }

    public function count()
    {
        $identifier = $this->getIdentifier();
        return response()->json([
            'count' => Cart::getCartCount($identifier['user_id'], $identifier['session_id'])
        ]);
    }

    public function apiCount()
    {
        $identifier = $this->getIdentifier();
        return response()->json([
            'count' => Cart::getCartCount($identifier['user_id'], $identifier['session_id'])
        ]);
    }

    public function apiIndex()
    {
        $identifier = $this->getIdentifier();
        $locale = app()->getLocale();

        $cartItems = Cart::with('product')
            ->where($identifier['user_id'] ? 'user_id' : 'session_id',
                    $identifier['user_id'] ?: $identifier['session_id'])
            ->get();

        $items = $cartItems->map(function($item) use ($locale) {
            $product = $item->product;
            $mainImage = null;

            // Get main image
            if ($product->main_image) {
                $mainImage = asset('storage/' . $product->main_image);
            } elseif ($product->images && is_array($product->images) && count($product->images) > 0) {
                $mainImage = asset('storage/' . $product->images[0]);
            }

            $currency = $locale == 'ar' ? 'د.إ' : 'AED';
            $defaultBrand = $locale == 'ar' ? 'ركاز' : 'Rakaz';

            return [
                'id' => $item->id,
                'image' => $mainImage,
                'brand' => ($product->brand && is_object($product->brand)) ? $product->brand->getName($locale) : $defaultBrand,
                'name' => $product->getName($locale),
                'price' => number_format($item->price, 0) . ' ' . $currency,
                'size' => $item->size ?? $item->shoe_size ?? '',
                'quantity' => $item->quantity
            ];
        });

        return response()->json([
            'items' => $items
        ]);
    }
}
