<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', app()->getLocale() == 'ar' ? 'يجب تسجيل الدخول لعرض الطلبات' : 'Please login to view orders');
        }

        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        // Check if user owns this order OR allow guest access by order number in session
        if (auth()->check()) {
            if ($order->user_id !== auth()->id()) {
                abort(403, 'Unauthorized access');
            }
        } else {
            // For guest orders, check session or require order number + email
            $guestOrderIds = session('guest_order_ids', []);
            if (!in_array($order->id, $guestOrderIds)) {
                abort(403, 'Unauthorized access');
            }
        }

        return view('frontend.orders.show', compact('order'));
    }

    public function track(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('frontend.orders.track');
        }

        $request->validate([
            'order_number' => 'required|string',
            'email' => 'required|email',
        ]);

        $order = Order::where('order_number', $request->order_number)
            ->where('customer_email', $request->email)
            ->first();

        if (!$order) {
            return back()->with('error', app()->getLocale() == 'ar' ? 'لم يتم العثور على الطلب' : 'Order not found');
        }

        // Store order ID in session for guest access
        $guestOrderIds = session('guest_order_ids', []);
        if (!in_array($order->id, $guestOrderIds)) {
            $guestOrderIds[] = $order->id;
            session(['guest_order_ids' => $guestOrderIds]);
        }

        return redirect()->route('orders.show', $order->id);
    }
}
