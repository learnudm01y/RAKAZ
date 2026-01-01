<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JeeblyController extends Controller
{
    /**
     * Jeebly API Configuration
     */
    private function getConfig(Request $request)
    {
        return [
            'base_url' => $request->input('base_url', 'https://demo.jeebly.com'),
            'client_key' => $request->input('client_key', '967X250731093419Y4d6f7374616661536862616972'),
            'api_key' => $request->input('api_key', 'JjEeEeBbLlYy1200'),
        ];
    }

    /**
     * Create a new order in Jeebly
     */
    public function createOrder(Request $request)
    {
        $config = $this->getConfig($request);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'order_value' => 'required|numeric|min:0',
            'delivery_fee' => 'nullable|numeric|min:0',
            'product_description' => 'nullable|string',
            'order_id' => 'nullable|string',
        ]);

        // Generate order ID if not provided
        $orderId = $validated['order_id'] ?? $this->generateOrderId();

        // Prepare request body for Jeebly API
        $orderData = [
            'client_key' => $config['client_key'],
            'order_id' => $orderId,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'order_value' => $validated['order_value'],
            'delivery_fee' => $validated['delivery_fee'] ?? 0,
            'product_description' => $validated['product_description'] ?? '',
            'payment_type' => 'COD', // Cash on Delivery
        ];

        try {
            Log::info('Jeebly Create Order Request', [
                'url' => $config['base_url'] . '/api/v1/order/create',
                'data' => $orderData
            ]);

            $response = Http::withoutVerifying()->withHeaders([
                'X-API-KEY' => $config['api_key'],
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->post($config['base_url'] . '/api/v1/order/create', $orderData);

            $responseData = $response->json();

            Log::info('Jeebly Create Order Response', [
                'status' => $response->status(),
                'data' => $responseData
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء الطلب بنجاح',
                    'data' => $responseData,
                    'order_id' => $orderId,
                    'request_sent' => $orderData,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $responseData['message'] ?? 'فشل إنشاء الطلب',
                    'error' => $responseData,
                    'request_sent' => $orderData,
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Jeebly Create Order Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطأ في الاتصال بـ Jeebly: ' . $e->getMessage(),
                'request_sent' => $orderData,
            ], 500);
        }
    }

    /**
     * Cancel an order in Jeebly
     */
    public function cancelOrder(Request $request)
    {
        $config = $this->getConfig($request);

        $validated = $request->validate([
            'order_id' => 'required|string',
            'cancel_reason' => 'nullable|string',
        ]);

        $cancelData = [
            'client_key' => $config['client_key'],
            'order_id' => $validated['order_id'],
            'cancel_reason' => $validated['cancel_reason'] ?? 'Customer request',
        ];

        try {
            Log::info('Jeebly Cancel Order Request', [
                'url' => $config['base_url'] . '/api/v1/order/cancel',
                'data' => $cancelData
            ]);

            $response = Http::withoutVerifying()->withHeaders([
                'X-API-KEY' => $config['api_key'],
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->post($config['base_url'] . '/api/v1/order/cancel', $cancelData);

            $responseData = $response->json();

            Log::info('Jeebly Cancel Order Response', [
                'status' => $response->status(),
                'data' => $responseData
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إلغاء الطلب بنجاح',
                    'data' => $responseData,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $responseData['message'] ?? 'فشل إلغاء الطلب',
                    'error' => $responseData,
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Jeebly Cancel Order Error', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطأ في الاتصال بـ Jeebly: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Track an order in Jeebly
     */
    public function trackOrder(Request $request)
    {
        $config = $this->getConfig($request);

        $validated = $request->validate([
            'order_id' => 'required|string',
        ]);

        try {
            Log::info('Jeebly Track Order Request', [
                'order_id' => $validated['order_id']
            ]);

            $response = Http::withoutVerifying()->withHeaders([
                'X-API-KEY' => $config['api_key'],
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->get($config['base_url'] . '/api/v1/order/track', [
                'client_key' => $config['client_key'],
                'order_id' => $validated['order_id'],
            ]);

            $responseData = $response->json();

            Log::info('Jeebly Track Order Response', [
                'status' => $response->status(),
                'data' => $responseData
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $responseData,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $responseData['message'] ?? 'فشل تتبع الطلب',
                    'error' => $responseData,
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Jeebly Track Order Error', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطأ في الاتصال بـ Jeebly: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook endpoint to receive status updates from Jeebly
     */
    public function webhookStatusUpdate(Request $request)
    {
        // Verify API Key
        $apiKey = $request->header('X-API-KEY');
        if ($apiKey !== 'Jeebly123') {
            Log::warning('Jeebly Webhook: Invalid API Key', [
                'received_key' => $apiKey
            ]);
            return response()->json([
                'result' => false,
                'code' => 401,
                'message' => 'Invalid API Key',
                'isSuccess' => false
            ], 401);
        }

        $data = $request->all();

        Log::info('Jeebly Webhook Received', $data);

        // Extract webhook data
        $orderId = $data['order_id'] ?? null;
        $status = $data['status'] ?? null;
        $cancelReason = $data['cancelreason'] ?? null;
        $eventDateTime = $data['event_date_time'] ?? null;
        $riderName = $data['rider_name'] ?? null;
        $riderPhone = $data['rider_phone'] ?? null;
        $lat = $data['lat'] ?? null;
        $lng = $data['lng'] ?? null;
        $podImage = $data['pod_image'] ?? null;

        // TODO: Update your order status in database here
        // Example:
        // Order::where('jeebly_order_id', $orderId)->update(['status' => $status]);

        // Store webhook event for display in test dashboard
        $this->storeWebhookEvent($data);

        return response()->json([
            'result' => true,
            'code' => 200,
            'message' => 'success',
            'isSuccess' => true
        ]);
    }

    /**
     * Get recent webhook events for test dashboard
     */
    public function getWebhookEvents(Request $request)
    {
        $events = cache()->get('jeebly_webhook_events', []);
        return response()->json([
            'success' => true,
            'events' => $events
        ]);
    }

    /**
     * Clear webhook events
     */
    public function clearWebhookEvents(Request $request)
    {
        cache()->forget('jeebly_webhook_events');
        return response()->json([
            'success' => true,
            'message' => 'تم مسح سجل الأحداث'
        ]);
    }

    /**
     * Store webhook event in cache for display
     */
    private function storeWebhookEvent($data)
    {
        $events = cache()->get('jeebly_webhook_events', []);

        array_unshift($events, [
            'data' => $data,
            'received_at' => now()->toISOString(),
        ]);

        // Keep only last 50 events
        $events = array_slice($events, 0, 50);

        cache()->put('jeebly_webhook_events', $events, now()->addDays(1));
    }

    /**
     * Generate unique order ID
     */
    private function generateOrderId()
    {
        return 'E' . date('ymd') . str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
    }

    /**
     * Test Jeebly connection
     */
    public function testConnection(Request $request)
    {
        Log::info('Jeebly Test Connection Called', $request->all());

        $config = $this->getConfig($request);

        try {
            // Try to make a simple request to Jeebly
            $response = Http::withoutVerifying()->withHeaders([
                'X-API-KEY' => $config['api_key'],
                'Content-Type' => 'application/json',
            ])->timeout(10)->get($config['base_url']);

            Log::info('Jeebly Test Connection Response', [
                'status' => $response->status(),
                'success' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم الاتصال بـ Jeebly بنجاح',
                'status_code' => $response->status(),
                'config' => [
                    'base_url' => $config['base_url'],
                    'client_key' => substr($config['client_key'], 0, 10) . '...',
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Jeebly Test Connection Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل الاتصال: ' . $e->getMessage(),
            ], 500);
        }
    }
}
