<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // إحصائيات حقيقية من قاعدة البيانات
        $stats = [
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'new_orders' => Order::where('status', 'pending')->count(),
            'total_customers' => User::where('role', '!=', 'admin')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'pending_messages' => ContactMessage::where('status', 'unread')->count(),
        ];

        // أحدث الطلبات (آخر 5)
        $recent_orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // أحدث المنتجات المضافة
        $recent_products = Product::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // أحدث الرسائل
        $recent_messages = ContactMessage::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // الطلبات حسب الحالة (للرسم البياني)
        $orders_by_status = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // المبيعات الشهرية (آخر 6 أشهر)
        $monthly_sales = Order::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->where('payment_status', 'paid')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // أفضل المنتجات مبيعاً
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

        return view('dashboard', compact(
            'stats',
            'recent_orders',
            'recent_products',
            'recent_messages',
            'orders_by_status',
            'monthly_sales',
            'top_products'
        ));
    }
}
