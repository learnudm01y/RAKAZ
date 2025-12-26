<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    /**
     * عرض صفحة إدارة المستخدمين مع الإحصائيات
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 15);

        // الإحصائيات
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'users_with_orders' => User::has('orders')->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)
                                          ->whereYear('created_at', now()->year)
                                          ->count(),
        ];

        // جلب المستخدمين مع البحث
        $users = User::withCount('orders')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('id', $search);
            })
            ->latest()
            ->paginate($perPage);

        return view('admin.users.index', compact('users', 'stats', 'search', 'perPage'));
    }

    /**
     * عرض صفحة إنشاء مستخدم جديد
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * حفظ مستخدم جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => $request->has('verified') ? now() : null,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    /**
     * عرض تفاصيل مستخدم
     */
    public function show($id)
    {
        $user = User::with(['orders' => function($query) {
            $query->latest()->take(10);
        }])->findOrFail($id);

        $userStats = [
            'total_orders' => $user->orders()->count(),
            'completed_orders' => $user->orders()->where('status', 'completed')->count(),
            'pending_orders' => $user->orders()->where('status', 'pending')->count(),
            'total_spent' => $user->orders()->where('status', 'completed')->sum('total'),
        ];

        return view('admin.users.show', compact('user', 'userStats'));
    }

    /**
     * عرض صفحة تعديل مستخدم
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * تحديث بيانات مستخدم
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->has('verified')) {
            $data['email_verified_at'] = $request->verified ? now() : null;
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    /**
     * حذف مستخدم
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // حذف طلبات المستخدم أولاً
        $user->orders()->delete();

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * تفعيل/إلغاء تفعيل البريد الإلكتروني
     */
    public function toggleVerification($id)
    {
        $user = User::findOrFail($id);

        $user->email_verified_at = $user->email_verified_at ? null : now();
        $user->save();

        $message = $user->email_verified_at ? 'تم تفعيل البريد الإلكتروني' : 'تم إلغاء تفعيل البريد الإلكتروني';

        return response()->json([
            'success' => true,
            'message' => $message,
            'verified' => $user->email_verified_at ? true : false
        ]);
    }
}
