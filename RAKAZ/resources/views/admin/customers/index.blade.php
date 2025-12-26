@extends('admin.layouts.app')

@section('title', 'إدارة العملاء')

@section('page-title')
    <span class="ar-text">إدارة العملاء</span>
    <span class="en-text">Customers Management</span>
@endsection

@push('styles')
<style>
    .table thead th {
        background: #1a1a1a !important;
        color: white !important;
        padding: 16px 20px !important;
        font-weight: 600 !important;
        border-bottom: 3px solid #000 !important;
        text-transform: none !important;
        letter-spacing: 0.3px !important;
        font-size: 14px !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
    }

    .table thead th svg {
        margin-inline-end: 8px;
        vertical-align: middle;
    }

    /* Pagination Styles */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: white;
        border-top: 2px solid #f0f0f0;
        margin-top: 0;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
    }

    .pagination-info i {
        margin-inline-end: 8px;
    }

    html[lang="ar"] .en-text,
    html[data-locale="ar"] .en-text,
    [dir="rtl"] .en-text {
        display: none !important;
    }

    html[lang="en"] .ar-text,
    html[data-locale="en"] .ar-text,
    [dir="ltr"] .ar-text {
        display: none !important;
    }

    .pagination-links {
        display: flex;
        align-items: center;
    }

    .pagination-links nav > div:first-child {
        display: none !important;
    }

    .pagination-links nav {
        display: block !important;
    }

    .pagination-links .d-none.flex-sm-fill div:first-child,
    .pagination-links .small.text-muted,
    .pagination-links p.small {
        display: none !important;
    }

    .pagination-links nav.d-flex {
        display: flex !important;
        justify-content: flex-end !important;
    }

    .pagination-links nav.d-flex > div:last-child {
        display: flex !important;
    }

    .pagination {
        display: flex;
        gap: 6px;
        margin: 0;
        list-style: none;
        padding: 0;
    }

    [dir="rtl"] .pagination {
        flex-direction: row-reverse;
    }

    .pagination .page-item {
        margin: 0;
    }

    .pagination .page-link {
        padding: 8px 14px;
        border: 2px solid #e5e7eb;
        background: white;
        color: #374151;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s ease;
        min-width: 40px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pagination .page-link:hover {
        background: #1a1a1a;
        border-color: #1a1a1a;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .pagination .page-item.active .page-link {
        background: #1a1a1a;
        border-color: #1a1a1a;
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(26, 26, 26, 0.2);
    }

    .pagination .page-item.disabled .page-link {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination .page-item.disabled .page-link:hover {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        transform: none;
        box-shadow: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="ar-text">إدارة العملاء</h2>
            <h2 class="en-text">Customers Management</h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $statistics['total_customers'] }}</div>
                    <div class="stats-label">
                        <span class="ar-text">إجمالي العملاء</span>
                        <span class="en-text">Total Customers</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $statistics['active_customers'] }}</div>
                    <div class="stats-label">
                        <span class="ar-text">عملاء نشطون</span>
                        <span class="en-text">Active Customers</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $statistics['total_orders'] }}</div>
                    <div class="stats-label">
                        <span class="ar-text">إجمالي الطلبات</span>
                        <span class="en-text">Total Orders</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="stats-content">
                    <div class="stats-value">${{ number_format($statistics['total_revenue'], 0) }}</div>
                    <div class="stats-label">
                        <span class="ar-text">إجمالي الإيرادات</span>
                        <span class="en-text">Total Revenue</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.customers.index') }}"
                   class="btn btn-{{ !request('filter') ? 'primary' : 'outline-primary' }}">
                    <span class="ar-text">الكل</span>
                    <span class="en-text">All</span>
                </a>
                <a href="{{ route('admin.customers.index', ['filter' => 'active']) }}"
                   class="btn btn-{{ request('filter') == 'active' ? 'primary' : 'outline-primary' }}">
                    <span class="ar-text">نشطون</span>
                    <span class="en-text">Active</span>
                </a>
                <a href="{{ route('admin.customers.index', ['filter' => 'inactive']) }}"
                   class="btn btn-{{ request('filter') == 'inactive' ? 'primary' : 'outline-primary' }}">
                    <span class="ar-text">غير نشطون</span>
                    <span class="en-text">Inactive</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($customers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                            #
                                        </th>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span class="ar-text">العميل</span>
                                            <span class="en-text">Customer</span>
                                        </th>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="ar-text">البريد الإلكتروني</span>
                                            <span class="en-text">Email</span>
                                        </th>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                            <span class="ar-text">الطلبات</span>
                                            <span class="en-text">Orders</span>
                                        </th>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="ar-text">إجمالي الإنفاق</span>
                                            <span class="en-text">Total Spent</span>
                                        </th>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="ar-text">آخر طلب</span>
                                            <span class="en-text">Last Order</span>
                                        </th>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                            <span class="ar-text">الإجراءات</span>
                                            <span class="en-text">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                        <tr>
                                            <td>{{ $customer->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar me-2">
                                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $customer->name }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $customer->email }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $customer->orders_count }}</span>
                                            </td>
                                            <td>
                                                <strong>${{ number_format($customer->total_spent ?? 0, 2) }}</strong>
                                            </td>
                                            <td>
                                                {{ $customer->last_order_date ? $customer->last_order_date->diffForHumans() : 'N/A' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.customers.show', $customer->id) }}"
                                                   class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger"
                                                        onclick="deleteCustomer({{ $customer->id }})"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pagination-wrapper">
                            <div class="pagination-info">
                                <span class="ar-text">عرض {{ $customers->firstItem() }} إلى {{ $customers->lastItem() }} من إجمالي {{ $customers->total() }} عميل</span>
                                <span class="en-text">Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} total customers</span>
                            </div>
                            <div class="pagination-links">
                                {{ $customers->appends(['filter' => request('filter'), 'per_page' => request('per_page')])->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <p class="text-muted">
                                <span class="ar-text">لا يوجد عملاء</span>
                                <span class="en-text">No customers found</span>
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

.stats-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stats-icon svg {
    width: 30px;
    height: 30px;
    color: white;
}

.stats-content {
    flex: 1;
}

.stats-value {
    font-size: 28px;
    font-weight: bold;
    color: #2d3748;
}

.stats-label {
    font-size: 13px;
    color: #718096;
    margin-top: 4px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.pagination-wrapper {
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f9fafb;
    border-radius: 0 0 12px 12px;
}

.pagination-info {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    font-size: 14px;
    font-weight: 500;
}

.pagination-info i {
    color: #3b82f6;
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

<script>
function deleteCustomer(id) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "سيتم حذف العميل وجميع طلباته!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/customers/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire('تم الحذف!', response.message, 'success')
                        .then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire('خطأ!', xhr.responseJSON?.message || 'حدث خطأ أثناء الحذف', 'error');
                }
            });
        }
    });
}
</script>
@endsection
