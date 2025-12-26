<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
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
        $tax = 0; // Calculate if needed
        $total = $cartTotal + $shippingCost + $tax;

        return view('frontend.checkout', compact('cartItems', 'cartTotal', 'shippingCost', 'tax', 'total'));
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

        $cartTotal = Cart::getCartTotal($identifier['user_id'], $identifier['session_id']);

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

        // Calculate tax (5% VAT)
        $tax = ($cartTotal + $shippingCost) * 0.05;
        $total = $cartTotal + $shippingCost + $tax;

        DB::beginTransaction();
        try {
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

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->getName(),
                    'product_sku' => $cartItem->product->sku,
                    'product_image' => $cartItem->product->main_image,
                    'size' => $cartItem->size,
                    'shoe_size' => $cartItem->shoe_size,
                    'color' => $cartItem->color,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->subtotal,
                ]);
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
