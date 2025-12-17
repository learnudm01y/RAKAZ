@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="/assets/css/shop-redesign.css">
    <link rel="stylesheet" href="/assets/css/product-details.css">
    <link rel="stylesheet" href="/assets/css/product-dynamic.css">
    <style>
        /* Product Modal Styles */
        .product-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9998;
        }

        /* Ensure SweetAlert appears above modal */
        .swal2-container {
            z-index: 10000 !important;
        }

        .product-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
        }

        .product-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 1200px;
            max-height: 90vh;
            background: white;
            border-radius: 12px;
            overflow-y: auto;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3);
        }

        .product-modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .product-modal-close:hover {
            background: #f5f5f5;
            transform: rotate(90deg);
        }

        .product-modal-close svg {
            width: 20px;
            height: 20px;
        }

        .modal-product-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 40px;
        }

        /* Gallery Styles */
        .modal-product-gallery {
            position: sticky;
            top: 20px;
        }

        .modal-main-image-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #f8f8f8;
        }

        .modal-main-product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-image-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .modal-image-nav:hover {
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .modal-image-nav.prev {
            left: 10px;
        }

        .modal-image-nav.next {
            right: 10px;
        }

        .modal-image-nav svg {
            width: 20px;
            height: 20px;
            stroke: #333;
        }

        .modal-image-nav:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .modal-thumbnails-wrapper {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 5px 0;
            scrollbar-width: thin;
            scrollbar-color: #ccc transparent;
        }

        .modal-thumbnails-wrapper::-webkit-scrollbar {
            height: 6px;
        }

        .modal-thumbnails-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .modal-thumbnails-wrapper::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .modal-thumbnails-wrapper::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .modal-thumbnail {
            width: 80px;
            height: 80px;
            min-width: 80px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .modal-thumbnail:hover,
        .modal-thumbnail.active {
            border-color: #1a1a1a;
        }

        /* Product Info Styles */
        .modal-product-info-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .modal-product-meta-top .modal-season-badge {
            display: inline-block;
            padding: 6px 12px;
            background: #1a1a1a;
            color: white;
            font-size: 12px;
            border-radius: 4px;
            font-weight: 500;
        }

        .modal-product-header {
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 15px;
        }

        .modal-product-title {
            font-size: 14px;
            color: #666;
            margin: 0 0 8px 0;
            font-weight: 500;
        }

        .modal-product-subtitle {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: #1a1a1a;
        }

        .modal-product-price-section {
            padding: 10px 0;
        }

        .modal-current-price {
            font-size: 28px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .modal-payment-options {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            padding: 15px 0;
            border-bottom: 1px solid #e5e5e5;
        }

        .modal-payment-option {
            display: flex;
            align-items: center;
        }

        .modal-payment-option .payment-icon {
            height: 20px;
        }

        .modal-payment-option .payment-text {
            font-size: 13px;
            color: #666;
        }

        .modal-delivery-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #f8f8f8;
            border-radius: 6px;
            font-size: 14px;
            color: #666;
        }

        .modal-delivery-info svg {
            width: 20px;
            height: 20px;
        }

        .modal-product-options {
            padding: 20px 0;
        }

        .modal-option-group {
            margin-bottom: 20px;
        }

        .modal-size-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .modal-option-label {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .modal-size-guide-link {
            font-size: 13px;
            color: #666;
            text-decoration: underline;
        }

        .modal-size-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .modal-size-btn {
            padding: 12px 20px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .modal-size-btn:hover {
            border-color: #1a1a1a;
        }

        .modal-size-btn.selected {
            background: #1a1a1a;
            color: white;
            border-color: #1a1a1a;
        }

        .modal-stock-notice {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 15px;
            background: #f0f7ff;
            border-radius: 6px;
            font-size: 13px;
            color: #333;
        }

        .modal-stock-notice svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .modal-product-actions {
            display: flex;
            gap: 15px;
            padding: 20px 0;
        }

        .modal-btn-add-to-bag {
            flex: 1;
            padding: 16px;
            background: #1a1a1a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .modal-btn-add-to-bag:hover {
            background: #333;
        }

        .modal-btn-add-to-bag svg {
            width: 20px;
            height: 20px;
        }

        .modal-btn-add-to-wishlist {
            width: 50px;
            height: 50px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .modal-btn-add-to-wishlist:hover {
            border-color: #1a1a1a;
            background: #f8f8f8;
        }

        .modal-btn-add-to-wishlist svg {
            width: 22px;
            height: 22px;
        }

        .modal-product-tabs {
            border-top: 1px solid #e5e5e5;
            padding-top: 20px;
        }

        .modal-tabs-header {
            display: flex;
            gap: 20px;
            border-bottom: 1px solid #e5e5e5;
            margin-bottom: 20px;
        }

        .modal-tab-btn {
            padding: 12px 0;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            font-size: 14px;
            font-weight: 500;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-tab-btn:hover {
            color: #1a1a1a;
        }

        .modal-tab-btn.active {
            color: #1a1a1a;
            border-bottom-color: #1a1a1a;
        }

        .modal-tab-panel {
            display: none;
            font-size: 14px;
            line-height: 1.8;
            color: #666;
        }

        .modal-tab-panel.active {
            display: block;
        }

        .modal-tab-panel ul {
            margin: 15px 0;
            padding-right: 20px;
        }

        .modal-tab-panel li {
            margin: 8px 0;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .modal-product-details {
                grid-template-columns: 1fr;
                padding: 20px;
                gap: 30px;
            }

            .modal-product-gallery {
                position: relative;
                top: 0;
            }

            .product-modal-content {
                width: 95%;
                max-height: 95vh;
            }

            .modal-product-subtitle {
                font-size: 20px;
            }

            .modal-current-price {
                font-size: 24px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Product Details Modal -->
    <div class="product-modal" id="productModal" style="display: none;">
        <div class="product-modal-overlay"></div>
        <div class="product-modal-content">
            <button class="product-modal-close" id="productModalClose">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <div class="modal-product-details">
                    <!-- Product Gallery -->
                    <div class="modal-product-gallery">
                        <div class="modal-main-image-wrapper">
                            <button class="modal-image-nav prev" id="modalPrevImage">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <img src="" alt="" class="modal-main-product-image" id="modalMainImage">
                            <button class="modal-image-nav next" id="modalNextImage">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-thumbnail-gallery">
                            <div class="modal-thumbnails-wrapper" id="modalThumbnails">
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="modal-product-info-section">
                        <!-- Season Badge -->
                        <div class="modal-product-meta-top">
                            <span class="modal-season-badge" id="modalSeasonBadge" style="display: none;">الموسم
                                الجديد</span>
                        </div>

                        <!-- Brand & Title -->
                        <div class="modal-product-header">
                            <h1 class="modal-product-title" id="modalBrand">ركاز</h1>
                            <h2 class="modal-product-subtitle" id="modalProductName"></h2>
                        </div>

                        <!-- Price -->
                        <div class="modal-product-price-section">
                            <span class="modal-current-price" id="modalPrice"></span>
                        </div>

                        <!-- Payment Options -->
                        <div class="modal-payment-options">
                            <div class="modal-payment-option">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal"
                                    class="payment-icon">
                            </div>
                            <div class="modal-payment-option">
                                <span class="payment-text">تمرا</span>
                            </div>
                            <div class="modal-payment-option">
                                <span class="payment-text">tabby</span>
                            </div>
                            <div class="modal-payment-option">
                                <span class="payment-text">تتوفر أقساط بدون فوائد</span>
                            </div>
                        </div>

                        <!-- Delivery Info -->
                        <div class="modal-delivery-info">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                            <span>توصيل خلال ساعتين في نفس اليوم إلى أبي ظبي</span>
                        </div>

                        <!-- Size Selection -->
                        <div class="modal-product-options">
                            <div class="modal-option-group">
                                <div class="modal-size-header">
                                    <label class="modal-option-label" style="font-size: 16px; font-weight: bold;">اختيار المقاس</label>
                                    <a href="#" class="modal-size-guide-link">جدول المقاسات</a>
                                </div>
                                <!-- Available Sizes Display -->
                                <div id="modalAvailableSizes" style="margin: 15px 0; padding: 15px; background: #f8f9fa; border-radius: 6px; border: 2px solid #28a745; display: none;">
                                    <div style="font-size: 14px; font-weight: 700; color: #28a745; margin-bottom: 10px;">
                                        ✅ <span id="modalSizesTitle">المقاسات المتوفرة:</span> <span id="modalSizesCount"></span>
                                    </div>
                                    <div id="modalSizesList" style="font-size: 16px; color: #212529; font-weight: 600; line-height: 1.8;"></div>
                                </div>
                                <!-- Size Dropdown -->
                                <select class="custom-select" id="modalSizeSelect" style="width: 100%; font-size: 16px; font-weight: 600;">
                                    <option value="">اختر المقاس</option>
                                </select>
                            </div>
                        </div>

                        <!-- Stock & Delivery Notice -->
                        <div class="modal-stock-notice">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>التحقق من الإمارات العربية المتحدة - التوصيل متوفر</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="modal-product-actions">
                            <button class="modal-btn-add-to-bag" id="modalAddToBag">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                                إضافة إلى حقيبة التسوق
                            </button>
                            <button class="modal-btn-add-to-wishlist">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                            </button>
                        </div>

                        <!-- Product Details Tabs -->
                        <div class="modal-product-tabs">
                            <div class="modal-tabs-header">
                                <button class="modal-tab-btn active" data-tab="modal-description">{{ app()->getLocale() == 'ar' ? 'وصف المنتج' : 'Description' }}</button>
                                <button class="modal-tab-btn" data-tab="modal-sizing" style="display: none;">{{ app()->getLocale() == 'ar' ? 'المقاسات والحجم' : 'Sizing Info' }}</button>
                                <button class="modal-tab-btn" data-tab="modal-design" style="display: none;">{{ app()->getLocale() == 'ar' ? 'تفاصيل التصميم' : 'Design Details' }}</button>
                                <button class="modal-tab-btn" data-tab="modal-shipping">{{ app()->getLocale() == 'ar' ? 'التوصيل والإرجاع' : 'Delivery & Returns' }}</button>
                            </div>
                            <div class="modal-tabs-content">
                                <div class="modal-tab-panel active" id="modal-description">
                                    <div id="modalDescription"></div>
                                </div>
                                <div class="modal-tab-panel" id="modal-sizing">
                                    <div id="modalSizingInfo"></div>
                                </div>
                                <div class="modal-tab-panel" id="modal-design">
                                    <div id="modalDesignDetails"></div>
                                </div>
                                <div class="modal-tab-panel" id="modal-shipping">
                                    <p>{{ app()->getLocale() == 'ar' ? 'نوفر شحن مجاني لجميع الطلبات. التوصيل خلال 2-3 أيام عمل.' : 'We offer free shipping on all orders. Delivery in 2-3 business days.' }}</p>
                                    <p>{{ app()->getLocale() == 'ar' ? 'الإرجاع مجاني خلال 14 يوم من تاريخ الاستلام.' : 'Free returns within 14 days from receipt date.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shop Page Content -->
        <main class="shop-page">
            <div class="shop-container">
                <!-- Mobile Filter Toggle -->
                <button class="mobile-filter-toggle" id="mobileFilterToggle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="4" y1="6" x2="20" y2="6"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="18" x2="20" y2="18"></line>
                    </svg>
                    <span>الفلاتر</span>
                </button>

                <!-- Mobile View Toggle (Only visible on mobile) -->
                <div class="mobile-view-toggle">
                    <button class="mobile-view-btn active" data-mobile-view="two">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="8" height="18"></rect>
                            <rect x="13" y="3" width="8" height="18"></rect>
                        </svg>
                        <span>منتجين</span>
                    </button>
                    <button class="mobile-view-btn" data-mobile-view="one">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18"></rect>
                        </svg>
                        <span>منتج واحد</span>
                    </button>
                </div>

                <!-- Sidebar Filters -->
                <aside class="shop-sidebar" id="shopSidebar">
                    <div class="sidebar-header">
                        <h2>الفلاتر</h2>
                        <button class="sidebar-close" id="sidebarClose">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- مقاس العلابيس (Clothing Size) -->
                    <div class="filter-section">
                        <h3 class="filter-title">مقاس العلابيس</h3>
                        <div class="size-grid">
                            @foreach($sizes as $size)
                            <label class="size-checkbox">
                                <input type="checkbox" name="size" value="{{ $size->name }}">
                                <span class="size-label">
                                    <span class="size-value">{{ $size->name }}</span>
                                    <span class="size-count">({{ $size->products_count ?? 0 }})</span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- مقاس الحذاء (Shoe Size) -->
                    <div class="filter-section">
                        <h3 class="filter-title">مقاس الحذاء</h3>
                        <div class="shoe-size-selector">
                            <select class="shoe-size-dropdown custom-select">
                                <option value="">EU</option>
                                @foreach($shoeSizes as $shoeSize)
                                <option value="{{ $shoeSize->size }}">{{ $shoeSize->size }} ({{ $shoeSize->products_count ?? 0 }})</option>
                                @endforeach
                            </select>
                            <div class="shoe-size-scroll">
                                @foreach($shoeSizes as $shoeSize)
                                <label class="shoe-size-checkbox">
                                    <input type="checkbox" name="shoe-size" value="{{ $shoeSize->size }}">
                                    <span class="shoe-label">
                                        <span class="shoe-count">({{ $shoeSize->products_count ?? 0 }})</span>
                                        <span class="shoe-value">{{ $shoeSize->size }}</span>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- اللون (Color) -->
                    <div class="filter-section">
                        <h3 class="filter-title">اللون</h3>
                        <div class="color-scroll">
                            @foreach($colors as $color)
                            <label class="color-checkbox">
                                <input type="checkbox" name="color" value="{{ strtolower(app()->getLocale() == 'ar' ? ($color->name['ar'] ?? '') : ($color->name['en'] ?? $color->name['ar'] ?? '')) }}">
                                <span class="color-label">
                                    <span class="color-circle" style="background-color: {{ $color->hex_code }};@if($color->hex_code == '#FFFFFF') border: 1px solid #ddd;@endif"></span>
                                    <span class="color-name">{{ app()->getLocale() == 'ar' ? ($color->name['ar'] ?? '') : ($color->name['en'] ?? $color->name['ar'] ?? '') }}</span>
                                    <span class="color-count">({{ $color->products_count ?? 0 }})</span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- السعر (Price) -->
                    <div class="filter-section">
                        <h3 class="filter-title">السعر</h3>
                        <div class="price-range-wrapper">
                            <div class="price-display">
                                <div class="price-box">
                                    <label>السعر الأدنى</label>
                                    <div class="price-input-wrapper">
                                        <input type="number" id="minPrice" value="{{ request('min_price', $minPrice) }}" min="{{ $minPrice }}"
                                            max="{{ $maxPrice }}">
                                        <span class="currency">AED</span>
                                    </div>
                                </div>
                                <div class="price-box">
                                    <label>السعر الأعلى</label>
                                    <div class="price-input-wrapper">
                                        <input type="number" id="maxPrice" value="{{ request('max_price', $maxPrice) }}" min="{{ $minPrice }}"
                                            max="{{ $maxPrice }}">
                                        <span class="currency">AED</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- استخدام (Apply Button) -->
                    <div class="filter-section">
                        <button class="apply-filters-btn">استخدام</button>
                    </div>
                </aside>

                <!-- Products Grid -->
                <section class="shop-content">
                    <div class="shop-header">
                        <div class="shop-results">
                            <h1>{{ isset($category) ? (app()->getLocale() == 'ar' ? $category->name['ar'] : $category->name['en']) : (app()->getLocale() == 'ar' ? 'جميع المنتجات' : 'All Products') }}</h1>
                            <p class="results-count">{{ $products->total() }} {{ app()->getLocale() == 'ar' ? 'منتج متاح' : 'Products Available' }}</p>
                        </div>
                        <div class="shop-controls">
                            <select class="sort-select custom-select">
                                <option value="featured">مميز</option>
                                <option value="price-low">السعر: من الأقل للأعلى</option>
                                <option value="price-high">السعر: من الأعلى للأقل</option>
                                <option value="newest">الأحدث</option>
                            </select>
                            <div class="view-controls">
                                <button class="view-btn" data-view="grid-2">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="3" width="7" height="7"></rect>
                                        <rect x="3" y="14" width="7" height="7"></rect>
                                        <rect x="14" y="14" width="7" height="7"></rect>
                                    </svg>
                                </button>
                                <button class="view-btn active" data-view="grid-3">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="5" height="5"></rect>
                                        <rect x="10" y="3" width="5" height="5"></rect>
                                        <rect x="17" y="3" width="5" height="5"></rect>
                                        <rect x="3" y="10" width="5" height="5"></rect>
                                        <rect x="10" y="10" width="5" height="5"></rect>
                                        <rect x="17" y="10" width="5" height="5"></rect>
                                    </svg>
                                </button>
                                <button class="view-btn" data-view="grid-4">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="4" height="4"></rect>
                                        <rect x="9" y="3" width="4" height="4"></rect>
                                        <rect x="15" y="3" width="4" height="4"></rect>
                                        <rect x="21" y="3" width="4" height="4"></rect>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="products-grid" data-view="grid-3">
                        @forelse($products as $product)
                        <div class="product-card">
                            <div class="product-image-wrapper" style="position: relative;">
                                <a href="{{ route('product.details', $product->getSlug()) }}" style="display: block;">
                                    <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('assets/images/placeholder.jpg') }}"
                                        alt="{{ $product->getName() }}" class="product-image-primary">
                                    @if($product->hover_image)
                                    <img src="{{ asset('storage/' . $product->hover_image) }}"
                                        alt="{{ $product->getName() }}" class="product-image-secondary">
                                    @else
                                    <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('assets/images/placeholder.jpg') }}"
                                        alt="{{ $product->getName() }}" class="product-image-secondary">
                                    @endif
                                </a>
                                <button class="wishlist-btn {{ auth()->check() && \App\Models\Wishlist::isInWishlist(auth()->id(), $product->id) ? 'active' : '' }}" data-product-id="{{ $product->id }}" onclick="event.stopPropagation();">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </button>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    @php
                                        $discountPercent = round((($product->price - $product->sale_price) / $product->price) * 100);
                                    @endphp
                                    <div class="discount-badge-wrapper">
                                        <img src="{{ asset('assets/images/discount.png') }}" alt="Discount" class="discount-badge-image">
                                        <div class="discount-badge-text">
                                            <span class="discount-text-ar">تخفيض</span>
                                            <span class="discount-text-en">DISCOUNT</span>
                                            <span class="discount-percent">{{ $discountPercent }}%</span>
                                        </div>
                                    </div>
                                @elseif($product->is_new)
                                    <span class="badge new-season">{{ app()->getLocale() == 'ar' ? 'موسم جديد' : 'New Season' }}</span>
                                @elseif($product->is_on_sale)
                                    <span class="badge discount">{{ app()->getLocale() == 'ar' ? 'عرض خاص' : 'On Sale' }}</span>
                                @endif
                            </div>
                            <div class="product-info">
                                @if($product->brand)
                                <p class="product-brand">{{ $product->brand }}</p>
                                @endif
                                <h3 class="product-name">{{ $product->getName() }}</h3>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                <p class="product-price">
                                    <span style="color: #999; text-decoration: line-through; font-size: 14px; margin-inline-end: 8px;">{{ number_format($product->price, 0) }}</span>
                                    {{ number_format($product->sale_price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}
                                </p>
                                @else
                                <p class="product-price">{{ number_format($product->price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</p>
                                @endif
                                @if($product->sizes && is_array($product->sizes) && count($product->sizes) > 0)
                                <div class="size-selector">
                                    <button class="size-arrow prev">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <div class="size-options-wrapper">
                                        @foreach($product->sizes as $size)
                                        <button class="size-option" data-size="{{ is_array($size) ? ($size['value'] ?? $size['ar'] ?? $size['en']) : $size }}">
                                            {{ is_array($size) ? (app()->getLocale() == 'ar' ? ($size['ar'] ?? $size['en']) : ($size['en'] ?? $size['ar'])) : $size }}
                                        </button>
                                        @endforeach
                                    </div>
                                    <button class="size-arrow next">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                                @endif
                                <button class="add-to-cart-btn" data-product-id="{{ $product->id }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                    {{ app()->getLocale() == 'ar' ? 'اختيار المنتج' : 'Select Product' }}
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500 text-lg">{{ app()->getLocale() == 'ar' ? 'لا توجد منتجات متاحة حالياً' : 'No products available at the moment' }}</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                    <div class="pagination">
                        @if($products->onFirstPage())
                        <button class="pagination-btn" disabled>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        @else
                        <a href="{{ $products->previousPageUrl() }}" class="pagination-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        @endif

                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="pagination-btn {{ $page == $products->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach

                        @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="pagination-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        @else
                        <button class="pagination-btn" disabled>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        @endif
                    </div>
                    @endif
                </section>
            </div>
        </main>
@endsection
@push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/pica@9.0.1/dist/pica.min.js"></script>
        <script>
            const isArabic = '{{ app()->getLocale() }}' === 'ar';

            // Initialize Custom Selects
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize all custom select dropdowns on page load
                if (typeof CustomSelect !== 'undefined') {
                    document.querySelectorAll('.custom-select').forEach(select => {
                        if (!select.parentElement.classList.contains('custom-select-wrapper')) {
                            new CustomSelect(select);
                        }
                    });
                }
            });

            // Smart Pica Implementation - Preserves aspect ratio
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof pica === 'undefined') return;

                const picaInstance = pica();

                // Process images after they load
                function processProductImage(img) {
                    // Skip if already processed or loading
                    if (img.dataset.picaProcessed || img.dataset.picaProcessing) return;

                    // Mark as processing
                    img.dataset.picaProcessing = 'true';

                    // Wait for image to fully load
                    if (!img.complete || img.naturalWidth === 0) {
                        img.addEventListener('load', function() {
                            enhanceImageWithPica(this);
                        }, { once: true });
                        return;
                    }

                    enhanceImageWithPica(img);
                }

                function enhanceImageWithPica(img) {
                    try {
                        // Get the container dimensions
                        const container = img.parentElement;
                        const containerRect = container.getBoundingClientRect();

                        // Calculate proper dimensions maintaining aspect ratio
                        const originalAspect = img.naturalWidth / img.naturalHeight;
                        const containerAspect = containerRect.width / containerRect.height;

                        let targetWidth, targetHeight;

                        // Use device pixel ratio for retina displays
                        const dpr = Math.min(window.devicePixelRatio || 1, 2);

                        // Calculate target dimensions based on object-fit: cover behavior
                        if (originalAspect > containerAspect) {
                            // Image is wider - fit to height
                            targetHeight = Math.round(containerRect.height * dpr);
                            targetWidth = Math.round(targetHeight * originalAspect);
                        } else {
                            // Image is taller - fit to width
                            targetWidth = Math.round(containerRect.width * dpr);
                            targetHeight = Math.round(targetWidth / originalAspect);
                        }

                        // Don't upscale images
                        if (targetWidth > img.naturalWidth || targetHeight > img.naturalHeight) {
                            img.dataset.picaProcessed = 'true';
                            delete img.dataset.picaProcessing;
                            return;
                        }

                        // Create canvas with calculated dimensions
                        const canvas = document.createElement('canvas');
                        canvas.width = targetWidth;
                        canvas.height = targetHeight;

                        // Create temporary image for processing
                        const tempImg = new Image();
                        tempImg.crossOrigin = 'anonymous';

                        tempImg.onload = function() {
                            // Use Pica with high quality settings
                            picaInstance.resize(tempImg, canvas, {
                                quality: 3,
                                alpha: true,
                                unsharpAmount: 160,
                                unsharpRadius: 0.6,
                                unsharpThreshold: 1
                            }).then(result => {
                                return picaInstance.toBlob(result, 'image/jpeg', 0.92);
                            }).then(blob => {
                                // Create object URL and update image
                                const url = URL.createObjectURL(blob);

                                // Store original src for potential revert
                                if (!img.dataset.originalSrc) {
                                    img.dataset.originalSrc = img.src;
                                }

                                img.src = url;
                                img.dataset.picaProcessed = 'true';
                                delete img.dataset.picaProcessing;

                                // Clean up old blob URL after a delay
                                if (img.dataset.picaBlobUrl) {
                                    setTimeout(() => URL.revokeObjectURL(img.dataset.picaBlobUrl), 100);
                                }
                                img.dataset.picaBlobUrl = url;

                            }).catch(err => {
                                console.warn('Pica processing failed:', err);
                                img.dataset.picaProcessed = 'true';
                                delete img.dataset.picaProcessing;
                            });
                        };

                        tempImg.onerror = function() {
                            img.dataset.picaProcessed = 'true';
                            delete img.dataset.picaProcessing;
                        };

                        tempImg.src = img.src;

                    } catch (err) {
                        console.warn('Pica setup failed:', err);
                        img.dataset.picaProcessed = 'true';
                        delete img.dataset.picaProcessing;
                    }
                }

                // Process all product images
                function processAllProductImages() {
                    const productImages = document.querySelectorAll('.product-image-primary, .product-image-secondary');
                    productImages.forEach(img => {
                        processProductImage(img);
                    });
                }

                // Initial processing
                setTimeout(processAllProductImages, 100);

                // Reprocess on window resize (debounced)
                let resizeTimer;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function() {
                        // Reset processed flag for responsive resize
                        document.querySelectorAll('[data-pica-processed]').forEach(img => {
                            delete img.dataset.picaProcessed;
                            delete img.dataset.picaProcessing;
                        });
                        processAllProductImages();
                    }, 250);
                });

                // Process new images added dynamically (e.g., infinite scroll)
                if (window.MutationObserver) {
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            mutation.addedNodes.forEach(function(node) {
                                if (node.nodeType === 1) {
                                    const images = node.querySelectorAll ?
                                        node.querySelectorAll('.product-image-primary, .product-image-secondary') : [];
                                    images.forEach(processProductImage);
                                }
                            });
                        });
                    });

                    const productsGrid = document.querySelector('.products-grid');
                    if (productsGrid) {
                        observer.observe(productsGrid, { childList: true, subtree: true });
                    }
                }
            });

            // Mobile Filter Toggle
            const mobileFilterToggle = document.getElementById('mobileFilterToggle');
            const shopSidebar = document.getElementById('shopSidebar');
            const sidebarClose = document.getElementById('sidebarClose');

            if (mobileFilterToggle) {
                mobileFilterToggle.addEventListener('click', function() {
                    shopSidebar.classList.add('active');
                });
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', function() {
                    shopSidebar.classList.remove('active');
                });
            }

            // Close sidebar when clicking overlay
            if (shopSidebar) {
                shopSidebar.addEventListener('click', function(e) {
                    if (e.target === this) {
                        shopSidebar.classList.remove('active');
                    }
                });
            }

            // Apply Filters Functionality
            const applyFiltersBtn = document.querySelector('.apply-filters-btn');
            if (applyFiltersBtn) {
                applyFiltersBtn.addEventListener('click', function() {
                    const url = new URL(window.location.href);

                    // Clear previous filter params
                    url.searchParams.delete('sizes[]');
                    url.searchParams.delete('shoe_sizes[]');
                    url.searchParams.delete('colors[]');
                    url.searchParams.delete('min_price');
                    url.searchParams.delete('max_price');

                    // Get selected sizes
                    const selectedSizes = [];
                    document.querySelectorAll('input[name="size"]:checked').forEach(input => {
                        selectedSizes.push(input.value);
                    });
                    selectedSizes.forEach(size => {
                        url.searchParams.append('sizes[]', size);
                    });

                    // Get selected shoe sizes
                    const selectedShoeSizes = [];
                    document.querySelectorAll('input[name="shoe-size"]:checked').forEach(input => {
                        selectedShoeSizes.push(input.value);
                    });
                    selectedShoeSizes.forEach(size => {
                        url.searchParams.append('shoe_sizes[]', size);
                    });

                    // Get selected colors
                    const selectedColors = [];
                    document.querySelectorAll('input[name="color"]:checked').forEach(input => {
                        selectedColors.push(input.value);
                    });
                    selectedColors.forEach(color => {
                        url.searchParams.append('colors[]', color);
                    });

                    // Get price range
                    const minPrice = document.getElementById('minPrice').value;
                    const maxPrice = document.getElementById('maxPrice').value;

                    if (minPrice) {
                        url.searchParams.set('min_price', minPrice);
                    }
                    if (maxPrice) {
                        url.searchParams.set('max_price', maxPrice);
                    }

                    // Redirect to filtered URL
                    window.location.href = url.toString();
                });
            }

            // Restore filter selections from URL
            const urlParams = new URLSearchParams(window.location.search);

            // Restore sizes
            const sizesParam = urlParams.getAll('sizes[]');
            sizesParam.forEach(size => {
                const checkbox = document.querySelector(`input[name="size"][value="${size}"]`);
                if (checkbox) checkbox.checked = true;
            });

            // Restore shoe sizes
            const shoeSizesParam = urlParams.getAll('shoe_sizes[]');
            shoeSizesParam.forEach(size => {
                const checkbox = document.querySelector(`input[name="shoe-size"][value="${size}"]`);
                if (checkbox) checkbox.checked = true;
            });

            // Restore colors
            const colorsParam = urlParams.getAll('colors[]');
            colorsParam.forEach(color => {
                const checkbox = document.querySelector(`input[name="color"][value="${color}"]`);
                if (checkbox) checkbox.checked = true;
            });

            // Product Image Hover Effect & Wishlist - Combined DOMContentLoaded
            document.addEventListener('DOMContentLoaded', function() {
                console.log('🚀 Shop page initialized - POWER MODE ACTIVATED');

                // Handle image hover for all product cards
                const productCards = document.querySelectorAll('.product-card');

                productCards.forEach(card => {
                    const imageWrapper = card.querySelector('.product-image-wrapper');
                    const secondaryImage = card.querySelector('.product-image-secondary');

                    if (imageWrapper && secondaryImage) {
                        card.addEventListener('mouseenter', function() {
                            secondaryImage.style.opacity = '1';
                        });

                        card.addEventListener('mouseleave', function() {
                            secondaryImage.style.opacity = '0';
                        });
                    }
                });

                // Wishlist functionality - Using event delegation
                console.log('💗 Initializing wishlist system...');

                // Use event delegation on the products grid
                const productsGrid = document.querySelector('.products-grid');

                if (!productsGrid) {
                    console.error('❌ Products grid not found!');
                    return;
                }

                console.log('✅ Products grid found, attaching event listeners');

                productsGrid.addEventListener('click', async function(e) {
                    // Check if clicked element is wishlist button or its child
                    const button = e.target.closest('.wishlist-btn');

                    if (button) {
                        console.log('💗 Wishlist button clicked! Product ID:', button.dataset.productId);

                        e.preventDefault();
                        e.stopPropagation();

                        @guest
                            console.log('⚠️ User not logged in');
                            Swal.fire({
                                icon: 'warning',
                                title: 'يجب تسجيل الدخول',
                                text: 'يجب عليك تسجيل الدخول أولاً لإضافة منتجات إلى قائمة الأمنيات',
                                confirmButtonText: 'تسجيل الدخول الآن',
                                showCancelButton: true,
                                cancelButtonText: 'إلغاء',
                                confirmButtonColor: '#1a1a1a',
                                cancelButtonColor: '#666',
                                background: '#ffffff',
                                color: '#000000',
                                iconColor: '#ffc107'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('login') }}";
                                }
                            });
                            return;
                        @endguest

                        const productId = button.dataset.productId;

                        if (!productId) {
                            console.error('❌ Product ID not found on button');
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: 'لم يتم العثور على معرف المنتج',
                                confirmButtonColor: '#1a1a1a',
                                background: '#ffffff',
                                color: '#000000',
                                iconColor: '#dc3545'
                            });
                            return;
                        }

                        console.log('📤 Sending request to add/remove from wishlist...');

                        // Show loading state
                        const originalHTML = button.innerHTML;
                        button.disabled = true;
                        button.style.opacity = '0.6';

                        try {
                            const response = await fetch("{{ route('wishlist.toggle') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ product_id: productId })
                            });

                            console.log('📥 Response status:', response.status);

                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            const data = await response.json();
                            console.log('📦 Response data:', data);

                            if (data.success) {
                                // Toggle active class
                                button.classList.toggle('active');

                                console.log('✅ Success! isAdded:', data.isAdded);

                                // Show beautiful success message
                                Swal.fire({
                                    icon: data.isAdded ? 'success' : 'error',
                                    title: data.isAdded ? 'تمت الإضافة بنجاح' : 'تم الحذف',
                                    text: data.isAdded
                                        ? 'تم إضافة المنتج إلى قائمة الأمنيات'
                                        : 'تم حذف المنتج من قائمة الأمنيات',
                                    timer: 2500,
                                    showConfirmButton: false,
                                    position: 'top-end',
                                    toast: true,
                                    background: '#ffffff',
                                    color: '#000000',
                                    iconColor: data.isAdded ? '#28a745' : '#dc3545',
                                    customClass: {
                                        popup: 'animated fadeInRight'
                                    }
                                });

                                // Update wishlist count in header badge
                                const wishlistBadge = document.querySelector('.header-link[href*="wishlist"] .badge');
                                if (wishlistBadge) {
                                    const currentCount = parseInt(wishlistBadge.textContent) || 0;
                                    const newCount = data.isAdded ? currentCount + 1 : Math.max(0, currentCount - 1);
                                    wishlistBadge.textContent = newCount;
                                    console.log(`🔢 Wishlist count updated: ${currentCount} → ${newCount}`);
                                }
                            } else {
                                throw new Error(data.message || 'حدث خطأ غير معروف');
                            }
                        } catch (error) {
                            console.error('❌ Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: error.message || 'حدث خطأ أثناء الإضافة للمفضلة. يرجى المحاولة مرة أخرى.',
                                confirmButtonText: 'حسناً',
                                confirmButtonColor: '#1a1a1a',
                                background: '#ffffff',
                                color: '#000000',
                                iconColor: '#dc3545'
                            });
                        } finally {
                            // Restore button state
                            button.disabled = false;
                            button.style.opacity = '1';
                        }
                    }
                });

                console.log('✅ Wishlist event listener attached successfully!');

                // Additional: Direct event listeners on all wishlist buttons as backup
                const allWishlistButtons = document.querySelectorAll('.wishlist-btn');
                console.log(`🔄 Found ${allWishlistButtons.length} wishlist buttons, attaching direct listeners...`);

                allWishlistButtons.forEach((btn, index) => {
                    btn.addEventListener('click', async function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();

                        console.log(`🎯 Direct listener fired for button ${index + 1}/${allWishlistButtons.length}`);
                        console.log('Product ID from button:', this.dataset.productId);

                        @guest
                            Swal.fire({
                                icon: 'warning',
                                title: 'يجب تسجيل الدخول',
                                text: 'يجب عليك تسجيل الدخول أولاً',
                                confirmButtonText: 'تسجيل الدخول',
                                showCancelButton: true,
                                cancelButtonText: 'إلغاء',
                                confirmButtonColor: '#1a1a1a',
                                cancelButtonColor: '#666',
                                background: '#ffffff',
                                color: '#000000',
                                iconColor: '#ffc107'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('login') }}";
                                }
                            });
                            return;
                        @endguest

                        const productId = this.dataset.productId;
                        if (!productId) {
                            console.error('❌ No product ID!');
                            return;
                        }

                        this.disabled = true;
                        this.style.opacity = '0.6';

                        try {
                            console.log('📡 Sending AJAX request...');
                            const response = await fetch("{{ route('wishlist.toggle') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ product_id: productId })
                            });

                            const data = await response.json();
                            console.log('✅ Response:', data);

                            if (data.success) {
                                this.classList.toggle('active');

                                Swal.fire({
                                    icon: data.isAdded ? 'success' : 'error',
                                    title: data.isAdded ? 'تمت الإضافة بنجاح' : 'تم الحذف',
                                    text: data.isAdded ? 'تم إضافة المنتج إلى قائمة الأمنيات' : 'تم حذف المنتج من قائمة الأمنيات',
                                    timer: 2500,
                                    showConfirmButton: false,
                                    position: 'top-end',
                                    toast: true,
                                    background: '#ffffff',
                                    color: '#000000',
                                    iconColor: data.isAdded ? '#28a745' : '#dc3545'
                                });

                                // Update wishlist count
                                const wishlistBadge = document.querySelector('.header-link[href*="wishlist"] .badge');
                                if (wishlistBadge) {
                                    const currentCount = parseInt(wishlistBadge.textContent) || 0;
                                    const newCount = data.isAdded ? currentCount + 1 : Math.max(0, currentCount - 1);
                                    wishlistBadge.textContent = newCount;
                                }
                            }
                        } catch (error) {
                            console.error('❌ Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: 'حدث خطأ. يرجى المحاولة مرة أخرى.',
                                confirmButtonColor: '#1a1a1a',
                                background: '#ffffff',
                                color: '#000000',
                                iconColor: '#dc3545'
                            });
                        } finally {
                            this.disabled = false;
                            this.style.opacity = '1';
                        }
                    });
                });

                console.log('🎉 All wishlist buttons ready!');

                // View toggle
                const viewButtons = document.querySelectorAll('.view-btn');

                viewButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        viewButtons.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        const view = this.getAttribute('data-view');
                        if (productsGrid) {
                            productsGrid.setAttribute('data-view', view);
                        }
                    });
                });

                // Mobile View Toggle (منتج واحد أو منتجين)
                const mobileViewButtons = document.querySelectorAll('.mobile-view-btn');

                mobileViewButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        mobileViewButtons.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        const view = this.getAttribute('data-mobile-view');

                        if (view === 'one') {
                            // عرض منتج واحد - يملأ الشاشة
                            productsGrid.setAttribute('data-view', 'list');
                        } else {
                            // عرض منتجين بجانب بعض
                            productsGrid.setAttribute('data-view', 'grid-2');
                        }
                    });
                });

                // Size selector
                document.querySelectorAll('.size-option').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const wrapper = this.closest('.size-options-wrapper');
                        wrapper.querySelectorAll('.size-option').forEach(b => b.classList.remove(
                            'selected'));
                        this.classList.add('selected');
                    });
                });

                // Size arrows
                document.querySelectorAll('.size-arrow').forEach(arrow => {
                    arrow.addEventListener('click', function() {
                        const wrapper = this.parentElement.querySelector('.size-options-wrapper');
                        const scrollAmount = 50;
                        if (this.classList.contains('prev')) {
                            wrapper.scrollLeft -= scrollAmount;
                        } else {
                            wrapper.scrollLeft += scrollAmount;
                        }
                    });
                });

                // Add to Cart Button functionality - Open Modal
                document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const productCard = this.closest('.product-card');
                        const productLink = productCard.querySelector('.product-image-wrapper a');
                        const productUrl = productLink.href;

                        // Fetch product data from server
                        fetch(productUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(product => {
                            console.log('Product data received from API:', product);
                            console.log('Total images:', product.images ? product.images.length : 0);
                            console.log('Description:', product.description ? 'Available' : 'Missing');
                            console.log('Sizing info:', product.sizing_info ? 'Available' : 'Missing');
                            console.log('Design details:', product.design_details ? 'Available' : 'Missing');
                            openProductModal(product);
                        })
                        .catch(error => {
                            console.error('Error loading product from API:', error);

                            // Fallback: get basic data from card (without description/details)
                            console.warn('Using fallback data from product card');
                            const productId = button.dataset.productId || productCard.querySelector('[data-product-id]')?.dataset.productId;
                            const productName = productCard.querySelector('.product-name').textContent;
                            const productBrandEl = productCard.querySelector('.product-brand');
                            const productBrand = productBrandEl ? productBrandEl.textContent : '';
                            const productPriceEl = productCard.querySelector('.product-price');
                            const productPrice = productPriceEl ? productPriceEl.textContent : '';
                            const primaryImage = productCard.querySelector('.product-image-primary').src;
                            const secondaryImage = productCard.querySelector('.product-image-secondary').src;
                            const hasNewSeasonBadge = productCard.querySelector('.badge.new-season') !== null;

                            // Get available sizes
                            const sizes = [];
                            productCard.querySelectorAll('.size-option').forEach(sizeBtn => {
                                sizes.push(sizeBtn.getAttribute('data-size'));
                            });

                            const fallbackProduct = {
                                id: productId,
                                name: productName,
                                brand: productBrand,
                                price: productPrice,
                                images: [primaryImage, secondaryImage],
                                hasNewSeason: hasNewSeasonBadge,
                                sizes: sizes,
                                description: '',
                                sizing_info: '',
                                design_details: ''
                            };

                            openProductModal(fallbackProduct);
                        });
                    });
                });

                // Product Modal Functions
                function openProductModal(product) {
                    const modal = document.getElementById('productModal');
                    const modalMainImage = document.getElementById('modalMainImage');
                    const modalBrand = document.getElementById('modalBrand');
                    const modalProductName = document.getElementById('modalProductName');
                    const modalPrice = document.getElementById('modalPrice');
                    const modalSeasonBadge = document.getElementById('modalSeasonBadge');
                    const modalThumbnails = document.getElementById('modalThumbnails');
                    const modalSizeButtons = document.getElementById('modalSizeButtons');

                    // Store product ID globally
                    currentProductId = product.id;
                    console.log('Current Product ID:', currentProductId);

                    // Set product data
                    const allImages = product.images || [];
                    if (allImages.length > 0) {
                        modalMainImage.src = allImages[0];
                        modalMainImage.alt = product.name;
                    }

                    modalBrand.textContent = product.brand || '';
                    modalProductName.textContent = product.name;
                    modalPrice.textContent = product.price;

                    // Update description
                    const modalDescription = document.getElementById('modalDescription');
                    if (modalDescription) {
                        if (product.description) {
                            modalDescription.innerHTML = product.description;
                        } else {
                            modalDescription.innerHTML = '<p>{{ app()->getLocale() == "ar" ? "لا يوجد وصف متاح" : "No description available" }}</p>';
                        }
                    }

                    // Update sizing info tab
                    const modalSizingInfo = document.getElementById('modalSizingInfo');
                    const sizingTabBtn = document.querySelector('.modal-tab-btn[data-tab="modal-sizing"]');
                    if (product.sizing_info && product.sizing_info.trim() !== '') {
                        if (modalSizingInfo) modalSizingInfo.innerHTML = product.sizing_info;
                        if (sizingTabBtn) {
                            sizingTabBtn.style.display = 'inline-block';
                            sizingTabBtn.style.visibility = 'visible';
                        }
                    } else {
                        if (modalSizingInfo) modalSizingInfo.innerHTML = '';
                        if (sizingTabBtn) {
                            sizingTabBtn.style.display = 'none';
                            sizingTabBtn.style.visibility = 'hidden';
                        }
                    }

                    // Update design details tab
                    const modalDesignDetails = document.getElementById('modalDesignDetails');
                    const designTabBtn = document.querySelector('.modal-tab-btn[data-tab="modal-design"]');
                    if (product.design_details && product.design_details.trim() !== '') {
                        if (modalDesignDetails) modalDesignDetails.innerHTML = product.design_details;
                        if (designTabBtn) {
                            designTabBtn.style.display = 'inline-block';
                            designTabBtn.style.visibility = 'visible';
                        }
                    } else {
                        if (modalDesignDetails) modalDesignDetails.innerHTML = '';
                        if (designTabBtn) {
                            designTabBtn.style.display = 'none';
                            designTabBtn.style.visibility = 'hidden';
                        }
                    }

                    // Log for debugging
                    console.log('Product data loaded:', {
                        has_description: !!product.description,
                        has_sizing: !!(product.sizing_info && product.sizing_info.trim()),
                        has_design: !!(product.design_details && product.design_details.trim())
                    });

                    // Reset tabs: hide all panels and deactivate all buttons
                    document.querySelectorAll('.modal-tab-panel').forEach(panel => {
                        panel.classList.remove('active');
                    });
                    document.querySelectorAll('.modal-tab-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    // Find and activate first visible tab
                    const allTabBtns = document.querySelectorAll('.modal-tab-btn');
                    let firstVisibleTab = null;
                    for (let btn of allTabBtns) {
                        const btnStyle = window.getComputedStyle(btn);
                        if (btnStyle.display !== 'none' && btnStyle.visibility !== 'hidden') {
                            firstVisibleTab = btn;
                            break;
                        }
                    }

                    if (firstVisibleTab) {
                        firstVisibleTab.classList.add('active');
                        const firstTabId = firstVisibleTab.getAttribute('data-tab');
                        const firstPanel = document.getElementById(firstTabId);
                        if (firstPanel) firstPanel.classList.add('active');
                    }

                    // Show/hide season badge
                    if (product.hasNewSeason) {
                        modalSeasonBadge.style.display = 'inline-block';
                    } else {
                        modalSeasonBadge.style.display = 'none';
                    }

                    // Add all thumbnails (main image + all gallery images)
                    modalThumbnails.innerHTML = '';
                    console.log('Total images for product:', allImages.length);

                    allImages.forEach((img, index) => {
                        const thumbnail = document.createElement('img');
                        thumbnail.src = img;
                        thumbnail.alt = `صورة ${index + 1}`;
                        thumbnail.className = `modal-thumbnail ${index === 0 ? 'active' : ''}`;
                        thumbnail.addEventListener('click', function() {
                            updateModalImage(index);
                        });
                        modalThumbnails.appendChild(thumbnail);
                    });

                    // Add sizes to dropdown
                    const modalSizeSelect = document.getElementById('modalSizeSelect');
                    const modalSizesList = document.getElementById('modalSizesList');
                    const modalAvailableSizes = document.getElementById('modalAvailableSizes');

                    // Debug: Log sizes
                    console.log('🔍 Product sizes:', product.sizes);
                    console.log('📊 Sizes length:', product.sizes ? product.sizes.length : 0);

                    // Clear existing options except first one
                    modalSizeSelect.innerHTML = '<option value="">اختر المقاس</option>';

                    if (product.sizes && product.sizes.length > 0) {
                        console.log('✅ Displaying ' + product.sizes.length + ' sizes');

                        // Update sizes count
                        document.getElementById('modalSizesCount').textContent = '(' + product.sizes.length + ')';

                        // Display available sizes list as badges
                        const sizesHTML = product.sizes.map(size =>
                            '<span style="display: inline-block; padding: 8px 16px; margin: 4px; background: #007bff; color: white; border-radius: 4px; font-weight: bold; font-size: 14px;">' +
                            size +
                            '</span>'
                        ).join('');

                        modalSizesList.innerHTML = sizesHTML;
                        modalAvailableSizes.style.display = 'block';

                        // Add sizes to dropdown
                        product.sizes.forEach(size => {
                            const option = document.createElement('option');
                            option.value = size;
                            option.textContent = size;
                            modalSizeSelect.appendChild(option);
                        });

                        console.log('✅ Added ' + product.sizes.length + ' options to dropdown');

                        // Destroy old custom select if exists
                        if (modalSizeSelect.parentElement.classList.contains('custom-select-wrapper')) {
                            const wrapper = modalSizeSelect.parentElement;
                            const parent = wrapper.parentElement;
                            parent.insertBefore(modalSizeSelect, wrapper);
                            wrapper.remove();
                        }

                        // Initialize custom select
                        if (typeof CustomSelect !== 'undefined') {
                            new CustomSelect(modalSizeSelect);
                            console.log('✅ CustomSelect initialized');
                        } else {
                            console.warn('⚠️ CustomSelect not available');
                        }
                    } else {
                        console.warn('⚠️ No sizes available for this product');
                        modalAvailableSizes.style.display = 'none';
                    }

                    // Setup image navigation
                    let currentImageIndex = 0;
                    const totalImages = allImages.length;

                    function updateModalImage(index) {
                        if (index >= 0 && index < totalImages) {
                            currentImageIndex = index;
                            modalMainImage.src = allImages[currentImageIndex];

                            // Update active thumbnail
                            document.querySelectorAll('.modal-thumbnail').forEach((t, i) => {
                                t.classList.toggle('active', i === currentImageIndex);
                            });

                            // Update navigation buttons state
                            document.getElementById('modalPrevImage').disabled = currentImageIndex === 0;
                            document.getElementById('modalNextImage').disabled = currentImageIndex === totalImages - 1;
                        }
                    }

                    // Previous image button
                    document.getElementById('modalPrevImage').onclick = function(e) {
                        e.stopPropagation();
                        if (currentImageIndex > 0) {
                            updateModalImage(currentImageIndex - 1);
                        }
                    };

                    // Next image button
                    document.getElementById('modalNextImage').onclick = function(e) {
                        e.stopPropagation();
                        if (currentImageIndex < totalImages - 1) {
                            updateModalImage(currentImageIndex + 1);
                        }
                    };

                    // Initial state
                    updateModalImage(0);

                    // Show modal
                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                }

                function closeProductModal() {
                    const modal = document.getElementById('productModal');
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }

                // Close modal button
                document.getElementById('productModalClose').addEventListener('click', closeProductModal);

                // Close modal on overlay click
                document.querySelector('.product-modal-overlay').addEventListener('click', closeProductModal);

                // Modal tabs functionality
                document.querySelectorAll('.modal-tab-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const targetTab = this.getAttribute('data-tab');

                        // Remove active class from all tabs and panels
                        document.querySelectorAll('.modal-tab-btn').forEach(b => b.classList.remove(
                            'active'));
                        document.querySelectorAll('.modal-tab-panel').forEach(p => p.classList.remove(
                            'active'));

                        // Add active class to clicked tab and corresponding panel
                        this.classList.add('active');
                        document.getElementById(targetTab).classList.add('active');
                    });
                });

                // Add to bag from modal
                let currentProductId = null;

                document.getElementById('modalAddToBag').addEventListener('click', function() {
                    const modalSizeSelect = document.getElementById('modalSizeSelect');
                    const selectedSize = modalSizeSelect ? modalSizeSelect.value : null;
                    const productName = document.getElementById('modalProductName').textContent;

                    if (modalSizeSelect && !selectedSize) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'اختر المقاس',
                            text: 'الرجاء اختيار المقاس أولاً',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#1a1a1a'
                        });
                        return;
                    }

                    // Add to cart via AJAX
                    const button = this;
                    button.disabled = true;

                    fetch("{{ route('cart.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: currentProductId,
                            quantity: 1,
                            size: selectedSize
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update cart count
                            if (typeof updateCartCount === 'function') {
                                updateCartCount();
                            }
                            const cartBadge = document.getElementById('cartBadge');
                            if (cartBadge && data.cartCount !== undefined) {
                                cartBadge.textContent = data.cartCount;
                            }

                            // Update cart sidebar
                            if (window.cartSidebarInstance && typeof window.cartSidebarInstance.loadCartFromServer === 'function') {
                                window.cartSidebarInstance.loadCartFromServer();
                            }

                            // Add visual feedback
                            button.innerHTML =
                                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> تمت الإضافة';
                            button.style.background = '#4CAF50';

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'تمت الإضافة!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // Close modal after adding
                            setTimeout(() => {
                                button.innerHTML =
                                    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg> إضافة إلى حقيبة التسوق';
                                button.style.background = '#1a1a1a';
                                button.disabled = false;
                                closeProductModal();
                            }, 1500);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        button.disabled = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: 'حدث خطأ أثناء الإضافة للسلة',
                            confirmButtonText: 'حسناً'
                        });
                    });
                });
            });
        </script>

@endpush
