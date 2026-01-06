<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Controllers\Admin\GeneralSettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    private function getIdentifier()
    {
        if (auth()->check()) {
            return ['user_id' => auth()->id(), 'session_id' => null];
        }

        if (!Session::has('cart_session_id')) {
            return redirect()->route('cart.index')->with('error', app()->getLocale() == 'ar' ? 'السلة فارغة' : 'Cart is empty');
        }

        return ['user_id' => null, 'session_id' => Session::get('cart_session_id')];
    }

    public function index()
    {
        $identifier = $this->getIdentifier();
        $cartItems = Cart::getCartItems($identifier['user_id'], $identifier['session_id']);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', app()->getLocale() == 'ar' ? 'السلة فارغة' : 'Cart is empty');
        }

        $cartTotal = Cart::getCartTotal($identifier['user_id'], $identifier['session_id']);
        $shippingCost = 0; // Free shipping or calculate based on rules
        $taxRate = GeneralSettingsController::getTaxRate();
        $taxPercentage = GeneralSettingsController::getTaxRatePercentage();
        $tax = $cartTotal * $taxRate;
        $total = $cartTotal + $shippingCost + $tax;

        return view('frontend.checkout', compact('cartItems', 'cartTotal', 'shippingCost', 'tax', 'total', 'taxPercentage'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_country' => 'required|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:20',
            'shipping_method' => 'required|in:standard,express,same-day',
            'payment_method' => 'required|in:cash',
            'notes' => 'nullable|string|max:1000',
        ]);

        $identifier = $this->getIdentifier();
        $cartItems = Cart::getCartItems($identifier['user_id'], $identifier['session_id']);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', app()->getLocale() == 'ar' ? 'السلة فارغة' : 'Cart is empty');
        }

        // SECURITY: Recalculate cart total from product prices in database (not from cart table)
        // This prevents price manipulation by hackers
        $cartTotal = 0;
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            // Get fresh price from product
            $productPrice = $product->sale_price && $product->sale_price < $product->price
                ? $product->sale_price
                : $product->price;
            $cartTotal += $productPrice * $cartItem->quantity;

            // Update cart item price if different (for display consistency)
            if ($cartItem->price != $productPrice) {
                $cartItem->price = $productPrice;
                $cartItem->save();
            }
        }

        // Calculate shipping cost based on method
        $shippingCost = 0;
        switch ($request->shipping_method) {
            case 'express':
                $shippingCost = 50;
                break;
            case 'same-day':
                $shippingCost = 100;
                break;
            default:
                $shippingCost = 0;
        }

        // Calculate tax from settings
        $taxRate = GeneralSettingsController::getTaxRate();
        $tax = ($cartTotal + $shippingCost) * $taxRate;
        $total = $cartTotal + $shippingCost + $tax;

        DB::beginTransaction();
        try {
            // Check stock availability before creating order
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                // Check if product requires stock management
                if ($product->manage_stock) {
                    // Check if enough stock is available
                    if ($product->stock_quantity < $cartItem->quantity) {
                        throw new \Exception(
                            app()->getLocale() == 'ar'
                                ? "المنتج '{$product->getName()}' غير متوفر بالكمية المطلوبة. المتوفر: {$product->stock_quantity}"
                                : "Product '{$product->getName()}' is not available in the requested quantity. Available: {$product->stock_quantity}"
                        );
                    }

                    // Check if product is out of stock
                    if ($product->stock_status === 'out_of_stock' || $product->stock_quantity <= 0) {
                        throw new \Exception(
                            app()->getLocale() == 'ar'
                                ? "المنتج '{$product->getName()}' غير متوفر حالياً"
                                : "Product '{$product->getName()}' is currently out of stock"
                        );
                    }
                }
            }

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $identifier['user_id'],
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_postal_code' => $request->shipping_postal_code,
                'shipping_country' => $request->shipping_country,
                'subtotal' => $cartTotal,
                'shipping_cost' => $shippingCost,
                'tax' => $tax,
                'discount' => 0,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Create order items with VERIFIED prices from products
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                // SECURITY: Get fresh price from product, not from cart
                $verifiedPrice = $product->sale_price && $product->sale_price < $product->price
                    ? $product->sale_price
                    : $product->price;
                $verifiedSubtotal = $verifiedPrice * $cartItem->quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $product->getName(),
                    'product_sku' => $product->sku,
                    'product_image' => $product->main_image,
                    'size' => $cartItem->size,
                    'shoe_size' => $cartItem->shoe_size,
                    'color' => $cartItem->color,
                    'quantity' => $cartItem->quantity,
                    'price' => $verifiedPrice,
                    'subtotal' => $verifiedSubtotal,
                ]);

                // Decrease stock quantity for the product
                $product->decreaseStock($cartItem->quantity);

                // Increment sales count
                $product->incrementSales($cartItem->quantity);
            }

            // Clear cart
            if ($identifier['user_id']) {
                Cart::where('user_id', $identifier['user_id'])->delete();
            } else {
                Cart::where('session_id', $identifier['session_id'])->delete();
            }

            DB::commit();

            // Store order ID in session for guest access
            if (!auth()->check()) {
                $guestOrderIds = session('guest_order_ids', []);
                $guestOrderIds[] = $order->id;
                session(['guest_order_ids' => $guestOrderIds]);
            }

            // Send confirmation email (optional)
            // Mail::to($order->customer_email)->send(new OrderConfirmation($order));

            return redirect()->route('orders.show', $order->id)->with('success', app()->getLocale() == 'ar' ? 'تم إنشاء الطلب بنجاح! رقم الطلب: ' . $order->order_number : 'Order placed successfully! Order number: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            return back()->with('error', app()->getLocale() == 'ar' ? 'حدث خطأ أثناء إنشاء الطلب' : 'Error creating order')->withInput();
        }
    }
}
