<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "===== إنشاء طلبات تجريبية متعددة =====\n\n";

// Get random products
$products = App\Models\Product::where('is_active', true)->limit(10)->get();

if ($products->isEmpty()) {
    echo "❌ لا توجد منتجات في قاعدة البيانات\n";
    exit(1);
}

echo "📦 تم العثور على {$products->count()} منتج\n\n";

// Sample customer data
$customers = [
    [
        'name' => 'محمد عبدالله',
        'email' => 'mohammed@example.com',
        'phone' => '+971501234567',
        'city' => 'دبي',
        'address' => 'شارع الشيخ زايد، برج الإمارات',
    ],
    [
        'name' => 'فاطمة أحمد',
        'email' => 'fatima@example.com',
        'phone' => '+971509876543',
        'city' => 'أبوظبي',
        'address' => 'شارع الكورنيش، مبنى النجوم',
    ],
    [
        'name' => 'خالد سالم',
        'email' => 'khaled@example.com',
        'phone' => '+971505556677',
        'city' => 'الشارقة',
        'address' => 'شارع الملك فيصل، برج الفلاح',
    ],
    [
        'name' => 'مريم حسن',
        'email' => 'mariam@example.com',
        'phone' => '+971504443322',
        'city' => 'عجمان',
        'address' => 'شارع الشيخ راشد، مجمع الواحة',
    ],
    [
        'name' => 'عمر يوسف',
        'email' => 'omar@example.com',
        'phone' => '+971502221144',
        'city' => 'رأس الخيمة',
        'address' => 'شارع البحر، فيلا ٣٢',
    ],
];

$statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
$paymentStatuses = ['pending', 'paid', 'failed'];

$ordersCreated = 0;

foreach ($customers as $index => $customer) {
    try {
        DB::beginTransaction();

        // Random number of items (1-4)
        $numItems = rand(1, 4);
        $randomProducts = $products->random(min($numItems, $products->count()));

        $subtotal = 0;
        $orderItems = [];

        foreach ($randomProducts as $product) {
            $quantity = rand(1, 3);
            $price = $product->sale_price ?? $product->price;
            $itemSubtotal = $price * $quantity;
            $subtotal += $itemSubtotal;

            $productName = $product->name;
            if (is_array($productName)) {
                $productName = $productName['ar'] ?? $productName['en'] ?? 'منتج';
            }

            $orderItems[] = [
                'product' => $product,
                'product_name' => $productName,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $itemSubtotal,
            ];
        }

        $shippingCost = rand(0, 1) ? 25.00 : 0; // Free or 25 AED
        $total = $subtotal + $shippingCost;

        // Random status
        $status = $statuses[array_rand($statuses)];
        $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];

        // Create order
        $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        $orderData = [
            'order_number' => $orderNumber,
            'user_id' => 1, // User ID 1
            'customer_name' => $customer['name'],
            'customer_email' => $customer['email'],
            'customer_phone' => $customer['phone'],
            'shipping_address' => $customer['address'],
            'shipping_city' => $customer['city'],
            'shipping_state' => $customer['city'],
            'shipping_postal_code' => '00000',
            'shipping_country' => 'الإمارات العربية المتحدة',
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'tax' => 0,
            'discount' => 0,
            'total' => $total,
            'payment_method' => rand(0, 1) ? 'cash_on_delivery' : 'credit_card',
            'payment_status' => $paymentStatus,
            'status' => $status,
            'notes' => rand(0, 1) ? 'يرجى التوصيل في المساء' : null,
        ];

        // Set timestamps based on status
        if ($status == 'confirmed' || $status == 'processing' || $status == 'shipped' || $status == 'delivered') {
            $orderData['confirmed_at'] = now()->subDays(rand(1, 3));
        }
        if ($status == 'shipped' || $status == 'delivered') {
            $orderData['shipped_at'] = now()->subDays(rand(0, 2));
        }
        if ($status == 'delivered') {
            $orderData['delivered_at'] = now()->subDays(rand(0, 1));
        }

        $order = App\Models\Order::create($orderData);

        // Create order items
        foreach ($orderItems as $item) {
            App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'product_name' => $item['product_name'],
                'product_sku' => $item['product']->sku ?? 'N/A',
                'product_image' => $item['product']->main_image ?? null,
                'size' => rand(0, 1) ? ['S', 'M', 'L', 'XL'][rand(0, 3)] : null,
                'color' => rand(0, 1) ? ['أبيض', 'أسود', 'بني', 'أزرق'][rand(0, 3)] : null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        DB::commit();
        $ordersCreated++;

        echo sprintf(
            "✅ طلب #%d: %s | %s | %.2f AED | الحالة: %s\n",
            $ordersCreated,
            $orderNumber,
            $customer['name'],
            $total,
            $status
        );

    } catch (Exception $e) {
        DB::rollBack();
        echo "❌ خطأ في إنشاء طلب {$customer['name']}: " . $e->getMessage() . "\n";
    }
}

echo "\n🎉 تم إنشاء {$ordersCreated} طلب بنجاح!\n";
echo "\n🔗 الروابط:\n";
echo "عرض الطلبات (مستخدم): http://127.0.0.1:8000/orders\n";
echo "لوحة التحكم (طلبات): http://127.0.0.1:8000/dashboard/orders\n";
