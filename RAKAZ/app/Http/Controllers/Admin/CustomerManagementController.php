<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerManagementController extends Controller
{
    /**
     * عرض صفحة إدارة العملاء مع الإحصائيات
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 15);
        $filter = $request->get('filter', 'all'); // all, active, inactive

        // الإحصائيات
        $statistics = [
            'total_customers' => User::has('orders')->count(),
            'active_customers' => User::has('orders')->whereHas('orders', function($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            })->count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'average_order_value' => Order::where('status', 'completed')->avg('total'),
        ];

        // جلب العملاء (المستخدمين الذين لديهم طلبات)
        $customers = User::has('orders')
            ->withCount('orders')
            ->with(['orders' => function($query) {
                $query->latest()->take(1);
            }])
            ->when($search, function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('id', $search);
            })
            ->when($filter === 'active', function($query) {
                $query->whereHas('orders', function($q) {
                    $q->where('created_at', '>=', now()->subDays(30));
                });
            })
            ->when($filter === 'inactive', function($query) {
                $query->whereDoesntHave('orders', function($q) {
                    $q->where('created_at', '>=', now()->subDays(30));
                });
            })
            ->latest('created_at')
            ->paginate($perPage);

        // حساب القيمة الإجمالية لكل عميل
        foreach ($customers as $customer) {
            $customer->total_spent = $customer->orders()->where('status', 'completed')->sum('total');
            $customer->last_order_date = $customer->orders()->latest()->first()?->created_at;
        }

        return view('admin.customers.index', compact('customers', 'statistics', 'search', 'perPage', 'filter'));
    }

    /**
     * عرض تفاصيل عميل
     */
    public function show($id)
    {
        $customer = User::with(['orders' => function($query) {
            $query->latest();
        }])->findOrFail($id);

        $customerStats = [
            'total_orders' => $customer->orders()->count(),
            'completed_orders' => $customer->orders()->where('status', 'completed')->count(),
            'pending_orders' => $customer->orders()->where('status', 'pending')->count(),
            'cancelled_orders' => $customer->orders()->where('status', 'cancelled')->count(),
            'total_spent' => $customer->orders()->where('status', 'completed')->sum('total'),
            'average_order' => $customer->orders()->where('status', 'completed')->avg('total'),
            'first_order' => $customer->orders()->oldest()->first()?->created_at,
            'last_order' => $customer->orders()->latest()->first()?->created_at,
        ];

        // آخر 10 طلبات
        $recentOrders = $customer->orders()->latest()->take(10)->get();

        return view('admin.customers.show', compact('customer', 'customerStats', 'recentOrders'));
    }

    /**
     * حذف عميل وجميع طلباته
     */
    public function destroy($id)
    {
        $customer = User::findOrFail($id);

        // حذف جميع طلبات العميل
        $customer->orders()->delete();

        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'تم حذف العميل وجميع طلباته بنجاح');
    }
}
