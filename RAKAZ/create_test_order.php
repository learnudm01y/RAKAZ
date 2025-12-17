<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "===== إنشاء طلب تجريبي =====\n\n";

// Get cart items for user 1
$userId = 1;
$cartItems = App\Models\Cart::where('user_id', $userId)->with('product')->get();

if ($cartItems->isEmpty()) {
    echo "❌ السلة فارغة للمستخدم {$userId}\n";
    exit(1);
}

echo "🛒 تم العثور على {$cartItems->count()} منتج في السلة\n\n";

// Calculate totals
$subtotal = 0;
foreach ($cartItems as $item) {
    $itemTotal = $item->price * $item->quantity;
    $subtotal += $itemTotal;

    $productName = $item->product->name;
    if (is_array($productName)) {
        $productName = $productName['ar'] ?? $productName['en'] ?? 'منتج';
    }

    echo sprintf(
        "• %s - الكمية: %d × %.2f = %.2f AED\n",
        $productName,
        $item->quantity,
        $item->price,
        $itemTotal
    );
}

$shippingCost = 25.00; // رسوم شحن
$tax = 0;
$discount = 0;
$total = $subtotal + $shippingCost + $tax - $discount;

echo "\n";
echo "المجموع الفرعي: {$subtotal} AED\n";
echo "رسوم الشحن: {$shippingCost} AED\n";
echo "الإجمالي: {$total} AED\n\n";

// Create order
DB::beginTransaction();
try {
    $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

    $order = App\Models\Order::create([
        'order_number' => $orderNumber,
        'user_id' => $userId,
        'customer_name' => 'أحمد محمد',
        'customer_email' => 'ahmed@example.com',
        'customer_phone' => '+971501234567',
        'shipping_address' => 'شارع الشيخ زايد، برج الإمارات',
        'shipping_city' => 'دبي',
        'shipping_state' => 'دبي',
        'shipping_postal_code' => '00000',
        'shipping_country' => 'الإمارات العربية المتحدة',
        'subtotal' => $subtotal,
        'shipping_cost' => $shippingCost,
        'tax' => $tax,
        'discount' => $discount,
        'total' => $total,
        'payment_method' => 'cash_on_delivery',
        'payment_status' => 'pending',
        'status' => 'pending',
        'notes' => 'طلب تجريبي - يرجى التوصيل في المساء',
    ]);

    echo "✅ تم إنشاء الطلب: {$orderNumber}\n\n";

    // Create order items
    foreach ($cartItems as $item) {
        $productName = $item->product->name;
        if (is_array($productName)) {
            $productName = $productName['ar'] ?? $productName['en'] ?? 'منتج';
        }

        $itemSubtotal = $item->price * $item->quantity;

        App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'product_name' => $productName,
            'product_sku' => $item->product->sku ?? 'N/A',
            'product_image' => $item->product->main_image ?? null,
            'size' => $item->size,
            'color' => $item->color,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'subtotal' => $itemSubtotal,
        ]);

        echo "  ✓ تم إضافة: {$productName}\n";
    }

    // Clear cart
    App\Models\Cart::where('user_id', $userId)->delete();
    echo "\n✅ تم تفريغ السلة\n";

    DB::commit();

    echo "\n🎉 تم إنشاء الطلب بنجاح!\n";
    echo "\n📋 معلومات الطلب:\n";
    echo "رقم الطلب: {$order->order_number}\n";
    echo "العميل: {$order->customer_name}\n";
    echo "الهاتف: {$order->customer_phone}\n";
    echo "العنوان: {$order->shipping_address}, {$order->shipping_city}\n";
    echo "الحالة: {$order->status}\n";
    echo "المبلغ: {$order->total} AED\n";
    echo "\n🔗 الروابط:\n";
    echo "عرض الطلبات (مستخدم): http://127.0.0.1:8000/orders\n";
    echo "لوحة التحكم (طلبات): http://127.0.0.1:8000/dashboard/orders\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "\n❌ خطأ: " . $e->getMessage() . "\n";
    exit(1);
}
