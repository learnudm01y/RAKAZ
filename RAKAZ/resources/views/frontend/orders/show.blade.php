@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .order-success-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .success-header {
        background: white;
        padding: 40px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 30px;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .success-icon svg {
        width: 40px;
        height: 40px;
        color: white;
    }

    .success-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        margin-bottom: 10px;
        color: #333;
    }

    .success-message {
        color: #666;
        font-size: 16px;
        margin-bottom: 20px;
    }

    .order-number {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        background: #f8f8f8;
        padding: 15px;
        border-radius: 4px;
        display: inline-block;
    }

    .order-details {
        background: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 2px solid #e5e5e5;
        padding-bottom: 10px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-label {
        font-size: 13px;
        color: #999;
        font-weight: 500;
    }

    .info-value {
        font-size: 15px;
        color: #333;
    }

    .order-items {
        margin-bottom: 30px;
    }

    .order-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
    }

    .item-specs {
        font-size: 13px;
        color: #666;
        margin-bottom: 5px;
    }

    .item-price {
        font-size: 15px;
        font-weight: 600;
        color: #333;
    }

    .order-summary {
        border-top: 2px solid #e5e5e5;
        padding-top: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 15px;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        padding: 20px 0;
        font-size: 20px;
        font-weight: 600;
        border-top: 2px solid #333;
        margin-top: 10px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background: #cce5ff;
        color: #004085;
    }

    .status-processing {
        background: #cce5ff;
        color: #004085;
    }

    .status-shipped {
        background: #d4edda;
        color: #155724;
    }

    .status-delivered {
        background: #28a745;
        color: white;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        flex: 1;
        padding: 15px;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #333;
        color: white;
    }

    .btn-primary:hover {
        background: #555;
    }

    .btn-secondary {
        background: white;
        color: #333;
        border: 2px solid #333;
    }

    .btn-secondary:hover {
        background: #f5f5f5;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .order-item {
            flex-direction: column;
        }

        .item-image {
            width: 100%;
            height: 200px;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endpush

<div class="order-success-container">
    <!-- Success Header -->
    <div class="success-header">
        <div class="success-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="success-title">تم استلام طلبك بنجاح!</h1>
        <p class="success-message">شكراً لك على طلبك. سنقوم بمعالجته والتواصل معك قريباً.</p>
        <div class="order-number">
            رقم الطلب: {{ $order->order_number }}
        </div>
    </div>

    <!-- Order Details -->
    <div class="order-details">
        <h2 class="section-title">معلومات الطلب</h2>

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">الاسم</span>
                <span class="info-value">{{ $order->customer_name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">البريد الإلكتروني</span>
                <span class="info-value">{{ $order->customer_email }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">رقم الهاتف</span>
                <span class="info-value">{{ $order->customer_phone }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">حالة الطلب</span>
                <span class="info-value">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $order->getStatusLabelAttribute() }}
                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">طريقة الدفع</span>
                <span class="info-value">{{ $order->payment_method == 'cash' ? 'الدفع عند الاستلام' : $order->payment_method }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">حالة الدفع</span>
                <span class="info-value">
                    <span class="status-badge status-{{ $order->payment_status }}">
                        {{ $order->getPaymentStatusLabelAttribute() }}
                    </span>
                </span>
            </div>
        </div>

        <h3 class="section-title">عنوان الشحن</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">العنوان</span>
                <span class="info-value">{{ $order->shipping_address }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">المدينة</span>
                <span class="info-value">{{ $order->shipping_city }}</span>
            </div>
            @if($order->shipping_state)
            <div class="info-item">
                <span class="info-label">المنطقة</span>
                <span class="info-value">{{ $order->shipping_state }}</span>
            </div>
            @endif
            @if($order->shipping_postal_code)
            <div class="info-item">
                <span class="info-label">الرمز البريدي</span>
                <span class="info-value">{{ $order->shipping_postal_code }}</span>
            </div>
            @endif
            <div class="info-item">
                <span class="info-label">الدولة</span>
                <span class="info-value">{{ $order->shipping_country }}</span>
            </div>
        </div>

        @if($order->notes)
        <h3 class="section-title">ملاحظات</h3>
        <p class="info-value">{{ $order->notes }}</p>
        @endif

        <h3 class="section-title">المنتجات</h3>
        <div class="order-items">
            @foreach($order->items as $item)
            <div class="order-item">
                <img src="{{ $item->product_image ? asset('storage/' . $item->product_image) : asset('assets/images/placeholder.jpg') }}"
                     alt="{{ $item->product_name }}"
                     class="item-image">
                <div class="item-details">
                    <div class="item-name">{{ $item->product_name }}</div>
                    <div class="item-specs">
                        @if($item->size)المقاس: {{ $item->size }}@endif
                        @if($item->shoe_size)@if($item->size) | @endif مقاس الحذاء: {{ $item->shoe_size }}@endif
                        @if($item->color)@if($item->size || $item->shoe_size) | @endif اللون: {{ $item->color }}@endif
                    </div>
                    <div class="item-specs">الكمية: {{ $item->quantity }} × {{ number_format($item->price, 2) }} د.إ</div>
                </div>
                <div class="item-price">{{ number_format($item->subtotal, 2) }} د.إ</div>
            </div>
            @endforeach
        </div>

        <div class="order-summary">
            <div class="summary-row">
                <span>المجموع الفرعي</span>
                <span>{{ number_format($order->subtotal, 2) }} د.إ</span>
            </div>
            <div class="summary-row">
                <span>الشحن</span>
                <span>{{ $order->shipping_cost > 0 ? number_format($order->shipping_cost, 2) . ' د.إ' : 'مجاني' }}</span>
            </div>
            <div class="summary-row">
                <span>الضريبة</span>
                <span>{{ number_format($order->tax, 2) }} د.إ</span>
            </div>
            @if($order->discount > 0)
            <div class="summary-row">
                <span>الخصم</span>
                <span>-{{ number_format($order->discount, 2) }} د.إ</span>
            </div>
            @endif
            <div class="summary-total">
                <span>المجموع الكلي</span>
                <span>{{ number_format($order->total, 2) }} د.إ</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('home') }}" class="btn btn-primary">العودة للرئيسية</a>
        @auth
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">طلباتي</a>
        @endauth
    </div>
</div>

@endsection
