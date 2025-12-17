<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "===== نظام الطلبات - فحص شامل =====\n\n";

// 1. عدد الطلبات
$ordersCount = App\Models\Order::count();
echo "📦 إجمالي الطلبات: {$ordersCount}\n";

// 2. عدد عناصر السلة
$cartCount = App\Models\Cart::count();
echo "🛒 عناصر في السلة: {$cartCount}\n\n";

// 3. عرض آخر 5 طلبات
if ($ordersCount > 0) {
    echo "📋 آخر 5 طلبات:\n";
    echo str_repeat("-", 80) . "\n";

    $orders = App\Models\Order::with(['user', 'items'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    foreach ($orders as $order) {
        echo sprintf(
            "رقم الطلب: %s | العميل: %s | الحالة: %s | المبلغ: %.2f AED | التاريخ: %s\n",
            $order->order_number,
            $order->customer_name,
            $order->status,
            $order->total,
            $order->created_at->format('Y-m-d H:i')
        );
        echo "  - المنتجات: " . $order->items->count() . " منتج\n";
        foreach ($order->items as $item) {
            $productName = $item->product ? $item->product->name : 'منتج محذوف';
            if (is_array($productName)) {
                $productName = $productName['ar'] ?? $productName['en'] ?? 'منتج';
            }
            echo sprintf(
                "    • %s (الكمية: %d × %.2f = %.2f AED)\n",
                $productName,
                $item->quantity,
                $item->price,
                $item->quantity * $item->price
            );
        }
        echo "\n";
    }
} else {
    echo "⚠️ لا توجد طلبات في قاعدة البيانات\n\n";
    echo "💡 لإنشاء طلب تجريبي:\n";
    echo "1. أضف منتجات إلى السلة من: http://127.0.0.1:8000/shop\n";
    echo "2. اذهب إلى السلة: http://127.0.0.1:8000/cart\n";
    echo "3. أكمل عملية الشراء: http://127.0.0.1:8000/checkout\n\n";
}

// 4. عرض عناصر السلة الحالية
if ($cartCount > 0) {
    echo "🛒 عناصر السلة الحالية:\n";
    echo str_repeat("-", 80) . "\n";

    $cartItems = App\Models\Cart::with('product')->limit(10)->get();

    foreach ($cartItems as $item) {
        $productName = $item->product ? $item->product->name : 'منتج محذوف';
        if (is_array($productName)) {
            $productName = $productName['ar'] ?? $productName['en'] ?? 'منتج';
        }

        $userInfo = $item->user_id ? "User ID: {$item->user_id}" : "Session: {$item->session_id}";

        echo sprintf(
            "• %s | الكمية: %d | السعر: %.2f AED | %s\n",
            $productName,
            $item->quantity,
            $item->price,
            $userInfo
        );
    }
    echo "\n";
}

// 5. إحصائيات حسب الحالة
if ($ordersCount > 0) {
    echo "📊 إحصائيات الطلبات حسب الحالة:\n";
    echo str_repeat("-", 80) . "\n";

    $statusCounts = App\Models\Order::selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->get();

    foreach ($statusCounts as $stat) {
        $statusLabels = [
            'pending' => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'processing' => 'قيد التجهيز',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي'
        ];

        $label = $statusLabels[$stat->status] ?? $stat->status;
        echo "  {$label}: {$stat->count}\n";
    }
    echo "\n";
}

echo "✅ الفحص اكتمل!\n";
