<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'items');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number, customer name, or email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Request $request, $id)
    {
        $order = Order::with('user', 'items.product')->findOrFail($id);

        if ($request->ajax()) {
            return view('admin.orders.partials.modal_content', compact('order'))->render();
        }

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            ]);

            $order = Order::findOrFail($id);
            $oldStatus = $order->status;
            $order->status = $request->status;

            // Update status timestamps
            switch ($request->status) {
                case 'confirmed':
                    if (!$order->confirmed_at) {
                        $order->confirmed_at = now();
                    }
                    break;
                case 'shipped':
                    if (!$order->shipped_at) {
                        $order->shipped_at = now();
                    }
                    break;
                case 'delivered':
                    if (!$order->delivered_at) {
                        $order->delivered_at = now();
                    }
                    if ($order->payment_method == 'cash' && $order->payment_status == 'pending') {
                        $order->payment_status = 'paid'; // Cash on delivery - paid when delivered
                    }
                    break;
            }

            $order->save();

            // Send status update email (optional)
            // Mail::to($order->customer_email)->send(new OrderStatusUpdated($order, $oldStatus));

            // Check if it's an AJAX request or expects JSON
            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => app()->getLocale() == 'ar' ? 'تم تحديث حالة الطلب' : 'Order status updated',
                    'status_label' => $order->status_label
                ]);
            }

            return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث حالة الطلب' : 'Order status updated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage(), [
                'order_id' => $id,
                'status' => $request->status,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => app()->getLocale() == 'ar' ? 'حدث خطأ أثناء تحديث الحالة' : 'An error occurred while updating status'
                ], 500);
            }

            return back()->with('error', app()->getLocale() == 'ar' ? 'حدث خطأ أثناء تحديث الحالة' : 'An error occurred');
        }
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_status' => 'required|in:pending,paid,failed',
            ]);

            $order = Order::findOrFail($id);
            $order->payment_status = $request->payment_status;
            $order->save();

            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => app()->getLocale() == 'ar' ? 'تم تحديث حالة الدفع' : 'Payment status updated',
                    'payment_status_label' => $order->payment_status_label
                ]);
            }

            return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث حالة الدفع' : 'Payment status updated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating payment status: ' . $e->getMessage(), [
                'order_id' => $id,
                'payment_status' => $request->payment_status,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => app()->getLocale() == 'ar' ? 'حدث خطأ أثناء تحديث حالة الدفع' : 'An error occurred while updating payment status'
                ], 500);
            }

            return back()->with('error', app()->getLocale() == 'ar' ? 'حدث خطأ أثناء تحديث حالة الدفع' : 'An error occurred');
        }
    }

    public function print($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('admin.orders.print', compact('order'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Only allow deletion of cancelled orders
        if ($order->status !== 'cancelled') {
            return back()->with('error', app()->getLocale() == 'ar' ? 'يمكن حذف الطلبات الملغاة فقط' : 'Only cancelled orders can be deleted');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', app()->getLocale() == 'ar' ? 'تم حذف الطلب' : 'Order deleted');
    }
}
