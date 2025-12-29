@extends('admin.layouts.app')

@section('title', __('admin.orders.title'))

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

    /* Force hide English text in Arabic mode */
    html[lang="ar"] .en-text,
    html[data-locale="ar"] .en-text,
    [dir="rtl"] .en-text {
        display: none !important;
    }

    /* Force hide Arabic text in English mode */
    html[lang="en"] .ar-text,
    html[data-locale="en"] .ar-text,
    [dir="ltr"] .ar-text {
        display: none !important;
    }

    .pagination-links {
        display: flex;
        align-items: center;
    }

    /* Hide default Bootstrap pagination text */
    .pagination-links nav > div:first-child {
        display: none !important;
    }

    .pagination-links nav {
        display: block !important;
    }

    /* Hide the English stats in Bootstrap pagination */
    .pagination-links .d-none.flex-sm-fill div:first-child,
    .pagination-links .small.text-muted,
    .pagination-links p.small {
        display: none !important;
    }

    /* Ensure pagination nav uses flex for proper alignment */
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
<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col-12">
            <h2>{{ __('admin.orders.title') }}</h2>
            <p class="text-muted">{{ __('admin.orders.total_orders') }}: {{ $orders->total() }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3 overflow-visible">
        <div class="card-body">
            <form method="GET">
                <div class="row g-2 align-items-end">
                    {{-- Search Input --}}
                    <div class="col-md-4">
                        <label class="form-label small text-muted">{{ __('admin.orders.search') }}</label>
                        <input type="text" name="search" class="form-control" placeholder="{{ __('admin.orders.search_placeholder') }}" value="{{ request('search') }}">
                    </div>

                    {{-- Date From --}}
                    <div class="col-md-2">
                        <label class="form-label small text-muted">{{ __('admin.orders.date_from') }}</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    {{-- Date To --}}
                    <div class="col-md-2">
                        <label class="form-label small text-muted">{{ __('admin.orders.date_to') }}</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    {{-- Status Filter --}}
                    <div class="col-md-2">
                        <label class="form-label small text-muted">{{ __('admin.orders.status') }}</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">{{ __('admin.orders.all_statuses') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('admin.orders.statuses.pending') }}</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>{{ __('admin.orders.statuses.confirmed') }}</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('admin.orders.statuses.processing') }}</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>{{ __('admin.orders.statuses.shipped') }}</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('admin.orders.statuses.delivered') }}</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('admin.orders.statuses.cancelled') }}</option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">{{ __('admin.orders.filter') }}</button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary flex-grow-1">{{ __('admin.orders.reset') }}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($orders->count() > 0)
                <div class="table-responsive" style="min-height: 400px;">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    {{ __('admin.orders.table.order_number') }}
                                </th>
                                <th class="py-3">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ __('admin.orders.table.customer') }}
                                </th>
                                <th class="py-3">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    {{ __('admin.orders.table.products') }}
                                </th>
                                <th class="py-3">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('admin.orders.table.total') }}
                                </th>
                                <th class="py-3" style="width: 180px;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('admin.orders.table.status') }}
                                </th>
                                <th class="py-3">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ __('admin.orders.table.date') }}
                                </th>
                                <th class="px-4 py-3">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ __('admin.orders.table.view') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="px-4"><strong>#{{ $order->order_number }}</strong></td>
                                    <td>
                                        <div class="fw-bold">{{ $order->customer_name }}</div>
                                        <div class="small text-muted">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $order->items->count() }}</span></td>
                                    <td>{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @php
                                            $statuses = [
                                                'pending' => __('admin.orders.statuses.pending'),
                                                'confirmed' => __('admin.orders.statuses.confirmed'),
                                                'processing' => __('admin.orders.statuses.processing'),
                                                'shipped' => __('admin.orders.statuses.shipped'),
                                                'delivered' => __('admin.orders.statuses.delivered'),
                                                'cancelled' => __('admin.orders.statuses.cancelled')
                                            ];
                                            $statusClasses = [
                                                'pending' => 'bg-warning text-dark',
                                                'confirmed' => 'bg-info text-white',
                                                'processing' => 'bg-primary text-white',
                                                'shipped' => 'bg-info text-white',
                                                'delivered' => 'bg-success text-white',
                                                'cancelled' => 'bg-danger text-white',
                                            ];
                                            $currentStatus = $statuses[$order->status] ?? $order->status;
                                            $currentClass = $statusClasses[$order->status] ?? 'bg-secondary text-white';
                                        @endphp
                                        <span class="badge {{ $currentClass }} fs-6 fw-normal px-3 py-2 rounded-pill" id="status-badge-{{ $order->id }}">
                                            {{ $currentStatus }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4">
                                        <button type="button" class="btn btn-sm btn-primary view-order-btn" data-order-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        <span class="ar-text">عرض {{ $orders->firstItem() ?? 0 }} إلى {{ $orders->lastItem() ?? 0 }} من إجمالي {{ $orders->total() }} طلب</span>
                        <span class="en-text">Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} total orders</span>
                    </div>
                    <div class="pagination-links">
                        {{ $orders->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">{{ __('admin.orders.no_orders') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">{{ __('admin.orders.order_details') }} <span id="modalOrderNumber" class="fw-bold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('admin.orders.loading') }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="printInvoiceBtn">
                    <i class="fas fa-print me-2"></i>{{ __('admin.orders.print_invoice') }}
                </button>
                <button type="button" class="btn btn-success" id="downloadInvoiceBtn">
                    <i class="fas fa-download me-2"></i>{{ __('admin.orders.download_pdf') }}
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.orders.close') }}</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Fix for dropdowns inside responsive tables */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Ensure dropdowns are visible */
    .dropdown-menu {
        margin-top: 0;
    }

    /* Custom scrollbar for table */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Update Logic (Delegated for Modal)
    document.addEventListener('click', async function(e) {
        if (e.target && e.target.id === 'updateStatusBtn') {
            e.preventDefault();

            const btn = e.target;
            const form = document.getElementById('updateStatusForm');
            const orderId = form.dataset.orderId;
            const statusSelect = document.getElementById('modalStatusSelect');
            const newStatus = statusSelect.value;
            const newLabel = statusSelect.options[statusSelect.selectedIndex].text;

            // SweetAlert Confirmation
            const result = await Swal.fire({
                title: '{{ __('admin.orders.messages.confirm_status_change') }}',
                text: '{{ __('admin.orders.messages.change_status_text') }}'.replace(':status', newLabel),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __('admin.orders.messages.yes_change') }}',
                cancelButtonText: '{{ __('admin.orders.messages.cancel') }}'
            });

            if (!result.isConfirmed) return;

            btn.disabled = true;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('admin.orders.messages.updating') }}';

            try {
                const response = await fetch(`/admin/orders/${orderId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                // Check if response is OK before parsing JSON
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Server Error:', errorText);
                    throw new Error(`خطأ في الخادم (${response.status}): ${response.statusText}`);
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const htmlResponse = await response.text();
                    console.error('Expected JSON but received:', htmlResponse.substring(0, 200));
                    throw new Error('الخادم لم يرجع استجابة JSON صحيحة');
                }

                const data = await response.json();

                if (data.success) {
                    // Update Badge in Table
                    const badge = document.getElementById(`status-badge-${orderId}`);
                    if (badge) {
                        badge.textContent = newLabel;

                        // Update Badge Color
                        badge.className = 'badge fs-6 fw-normal px-3 py-2 rounded-pill'; // Reset
                        const statusClasses = {
                            'pending': 'bg-warning text-dark',
                            'confirmed': 'bg-info text-white',
                            'processing': 'bg-primary text-white',
                            'shipped': 'bg-info text-white',
                            'delivered': 'bg-success text-white',
                            'cancelled': 'bg-danger text-white'
                        };
                        const newClass = statusClasses[newStatus] || 'bg-secondary text-white';
                        const classesToAdd = newClass.split(' ');
                        badge.classList.add(...classesToAdd);
                    }

                    // Success Message
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __('admin.orders.messages.updated') }}',
                        text: '{{ __('admin.orders.messages.status_updated_success') }}',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message || '{{ __('admin.orders.messages.error_occurred') }}');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('admin.orders.messages.error') }}',
                    text: error.message
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    });

    // Modal Logic
    const orderModal = new bootstrap.Modal(document.getElementById('orderModal'));

    document.querySelectorAll('.view-order-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const orderId = this.dataset.orderId;
            const orderNumber = this.dataset.orderNumber;
            const modalContent = document.getElementById('modalContent');
            const modalTitle = document.getElementById('modalOrderNumber');

            modalTitle.textContent = '#' + orderNumber;

            // Show modal with loading state
            modalContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('admin.orders.loading') }}</span>
                    </div>
                </div>
            `;
            orderModal.show();

            try {
                const response = await fetch(`/admin/orders/${orderId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const html = await response.text();
                modalContent.innerHTML = html;

            } catch (error) {
                modalContent.innerHTML = `
                    <div class="alert alert-danger">
                        {{ __('admin.orders.messages.load_error') }}
                    </div>
                `;
            }
        });
    });

    // Invoice Preview & Download Functionality
    let currentOrderId = null;

    // Store order ID when modal opens
    document.querySelectorAll('.view-order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentOrderId = this.dataset.orderId;
        });
    });

    // Print Invoice Directly
    document.addEventListener('click', function(e) {
        if (e.target && (e.target.id === 'printInvoiceBtn' || e.target.closest('#printInvoiceBtn'))) {
            e.preventDefault();
            if (currentOrderId) {
                // Open invoice in new window and trigger print
                var printWindow = window.open(`/admin/orders/${currentOrderId}/invoice/stream`, '_blank');
                printWindow.onload = function() {
                    printWindow.print();
                };
            }
        }
    });

    // Download Invoice as PDF
    document.addEventListener('click', function(e) {
        if (e.target && (e.target.id === 'downloadInvoiceBtn' || e.target.closest('#downloadInvoiceBtn'))) {
            e.preventDefault();
            if (currentOrderId) {
                window.location.href = `/admin/orders/${currentOrderId}/invoice/download`;
            }
        }
    });
});
</script>
@endpush
@endsection
