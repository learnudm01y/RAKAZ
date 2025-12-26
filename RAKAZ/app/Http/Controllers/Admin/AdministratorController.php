<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdministratorController extends Controller
{
    /**
     * Display a listing of administrators.
     */
    public function index(Request $request)
    {
        try {
            $query = User::where('is_admin', true);

            // Search functionality
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('id', $search);
                });
            }

            $perPage = $request->get('per_page', 15);
            $administrators = $query->orderBy('created_at', 'desc')->paginate($perPage);

            // Statistics
            $statistics = [
                'total_admins' => User::where('is_admin', true)->count(),
                'verified_admins' => User::where('is_admin', true)->whereNotNull('email_verified_at')->count(),
                'unverified_admins' => User::where('is_admin', true)->whereNull('email_verified_at')->count(),
                'new_this_month' => User::where('is_admin', true)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
            ];

            Log::info('Administrators index page loaded', [
                'total' => $administrators->total(),
                'statistics' => $statistics
            ]);

            return view('admin.administrators.index', compact('administrators', 'statistics'));
        } catch (\Exception $e) {
            Log::error('Error loading administrators index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'حدث خطأ أثناء تحميل المسؤولين');
        }
    }

    /**
     * Show the form for creating a new administrator.
     */
    public function create()
    {
        return view('admin.administrators.create');
    }

    /**
     * Store a newly created administrator in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $admin = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_admin' => true,
                'email_verified_at' => $request->has('email_verified') ? now() : null,
            ]);

            Log::info('New administrator created', [
                'admin_id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email
            ]);

            return redirect()->route('admin.administrators.index')
                ->with('success', 'تم إنشاء المسؤول بنجاح');
        } catch (\Exception $e) {
            Log::error('Error creating administrator', [
                'error' => $e->getMessage(),
                'data' => $request->except('password')
            ]);

            return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء المسؤول');
        }
    }

    /**
     * Display the specified administrator.
     */
    public function show($id)
    {
        try {
            $admin = User::where('is_admin', true)->findOrFail($id);

            Log::info('Administrator details viewed', [
                'admin_id' => $admin->id,
                'name' => $admin->name
            ]);

            return view('admin.administrators.show', compact('admin'));
        } catch (\Exception $e) {
            Log::error('Error loading administrator details', [
                'admin_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.administrators.index')
                ->with('error', 'المسؤول غير موجود');
        }
    }

    /**
     * Show the form for editing the specified administrator.
     */
    public function edit($id)
    {
        try {
            $admin = User::where('is_admin', true)->findOrFail($id);
            return view('admin.administrators.edit', compact('admin'));
        } catch (\Exception $e) {
            Log::error('Error loading administrator edit form', [
                'admin_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.administrators.index')
                ->with('error', 'المسؤول غير موجود');
        }
    }

    /**
     * Update the specified administrator in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $admin = User::where('is_admin', true)->findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $admin->name = $validated['name'];
            $admin->email = $validated['email'];

            if ($request->filled('password')) {
                $admin->password = Hash::make($validated['password']);
            }

            if ($request->has('email_verified')) {
                $admin->email_verified_at = now();
            } else {
                $admin->email_verified_at = null;
            }

            $admin->save();

            Log::info('Administrator updated', [
                'admin_id' => $admin->id,
                'name' => $admin->name,
                'password_changed' => $request->filled('password')
            ]);

            return redirect()->route('admin.administrators.index')
                ->with('success', 'تم تحديث المسؤول بنجاح');
        } catch (\Exception $e) {
            Log::error('Error updating administrator', [
                'admin_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث المسؤول');
        }
    }

    /**
     * Remove the specified administrator from storage.
     */
    public function destroy($id)
    {
        try {
            // Prevent deleting yourself
            if (auth()->id() == $id) {
                return response()->json([
                    'message' => 'لا يمكنك حذف حسابك الخاص'
                ], 403);
            }

            $admin = User::where('is_admin', true)->findOrFail($id);
            $adminName = $admin->name;

            $admin->delete();

            Log::warning('Administrator deleted', [
                'admin_id' => $id,
                'name' => $adminName,
                'deleted_by' => auth()->id()
            ]);

            return response()->json([
                'message' => 'تم حذف المسؤول بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting administrator', [
                'admin_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'حدث خطأ أثناء حذف المسؤول'
            ], 500);
        }
    }

    /**
     * Toggle administrator verification status.
     */
    public function toggleVerification($id)
    {
        try {
            $admin = User::where('is_admin', true)->findOrFail($id);

            $admin->email_verified_at = $admin->email_verified_at ? null : now();
            $admin->save();

            Log::info('Administrator verification toggled', [
                'admin_id' => $admin->id,
                'verified' => $admin->email_verified_at ? true : false
            ]);

            return response()->json([
                'message' => 'تم تحديث حالة التحقق بنجاح',
                'verified' => $admin->email_verified_at ? true : false
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling administrator verification', [
                'admin_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'حدث خطأ أثناء تحديث حالة التحقق'
            ], 500);
        }
    }
}
