@extends('admin.layouts.app')

@section('title', 'تفاصيل العميل')

@section('page-title')
    <span class="ar-text">تفاصيل العميل</span>
    <span class="en-text">Customer Details</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="ar-text">تفاصيل العميل</h2>
                <h2 class="en-text">Customer Details</h2>
            </div>
            <div>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i>
                    <span class="ar-text">رجوع</span>
                    <span class="en-text">Back</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Info Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="customer-avatar-large">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                    </div>
                    <h4>{{ $customer->name }}</h4>
                    <p class="text-muted">{{ $customer->email }}</p>
                    @if($customer->email_verified_at)
                        <span class="badge bg-success mb-3">
                            <i class="fas fa-check-circle"></i>
                            <span class="ar-text">تم التحقق</span>
                            <span class="en-text">Verified</span>
                        </span>
                    @endif

                    <div class="mt-3">
                        <small class="text-muted">
                            <span class="ar-text">عضو منذ</span>
                            <span class="en-text">Member since</span>
                            {{ $customer->created_at->format('F Y') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <span class="ar-text">إحصائيات العميل</span>
                        <span class="en-text">Customer Statistics</span>
                    </h5>
                    <div class="stat-item">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-value">{{ $statistics['total_orders'] }}</div>
                            <div class="stat-label">
                                <span class="ar-text">إجمالي الطلبات</span>
                                <span class="en-text">Total Orders</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-value">${{ number_format($statistics['total_spent'], 2) }}</div>
                            <div class="stat-label">
                                <span class="ar-text">إجمالي الإنفاق</span>
                                <span class="en-text">Total Spent</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-value">${{ number_format($statistics['average_order_value'], 2) }}</div>
                            <div class="stat-label">
                                <span class="ar-text">متوسط قيمة الطلب</span>
                                <span class="en-text">Avg. Order Value</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-value">
                                {{ $statistics['last_order_date'] ? $statistics['last_order_date']->format('Y-m-d') : 'N/A' }}
                            </div>
                            <div class="stat-label">
                                <span class="ar-text">آخر طلب</span>
                                <span class="en-text">Last Order</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders History -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <span class="ar-text">سجل الطلبات</span>
                        <span class="en-text">Order History</span>
                    </h5>
                    <span class="badge bg-primary">{{ $orders->total() }} <span class="ar-text">طلب</span><span class="en-text">orders</span></span>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>
                                            <span class="ar-text">التاريخ</span>
                                            <span class="en-text">Date</span>
                                        </th>
                                        <th>
                                            <span class="ar-text">الحالة</span>
                                            <span class="en-text">Status</span>
                                        </th>
                                        <th>
                                            <span class="ar-text">العناصر</span>
                                            <span class="en-text">Items</span>
                                        </th>
                                        <th>
                                            <span class="ar-text">المبلغ</span>
                                            <span class="en-text">Amount</span>
                                        </th>
                                        <th>
                                            <span class="ar-text">الإجراءات</span>
                                            <span class="en-text">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td><strong>#{{ $order->id }}</strong></td>
                                            <td>
                                                <div>{{ $order->created_at->format('Y-m-d') }}</div>
                                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'completed' => 'success',
                                                        'pending' => 'warning',
                                                        'processing' => 'info',
                                                        'cancelled' => 'danger',
                                                        'refunded' => 'secondary'
                                                    ];
                                                    $color = $statusColors[$order->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $order->items_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <strong>${{ number_format($order->total, 2) }}</strong>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="View Order">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pagination-wrapper mt-3">
                            <div class="pagination-links">
                                {{ $orders->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                            <p class="text-muted">
                                <span class="ar-text">لا توجد طلبات لهذا العميل</span>
                                <span class="en-text">No orders found for this customer</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
b, strong {
    font-weight: bolder;
    padding-right: 10px;
}
.align-items-center {
    align-items: center !important;
    margin-right: -35px;
}

.customer-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: bold;
    margin: 0 auto;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    border-radius: 8px;
    background: #f8f9fa;
    margin-bottom: 15px;
    transition: transform 0.2s;
}

.stat-item:hover {
    transform: translateX(-5px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.stat-details {
    flex: 1;
}

.stat-value {
    font-size: 20px;
    font-weight: bold;
    color: #2d3748;
}

.stat-label {
    font-size: 12px;
    color: #718096;
    margin-top: 2px;
}

.pagination-wrapper {
    padding: 16px 0;
    display: flex;
    justify-content: center;
}

.pagination-links {
    display: flex;
    gap: 8px;
}

.pagination {
    display: flex;
    gap: 4px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.page-item {
    border-radius: 8px;
    overflow: hidden;
}

.page-link {
    padding: 10px 16px;
    border: 1px solid #e5e7eb;
    color: #1f2937;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    transition: all 0.2s;
    background: white;
    font-weight: 500;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.page-link:hover:not(.active) {
    background: #f3f4f6;
    border-color: #d1d5db;
    transform: translateY(-2px);
}

.page-item.disabled .page-link {
    background: #f9fafb;
    color: #d1d5db;
    cursor: not-allowed;
}
</style>
@endsection
