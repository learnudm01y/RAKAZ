<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StatisticsApiController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * Get all dashboard statistics
     */
    public function all(): JsonResponse
    {
        try {
            $data = $this->statisticsService->getAllDashboardData();

            return response()->json([
                'success' => true,
                'data' => $data,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load statistics',
                'message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get dashboard stats (orders, products, revenue, etc.)
     */
    public function dashboardStats(): JsonResponse
    {
        try {
            $data = $this->statisticsService->getDashboardStats();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load dashboard stats'
            ], 500);
        }
    }

    /**
     * Get visitor statistics
     */
    public function visitorStats(): JsonResponse
    {
        try {
            $data = $this->statisticsService->getVisitorStats();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load visitor stats'
            ], 500);
        }
    }

    /**
     * Get orders by status
     */
    public function ordersByStatus(): JsonResponse
    {
        try {
            $data = $this->statisticsService->getOrdersByStatus();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load orders by status'
            ], 500);
        }
    }

    /**
     * Get monthly sales
     */
    public function monthlySales(): JsonResponse
    {
        try {
            $data = $this->statisticsService->getMonthlySales();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load monthly sales'
            ], 500);
        }
    }

    /**
     * Get top products
     */
    public function topProducts(): JsonResponse
    {
        try {
            $data = $this->statisticsService->getTopProducts();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load top products'
            ], 500);
        }
    }

    /**
     * Get recent orders
     */
    public function recentOrders(): JsonResponse
    {
        try {
            $data = $this->statisticsService->getRecentOrders();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load recent orders'
            ], 500);
        }
    }

    /**
     * Force refresh all statistics
     */
    public function refresh(): JsonResponse
    {
        try {
            $data = $this->statisticsService->refreshAll();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Statistics refreshed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to refresh statistics'
            ], 500);
        }
    }

    /**
     * Get cache status (for debugging)
     */
    public function cacheStatus(): JsonResponse
    {
        try {
            $status = $this->statisticsService->getCacheStatus();

            return response()->json([
                'success' => true,
                'data' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get cache status'
            ], 500);
        }
    }
}
