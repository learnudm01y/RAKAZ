@extends('admin.layouts.app')

@section('title', 'تفاصيل المستخدم')

@section('page-title')
    <span class="ar-text">تفاصيل المستخدم</span>
    <span class="en-text">User Details</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="ar-text">تفاصيل المستخدم</h2>
                <h2 class="en-text">User Details</h2>
            </div>
            <div>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    <span class="ar-text">تعديل</span>
                    <span class="en-text">Edit</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i>
                    <span class="ar-text">رجوع</span>
                    <span class="en-text">Back</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="user-avatar-large">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    @if($user->email_verified_at)
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle"></i>
                            <span class="ar-text">تم التحقق</span>
                            <span class="en-text">Verified</span>
                        </span>
                    @else
                        <span class="badge bg-warning">
                            <i class="fas fa-exclamation-circle"></i>
                            <span class="ar-text">غير محقق</span>
                            <span class="en-text">Not Verified</span>
                        </span>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <span class="ar-text">الإحصائيات</span>
                        <span class="en-text">Statistics</span>
                    </h5>
                    <ul class="list-unstyled">
                        <li class="d-flex justify-content-between mb-2">
                            <span class="ar-text">إجمالي الطلبات:</span>
                            <span class="en-text">Total Orders:</span>
                            <strong>{{ $orderStats['total_orders'] }}</strong>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span class="ar-text">إجمالي الإنفاق:</span>
                            <span class="en-text">Total Spent:</span>
                            <strong>${{ number_format($orderStats['total_spent'], 2) }}</strong>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span class="ar-text">آخر طلب:</span>
                            <span class="en-text">Last Order:</span>
                            <strong>{{ $orderStats['last_order_date'] ? $orderStats['last_order_date']->format('Y-m-d') : 'N/A' }}</strong>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span class="ar-text">تاريخ التسجيل:</span>
                            <span class="en-text">Registered:</span>
                            <strong>{{ $user->created_at->format('Y-m-d') }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <span class="ar-text">سجل الطلبات</span>
                        <span class="en-text">Order History</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <span class="ar-text">رقم الطلب</span>
                                            <span class="en-text">Order #</span>
                                        </th>
                                        <th>
                                            <span class="ar-text">التاريخ</span>
                                            <span class="en-text">Date</span>
                                        </th>
                                        <th>
                                            <span class="ar-text">الحالة</span>
                                            <span class="en-text">Status</span>
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
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>${{ number_format($order->total, 2) }}</td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
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
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">
                                <span class="ar-text">لا توجد طلبات لهذا المستخدم</span>
                                <span class="en-text">No orders for this user</span>
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

.user-avatar-large {
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
