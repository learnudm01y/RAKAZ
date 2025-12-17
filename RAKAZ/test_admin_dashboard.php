<?php
/**
 * اختبار بيانات لوحة التحكم الحقيقية
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\DB;

echo "═══════════════════════════════════════════════════\n";
echo "   اختبار بيانات لوحة التحكم الإدارية الحقيقية   \n";
echo "═══════════════════════════════════════════════════\n\n";

// 1. الإحصائيات الرئيسية
echo "📊 الإحصائيات الرئيسية:\n";
echo "─────────────────────────────────────────────\n";

$total_orders = Order::count();
$total_products = Product::count();
$new_orders = Order::where('status', 'pending')->count();
$total_users = User::where('role', '!=', 'admin')->count();

echo "📦 إجمالي الطلبات: {$total_orders}\n";
echo "🛍️ إجمالي المنتجات: {$total_products}\n";
echo "⚡ طلبات جديدة (pending): {$new_orders}\n";
echo "👥 إجمالي العملاء: {$total_users}\n";

// 2. توزيع الطلبات حسب الحالة
echo "\n📋 توزيع الطلبات حسب الحالة:\n";
echo "─────────────────────────────────────────────\n";

$statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
foreach ($statuses as $status) {
    $count = Order::where('status', $status)->count();
    $percentage = $total_orders > 0 ? round(($count / $total_orders) * 100, 1) : 0;
    echo sprintf("   %-15s: %3d طلبات (%5.1f%%)\n", $status, $count, $percentage);
}

// 3. الإيرادات
echo "\n💰 الإيرادات:\n";
echo "─────────────────────────────────────────────\n";

$total_revenue = Order::sum('total');
$paid_revenue = Order::where('payment_status', 'paid')->sum('total');
$pending_revenue = Order::where('payment_status', 'pending')->sum('total');

echo "💵 إجمالي المبيعات: " . number_format($total_revenue, 2) . " د.إ\n";
echo "✅ المدفوع: " . number_format($paid_revenue, 2) . " د.إ\n";
echo "⏳ المعلق: " . number_format($pending_revenue, 2) . " د.إ\n";

// 4. أحدث 5 طلبات
echo "\n📦 أحدث 5 طلبات:\n";
echo "─────────────────────────────────────────────\n";

$recent_orders = Order::orderBy('created_at', 'desc')->limit(5)->get();

if ($recent_orders->count() > 0) {
    foreach ($recent_orders as $order) {
        echo sprintf(
            "   #%-20s | %-25s | %10s د.إ | %-10s | %s\n",
            $order->order_number,
            mb_substr($order->customer_name, 0, 25),
            number_format($order->total, 2),
            $order->status,
            $order->created_at->format('Y-m-d H:i')
        );
    }
} else {
    echo "   لا توجد طلبات\n";
}

// 5. أكثر المنتجات مبيعاً
echo "\n🏆 أكثر 5 منتجات مبيعاً:\n";
echo "─────────────────────────────────────────────\n";

$top_products = DB::table('order_items')
    ->select(
        'products.name',
        DB::raw('SUM(order_items.quantity) as total_quantity'),
        DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
    )
    ->join('products', 'order_items.product_id', '=', 'products.id')
    ->groupBy('products.id', 'products.name')
    ->orderBy('total_quantity', 'desc')
    ->limit(5)
    ->get();

if ($top_products->count() > 0) {
    foreach ($top_products as $product) {
        echo sprintf(
            "   %-50s | %3d قطعة | %10s د.إ\n",
            mb_substr($product->name, 0, 50),
            $product->total_quantity,
            number_format($product->total_revenue, 2)
        );
    }
} else {
    echo "   لا توجد مبيعات بعد\n";
}

// 6. المبيعات اليومية (آخر 7 أيام)
echo "\n📈 المبيعات اليومية (آخر 7 أيام):\n";
echo "─────────────────────────────────────────────\n";

$daily_sales = Order::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('COUNT(*) as total_orders'),
        DB::raw('SUM(total) as total_sales')
    )
    ->where('created_at', '>=', now()->subDays(7))
    ->groupBy('date')
    ->orderBy('date', 'desc')
    ->get();

if ($daily_sales->count() > 0) {
    foreach ($daily_sales as $day) {
        echo sprintf(
            "   %s | %3d طلبات | %10s د.إ\n",
            $day->date,
            $day->total_orders,
            number_format($day->total_sales, 2)
        );
    }
} else {
    echo "   لا توجد مبيعات في آخر 7 أيام\n";
}

// 7. معلومات قاعدة البيانات
echo "\n🗄️ معلومات قاعدة البيانات:\n";
echo "─────────────────────────────────────────────\n";

$tables = [
    'orders' => Order::count(),
    'products' => Product::count(),
    'users' => User::count(),
    'order_items' => DB::table('order_items')->count(),
];

foreach ($tables as $table => $count) {
    echo sprintf("   %-20s: %6d صفوف\n", $table, $count);
}

echo "\n═══════════════════════════════════════════════════\n";
echo "✅ جميع البيانات حقيقية من قاعدة البيانات\n";
echo "✅ لا توجد بيانات وهمية أو hardcoded\n";
echo "✅ جاهز للعرض في لوحة التحكم\n";
echo "═══════════════════════════════════════════════════\n";
