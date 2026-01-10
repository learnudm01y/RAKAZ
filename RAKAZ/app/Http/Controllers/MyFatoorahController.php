<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SiteSetting;
use App\Http\Controllers\Admin\GeneralSettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;

class MyFatoorahController extends Controller
{
    /**
     * MyFatoorah Payment API instance
     */
    protected MyFatoorahPayment $mfPayment;

    /**
     * MyFatoorah Payment Status API instance
     */
    protected MyFatoorahPaymentStatus $mfStatus;

    /**
     * Supplier Code for multi-vendor split
     */
    protected int $supplierCode;

    /**
     * Constructor - Initialize MyFatoorah API
     */
    public function __construct()
    {
        $config = [
            'apiKey' => config('myfatoorah.api_key'),
            'isTest' => config('myfatoorah.test_mode'),
            'countryCode' => config('myfatoorah.country_iso'),
        ];

        $this->mfPayment = new MyFatoorahPayment($config);
        $this->mfStatus = new MyFatoorahPaymentStatus($config);
        $this->supplierCode = config('myfatoorah.supplier_code', 1);
    }

    /**
     * Get cart identifier for user/guest
     */
    private function getIdentifier()
    {
        if (auth()->check()) {
            return ['user_id' => auth()->id(), 'session_id' => null];
        }

        if (!Session::has('cart_session_id')) {
            return null;
        }

        return ['user_id' => null, 'session_id' => Session::get('cart_session_id')];
    }

