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
                padding: 40px 20px;
            }

            .cart-title {
                font-size: 32px;
            }

            .cart-item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }

            .cart-item-image {
                width: 80px;
                height: 80px;
            }

            .cart-item-actions {
                grid-column: 1 / -1;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }
    </style>
@endpush

    <!-- Cart Content -->
    <div class="cart-container">
        <div class="cart-header">
            <h1 class="cart-title">سلة التسوق</h1>
            <p class="cart-count">
                @if($cartItems && count($cartItems) > 0)
                    لديك {{ count($cartItems) }} {{ count($cartItems) == 1 ? 'منتج' : 'منتجات' }} في سلة التسوق
                @else
                    السلة فارغة
                @endif
            </p>
            <div class="wishlist-actions">
                <a href="{{ route('wishlist') }}" class="wishlist-action-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    المفضلة
                </a>
                <a href="{{ route('orders.index') }}" class="wishlist-action-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    طلباتي
                </a>
                <a href="{{ route('cart.index') }}" class="wishlist-action-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    السلة
                </a>
            </div>
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
                        $productName = is_array($product->name)
                            ? ($product->name[app()->getLocale()] ?? $product->name['ar'] ?? $product->name['en'] ?? 'منتج')
                            : $product->name;
                    @endphp

                    <img src="{{ $mainImage }}" alt="{{ $productName }}" class="cart-item-image">
                    <div class="cart-item-details">
                        <div class="cart-item-brand">{{ $product->brand ?? 'ركاز' }}</div>
                        <div class="cart-item-name">{{ $productName }}</div>
                        <div class="cart-item-specs">
                            @if($item->size)
                                <span class="cart-item-spec">المقاس: {{ $item->size }}</span>
                            @endif
                            @if($item->shoe_size)
                                <span class="cart-item-spec">مقاس الحذاء: {{ $item->shoe_size }}</span>
                            @endif
                            @if($item->color)
                                <span class="cart-item-spec">اللون: {{ $item->color }}</span>
                            @endif
                            @if($product->fabric)
                                <span class="cart-item-spec">القماش: {{ $product->fabric }}</span>
                            @endif
                        </div>
                        <div class="cart-item-price">{{ number_format($item->price, 0) }} د.إ</div>
                    </div>
                    <div class="cart-item-actions">
                        <button class="remove-item-btn" data-cart-id="{{ $item->id }}" title="إزالة من السلة">
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
                <h3 class="summary-title">ملخص الطلب</h3>
                <div class="summary-row">
                    <span>المجموع الفرعي</span>
                    <span id="subtotal">{{ number_format($cartTotal, 2) }} د.إ</span>
                </div>
                <div class="summary-row">
                    <span>الشحن</span>
                    <span>مجاني</span>
                </div>
                <div class="summary-row">
                    <span>الضريبة (5%)</span>
                    <span id="tax">{{ number_format($cartTotal * 0.05, 2) }} د.إ</span>
                </div>
                <div class="summary-total">
                    <span>المجموع الكلي</span>
                    <span id="total">{{ number_format($cartTotal * 1.05, 2) }} د.إ</span>
                </div>
                <button class="checkout-btn" onclick="window.location.href='{{ route('checkout.index') }}'">إتمام الطلب</button>
                <a href="{{ route('home') }}" class="continue-shopping">متابعة التسوق</a>

                <div class="promo-code">
                    <div class="promo-code-title">هل لديك كود خصم؟</div>
                    <div class="promo-code-input">
                        <input type="text" placeholder="أدخل الكود" id="promoCodeInput">
                        <button onclick="applyPromoCode()">تطبيق</button>
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
            <h2 style="font-size: 28px; color: #333; margin-bottom: 15px;">سلة التسوق فارغة</h2>
            <p style="color: #666; margin-bottom: 30px;">لم تقم بإضافة أي منتجات إلى سلة التسوق بعد</p>
            <a href="{{ route('shop') }}" class="checkout-btn" style="display: inline-block; width: auto; min-width: 200px; text-decoration: none;">تصفح المنتجات</a>
        </div>
        @endif
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remove item from cart
    document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const cartId = this.dataset.cartId;

            const result = await Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم إزالة المنتج من سلة التسوق',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#333',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
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
                            title: 'تم الحذف!',
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
                        title: 'خطأ',
                        text: 'حدث خطأ أثناء حذف المنتج'
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
                title: 'خطأ',
                text: 'حدث خطأ أثناء تحديث الكمية'
            });
        }
    }

    function updateCartSummary(total) {
        const subtotal = parseFloat(total);
        const tax = subtotal * 0.05;
        const grandTotal = subtotal + tax;

        document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' د.إ';
        document.getElementById('tax').textContent = tax.toFixed(2) + ' د.إ';
        document.getElementById('total').textContent = grandTotal.toFixed(2) + ' د.إ';
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
            title: 'تنبيه',
            text: 'الرجاء إدخال كود الخصم'
        });
        return;
    }

    Swal.fire({
        icon: 'info',
        title: 'قريباً',
        text: 'ميزة رموز الخصم ستكون متاحة قريباً'
    });
}
</script>
@endpush
@endsection

