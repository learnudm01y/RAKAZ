@extends('admin.layouts.app')

@section('title', 'إدارة الطلبات')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">إدارة الطلبات</h1>
        <div>
            <span class="text-muted">إجمالي الطلبات: {{ $orders->total() }}</span>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">البحث</label>
                    <input type="text" name="search" class="form-control" placeholder="رقم الطلب، الاسم، البريد..." value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> بحث
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> إعادة تعيين
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>رقم الطلب</th>
                                <th>العميل</th>
                                <th>المنتجات</th>
                                <th>الإجمالي</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong>#{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->customer_name }}</strong><br>
                                            <small class="text-muted">{{ $order->customer_email }}</small><br>
                                            <small class="text-muted">{{ $order->customer_phone }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $order->items->count() }} منتج</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($order->total_amount, 2) }} د.إ</strong>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm status-select"
                                                data-order-id="{{ $order->id }}"
                                                style="width: 150px;">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                قيد الانتظار
                                            </option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                                قيد المعالجة
                                            </option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                                مكتمل
                                            </option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                                ملغي
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <small>{{ $order->created_at->format('Y-m-d') }}</small><br>
                                        <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                           class="btn btn-sm btn-info"
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد طلبات</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle status change
    const statusSelects = document.querySelectorAll('.status-select');

    statusSelects.forEach(select => {
        const originalValue = select.value;

        // Listen to change event
        select.addEventListener('change', function(e) {
            handleStatusChange(this, originalValue);
        });
    });

    async function handleStatusChange(selectElement, originalValue) {
        const orderId = selectElement.dataset.orderId;
        const newStatus = selectElement.value;

        try {
            const result = await Swal.fire({
                title: 'تأكيد التغيير',
                text: 'هل أنت متأكد من تغيير حالة الطلب؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، غيّر الحالة',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            });

            if (result.isConfirmed) {
                const response = await fetch(`/admin/orders/${orderId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم التحديث!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message || 'حدث خطأ');
                }
            } else {
                // Revert to original value
                selectElement.value = originalValue;
                // Trigger change event for CustomSelect to update UI
                selectElement.dispatchEvent(new Event('change'));
            }
        } catch (error) {
            console.error('Error:', error);
            selectElement.value = originalValue;
            selectElement.dispatchEvent(new Event('change'));

            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: error.message || 'حدث خطأ أثناء تحديث الحالة'
            });
        }
    }
});
</script>
@endpush
@endsection
