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
            <p class="cart-count">لديك منتجان في سلة التسوق</p>
            <div class="wishlist-actions">
                <button class="wishlist-action-btn" onclick="window.location.href='wishlist.html'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    المفضلة
                </button>
                <button class="wishlist-action-btn" onclick="window.location.href='orders.html'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    طلباتي
                </button>
                <button class="wishlist-action-btn" onclick="window.location.href='cart.html'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    السلة
                </button>
            </div>
        </div>

        <div class="cart-content">
            <!-- Cart Items -->
            <div class="cart-items">
                <!-- Item 1 -->
                <div class="cart-item">
                    <img src="/assets/images/New folder/Emirati_Gold_Edition_White.jpg" alt="كندورة إماراتية بيضاء" class="cart-item-image">
                    <div class="cart-item-details">
                        <div class="cart-item-brand">ركاز</div>
                        <div class="cart-item-name">كندورة إماراتية كلاسيكية بيضاء</div>
                        <div class="cart-item-specs">
                            <span class="cart-item-spec">المقاس: L</span>
                            <span class="cart-item-spec">اللون: أبيض</span>
                            <span class="cart-item-spec">القماش: قطن سويسري</span>
                        </div>
                        <div class="cart-item-price">775 د.إ</div>
                    </div>
                    <div class="cart-item-actions">
                        <button class="remove-item-btn" title="إزالة من السلة">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                            </svg>
                        </button>
                        <div class="quantity-selector">
                            <button class="quantity-btn">-</button>
                            <span class="quantity-value">1</span>
                            <button class="quantity-btn">+</button>
                        </div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="cart-item">
                    <img src="/assets/images/New folder/Kuwaiti_blue_image_3_treated.jpg" alt="كندورة كويتية زرقاء" class="cart-item-image">
                    <div class="cart-item-details">
                        <div class="cart-item-brand">ركاز</div>
                        <div class="cart-item-name">كندورة كويتية فاخرة زرقاء</div>
                        <div class="cart-item-specs">
                            <span class="cart-item-spec">المقاس: M</span>
                            <span class="cart-item-spec">اللون: أزرق</span>
                            <span class="cart-item-spec">القماش: قطن مصري</span>
                        </div>
                        <div class="cart-item-price">850 د.إ</div>
                    </div>
                    <div class="cart-item-actions">
                        <button class="remove-item-btn" title="إزالة من السلة">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                            </svg>
                        </button>
                        <div class="quantity-selector">
                            <button class="quantity-btn">-</button>
                            <span class="quantity-value">1</span>
                            <button class="quantity-btn">+</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 class="summary-title">ملخص الطلب</h3>
                <div class="summary-row">
                    <span>المجموع الفرعي</span>
                    <span>1,625 د.إ</span>
                </div>
                <div class="summary-row">
                    <span>الشحن</span>
                    <span>مجاني</span>
                </div>
                <div class="summary-row">
                    <span>الضريبة (5%)</span>
                    <span>81.25 د.إ</span>
                </div>
                <div class="summary-total">
                    <span>المجموع الكلي</span>
                    <span>1,706.25 د.إ</span>
                </div>
                <button class="checkout-btn" onclick="window.location.href='checkout.html'">إتمام الطلب</button>
                <a href="{{ route('home') }}" class="continue-shopping">متابعة التسوق</a>

                <div class="promo-code">
                    <div class="promo-code-title">هل لديك كود خصم؟</div>
                    <div class="promo-code-input">
                        <input type="text" placeholder="أدخل الكود">
                        <button>تطبيق</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