    /**
     * Initiate payment with MyFatoorah
     * This method creates the order and redirects to payment gateway
     * For AJAX requests (Capacitor), returns JSON with payment URL
     */
    public function pay(Request $request)
    {
        // Check if this is an AJAX request from Capacitor
        $isAjax = $request->ajax() || $request->wantsJson();

        // Validate checkout form data
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
            'notes' => 'nullable|string|max:1000',
        ]);

        $identifier = $this->getIdentifier();

        if (!$identifier) {
            return redirect()->route('cart.index')
                ->with('error', app()->getLocale() == 'ar' ? 'السلة فارغة' : 'Cart is empty');
        }

        $cartItems = Cart::getCartItems($identifier['user_id'], $identifier['session_id']);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', app()->getLocale() == 'ar' ? 'السلة فارغة' : 'Cart is empty');
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
        $shippingCost = match ($request->shipping_method) {
            'express' => 50,
            'same-day' => 100,
            default => 0,
        };

        // Calculate tax from settings
        $taxRate = GeneralSettingsController::getTaxRate();
        $taxPercentage = GeneralSettingsController::getTaxRatePercentage();
        $tax = ($cartTotal + $shippingCost) * $taxRate;
        $total = $cartTotal + $shippingCost + $tax;

        DB::beginTransaction();
        try {
            // Check stock availability before creating order
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                if ($product->manage_stock) {
                    if ($product->stock_quantity < $cartItem->quantity) {
                        throw new \Exception(
                            app()->getLocale() == 'ar'
                                ? "المنتج '{$product->getName()}' غير متوفر بالكمية المطلوبة. المتوفر: {$product->stock_quantity}"
                                : "Product '{$product->getName()}' is not available in the requested quantity. Available: {$product->stock_quantity}"
                        );
                    }

                    if ($product->stock_status === 'out_of_stock' || $product->stock_quantity <= 0) {
                        throw new \Exception(
                            app()->getLocale() == 'ar'
                                ? "المنتج '{$product->getName()}' غير متوفر حالياً"
                                : "Product '{$product->getName()}' is currently out of stock"
                        );
                    }
                }
            }

            // Create order with pending payment status
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
                'payment_method' => 'myfatoorah',
                'payment_status' => 'pending',
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Create order items with verified prices from database
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                // SECURITY: Get fresh price from product database
                $verifiedPrice = $product->sale_price && $product->sale_price < $product->price
                    ? $product->sale_price
                    : $product->price;

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
                    'price' => $verifiedPrice, // Use verified price from product
                    'subtotal' => $verifiedPrice * $cartItem->quantity, // Calculate subtotal from verified price
                ]);
            }

            DB::commit();

            // Store order ID in session for callback verification
            Session::put('pending_order_id', $order->id);

            // Store order ID in session for guest access
            if (!auth()->check()) {
                $guestOrderIds = session('guest_order_ids', []);
                $guestOrderIds[] = $order->id;
                session(['guest_order_ids' => $guestOrderIds]);
            }

            // Prepare invoice items for MyFatoorah with verified prices
            $invoiceItems = [];
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                // SECURITY: Get fresh price from product database
                $verifiedPrice = $product->sale_price && $product->sale_price < $product->price
                    ? $product->sale_price
                    : $product->price;

                $invoiceItems[] = [
                    'ItemName' => $product->getName(),
                    'Quantity' => $cartItem->quantity,
                    'UnitPrice' => round($verifiedPrice, 2),
                ];
            }

            // Add shipping as an item if applicable
            if ($shippingCost > 0) {
                $invoiceItems[] = [
                    'ItemName' => app()->getLocale() == 'ar' ? 'رسوم الشحن' : 'Shipping Fee',
                    'Quantity' => 1,
                    'UnitPrice' => $shippingCost,
                ];
            }

            // Add tax as an item
            if ($tax > 0) {
                $invoiceItems[] = [
                    'ItemName' => app()->getLocale() == 'ar' ? 'ضريبة القيمة المضافة (' . $taxPercentage . '%)' : 'VAT (' . $taxPercentage . '%)',
                    'Quantity' => 1,
                    'UnitPrice' => round($tax, 2),
                ];
            }

            // Build MyFatoorah payload
            $payloadData = [
                'InvoiceValue' => round($total, 2),
                'CustomerName' => $request->customer_name,
                'CustomerEmail' => $request->customer_email,
                'CustomerMobile' => preg_replace('/[^0-9]/', '', $request->customer_phone),
                'CustomerReference' => $order->order_number,
                'DisplayCurrencyIso' => config('myfatoorah.display_currency', 'KWD'),
                'CallBackUrl' => route('myfatoorah.callback'),
                'ErrorUrl' => route('myfatoorah.callback'),
                'Language' => app()->getLocale() == 'ar' ? 'AR' : 'EN',
                'InvoiceItems' => $invoiceItems,
                'CustomerAddress' => [
                    'Address' => $request->shipping_address,
                    'AddressInstructions' => $request->notes ?? '',
                ],
                // Multi-Vendor: Supplier allocation
                'Suppliers' => [
                    [
                        'SupplierCode' => $this->supplierCode,
                        'ProposedShare' => round($total, 2),
                        'InvoiceShare' => round($total, 2),
                    ]
                ],
            ];

            Log::info('MyFatoorah Payment Initiated', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $total,
                'customer_email' => $request->customer_email,
            ]);

            // Execute payment and get redirect URL
            $response = $this->mfPayment->getInvoiceURL($payloadData);

            // Store invoice ID for later verification
            if (isset($response['invoiceId'])) {
                $order->update(['payment_reference' => $response['invoiceId']]);
            }

            // For AJAX requests (Capacitor), return JSON with payment URL
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'payment_url' => $response['invoiceURL'],
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);
            }

            // For regular requests, redirect to MyFatoorah payment page
            return redirect()->away($response['invoiceURL']);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('MyFatoorah Payment Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // For AJAX requests, return JSON error
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => app()->getLocale() == 'ar'
                        ? 'حدث خطأ أثناء معالجة الدفع: ' . $e->getMessage()
                        : 'Error processing payment: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->with('error', app()->getLocale() == 'ar'
                    ? 'حدث خطأ أثناء معالجة الدفع: ' . $e->getMessage()
                    : 'Error processing payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Handle MyFatoorah callback (success or error)
     */
    public function callback(Request $request)
    {
        try {
            // Get payment ID from query parameters
            $paymentId = $request->query('paymentId');

            if (!$paymentId) {
                Log::error('MyFatoorah Callback: No paymentId received');
                return redirect()->route('checkout.index')
                    ->with('error', app()->getLocale() == 'ar'
                        ? 'لم يتم استلام معرف الدفع'
                        : 'Payment ID not received');
            }

            // Get payment status from MyFatoorah
            $paymentData = $this->mfStatus->getPaymentStatus($paymentId, 'PaymentId');

            Log::info('MyFatoorah Callback Received', [
                'paymentId' => $paymentId,
                'invoiceId' => $paymentData->InvoiceId ?? null,
                'invoiceStatus' => $paymentData->InvoiceStatus ?? null,
            ]);

            // Find the order
            $orderId = Session::get('pending_order_id');
            $order = null;

            if ($orderId) {
                $order = Order::find($orderId);
            }

            // Fallback: find by payment reference (InvoiceId)
            if (!$order && isset($paymentData->InvoiceId)) {
                $order = Order::where('payment_reference', $paymentData->InvoiceId)->first();
            }

            if (!$order) {
                Log::error('MyFatoorah Callback: Order not found', [
                    'session_order_id' => $orderId,
                    'invoice_id' => $paymentData->InvoiceId ?? null,
                ]);

                return redirect()->route('checkout.index')
                    ->with('error', app()->getLocale() == 'ar'
                        ? 'لم يتم العثور على الطلب'
                        : 'Order not found');
            }

            // Check payment status
            $invoiceStatus = $paymentData->InvoiceStatus ?? 'Unknown';

            if ($invoiceStatus === 'Paid') {
                // Payment successful
                DB::beginTransaction();
                try {
                    // Update order status
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'payment_reference' => $paymentData->InvoiceId,
                        'paid_at' => now(),
                    ]);

                    // Get cart identifier
                    $identifier = $this->getIdentifier();

                    if ($identifier) {
                        $cartItems = Cart::getCartItems($identifier['user_id'], $identifier['session_id']);

                        // Decrease stock and increment sales
                        foreach ($cartItems as $cartItem) {
                            $product = $cartItem->product;
                            $product->decreaseStock($cartItem->quantity);
                            $product->incrementSales($cartItem->quantity);
                        }

                        // Clear cart
                        if ($identifier['user_id']) {
                            Cart::where('user_id', $identifier['user_id'])->delete();
                        } else {
                            Cart::where('session_id', $identifier['session_id'])->delete();
                        }
                    }

                    // Clear pending order from session
                    Session::forget('pending_order_id');

                    DB::commit();

                    Log::info('MyFatoorah Payment Successful', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'invoice_id' => $paymentData->InvoiceId,
                        'amount' => $order->total,
                    ]);

                    return redirect()->route('orders.show', $order->id)
                        ->with('success', app()->getLocale() == 'ar'
                            ? 'تم الدفع بنجاح! رقم الطلب: ' . $order->order_number
                            : 'Payment successful! Order number: ' . $order->order_number);

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('MyFatoorah Post-Payment Error', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);

                    // Payment was successful but post-processing failed
                    // Still show success to user but log for admin review
                    return redirect()->route('orders.show', $order->id)
                        ->with('success', app()->getLocale() == 'ar'
                            ? 'تم الدفع بنجاح! رقم الطلب: ' . $order->order_number
                            : 'Payment successful! Order number: ' . $order->order_number);
                }

            } else {
                // Payment failed or cancelled
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);

                // Clear pending order from session
                Session::forget('pending_order_id');

                Log::warning('MyFatoorah Payment Failed', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'invoice_status' => $invoiceStatus,
                    'invoice_id' => $paymentData->InvoiceId ?? null,
                ]);

                return redirect()->route('checkout.index')
                    ->with('error', app()->getLocale() == 'ar'
                        ? 'فشل الدفع. يرجى المحاولة مرة أخرى.'
                        : 'Payment failed. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('MyFatoorah Callback Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('checkout.index')
                ->with('error', app()->getLocale() == 'ar'
                    ? 'حدث خطأ أثناء التحقق من الدفع'
                    : 'Error verifying payment');
        }
    }

    /**
     * AJAX payment endpoint for Capacitor app
     * Returns JSON with payment URL instead of redirecting
     */
    public function payAjax(Request $request)
    {
        // Force JSON response
        $request->headers->set('Accept', 'application/json');
        
        // Call the main pay method which now handles AJAX requests
        return $this->pay($request);
    }

    /**
     * Get payment status for an order (for polling from Capacitor app)
     */
    public function getPaymentStatus($orderId)
    {
        try {
            $order = Order::find($orderId);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => app()->getLocale() == 'ar' ? 'الطلب غير موجود' : 'Order not found'
                ], 404);
            }

            // Check if user has access to this order
            if (auth()->check()) {
                if ($order->user_id != auth()->id()) {
                    return response()->json([
                        'success' => false,
                        'message' => app()->getLocale() == 'ar' ? 'غير مصرح لك بالوصول لهذا الطلب' : 'Unauthorized'
                    ], 403);
                }
            } else {
                // Guest user - check session
                $guestOrderIds = session('guest_order_ids', []);
                if (!in_array($order->id, $guestOrderIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => app()->getLocale() == 'ar' ? 'غير مصرح لك بالوصول لهذا الطلب' : 'Unauthorized'
                    ], 403);
                }
            }

            return response()->json([
                'success' => true,
                'payment_status' => $order->payment_status,
                'order_status' => $order->status,
                'order_number' => $order->order_number,
                'redirect_url' => $order->payment_status === 'paid' 
                    ? route('orders.show', $order->id) 
                    : null
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Status Check Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => app()->getLocale() == 'ar' 
                    ? 'حدث خطأ أثناء التحقق من حالة الدفع' 
                    : 'Error checking payment status'
            ], 500);
        }
    }

    /**
     * Webhook handler for MyFatoorah server-to-server notifications
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('MyFatoorah Webhook Received', $request->all());

            $data = $request->all();

            if (!isset($data['Data']['InvoiceId'])) {
                return response()->json(['status' => 'error', 'message' => 'Invalid webhook data'], 400);
            }

            $invoiceId = $data['Data']['InvoiceId'];
            $invoiceStatus = $data['Data']['InvoiceStatus'] ?? null;

            // Find order by payment reference
            $order = Order::where('payment_reference', $invoiceId)->first();

            if (!$order) {
                Log::warning('MyFatoorah Webhook: Order not found', ['invoice_id' => $invoiceId]);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Update order based on status
            if ($invoiceStatus === 'Paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'paid_at' => now(),
                ]);

                Log::info('MyFatoorah Webhook: Order marked as paid', [
                    'order_id' => $order->id,
                    'invoice_id' => $invoiceId,
                ]);

            } elseif (in_array($invoiceStatus, ['Failed', 'Expired', 'Cancelled'])) {
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);

                Log::info('MyFatoorah Webhook: Order payment failed', [
                    'order_id' => $order->id,
                    'invoice_id' => $invoiceId,
                    'status' => $invoiceStatus,
                ]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('MyFatoorah Webhook Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
