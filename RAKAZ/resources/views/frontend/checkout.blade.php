@extends('layouts.app')

@push('styles')
  <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lora', serif;
            background: #f8f8f8;
            color: #333;
        }

        .checkout-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 40px;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }

        .checkout-logo {
            margin-bottom: 20px;
        }

        .checkout-logo img {
            height: 50px;
        }

        .checkout-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 400;
            color: #1a1a1a;
        }

        .checkout-steps {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 30px;
        }

        .checkout-step {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #999;
        }

        .checkout-step.active {
            color: #333;
            font-weight: 500;
        }

        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid #e5e5e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .checkout-step.active .step-number {
            background: #333;
            color: white;
            border-color: #333;
        }

        .checkout-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        .checkout-form {
            background: white;
            padding: 40px;
            border-radius: 8px;
        }

        .form-section {
            margin-bottom: 40px;
        }

        .form-section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e5e5;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            overflow: visible;
        }

        .form-grid.full {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            overflow: visible;
            position: relative;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        .form-label .required {
            color: #e74c3c;
        }

        .form-input {
            padding: 12px 15px;
            border: 2px solid #e5e5e5;
            border-radius: 4px;
            font-size: 14px;
            font-family: 'Lora', serif;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #333;
        }

        /* Custom Select Styles */
        .custom-select-wrapper {
            position: relative;
            width: 100%;
        }

        .custom-select-trigger {
            padding: 12px 15px;
            border: 2px solid #e5e5e5;
            border-radius: 4px;
            font-size: 14px;
            font-family: 'Lora', serif;
            background: white;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.3s ease;
        }

        .custom-select-trigger:hover,
        .custom-select-wrapper.active .custom-select-trigger {
            border-color: #333;
        }

        .custom-select-trigger .arrow {
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid #333;
            transition: transform 0.3s ease;
        }

        .custom-select-wrapper.active .custom-select-trigger .arrow {
            transform: rotate(180deg);
        }

        #countrySelect .custom-select-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #333;
            border-radius: 4px;
            margin-top: 5px;
            max-height: 250px;
            overflow-y: auto;
            z-index: 9999;
            display: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            opacity: 1 !important;
            visibility: visible !important;
            transform: none !important;
        }

        #countrySelect.active .custom-select-options {
            display: block !important;
            max-height: 250px !important;
        }

        .custom-select-option {
            padding: 12px 15px;
            cursor: pointer;
            transition: background 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .custom-select-option:hover {
            background: #f5f5f5;
        }

        .custom-select-option.selected {
            background: #333;
            color: white;
        }

        .custom-select-option .country-flag {
            font-size: 18px;
        }

        .custom-select-trigger .selected-flag {
            margin-left: 8px;
            font-size: 18px;
        }

        .form-textarea {
            padding: 12px 15px;
            border: 2px solid #e5e5e5;
            border-radius: 4px;
            font-size: 14px;
            font-family: 'Lora', serif;
            min-height: 100px;
            resize: vertical;
        }

        .shipping-methods {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .shipping-option {
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .shipping-option:hover {
            border-color: #333;
        }

        .shipping-option.selected {
            border-color: #333;
            background: #f8f8f8;
        }

        .shipping-option input[type="radio"] {
            display: none;
        }

        .shipping-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .shipping-name {
            font-weight: 600;
            font-size: 16px;
        }

        .shipping-price {
            font-weight: 600;
            color: #28a745;
        }

        .shipping-description {
            font-size: 13px;
            color: #666;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .payment-option {
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            padding: 20px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
        }

        .payment-option:hover {
            border-color: #333;
        }

        .payment-option.selected {
            border-color: #333;
            background: #f8f8f8;
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .payment-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .payment-name {
            font-weight: 500;
            font-size: 14px;
        }

        .payment-desc {
            font-size: 11px;
            color: #888;
            margin-top: 5px;
        }

        .order-summary {
            background: white;
            padding: 30px;
            border-radius: 8px;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .summary-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #333;
        }

        .summary-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #e5e5e5;
        }

        .summary-item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }

        .summary-item-details {
            flex: 1;
        }

        .summary-item-name {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .summary-item-specs {
            font-size: 11px;
            color: #999;
        }

        .summary-item-price {
            font-size: 14px;
            font-weight: 600;
        }

        .summary-totals {
            padding: 20px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: 20px 0;
            font-size: 18px;
            font-weight: 600;
            border-top: 2px solid #333;
            margin-top: 10px;
        }

        .place-order-btn {
            width: 100%;
            padding: 15px;
            background: #333;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 20px;
        }

        .place-order-btn:hover {
            background: #555;
        }

        .back-to-cart {
            width: 100%;
            padding: 15px;
            background: white;
            color: #333;
            border: 2px solid #333;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            text-align: center;
            display: block;
            text-decoration: none;
        }

        .back-to-cart:hover {
            background: #f5f5f5;
        }

        .secure-checkout {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
            color: #666;
        }

        .secure-checkout svg {
            width: 16px;
            height: 16px;
            vertical-align: middle;
            margin-left: 5px;
        }

        @media (max-width: 1024px) {
            .checkout-content {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
                order: -1;
            }
        }

        @media (max-width: 768px) {
            .checkout-steps {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .payment-methods {
                grid-template-columns: 1fr;
            }

            .checkout-form {
                padding: 20px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="checkout-container">
        <!-- Header -->
        <div class="checkout-header">
            <div class="checkout-logo">
                <a href="{{ route('home') }}">
                    <img src="/assets/images/rakazLogo.png" alt="{{ app()->getLocale() == 'ar' ? 'ركاز' : 'Rakaz' }}">
                </a>
            </div>
            <h1 class="checkout-title">{{ app()->getLocale() == 'ar' ? 'إتمام الطلب' : 'Checkout' }}</h1>
            <div class="checkout-steps">
                <div class="checkout-step active">
                    <span class="step-number">1</span>
                    <span>{{ app()->getLocale() == 'ar' ? 'معلومات الشحن' : 'Shipping Info' }}</span>
                </div>
                <div class="checkout-step">
                    <span class="step-number">2</span>
                    <span>{{ app()->getLocale() == 'ar' ? 'طريقة الدفع' : 'Payment Method' }}</span>
                </div>
                <div class="checkout-step">
                    <span class="step-number">3</span>
                    <span>{{ app()->getLocale() == 'ar' ? 'تأكيد الطلب' : 'Confirm Order' }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="checkout-content">
                <!-- Checkout Form -->
                <div class="checkout-form">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Contact Information -->
                    <div class="form-section">
                        <h2 class="form-section-title">{{ app()->getLocale() == 'ar' ? 'معلومات الاتصال' : 'Contact Information' }}</h2>
                        <div class="form-grid full">
                            <div class="form-group">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الاسم الكامل' : 'Full Name' }} <span class="required">*</span></label>
                                <input type="text" name="customer_name" class="form-input" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }} <span class="required">*</span></label>
                                <input type="email" name="customer_email" class="form-input" value="{{ old('customer_email', auth()->user()->email ?? '') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'رقم الهاتف' : 'Phone Number' }} <span class="required">*</span></label>
                                <input type="tel" name="customer_phone" class="form-input" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="form-section">
                        <h2 class="form-section-title">{{ app()->getLocale() == 'ar' ? 'عنوان الشحن' : 'Shipping Address' }}</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الدولة' : 'Country' }} <span class="required">*</span></label>
                                @php
                                    $userCountry = old('shipping_country', auth()->user()->country ?? '');
                                    $countries = [
                                        ['value' => 'AE', 'flag' => '🇦🇪', 'name_ar' => 'الإمارات العربية المتحدة', 'name_en' => 'United Arab Emirates'],
                                        ['value' => 'SA', 'flag' => '🇸🇦', 'name_ar' => 'المملكة العربية السعودية', 'name_en' => 'Saudi Arabia'],
                                        ['value' => 'KW', 'flag' => '🇰🇼', 'name_ar' => 'الكويت', 'name_en' => 'Kuwait'],
                                        ['value' => 'BH', 'flag' => '🇧🇭', 'name_ar' => 'البحرين', 'name_en' => 'Bahrain'],
                                        ['value' => 'QA', 'flag' => '🇶🇦', 'name_ar' => 'قطر', 'name_en' => 'Qatar'],
                                        ['value' => 'OM', 'flag' => '🇴🇲', 'name_ar' => 'عُمان', 'name_en' => 'Oman'],
                                    ];
                                    $selectedCountry = collect($countries)->firstWhere('value', $userCountry);
                                @endphp
                                <input type="hidden" name="shipping_country" id="shipping_country_input" value="{{ $userCountry }}">
                                <div class="custom-select-wrapper" id="countrySelect">
                                    <div class="custom-select-trigger">
                                        <span class="selected-text">
                                            @if($selectedCountry)
                                                <span class="selected-flag">{{ $selectedCountry['flag'] }}</span>
                                                {{ app()->getLocale() == 'ar' ? $selectedCountry['name_ar'] : $selectedCountry['name_en'] }}
                                            @else
                                                {{ app()->getLocale() == 'ar' ? 'اختر الدولة' : 'Select Country' }}
                                            @endif
                                        </span>
                                        <span class="arrow"></span>
                                    </div>
                                    <div class="custom-select-options">
                                        @foreach($countries as $country)
                                            <div class="custom-select-option {{ $userCountry == $country['value'] ? 'selected' : '' }}" data-value="{{ $country['value'] }}" data-flag="{{ $country['flag'] }}">
                                                <span class="country-flag">{{ $country['flag'] }}</span>
                                                <span>{{ app()->getLocale() == 'ar' ? $country['name_ar'] : $country['name_en'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'المدينة' : 'City' }} <span class="required">*</span></label>
                                <input type="text" name="shipping_city" class="form-input" value="{{ old('shipping_city', auth()->user()->city ?? '') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'المنطقة / الحي' : 'Area / District' }}</label>
                                <input type="text" name="shipping_state" class="form-input" value="{{ old('shipping_state', auth()->user()->state ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الرمز البريدي' : 'Postal Code' }}</label>
                                <input type="text" name="shipping_postal_code" class="form-input" value="{{ old('shipping_postal_code', auth()->user()->postal_code ?? '') }}">
                            </div>
                        </div>
                        <div class="form-grid full">
                            <div class="form-group">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'العنوان بالتفصيل' : 'Detailed Address' }} <span class="required">*</span></label>
                                <textarea name="shipping_address" class="form-textarea" placeholder="{{ app()->getLocale() == 'ar' ? 'الشارع، رقم المبنى، الطابق، رقم الشقة...' : 'Street, Building number, Floor, Apartment number...' }}" required>{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="form-section">
                        <h2 class="form-section-title">{{ app()->getLocale() == 'ar' ? 'طريقة الشحن' : 'Shipping Method' }}</h2>
                        <div class="shipping-methods">
                            <label class="shipping-option selected">
                                <input type="radio" name="shipping_method" value="standard" data-cost="0" checked>
                                <div class="shipping-header">
                                    <span class="shipping-name">{{ app()->getLocale() == 'ar' ? 'الشحن القياسي' : 'Standard Shipping' }}</span>
                                    <span class="shipping-price">{{ app()->getLocale() == 'ar' ? 'مجاني' : 'Free' }}</span>
                                </div>
                                <div class="shipping-description">{{ app()->getLocale() == 'ar' ? 'التسليم خلال 3-5 أيام عمل' : 'Delivery within 3-5 business days' }}</div>
                            </label>
                            <label class="shipping-option">
                                <input type="radio" name="shipping_method" value="express" data-cost="50">
                                <div class="shipping-header">
                                    <span class="shipping-name">{{ app()->getLocale() == 'ar' ? 'الشحن السريع' : 'Express Shipping' }}</span>
                                    <span class="shipping-price">{{ app()->getLocale() == 'ar' ? '50 د.إ' : '50 AED' }}</span>
                                </div>
                                <div class="shipping-description">{{ app()->getLocale() == 'ar' ? 'التسليم خلال يوم عمل واحد' : 'Delivery within 1 business day' }}</div>
                            </label>
                            <label class="shipping-option">
                                <input type="radio" name="shipping_method" value="same-day" data-cost="100">
                                <div class="shipping-header">
                                    <span class="shipping-name">{{ app()->getLocale() == 'ar' ? 'التوصيل في نفس اليوم' : 'Same Day Delivery' }}</span>
                                    <span class="shipping-price">{{ app()->getLocale() == 'ar' ? '100 د.إ' : '100 AED' }}</span>
                                </div>
                                <div class="shipping-description">{{ app()->getLocale() == 'ar' ? 'التسليم خلال ساعتين (دبي فقط)' : 'Delivery within 2 hours (Dubai only)' }}</div>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-section">
                        <h2 class="form-section-title">{{ app()->getLocale() == 'ar' ? 'طريقة الدفع' : 'Payment Method' }}</h2>
                        <div class="payment-methods">
                            <label class="payment-option selected">
                                <input type="radio" name="payment_method" value="myfatoorah" checked>
                                <div class="payment-icon">💳</div>
                                <div class="payment-name">{{ app()->getLocale() == 'ar' ? 'الدفع الإلكتروني' : 'Online Payment' }}</div>
                                <div class="payment-desc">{{ app()->getLocale() == 'ar' ? 'بطاقة ائتمان / Apple Pay / Google Pay' : 'Credit Card / Apple Pay / Google Pay' }}</div>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cash">
                                <div class="payment-icon">💵</div>
                                <div class="payment-name">{{ app()->getLocale() == 'ar' ? 'الدفع عند الاستلام' : 'Cash on Delivery' }}</div>
                                <div class="payment-desc">{{ app()->getLocale() == 'ar' ? 'ادفع نقداً عند التسليم' : 'Pay cash when delivered' }}</div>
                            </label>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="form-section">
                        <h2 class="form-section-title">{{ app()->getLocale() == 'ar' ? 'ملاحظات إضافية (اختياري)' : 'Additional Notes (Optional)' }}</h2>
                        <div class="form-grid full">
                            <div class="form-group">
                                <textarea name="notes" class="form-textarea" placeholder="{{ app()->getLocale() == 'ar' ? 'أضف أي ملاحظات خاصة بطلبك...' : 'Add any special notes for your order...' }}">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h3 class="summary-title">{{ app()->getLocale() == 'ar' ? 'ملخص الطلب' : 'Order Summary' }}</h3>

                <!-- Items -->
                @foreach($cartItems as $item)
                <div class="summary-item">
                    <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : asset('assets/images/placeholder.jpg') }}"
                         alt="{{ $item->product->getName() }}"
                         class="summary-item-image">
                    <div class="summary-item-details">
                        <div class="summary-item-name">{{ $item->product->getName() }}</div>
                        <div class="summary-item-specs">
                            @if($item->size){{ app()->getLocale() == 'ar' ? 'المقاس' : 'Size' }}: {{ $item->size }}@endif
                            @if($item->shoe_size)@if($item->size) | @endif {{ app()->getLocale() == 'ar' ? 'مقاس الحذاء' : 'Shoe Size' }}: {{ $item->shoe_size }}@endif
                            @if($item->color)@if($item->size || $item->shoe_size) | @endif {{ app()->getLocale() == 'ar' ? 'اللون' : 'Color' }}: {{ $item->color }}@endif
                            | {{ app()->getLocale() == 'ar' ? 'الكمية' : 'Qty' }}: {{ $item->quantity }}
                        </div>
                    </div>
                    <div class="summary-item-price">{{ number_format($item->subtotal, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</div>
                </div>
                @endforeach

                <!-- Totals -->
                <div class="summary-totals">
                    <div class="summary-row">
                        <span>{{ app()->getLocale() == 'ar' ? 'المجموع الفرعي' : 'Subtotal' }}</span>
                        <span id="subtotalDisplay">{{ number_format($cartTotal, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                    </div>
                    <div class="summary-row">
                        <span>{{ app()->getLocale() == 'ar' ? 'الشحن' : 'Shipping' }}</span>
                        <span id="shippingDisplay">{{ app()->getLocale() == 'ar' ? 'مجاني' : 'Free' }}</span>
                    </div>
                    <div class="summary-row">
                        <span>{{ app()->getLocale() == 'ar' ? 'الضريبة (' . $taxPercentage . '%)' : 'Tax (' . $taxPercentage . '%)' }}</span>
                        <span id="taxDisplay">{{ number_format($tax, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                    </div>
                    <div class="summary-total">
                        <span>{{ app()->getLocale() == 'ar' ? 'المجموع الكلي' : 'Total' }}</span>
                        <span id="totalDisplay">{{ number_format($total, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                    </div>
                </div>

                <button type="submit" class="place-order-btn" id="placeOrderBtn">{{ app()->getLocale() == 'ar' ? 'تأكيد وإتمام الطلب' : 'Place Order' }}</button>
                <a href="{{ route('cart.index') }}" class="back-to-cart">{{ app()->getLocale() == 'ar' ? 'العودة إلى السلة' : 'Back to Cart' }}</a>

                <div class="secure-checkout">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-9-5zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V7.3l7-3.11v8.8z"/>
                    </svg>
                    {{ app()->getLocale() == 'ar' ? 'عملية دفع آمنة ومشفرة' : 'Secure & Encrypted Checkout' }}
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection

@push('scripts')

    <script>
        const subtotal = {{ $cartTotal }};
        const taxRate = {{ $taxPercentage / 100 }};
        const taxPercentage = {{ $taxPercentage }};
        let shippingCost = 0;
        const isArabic = '{{ app()->getLocale() }}' === 'ar';
        const currency = isArabic ? 'د.إ' : 'AED';
        const freeText = isArabic ? 'مجاني' : 'Free';

        // Update totals display
        function updateTotals() {
            const tax = (subtotal + shippingCost) * taxRate;
            const total = subtotal + shippingCost + tax;

            document.getElementById('shippingDisplay').textContent = shippingCost > 0 ? shippingCost.toFixed(2) + ' ' + currency : freeText;
            document.getElementById('taxDisplay').textContent = tax.toFixed(2) + ' ' + currency;
            document.getElementById('totalDisplay').textContent = total.toFixed(2) + ' ' + currency;
        }

        // Custom Country Select
        const countrySelectWrapper = document.getElementById('countrySelect');
        console.log('countrySelectWrapper:', countrySelectWrapper);

        if (countrySelectWrapper) {
            const countryTrigger = countrySelectWrapper.querySelector('.custom-select-trigger');
            const countryOptionsContainer = countrySelectWrapper.querySelector('.custom-select-options');
            const countryOptions = countrySelectWrapper.querySelectorAll('.custom-select-option');
            const countryInput = document.getElementById('shipping_country_input');

            console.log('countryTrigger:', countryTrigger);
            console.log('countryOptions:', countryOptions.length);

            countryTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Trigger clicked!');
                countrySelectWrapper.classList.toggle('active');
                countryTrigger.classList.toggle('active');
                countryOptionsContainer.classList.toggle('active');
                console.log('Is active:', countrySelectWrapper.classList.contains('active'));
            });

            countryOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const value = this.getAttribute('data-value');
                    const flag = this.getAttribute('data-flag');
                    const text = this.querySelector('span:last-child').textContent;

                    // Update hidden input
                    countryInput.value = value;

                    // Update trigger text
                    countryTrigger.querySelector('.selected-text').innerHTML =
                        '<span class="selected-flag">' + flag + '</span> ' + text;

                    // Update selected state
                    countryOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');

                    // Close dropdown
                    countrySelectWrapper.classList.remove('active');
                    countryTrigger.classList.remove('active');
                    countryOptionsContainer.classList.remove('active');
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!countrySelectWrapper.contains(e.target)) {
                    countrySelectWrapper.classList.remove('active');
                    countryTrigger.classList.remove('active');
                    countryOptionsContainer.classList.remove('active');
                }
            });
        }

        // Shipping method selection
        document.querySelectorAll('.shipping-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.shipping-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');

                const radioInput = this.querySelector('input[type="radio"]');
                shippingCost = parseFloat(radioInput.getAttribute('data-cost')) || 0;
                updateTotals();
            });
        });

        // Payment method selection
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');

                // Update form action based on payment method
                updateFormAction();
            });
        });

        // Update form action based on selected payment method
        function updateFormAction() {
            const form = document.getElementById('checkoutForm');
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;

            if (selectedPayment === 'myfatoorah') {
                form.action = '{{ route('myfatoorah.pay') }}';
            } else {
                form.action = '{{ route('checkout.process') }}';
            }
        }

        // Form validation and submission
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate form
            const form = this;
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Get selected payment method
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
            const isOnlinePayment = selectedPayment === 'myfatoorah';

            // Build confirmation message based on payment method
            let confirmTitle, confirmHtml, confirmButton, loadingTitle;

            if (isOnlinePayment) {
                confirmTitle = isArabic ? 'تأكيد الدفع الإلكتروني' : 'Confirm Online Payment';
                confirmHtml = isArabic
                    ? 'سيتم توجيهك إلى بوابة الدفع الآمنة لإتمام عملية الشراء.<br><small>المجموع الكلي: ' + document.getElementById('totalDisplay').textContent + '</small>'
                    : 'You will be redirected to the secure payment gateway to complete your purchase.<br><small>Total: ' + document.getElementById('totalDisplay').textContent + '</small>';
                confirmButton = isArabic ? 'متابعة للدفع' : 'Proceed to Payment';
                loadingTitle = isArabic ? 'جاري التوجيه لبوابة الدفع...' : 'Redirecting to payment gateway...';
            } else {
                confirmTitle = isArabic ? 'تأكيد الطلب' : 'Confirm Order';
                confirmHtml = isArabic
                    ? 'هل أنت متأكد من رغبتك في إتمام الطلب؟<br><small>المجموع الكلي: ' + document.getElementById('totalDisplay').textContent + '</small>'
                    : 'Are you sure you want to place this order?<br><small>Total: ' + document.getElementById('totalDisplay').textContent + '</small>';
                confirmButton = isArabic ? 'نعم، أكمل الطلب' : 'Yes, Place Order';
                loadingTitle = isArabic ? 'جاري معالجة الطلب...' : 'Processing your order...';
            }

            // Show confirmation dialog
            Swal.fire({
                title: confirmTitle,
                html: confirmHtml,
                icon: isOnlinePayment ? 'info' : 'question',
                showCancelButton: true,
                confirmButtonText: confirmButton,
                cancelButtonText: isArabic ? 'المراجعة' : 'Review',
                confirmButtonColor: '#000',
                cancelButtonColor: '#666',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: loadingTitle,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Check if we're in Capacitor app and using online payment
                    const isCapacitor = document.body.classList.contains('capacitor-app');

                    if (isCapacitor && isOnlinePayment) {
                        // For Capacitor + online payment, use AJAX to get payment URL and open in new window
                        const formData = new FormData(form);

                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Open payment URL in new window (system browser in Capacitor)
                                window.open(data.payment_url, '_blank');
                                // Close loading and show success message
                                Swal.close();
                                Swal.fire({
                                    title: isArabic ? 'تم إنشاء الطلب' : 'Order Created',
                                    html: isArabic
                                        ? 'تم إنشاء طلبك بنجاح. يرجى إتمام الدفع في النافذة الجديدة.<br><small>رقم الطلب: ' + data.order_number + '</small>'
                                        : 'Your order has been created successfully. Please complete payment in the new window.<br><small>Order Number: ' + data.order_number + '</small>',
                                    icon: 'success',
                                    confirmButtonText: isArabic ? 'موافق' : 'OK'
                                }).then(() => {
                                    // Redirect to order details or home
                                    window.location.href = '{{ route('orders.index') }}';
                                });
                            } else {
                                throw new Error(data.message || 'Payment initiation failed');
                            }
                        })
                        .catch(error => {
                            console.error('Payment error:', error);
                            Swal.close();
                            Swal.fire({
                                title: isArabic ? 'خطأ في الدفع' : 'Payment Error',
                                text: error.message || (isArabic ? 'حدث خطأ أثناء بدء عملية الدفع' : 'An error occurred while initiating payment'),
                                icon: 'error',
                                confirmButtonText: isArabic ? 'موافق' : 'OK'
                            });
                        });
                    } else {
                        // Normal form submission for regular browser or cash payment
                        form.submit();
                    }
                }
            });
        });

        // Initialize totals
        updateTotals();

        // Initialize form action based on default payment method
        updateFormAction();
    </script>

@endpush
