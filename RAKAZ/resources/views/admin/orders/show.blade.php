@extends('admin.layouts.app')

@section('title', 'تفاصيل الطلب #' . $order->order_number)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">تفاصيل الطلب #{{ $order->order_number }}</h1>
            <small class="text-muted">تاريخ الطلب: {{ $order->created_at->format('d F Y - H:i') }}</small>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
    </div>

    <div class="row">
        <!-- Order Status Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> حالة الطلب
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">الحالة الحالية</label>
                        <select class="form-select" id="orderStatus">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary w-100" onclick="updateOrderStatus()">
                        <i class="fas fa-save"></i> تحديث الحالة
                    </button>

                    <hr>

                    <div class="mb-2">
                        <strong>الإجمالي:</strong>
                        <span class="float-end">{{ number_format($order->total_amount, 2) }} د.إ</span>
                    </div>
                    <div class="mb-2">
                        <strong>رسوم الشحن:</strong>
                        <span class="float-end">{{ number_format($order->shipping_fee ?? 0, 2) }} د.إ</span>
                    </div>
                    @if($order->discount_amount)
                    <div class="mb-2 text-success">
                        <strong>الخصم:</strong>
                        <span class="float-end">-{{ number_format($order->discount_amount, 2) }} د.إ</span>
                    </div>
                    @endif
                    <hr>
                    <div>
                        <strong>المجموع الكلي:</strong>
                        <span class="float-end h5 text-primary">{{ number_format($order->total_amount, 2) }} د.إ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info Card -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> معلومات العميل
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">الاسم</label>
                            <p class="mb-0"><strong>{{ $order->customer_name }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">البريد الإلكتروني</label>
                            <p class="mb-0">{{ $order->customer_email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">رقم الهاتف</label>
                            <p class="mb-0">{{ $order->customer_phone }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">طريقة الدفع</label>
                            <p class="mb-0">
                                @if($order->payment_method == 'cash')
                                    الدفع عند الاستلام
                                @else
                                    {{ $order->payment_method }}
                                @endif
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted">عنوان الشحن</label>
                            <p class="mb-0">{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items Card -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-shopping-bag"></i> المنتجات ({{ $order->items->count() }} منتج)
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">الصورة</th>
                            <th>المنتج</th>
                            <th>اللون</th>
                            <th>المقاس</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>الإجمالي</th>
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
                                    ? ($product->name[app()->getLocale()] ?? $product->name['ar'] ?? $product->name['en'] ?? 'منتج')
                                    : $product->name) : 'منتج محذوف';
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
                                <td>{{ number_format($item->price, 2) }} د.إ</td>
                                <td><span class="badge bg-secondary">{{ $item->quantity }}</span></td>
                                <td><strong>{{ number_format($item->price * $item->quantity, 2) }} د.إ</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="6" class="text-end"><strong>الإجمالي:</strong></td>
                            <td><strong>{{ number_format($order->total_amount, 2) }} د.إ</strong></td>
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
async function updateOrderStatus() {
    const newStatus = document.getElementById('orderStatus').value;

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
            const response = await fetch('{{ route("admin.orders.updateStatus", $order->id) }}', {
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
        }
    } catch (error) {
        console.error('Error:', error);

        Swal.fire({
            icon: 'error',
            title: 'خطأ!',
            text: error.message || 'حدث خطأ أثناء تحديث الحالة'
        });
    }
}
</script>
@endpush
@endsection
