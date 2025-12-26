<?php

namespace App\Services;

use App\Models\StatisticsCache;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ContactMessage;
use App\Models\SiteVisit;
use App\Models\OnlineUser;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsService
{
    // Cache TTL in seconds
    const TTL_REALTIME = 60;        // 1 minute for real-time data (online users)
    const TTL_SHORT = 300;          // 5 minutes for frequently changing data
    const TTL_MEDIUM = 900;         // 15 minutes for moderately changing data
    const TTL_LONG = 3600;          // 1 hour for rarely changing data

    /**
     * Get all dashboard statistics
     */
    public function getDashboardStats()
    {
        return StatisticsCache::getOrCompute('dashboard_stats', function() {
            return [
                'total_orders' => Order::count(),
                'total_products' => Product::count(),
                'new_orders' => Order::where('status', 'pending')->count(),
                'total_customers' => User::where('role', '!=', 'admin')->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'processing_orders' => Order::where('status', 'processing')->count(),
                'shipped_orders' => Order::where('status', 'shipped')->count(),
                'delivered_orders' => Order::where('status', 'delivered')->count(),
                'total_revenue' => (float) Order::where('payment_status', 'paid')->sum('total'),
                'pending_messages' => ContactMessage::where('status', 'unread')->count(),
            ];
        }, 'dashboard', self::TTL_SHORT);
    }

    /**
     * Get visitor statistics
     */
    public function getVisitorStats()
    {
        // Online users - needs real-time data
        $onlineStats = StatisticsCache::getOrCompute('online_users_stats', function() {
            return [
                'online_users' => OnlineUser::activeCount(5),
                'registered_online' => OnlineUser::registeredActiveCount(5),
                'guest_online' => OnlineUser::guestActiveCount(5),
            ];
        }, 'visitors', self::TTL_REALTIME);

        // Unique visitors - can be cached longer
        $visitStats = StatisticsCache::getOrCompute('visit_stats', function() {
            return [
                'visits_today' => SiteVisit::todayCount(),
                'visits_month' => SiteVisit::thisMonthCount(),
                'visits_year' => SiteVisit::thisYearCount(),
            ];
        }, 'visitors', self::TTL_SHORT);

        // Page views - can be cached longer
        $pageviewStats = StatisticsCache::getOrCompute('pageview_stats', function() {
            return [
                'pageviews_today' => PageView::todayCount(),
                'pageviews_month' => PageView::thisMonthCount(),
                'pageviews_year' => PageView::thisYearCount(),
            ];
        }, 'visitors', self::TTL_SHORT);

        return array_merge($onlineStats, $visitStats, $pageviewStats);
    }

    /**
     * Get orders by status
     */
    public function getOrdersByStatus()
    {
        return StatisticsCache::getOrCompute('orders_by_status', function() {
            return Order::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
        }, 'orders', self::TTL_SHORT);
    }

    /**
     * Get monthly sales data
     */
    public function getMonthlySales($months = 6)
    {
        return StatisticsCache::getOrCompute('monthly_sales_' . $months, function() use ($months) {
            return Order::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('SUM(total) as total_sales'),
                    DB::raw('COUNT(*) as total_orders')
                )
                ->where('created_at', '>=', Carbon::now()->subMonths($months))
                ->where('payment_status', 'paid')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get()
                ->toArray();
        }, 'sales', self::TTL_MEDIUM);
    }

    /**
     * Get top selling products
     */
    public function getTopProducts($limit = 5)
    {
        return StatisticsCache::getOrCompute('top_products_' . $limit, function() use ($limit) {
            return DB::table('order_items')
                ->select(
                    'products.name',
                    DB::raw('SUM(order_items.quantity) as total_quantity'),
                    DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
                )
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->groupBy('products.id', 'products.name')
                ->orderBy('total_quantity', 'desc')
                ->limit($limit)
                ->get()
                ->toArray();
        }, 'products', self::TTL_MEDIUM);
    }

    /**
     * Get recent orders
     */
    public function getRecentOrders($limit = 5)
    {
        return StatisticsCache::getOrCompute('recent_orders_' . $limit, function() use ($limit) {
            return Order::with('user')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_name' => $order->customer_name,
                        'total' => $order->total,
                        'status' => $order->status,
                        'created_at' => $order->created_at->toDateTimeString(),
                        'created_at_human' => $order->created_at->locale('ar')->diffForHumans(),
                    ];
                })
                ->toArray();
        }, 'orders', self::TTL_SHORT);
    }

    /**
     * Get all dashboard data at once
     */
    public function getAllDashboardData()
    {
        return [
            'stats' => $this->getDashboardStats(),
            'visitorStats' => $this->getVisitorStats(),
            'ordersByStatus' => $this->getOrdersByStatus(),
            'monthlySales' => $this->getMonthlySales(),
            'topProducts' => $this->getTopProducts(),
            'recentOrders' => $this->getRecentOrders(),
        ];
    }

    /**
     * Force refresh all statistics
     */
    public function refreshAll()
    {
        StatisticsCache::clearAll();
        return $this->getAllDashboardData();
    }

    /**
     * Force refresh a specific group
     */
    public function refreshGroup($group)
    {
        StatisticsCache::clearGroup($group);

        switch ($group) {
            case 'dashboard':
                return $this->getDashboardStats();
            case 'visitors':
                return $this->getVisitorStats();
            case 'orders':
                return $this->getOrdersByStatus();
            case 'sales':
                return $this->getMonthlySales();
            case 'products':
                return $this->getTopProducts();
            default:
                return null;
        }
    }

    /**
     * Get cache status for debugging
     */
    public function getCacheStatus()
    {
        $keys = [
            'dashboard_stats',
            'online_users_stats',
            'visit_stats',
            'pageview_stats',
            'orders_by_status',
            'monthly_sales_6',
            'top_products_5',
            'recent_orders_5'
        ];

        $status = [];
        foreach ($keys as $key) {
            $info = StatisticsCache::getCacheInfo($key);
            $status[$key] = $info ?: ['status' => 'not_cached'];
        }

        return $status;
    }
}
