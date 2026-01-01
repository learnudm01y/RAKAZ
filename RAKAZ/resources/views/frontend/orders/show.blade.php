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
        <h1 class="success-title">
            @if(app()->getLocale() == 'ar')
                تم استلام طلبك بنجاح!
            @else
                Your Order Has Been Received!
            @endif
        </h1>
        <p class="success-message">
            @if(app()->getLocale() == 'ar')
                شكراً لك على طلبك. سنقوم بمعالجته والتواصل معك قريباً.
            @else
                Thank you for your order. We will process it and contact you soon.
            @endif
        </p>
        <div class="order-number">
            @if(app()->getLocale() == 'ar')
                رقم الطلب: {{ $order->order_number }}
            @else
                Order Number: {{ $order->order_number }}
            @endif
        </div>
    </div>

    <!-- Order Details -->
    <div class="order-details">
        <h2 class="section-title">{{ app()->getLocale() == 'ar' ? 'معلومات الطلب' : 'Order Information' }}</h2>

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">{{ app()->getLocale() == 'ar' ? 'الاسم' : 'Name' }}</span>
                <span class="info-value">{{ $order->customer_name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }}</span>
                <span class="info-value">{{ $order->customer_email }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ app()->getLocale() == 'ar' ? 'رقم الهاتف' : 'Phone Number' }}</span>
                <span class="info-value">{{ $order->customer_phone }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ app()->getLocale() == 'ar' ? 'حالة الطلب' : 'Order Status' }}</span>
                <span class="info-value">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $order->getStatusLabelAttribute() }}
                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ app()->getLocale() == 'ar' ? 'طريقة الدفع' : 'Payment Method' }}</span>
                <span class="info-value">
                    @if($order->payment_method == 'cash')
                        {{ app()->getLocale() == 'ar' ? 'الدفع عند الاستلام' : 'Cash on Delivery' }}
                    @else
                        {{ $order->payment_method }}
                    @endif
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ app()->getLocale() == 'ar' ? 'حالة الدفع' : 'Payment Status' }}</span>
                <span class="info-value">
                    <span class="status-badge status-{{ $order->payment_status }}">
                        {{ $order->getPaymentStatusLabelAttribute() }}
                    </span>
                </span>
            </div>
        </div>

        <h3 class="section-title">{{ app()->getLocale() == 'ar' ? 'عنوان الشحن' : 'Shipping Address' }}</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">{{ app()->getLocale() == 'ar' ? 'العنوان' : 'Address' }}</span>
                <span class="info-value">{{ $order->shipping_address }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">{{ app()->getLocale() == 'ar' ? 'المدينة' : 'City' }}</span>
                <span class="info-value">{{ $order->shipping_city }}</span>
            </div>
            @if($order->shipping_state)
            <div class="info-item">
                <span class="info-label">{{ app()->getLocale() == 'ar' ? 'المنطقة' : 'State/Region' }}</span>
                <span class="info-value">{{ $order->shipping_state }}</span>
            </div>
            @endif
            @if($order->shipping_postal_code)
            <div class="info-item">
                <span class="info-label">{{ app()->getLocale() == 'ar' ? 'الرمز البريدي' : 'Postal Code' }}</span>
                <span class="info-value">{{ $order->shipping_postal_code }}</span>
            </div>
            @endif
            <div class="info-item">
                <span class="info-label">{{ app()->getLocale() == 'ar' ? 'الدولة' : 'Country' }}</span>
                <span class="info-value">{{ $order->shipping_country }}</span>
            </div>
        </div>

        @if($order->notes)
        <h3 class="section-title">{{ app()->getLocale() == 'ar' ? 'ملاحظات' : 'Notes' }}</h3>
        <p class="info-value">{{ $order->notes }}</p>
        @endif

        <h3 class="section-title">{{ app()->getLocale() == 'ar' ? 'المنتجات' : 'Products' }}</h3>
        <div class="order-items">
            @foreach($order->items as $item)
            <div class="order-item">
                <img src="{{ $item->product_image ? asset('storage/' . $item->product_image) : asset('assets/images/placeholder.jpg') }}"
                     alt="{{ $item->product_name }}"
                     class="item-image">
                <div class="item-details">
                    <div class="item-name">{{ $item->product_name }}</div>
                    <div class="item-specs">
                        @if($item->size){{ app()->getLocale() == 'ar' ? 'المقاس' : 'Size' }}: {{ $item->size }}@endif
                        @if($item->shoe_size)@if($item->size) | @endif {{ app()->getLocale() == 'ar' ? 'مقاس الحذاء' : 'Shoe Size' }}: {{ $item->shoe_size }}@endif
                        @if($item->color)@if($item->size || $item->shoe_size) | @endif {{ app()->getLocale() == 'ar' ? 'اللون' : 'Color' }}: {{ $item->color }}@endif
                    </div>
                    <div class="item-specs">{{ app()->getLocale() == 'ar' ? 'الكمية' : 'Qty' }}: {{ $item->quantity }} × {{ number_format($item->price, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</div>
                </div>
                <div class="item-price">{{ number_format($item->subtotal, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</div>
            </div>
            @endforeach
        </div>

        <div class="order-summary">
            <div class="summary-row">
                <span>{{ app()->getLocale() == 'ar' ? 'المجموع الفرعي' : 'Subtotal' }}</span>
                <span>{{ number_format($order->subtotal, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
            </div>
            <div class="summary-row">
                <span>{{ app()->getLocale() == 'ar' ? 'الشحن' : 'Shipping' }}</span>
                <span>
                    @if($order->shipping_cost > 0)
                        {{ number_format($order->shipping_cost, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}
                    @else
                        {{ app()->getLocale() == 'ar' ? 'مجاني' : 'Free' }}
                    @endif
                </span>
            </div>
            <div class="summary-row">
                <span>{{ app()->getLocale() == 'ar' ? 'الضريبة' : 'Tax' }}</span>
                <span>{{ number_format($order->tax, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
            </div>
            @if($order->discount > 0)
            <div class="summary-row">
                <span>{{ app()->getLocale() == 'ar' ? 'الخصم' : 'Discount' }}</span>
                <span>-{{ number_format($order->discount, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
            </div>
            @endif
            <div class="summary-total">
                <span>{{ app()->getLocale() == 'ar' ? 'المجموع الكلي' : 'Total' }}</span>
                <span>{{ number_format($order->total, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('home') }}" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}</a>
        @auth
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">{{ app()->getLocale() == 'ar' ? 'طلباتي' : 'My Orders' }}</a>
        @endauth
    </div>
</div>

@endsection
