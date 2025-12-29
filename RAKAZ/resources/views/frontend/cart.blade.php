@extends('layouts.app')

@section('content')
@push('styles')
    <style>
        /* Cart Page Specific Styles */
        .cart-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 40px;
        }

        .cart-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .cart-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 400;
            margin-bottom: 15px;
            color: #1a1a1a;
        }

        .cart-count {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        .wishlist-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .wishlist-action-btn {
            flex: 1;
            min-width: 180px;
            max-width: 220px;
            padding: 12px 20px;
            border: 2px solid #333;
            border-radius: 4px;
            background: white;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .wishlist-action-btn:hover {
            background: #f5f5f5;
            border-color: #555;
        }

        .wishlist-action-btn svg {
            width: 18px;
            height: 18px;
        }

        /* Cart Content */
        .cart-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
            margin-top: 50px;
        }

        /* Cart Items */
        .cart-items {
            background: white;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 20px;
            padding: 25px;
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }

        .cart-item:hover {
            border-color: #333;
        }

        .cart-item-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 4px;
            background: #f5f5f5;
        }

        .cart-item-details {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .cart-item-brand {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cart-item-name {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }

        .cart-item-specs {
            font-size: 14px;
            color: #666;
        }

        .cart-item-spec {
            display: inline-block;
            margin-left: 15px;
        }

        .cart-item-price {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-top: auto;
        }

        .cart-item-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 20px;
        }

        .remove-item-btn {
            background: transparent;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
        }

        .remove-item-btn:hover {
            color: #e74c3c;
        }

        .remove-item-btn svg {
            width: 20px;
            height: 20px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 2px solid #e5e5e5;
            border-radius: 4px;
            padding: 5px;
        }

        .quantity-btn {
            background: transparent;
            border: none;
            color: #333;
            cursor: pointer;
            padding: 5px 10px;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .quantity-btn:hover {
            background: #f5f5f5;
        }

        .quantity-value {
            min-width: 30px;
            text-align: center;
            font-weight: 500;
        }

        /* Cart Summary */
        .cart-summary {
            background: #f8f8f8;
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            padding: 30px;
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

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 15px;
            color: #666;
            border-bottom: 1px solid #e5e5e5;
        }

        .summary-row:last-of-type {
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: 20px 0;
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .checkout-btn {
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
            margin-bottom: 15px;
        }

        .checkout-btn:hover {
            background: #555;
        }

        .continue-shopping {
            width: 100%;
            padding: 15px;
            background: white;
            color: #333;
            border: 2px solid #333;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .continue-shopping:hover {
            background: #f5f5f5;
        }

        .promo-code {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e5e5e5;
        }

        .promo-code-title {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
            color: #333;
        }

        .promo-code-input {
            display: flex;
            gap: 10px;
        }

        .promo-code-input input {
            flex: 1;
            padding: 10px;
            border: 2px solid #e5e5e5;
            border-radius: 4px;
            font-size: 14px;
        }

        .promo-code-input button {
            padding: 10px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .promo-code-input button:hover {
            background: #555;
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-cart-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            opacity: 0.3;
        }

        .empty-cart-title {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 10px;
        }

        .empty-cart-text {
            font-size: 15px;
            color: #999;
            margin-bottom: 25px;
        }

        .empty-cart-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .empty-cart-btn:hover {
            background: #555;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .cart-content {
                grid-template-columns: 1fr;
            }

            .cart-summary {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .cart-container {
                padding: 30px 15px;
            }

            .cart-title {
                font-size: 28px;
            }

            .cart-header {
                margin-bottom: 30px;
            }

            .wishlist-actions {
                gap: 10px;
            }

            .wishlist-action-btn {
                min-width: 100px;
                max-width: none;
                flex: 1;
                padding: 10px 12px;
                font-size: 12px;
            }

            .cart-content {
                gap: 25px;
                margin-top: 30px;
            }

            .cart-item {
                grid-template-columns: 90px 1fr;
                gap: 12px;
                padding: 15px;
                margin-bottom: 15px;
            }

            .cart-item-image {
                width: 90px;
                height: 90px;
            }

            .cart-item-brand {
                font-size: 11px;
            }

            .cart-item-name {
                font-size: 14px;
                line-height: 1.3;
            }

            .cart-item-specs {
                font-size: 12px;
            }

            .cart-item-spec {
                display: block;
                margin-left: 0;
                margin-bottom: 3px;
            }

            .cart-item-price {
                font-size: 16px;
            }

            .cart-item-actions {
                grid-column: 1 / -1;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px solid #eee;
            }

            .remove-item-btn {
                order: 2;
            }

            .quantity-selector {
                order: 1;
            }

            /* Summary Box Mobile */
            .cart-summary {
                padding: 20px;
            }

            .summary-title {
                font-size: 18px;
            }

            .summary-row {
                font-size: 14px;
            }

            .summary-total {
                font-size: 16px;
            }

            .checkout-btn {
                padding: 14px 20px;
                font-size: 15px;
            }

            .continue-shopping-btn {
                padding: 12px 20px;
                font-size: 14px;
            }

            .promo-code-input input {
                font-size: 14px;
            }

            .promo-code-input button {
                font-size: 13px;
                padding: 10px 15px;
            }
        }

        @media (max-width: 480px) {
            .cart-container {
                padding: 20px 12px;
            }

            .cart-title {
                font-size: 24px;
            }

            .wishlist-action-btn {
                font-size: 11px;
                padding: 8px 10px;
            }

            .wishlist-action-btn svg {
                width: 14px;
                height: 14px;
            }

            .cart-item {
                grid-template-columns: 75px 1fr;
                gap: 10px;
                padding: 12px;
            }

            .cart-item-image {
                width: 75px;
                height: 75px;
            }

            .cart-item-name {
                font-size: 13px;
            }

            .cart-item-price {
                font-size: 14px;
            }

            .quantity-btn {
                width: 28px;
                height: 28px;
                font-size: 14px;
            }

            .quantity-value {
                min-width: 30px;
                font-size: 13px;
            }

            .cart-summary {
                padding: 15px;
            }
        }
    </style>
@endpush

    <!-- Cart Content -->
    <div class="cart-container">
        <div class="cart-header">
            @php
                $isAr = app()->getLocale() == 'ar';
                $currencyLabel = $isAr ? 'د.إ' : 'AED';
                $sanitizeText = function ($value) use ($isAr) {
                    if ($isAr) {
                        return $value;
                    }
                    if (!is_string($value)) {
                        return $value;
                    }

                    return preg_match('/[\x{0600}-\x{06FF}]/u', $value) ? '' : $value;
                };
                $cartCount = $cartItems ? count($cartItems) : 0;
                $cartSubtitle = $cartCount > 0
                    ? (
                        $isAr
                            ? ('لديك ' . $cartCount . ' ' . ($cartCount === 1 ? 'منتج' : 'منتجات') . ' في سلة التسوق')
                            : ('You have ' . $cartCount . ' ' . ($cartCount === 1 ? 'item' : 'items') . ' in your cart')
                    )
                    : ($isAr ? 'السلة فارغة' : 'Your cart is empty');
            @endphp

            <x-account-nav-header
                :title="($isAr ? 'سلة التسوق' : 'Shopping Cart') . ' (' . $cartCount . ')'"
                :subtitle="$cartSubtitle"
            />
        </div>

        @if($cartItems && count($cartItems) > 0)
        <div class="cart-content">
            <!-- Cart Items -->
            <div class="cart-items">
                @foreach($cartItems as $item)
                <div class="cart-item" data-cart-id="{{ $item->id }}">
                    @php
                        $product = $item->product;
                        $mainImage = null;

                        if ($product->main_image) {
                            $mainImage = asset('storage/' . $product->main_image);
                        } elseif ($product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0) {
                            $mainImage = asset('storage/' . $product->gallery_images[0]);
                        } else {
                            $mainImage = asset('assets/images/placeholder.jpg');
                        }

                        // Get product name (handle if it's array or string)
                        $productName = '';
                        if (is_array($product->name)) {
                            $productName = $isAr
                                ? ($product->name['ar'] ?? $product->name['en'] ?? 'منتج')
                                : ($product->name['en'] ?? $product->name['ar'] ?? 'Product');
                        } elseif (is_string($product->name)) {
                            $productName = $product->name;
                        } else {
                            $productName = $isAr ? 'منتج' : 'Product';
                        }

                        // Fallback for Arabic text in English mode
                        if (!$isAr && is_string($productName) && preg_match('/[\x{0600}-\x{06FF}]/u', $productName)) {
                            $productName = $isAr ? 'منتج' : 'Product';
                        }

                        // Get brand name properly (handle Brand model object)
                        $brandName = $isAr ? 'ركاز' : 'Rakaz';
                        if ($product->brand) {
                            if (is_object($product->brand) && isset($product->brand->name)) {
                                // Brand is a model object
                                $brandName = is_array($product->brand->name)
                                    ? ($isAr ? ($product->brand->name['ar'] ?? $product->brand->name['en'] ?? $brandName) : ($product->brand->name['en'] ?? $product->brand->name['ar'] ?? $brandName))
                                    : $product->brand->name;
                            } elseif (is_string($product->brand)) {
                                $brandName = $product->brand;
                            } elseif (is_array($product->brand) && isset($product->brand['name'])) {
                                $brandName = is_array($product->brand['name'])
                                    ? ($isAr ? ($product->brand['name']['ar'] ?? $product->brand['name']['en'] ?? $brandName) : ($product->brand['name']['en'] ?? $product->brand['name']['ar'] ?? $brandName))
                                    : $product->brand['name'];
                            }
                        }
                    @endphp

                    <img src="{{ $mainImage }}" alt="{{ $productName }}" class="cart-item-image">
                    <div class="cart-item-details">
                        <div class="cart-item-brand">{{ $brandName }}</div>
                        <div class="cart-item-name">{{ $productName }}</div>
                        <div class="cart-item-specs">
                            @if($item->size)
                                <span class="cart-item-spec">{{ $isAr ? 'المقاس:' : 'Size:' }} {{ $item->size }}</span>
                            @endif
                            @if($item->shoe_size)
                                <span class="cart-item-spec">{{ $isAr ? 'مقاس الحذاء:' : 'Shoe size:' }} {{ $item->shoe_size }}</span>
                            @endif
                            @if($item->color)
                                @php $colorText = $sanitizeText($item->color); @endphp
                                @if($colorText !== '')
                                    <span class="cart-item-spec">{{ $isAr ? 'اللون:' : 'Color:' }} {{ $colorText }}</span>
                                @endif
                            @endif
                            @if($product->fabric)
                                @php $fabricText = $sanitizeText($product->fabric); @endphp
                                @if($fabricText !== '')
                                    <span class="cart-item-spec">{{ $isAr ? 'القماش:' : 'Fabric:' }} {{ $fabricText }}</span>
                                @endif
                            @endif
                        </div>
                        <div class="cart-item-price">{{ number_format($item->price, 0) }} {{ $currencyLabel }}</div>
                    </div>
                    <div class="cart-item-actions">
                        <button class="remove-item-btn" data-cart-id="{{ $item->id }}" title="{{ $isAr ? 'إزالة من السلة' : 'Remove from cart' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                            </svg>
                        </button>
                        <div class="quantity-selector">
                            <button class="quantity-btn quantity-decrease" data-cart-id="{{ $item->id }}">-</button>
                            <span class="quantity-value">{{ $item->quantity }}</span>
                            <button class="quantity-btn quantity-increase" data-cart-id="{{ $item->id }}">+</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 class="summary-title">{{ $isAr ? 'ملخص الطلب' : 'Order Summary' }}</h3>
                <div class="summary-row">
                    <span>{{ $isAr ? 'المجموع الفرعي' : 'Subtotal' }}</span>
                    <span id="subtotal">{{ number_format($cartTotal, 2) }} {{ $currencyLabel }}</span>
                </div>
                <div class="summary-row">
                    <span>{{ $isAr ? 'الشحن' : 'Shipping' }}</span>
                    <span>{{ $isAr ? 'مجاني' : 'Free' }}</span>
                </div>
                <div class="summary-row">
                    <span>{{ $isAr ? 'الضريبة (5%)' : 'Tax (5%)' }}</span>
                    <span id="tax">{{ number_format($cartTotal * 0.05, 2) }} {{ $currencyLabel }}</span>
                </div>
                <div class="summary-total">
                    <span>{{ $isAr ? 'المجموع الكلي' : 'Total' }}</span>
                    <span id="total">{{ number_format($cartTotal * 1.05, 2) }} {{ $currencyLabel }}</span>
                </div>
                <button class="checkout-btn" onclick="window.location.href='{{ route('checkout.index') }}'">{{ $isAr ? 'إتمام الطلب' : 'Checkout' }}</button>
                <a href="{{ route('home') }}" class="continue-shopping">{{ $isAr ? 'متابعة التسوق' : 'Continue shopping' }}</a>

                <div class="promo-code">
                    <div class="promo-code-title">{{ $isAr ? 'هل لديك كود خصم؟' : 'Have a promo code?' }}</div>
                    <div class="promo-code-input">
                        <input type="text" placeholder="{{ $isAr ? 'أدخل الكود' : 'Enter code' }}" id="promoCodeInput">
                        <button onclick="applyPromoCode()">{{ $isAr ? 'تطبيق' : 'Apply' }}</button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div style="text-align: center; padding: 80px 20px;">
            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1" style="margin: 0 auto 30px;">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <h2 style="font-size: 28px; color: #333; margin-bottom: 15px;">{{ $isAr ? 'سلة التسوق فارغة' : 'Your cart is empty' }}</h2>
            <p style="color: #666; margin-bottom: 30px;">{{ $isAr ? 'لم تقم بإضافة أي منتجات إلى سلة التسوق بعد' : "You haven't added any items to your cart yet." }}</p>
            <a href="{{ route('shop') }}" class="checkout-btn" style="display: inline-block; width: auto; min-width: 200px; text-decoration: none;">{{ $isAr ? 'تصفح المنتجات' : 'Browse products' }}</a>
        </div>
        @endif
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isArabic = @json(app()->getLocale() == 'ar');
    const currencyLabel = @json($currencyLabel);
    const __t = (ar, en) => (isArabic ? ar : en);

    // Remove item from cart
    document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const cartId = this.dataset.cartId;

            const result = await Swal.fire({
                title: __t('هل أنت متأكد؟', 'Are you sure?'),
                text: __t('سيتم إزالة المنتج من سلة التسوق', 'This item will be removed from your cart.'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#333',
                cancelButtonColor: '#d33',
                confirmButtonText: __t('نعم، احذف', 'Yes, remove'),
                cancelButtonText: __t('إلغاء', 'Cancel')
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/cart/${cartId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: __t('تم الحذف!', 'Removed!'),
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Remove item from DOM
                        const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
                        cartItem.style.opacity = '0';
                        setTimeout(() => {
                            cartItem.remove();
                            updateCartSummary(data.cartTotal);
                            updateCartCount();

                            // Reload if no items left
                            if (document.querySelectorAll('.cart-item').length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: __t('خطأ', 'Error'),
                        text: __t('حدث خطأ أثناء حذف المنتج', 'An error occurred while removing the item.')
                    });
                }
            }
        });
    });

    // Increase quantity
    document.querySelectorAll('.quantity-increase').forEach(button => {
        button.addEventListener('click', async function() {
            const cartId = this.dataset.cartId;
            const quantitySpan = this.previousElementSibling;
            const currentQuantity = parseInt(quantitySpan.textContent);

            await updateQuantity(cartId, currentQuantity + 1, quantitySpan);
        });
    });

    // Decrease quantity
    document.querySelectorAll('.quantity-decrease').forEach(button => {
        button.addEventListener('click', async function() {
            const cartId = this.dataset.cartId;
            const quantitySpan = this.nextElementSibling;
            const currentQuantity = parseInt(quantitySpan.textContent);

            if (currentQuantity > 1) {
                await updateQuantity(cartId, currentQuantity - 1, quantitySpan);
            } else {
                // If quantity is 1, trigger remove
                document.querySelector(`.remove-item-btn[data-cart-id="${cartId}"]`).click();
            }
        });
    });

    async function updateQuantity(cartId, newQuantity, quantitySpan) {
        try {
            const response = await fetch(`/cart/${cartId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: newQuantity })
            });

            const data = await response.json();

            if (data.success) {
                quantitySpan.textContent = newQuantity;
                updateCartSummary(data.cartTotal);
                updateCartCount();
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: __t('خطأ', 'Error'),
                text: __t('حدث خطأ أثناء تحديث الكمية', 'An error occurred while updating quantity.')
            });
        }
    }

    function updateCartSummary(total) {
        const subtotal = parseFloat(total);
        const tax = subtotal * 0.05;
        const grandTotal = subtotal + tax;

        document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' ' + currencyLabel;
        document.getElementById('tax').textContent = tax.toFixed(2) + ' ' + currencyLabel;
        document.getElementById('total').textContent = grandTotal.toFixed(2) + ' ' + currencyLabel;
    }

    function updateCartCount() {
        if (typeof window.updateCartCount === 'function') {
            window.updateCartCount();
        }

        // Also update sidebar if exists
        if (window.cartSidebarInstance && typeof window.cartSidebarInstance.loadCartFromServer === 'function') {
            window.cartSidebarInstance.loadCartFromServer();
        }
    }
});

function applyPromoCode() {
    const code = document.getElementById('promoCodeInput').value.trim();

    if (!code) {
        Swal.fire({
            icon: 'warning',
            title: __t('تنبيه', 'Notice'),
            text: __t('الرجاء إدخال كود الخصم', 'Please enter a promo code.')
        });
        return;
    }

    Swal.fire({
        icon: 'info',
        title: __t('قريباً', 'Coming soon'),
        text: __t('ميزة رموز الخصم ستكون متاحة قريباً', 'Promo codes will be available soon.')
    });
}
</script>
@endpush
@endsection

