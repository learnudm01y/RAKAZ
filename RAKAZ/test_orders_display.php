<?php
/**
 * ملف اختبار لعرض الطلبات من قاعدة البيانات
 * Test file to display orders from database
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;

echo "=== عرض الطلبات من قاعدة البيانات ===\n\n";

// جلب جميع الطلبات مع المنتجات
$orders = Order::with(['items.product'])->orderBy('created_at', 'desc')->limit(6)->get();

if ($orders->count() === 0) {
    echo "❌ لا توجد طلبات في قاعدة البيانات!\n";
    exit;
}

echo "✅ تم العثور على {$orders->count()} طلبات\n\n";

foreach ($orders as $order) {
    echo "─────────────────────────────────────────\n";
    echo "📦 رقم الطلب: {$order->order_number}\n";
    echo "👤 اسم العميل: {$order->customer_name}\n";
    echo "💰 المبلغ الإجمالي: {$order->total} د.إ\n";
    echo "📅 تاريخ الطلب: {$order->created_at->format('Y-m-d H:i')}\n";

    // حالة الطلب
    $statusLabels = [
        'pending' => '⏳ قيد الانتظار',
        'confirmed' => '✓ تم التأكيد',
        'processing' => '📦 قيد المعالجة',
        'shipped' => '🚚 تم الشحن',
        'delivered' => '✅ تم التوصيل',
        'cancelled' => '❌ ملغي'
    ];
    echo "🔖 الحالة: " . ($statusLabels[$order->status] ?? $order->status) . "\n";

    // عرض المنتجات
    echo "\n📋 المنتجات:\n";
    foreach ($order->items as $item) {
        echo "   • {$item->product_name} x{$item->quantity} = {$item->price} د.إ\n";
    }

    // حساب progress للخط الأخضر
    $statusOrder = ['pending' => 0, 'confirmed' => 1, 'processing' => 2, 'shipped' => 3, 'delivered' => 4];
    $currentIndex = $statusOrder[$order->status] ?? 0;
    $progress = ($currentIndex / 4) * 100;
    echo "\n📊 نسبة التقدم: {$progress}%\n";

    // عرض الخطوات
    echo "   الخطوات: ";
    $steps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
    foreach ($steps as $index => $step) {
        if ($index <= $currentIndex) {
            echo "●";
        } else {
            echo "○";
        }
        if ($index < 4) echo "━";
    }
    echo "\n";
}

echo "\n─────────────────────────────────────────\n";
echo "✅ اختبار كامل - البيانات من قاعدة البيانات مباشرة\n";
echo "✅ جميع الطلبات تُعرض بناءً على حالتها الفعلية\n";
echo "✅ الخط الأخضر يحسب تلقائياً بناءً على الحالة\n";
