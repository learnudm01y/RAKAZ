@extends('admin.layouts.app')

@section('title', (app()->getLocale() == 'ar' ? 'تفاصيل الطلب #' : 'Order Details #') . $order->order_number)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ app()->getLocale() == 'ar' ? 'تفاصيل الطلب' : 'Order Details' }} #{{ $order->order_number }}</h1>
            <small class="text-muted">{{ app()->getLocale() == 'ar' ? 'تاريخ الطلب' : 'Order Date' }}: {{ $order->created_at->format('d F Y - H:i') }}</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-dark" id="printInvoiceBtn">
                <i class="fas fa-print {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ app()->getLocale() == 'ar' ? 'طباعة الفاتورة' : 'Print Invoice' }}
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i> {{ app()->getLocale() == 'ar' ? 'العودة للقائمة' : 'Back to List' }}
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Status Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-dark">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> {{ app()->getLocale() == 'ar' ? 'حالة الطلب' : 'Order Status' }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الحالة الحالية' : 'Current Status' }}</label>
                        <select class="form-select" id="orderStatus">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'قيد الانتظار' : 'Pending' }}</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'قيد التحضير' : 'Confirmed' }}</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'قيد المعالجة' : 'Processing' }}</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'تم الشحن' : 'Shipped' }}</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'تم التوصيل' : 'Delivered' }}</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'ملغي' : 'Cancelled' }}</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-dark w-100" onclick="updateOrderStatus()">
                        <i class="fas fa-save"></i> {{ app()->getLocale() == 'ar' ? 'تحديث الحالة' : 'Update Status' }}
                    </button>

                    <hr>

                    <div class="mb-2 d-flex justify-content-between">
                        <strong>{{ app()->getLocale() == 'ar' ? 'الإجمالي:' : 'Subtotal:' }}</strong>
                        <span>{{ number_format($order->total_amount, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <strong>{{ app()->getLocale() == 'ar' ? 'رسوم الشحن:' : 'Shipping:' }}</strong>
                        <span>{{ number_format($order->shipping_fee ?? 0, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                    </div>
                    @if($order->discount_amount)
                    <div class="mb-2 d-flex justify-content-between">
                        <strong>{{ app()->getLocale() == 'ar' ? 'الخصم:' : 'Discount:' }}</strong>
                        <span>-{{ number_format($order->discount_amount, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>{{ app()->getLocale() == 'ar' ? 'المجموع الكلي:' : 'Total:' }}</strong>
                        <span class="h5 mb-0" style="color: #000;">{{ number_format($order->total_amount, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info Card -->
        <div class="col-md-8 mb-4">
            <div class="card border-dark">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> {{ app()->getLocale() == 'ar' ? 'معلومات العميل' : 'Customer Information' }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">{{ app()->getLocale() == 'ar' ? 'الاسم' : 'Name' }}</label>
                            <p class="mb-0"><strong>{{ $order->customer_name }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                            <p class="mb-0">{{ $order->customer_email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">{{ app()->getLocale() == 'ar' ? 'رقم الهاتف' : 'Phone Number' }}</label>
                            <p class="mb-0">{{ $order->customer_phone }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">{{ app()->getLocale() == 'ar' ? 'طريقة الدفع' : 'Payment Method' }}</label>
                            <p class="mb-0">
                                @if($order->payment_method == 'cash')
                                    {{ app()->getLocale() == 'ar' ? 'الدفع عند الاستلام' : 'Cash on Delivery' }}
                                @else
                                    {{ $order->payment_method }}
                                @endif
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted">{{ app()->getLocale() == 'ar' ? 'عنوان الشحن' : 'Shipping Address' }}</label>
                            <p class="mb-0">{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items Card -->
    <div class="card border-dark">
        <div class="card-header bg-dark text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-shopping-bag"></i> {{ app()->getLocale() == 'ar' ? 'المنتجات' : 'Products' }} ({{ $order->items->count() }} {{ app()->getLocale() == 'ar' ? 'منتج' : 'items' }})
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 80px;">{{ app()->getLocale() == 'ar' ? 'الصورة' : 'Image' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'المنتج' : 'Product' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'اللون' : 'Color' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'المقاس' : 'Size' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'السعر' : 'Price' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'الكمية' : 'Qty' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'الإجمالي' : 'Total' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            @php
                                $product = $item->product;
                                $mainImage = null;

                                if ($product && $product->main_image) {
                                    $mainImage = asset('storage/' . $product->main_image);
                                } elseif ($product && $product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0) {
                                    $mainImage = asset('storage/' . $product->gallery_images[0]);
                                } else {
                                    $mainImage = asset('assets/images/placeholder.jpg');
                                }

                                $productName = $product ? (is_array($product->name)
                                    ? ($product->name[app()->getLocale()] ?? $product->name['ar'] ?? $product->name['en'] ?? (app()->getLocale() == 'ar' ? 'منتج' : 'Product'))
                                    : $product->name) : (app()->getLocale() == 'ar' ? 'منتج محذوف' : 'Deleted Product');
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ $mainImage }}" alt="{{ $productName }}"
                                         class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                </td>
                                <td>
                                    <strong>{{ $productName }}</strong>
                                    @if($product)
                                        <br><small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                                    @endif
                                </td>
                                <td>{{ $item->color ?? '-' }}</td>
                                <td>{{ $item->size ?? '-' }}</td>
                                <td>{{ number_format($item->price, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</td>
                                <td><span class="badge bg-dark">{{ $item->quantity }}</span></td>
                                <td><strong>{{ number_format($item->price * $item->quantity, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="6" class="text-end"><strong>{{ app()->getLocale() == 'ar' ? 'الإجمالي:' : 'Total:' }}</strong></td>
                            <td><strong>{{ number_format($order->total_amount, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const isArabic = '{{ app()->getLocale() }}' === 'ar';
const orderId = {{ $order->id }};

// Print Invoice Button
document.getElementById('printInvoiceBtn').addEventListener('click', function(e) {
    e.preventDefault();
    // Open invoice in new window and trigger print
    var printWindow = window.open(`/admin/orders/${orderId}/invoice/stream`, '_blank');
    printWindow.onload = function() {
        printWindow.print();
    };
});

async function updateOrderStatus() {
    const newStatus = document.getElementById('orderStatus').value;

    try {
        const result = await Swal.fire({
            title: isArabic ? 'تأكيد التغيير' : 'Confirm Change',
            text: isArabic ? 'هل أنت متأكد من تغيير حالة الطلب؟' : 'Are you sure you want to change the order status?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: isArabic ? 'نعم، غيّر الحالة' : 'Yes, Change Status',
            cancelButtonText: isArabic ? 'إلغاء' : 'Cancel',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33'
        });

        if (result.isConfirmed) {
            const response = await fetch('{{ route("admin.orders.updateStatus", $order->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ status: newStatus })
            });

            // Check if response is OK before parsing JSON
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server Error:', errorText);
                throw new Error(isArabic ? `خطأ في الخادم (${response.status}): ${response.statusText}` : `Server Error (${response.status}): ${response.statusText}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const htmlResponse = await response.text();
                console.error('Expected JSON but received:', htmlResponse.substring(0, 200));
                throw new Error(isArabic ? 'الخادم لم يرجع استجابة JSON صحيحة' : 'Server did not return a valid JSON response');
            }

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: isArabic ? 'تم التحديث!' : 'Updated!',
                    text: data.message || (isArabic ? 'تم تحديث الحالة بنجاح' : 'Status updated successfully'),
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message || (isArabic ? 'حدث خطأ' : 'An error occurred'));
            }
        }
    } catch (error) {
        console.error('Error:', error);

        Swal.fire({
            icon: 'error',
            title: isArabic ? 'خطأ!' : 'Error!',
            text: error.message || (isArabic ? 'حدث خطأ أثناء تحديث الحالة' : 'An error occurred while updating the status')
        });
    }
}
</script>
@endpush
@endsection
