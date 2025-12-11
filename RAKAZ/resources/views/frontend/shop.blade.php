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
                            <img src="" alt="" class="modal-main-product-image" id="modalMainImage">
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
                                    <label class="modal-option-label">اختيار المقاس</label>
                                    <a href="#" class="modal-size-guide-link">جدول المقاسات</a>
                                </div>
                                <div class="modal-size-buttons" id="modalSizeButtons">
                                </div>
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
                                <button class="modal-tab-btn active" data-tab="modal-description">وصف المنتج</button>
                                <button class="modal-tab-btn" data-tab="modal-shipping">التوصيل والإرجاع</button>
                                <button class="modal-tab-btn" data-tab="modal-details">المقاسات والحجم</button>
                            </div>
                            <div class="modal-tabs-content">
                                <div class="modal-tab-panel active" id="modal-description">
                                    <p id="modalDescription">منتج فاخر من ركاز مصنوع بعناية فائقة من أجود أنواع الأقمشة
                                        لراحتك وأناقتك.</p>
                                    <ul>
                                        <li>مصنوع من أجود أنواع القطن</li>
                                        <li>تصميم عصري وأنيق</li>
                                        <li>مريح للاستخدام اليومي</li>
                                    </ul>
                                </div>
                                <div class="modal-tab-panel" id="modal-shipping">
                                    <p>نوفر شحن مجاني لجميع الطلبات داخل الإمارات.</p>
                                    <p>التوصيل خلال 2-3 أيام عمل، أو توصيل في نفس اليوم في دبي وأبوظبي.</p>
                                    <p>الإرجاع مجاني خلال 14 يوم من تاريخ الاستلام.</p>
                                </div>
                                <div class="modal-tab-panel" id="modal-details">
                                    <p>رمز المنتج: <span id="modalProductCode">RKZ-001</span></p>
                                    <ul>
                                        <li>مصنوع من مواد عالية الجودة</li>
                                        <li>تصميم تقليدي مع لمسة عصرية</li>
                                        <li>متوفر بعدة مقاسات</li>
                                        <li>مناسب لجميع المناسبات</li>
                                    </ul>
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
                            <label class="size-checkbox">
                                <input type="checkbox" name="size" value="XXS">
                                <span class="size-label">
                                    <span class="size-value">XXS</span>
                                    <span class="size-count">(24)</span>
                                </span>
                            </label>
                            <label class="size-checkbox">
                                <input type="checkbox" name="size" value="XS">
                                <span class="size-label">
                                    <span class="size-value">XS</span>
                                    <span class="size-count">(214)</span>
                                </span>
                            </label>
                            <label class="size-checkbox">
                                <input type="checkbox" name="size" value="S">
                                <span class="size-label">
                                    <span class="size-value">S</span>
                                    <span class="size-count">(1331)</span>
                                </span>
                            </label>
                            <label class="size-checkbox">
                                <input type="checkbox" name="size" value="M">
                                <span class="size-label">
                                    <span class="size-value">M</span>
                                    <span class="size-count">(1329)</span>
                                </span>
                            </label>
                            <label class="size-checkbox">
                                <input type="checkbox" name="size" value="L">
                                <span class="size-label">
                                    <span class="size-value">L</span>
                                    <span class="size-count">(1323)</span>
                                </span>
                            </label>
                            <label class="size-checkbox">
                                <input type="checkbox" name="size" value="XL">
                                <span class="size-label">
                                    <span class="size-value">XL</span>
                                    <span class="size-count">(1211)</span>
                                </span>
                            </label>
                            <label class="size-checkbox">
                                <input type="checkbox" name="size" value="XXL">
                                <span class="size-label">
                                    <span class="size-value">XXL</span>
                                    <span class="size-count">(764)</span>
                                </span>
                            </label>
                            <label class="size-checkbox">
                                <input type="checkbox" name="size" value="XXXL">
                                <span class="size-label">
                                    <span class="size-value">XXXL</span>
                                    <span class="size-count">(52)</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- مقاس الحذاء (Shoe Size) -->
                    <div class="filter-section">
                        <h3 class="filter-title">مقاس الحذاء</h3>
                        <div class="shoe-size-selector">
                            <select class="shoe-size-dropdown custom-select">
                                <option value="">EU</option>
                                <option value="36">36 (3)</option>
                                <option value="37">37 (4)</option>
                                <option value="37.5">37.5 (5)</option>
                                <option value="38">38 (4)</option>
                                <option value="38.5">38.5 (10)</option>
                                <option value="39">39 (15)</option>
                                <option value="39.5">39.5 (1)</option>
                                <option value="40">40 (212)</option>
                                <option value="40.5">40.5 (66)</option>
                                <option value="41">41 (279)</option>
                                <option value="41.5">41.5 (39)</option>
                                <option value="42">42 (391)</option>
                                <option value="42.5">42.5 (85)</option>
                                <option value="43">43 (365)</option>
                                <option value="43.5">43.5 (28)</option>
                                <option value="44">44 (291)</option>
                                <option value="44.5">44.5 (21)</option>
                                <option value="45">45 (189)</option>
                            </select>
                            <div class="shoe-size-scroll">
                                <label class="shoe-size-checkbox">
                                    <input type="checkbox" name="shoe-size" value="36">
                                    <span class="shoe-label">
                                        <span class="shoe-count">(3)</span>
                                        <span class="shoe-value">36</span>
                                    </span>
                                </label>
                                <label class="shoe-size-checkbox">
                                    <input type="checkbox" name="shoe-size" value="37">
                                    <span class="shoe-label">
                                        <span class="shoe-count">(4)</span>
                                        <span class="shoe-value">37</span>
                                    </span>
                                </label>
                                <label class="shoe-size-checkbox">
                                    <input type="checkbox" name="shoe-size" value="37.5">
                                    <span class="shoe-label">
                                        <span class="shoe-count">(5)</span>
                                        <span class="shoe-value">37.5</span>
                                    </span>
                                </label>
                                <label class="shoe-size-checkbox">
                                    <input type="checkbox" name="shoe-size" value="38">
                                    <span class="shoe-label">
                                        <span class="shoe-count">(4)</span>
                                        <span class="shoe-value">38</span>
                                    </span>
                                </label>
                                <label class="shoe-size-checkbox">
                                    <input type="checkbox" name="shoe-size" value="38.5">
                                    <span class="shoe-label">
                                        <span class="shoe-count">(10)</span>
                                        <span class="shoe-value">38.5</span>
                                    </span>
                                </label>
                                <label class="shoe-size-checkbox">
                                    <input type="checkbox" name="shoe-size" value="39">
                                    <span class="shoe-label">
                                        <span class="shoe-count">(15)</span>
                                        <span class="shoe-value">39</span>
                                    </span>
                                </label>
                                <label class="shoe-size-checkbox">
                                    <input type="checkbox" name="shoe-size" value="39.5">
                                    <span class="shoe-label">
                                        <span class="shoe-count">(1)</span>
                                        <span class="shoe-value">39.5</span>
                                    </span>
                                </label>
                                <label class="shoe-size-checkbox">
                                    <input type="checkbox" name="shoe-size" value="40">
                                    <span class="shoe-label">
                                        <span class="shoe-count">(212)</span>
                                        <span class="shoe-value">40</span>
                                    </span>
                                </label>
                                <label class="shoe-size-checkbox">
                                    <input type="checkbox" name="shoe-size" value="40.5">
                                    <span class="shoe-label">
                                        <span class="shoe-count">(66)</span>
                                        <span class="shoe-value">40.5</span>
                                    </span>
                                </label>
                                <label class="shoe-size-checkbox">
                                    <input type="checkbox" name="shoe-size" value="41">
                                    <span class="shoe-label">
                                        <span class="shoe-count">(279)</span>
                                        <span class="shoe-value">41</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- اللون (Color) -->
                    <div class="filter-section">
                        <h3 class="filter-title">اللون</h3>
                        <div class="color-scroll">
                            <label class="color-checkbox">
                                <input type="checkbox" name="color" value="black">
                                <span class="color-label">
                                    <span class="color-circle" style="background-color: #000000;"></span>
                                    <span class="color-name">أسود</span>
                                    <span class="color-count">(760)</span>
                                </span>
                            </label>
                            <label class="color-checkbox">
                                <input type="checkbox" name="color" value="blue">
                                <span class="color-label">
                                    <span class="color-circle" style="background-color: #4A90E2;"></span>
                                    <span class="color-name">أزرق</span>
                                    <span class="color-count">(484)</span>
                                </span>
                            </label>
                            <label class="color-checkbox">
                                <input type="checkbox" name="color" value="gray">
                                <span class="color-label">
                                    <span class="color-circle" style="background-color: #808080;"></span>
                                    <span class="color-name">رمادي</span>
                                    <span class="color-count">(290)</span>
                                </span>
                            </label>
                            <label class="color-checkbox">
                                <input type="checkbox" name="color" value="green">
                                <span class="color-label">
                                    <span class="color-circle" style="background-color: #7ED321;"></span>
                                    <span class="color-name">أخضر</span>
                                    <span class="color-count">(263)</span>
                                </span>
                            </label>
                            <label class="color-checkbox">
                                <input type="checkbox" name="color" value="beige">
                                <span class="color-label">
                                    <span class="color-circle" style="background-color: #D4C5B9;"></span>
                                    <span class="color-name">لون محايد</span>
                                    <span class="color-count">(250)</span>
                                </span>
                            </label>
                            <label class="color-checkbox">
                                <input type="checkbox" name="color" value="purple">
                                <span class="color-label">
                                    <span class="color-circle"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></span>
                                    <span class="color-name">ملون</span>
                                    <span class="color-count">(247)</span>
                                </span>
                            </label>
                            <label class="color-checkbox">
                                <input type="checkbox" name="color" value="brown">
                                <span class="color-label">
                                    <span class="color-circle" style="background-color: #8B4513;"></span>
                                    <span class="color-name">بني</span>
                                    <span class="color-count">(211)</span>
                                </span>
                            </label>
                            <label class="color-checkbox">
                                <input type="checkbox" name="color" value="metallic">
                                <span class="color-label">
                                    <span class="color-circle" style="background-color: #C0C0C0;"></span>
                                    <span class="color-name">عديم اللون</span>
                                    <span class="color-count">(167)</span>
                                </span>
                            </label>
                            <label class="color-checkbox">
                                <input type="checkbox" name="color" value="white">
                                <span class="color-label">
                                    <span class="color-circle"
                                        style="background-color: #FFFFFF; border: 1px solid #ddd;"></span>
                                    <span class="color-name">أبيض</span>
                                    <span class="color-count">(159)</span>
                                </span>
                            </label>
                            <label class="color-checkbox">
                                <input type="checkbox" name="color" value="red">
                                <span class="color-label">
                                    <span class="color-circle" style="background-color: #E53935;"></span>
                                    <span class="color-name">أحمر</span>
                                    <span class="color-count">(55)</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- السعر (Price) -->
                    <div class="filter-section">
                        <h3 class="filter-title">السعر</h3>
                        <div class="price-range-wrapper">
                            <div class="price-display">
                                <div class="price-box">
                                    <label>الكمية الأدنى</label>
                                    <div class="price-input-wrapper">
                                        <input type="number" id="minPrice" value="60" min="0"
                                            max="28050">
                                        <span class="currency">AED</span>
                                    </div>
                                </div>
                                <div class="price-box">
                                    <label>الكمية الأقصى</label>
                                    <div class="price-input-wrapper">
                                        <input type="number" id="maxPrice" value="28050" min="0"
                                            max="28050">
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
                            <a href="{{ route('product.details', $product->getSlug()) }}" class="product-image-wrapper">
                                <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('assets/images/placeholder.jpg') }}"
                                    alt="{{ $product->getName() }}" class="product-image-primary">
                                @if($product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0)
                                <img src="{{ asset('storage/' . $product->gallery_images[0]) }}"
                                    alt="{{ $product->getName() }}" class="product-image-secondary">
                                @else
                                <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('assets/images/placeholder.jpg') }}"
                                    alt="{{ $product->getName() }}" class="product-image-secondary">
                                @endif
                                <button class="wishlist-btn" data-product-id="{{ $product->id }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </button>
                                @if($product->is_new)
                                <span class="badge new-season">{{ app()->getLocale() == 'ar' ? 'موسم جديد' : 'New Season' }}</span>
                                @elseif($product->is_on_sale)
                                <span class="badge discount">{{ app()->getLocale() == 'ar' ? 'عرض خاص' : 'On Sale' }}</span>
                                @endif
                            </a>
                            <div class="product-info">
                                @if($product->brand)
                                <p class="product-brand">{{ $product->brand }}</p>
                                @endif
                                <h3 class="product-name">{{ $product->getName() }}</h3>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                <div class="price-group">
                                    <span class="product-price-original" style="text-decoration: line-through; color: #999; font-size: 14px;">{{ number_format($product->price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                                    <span class="product-price" style="color: #dc2626;">{{ number_format($product->sale_price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                                </div>
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
        <script>
            const isArabic = '{{ app()->getLocale() }}' === 'ar';

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

            // Wishlist functionality
            document.addEventListener('DOMContentLoaded', function() {
                const wishlistButtons = document.querySelectorAll('.wishlist-btn');
                wishlistButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        this.classList.toggle('active');
                    });
                });

                // View toggle
                const viewButtons = document.querySelectorAll('.view-btn');
                const productsGrid = document.querySelector('.products-grid');

                viewButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        viewButtons.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        const view = this.getAttribute('data-view');
                        productsGrid.setAttribute('data-view', view);
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
                        const productLink = productCard.querySelector('.product-image-wrapper');
                        const productUrl = productLink.href;

                        // Fetch product data from server
                        fetch(productUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .catch(() => {
                            // Fallback: get data from card
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

                            return {
                                name: productName,
                                brand: productBrand,
                                price: productPrice,
                                images: [primaryImage, secondaryImage],
                                hasNewSeason: hasNewSeasonBadge,
                                sizes: sizes
                            };
                        })
                        .then(product => {
                            console.log('Product data received:', product);
                            console.log('Total images:', product.images ? product.images.length : 0);
                            openProductModal(product);
                        })
                        .catch(error => {
                            console.error('Error loading product:', error);
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

                    // Set product data
                    const allImages = product.images || [];
                    if (allImages.length > 0) {
                        modalMainImage.src = allImages[0];
                        modalMainImage.alt = product.name;
                    }

                    modalBrand.textContent = product.brand || '';
                    modalProductName.textContent = product.name;
                    modalPrice.textContent = product.price;

                    // Update description if available
                    const modalDescription = document.getElementById('modalDescription');
                    if (modalDescription && product.description) {
                        modalDescription.innerHTML = product.description;
                    }

                    // Update product code if available
                    const modalProductCode = document.getElementById('modalProductCode');
                    if (modalProductCode && product.sku) {
                        modalProductCode.textContent = product.sku;
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
                            modalMainImage.src = img;
                            document.querySelectorAll('.modal-thumbnail').forEach(t => t.classList
                                .remove('active'));
                            this.classList.add('active');
                        });
                        modalThumbnails.appendChild(thumbnail);
                    });

                    // Add size buttons
                    modalSizeButtons.innerHTML = '';
                    if (product.sizes && product.sizes.length > 0) {
                        product.sizes.forEach(size => {
                            const sizeBtn = document.createElement('button');
                            sizeBtn.className = 'modal-size-btn';
                            sizeBtn.textContent = size;
                            sizeBtn.addEventListener('click', function() {
                                document.querySelectorAll('.modal-size-btn').forEach(btn => btn.classList
                                    .remove('selected'));
                                this.classList.add('selected');
                            });
                            modalSizeButtons.appendChild(sizeBtn);
                        });
                    }

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
                document.getElementById('modalAddToBag').addEventListener('click', function() {
                    const selectedSize = document.querySelector('.modal-size-btn.selected');
                    const productName = document.getElementById('modalProductName').textContent;

                    if (!selectedSize) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'اختر المقاس',
                            text: 'الرجاء اختيار المقاس أولاً',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#1a1a1a'
                        });
                        return;
                    }

                    // Add visual feedback
                    this.innerHTML =
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> تمت الإضافة';
                    this.style.background = '#4CAF50';

                    setTimeout(() => {
                        this.innerHTML =
                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg> إضافة إلى حقيبة التسوق';
                        this.style.background = '#1a1a1a';
                    }, 2000);

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'تمت الإضافة!',
                        text: `تم إضافة ${productName} للسلة بنجاح`,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Close modal after adding
                    setTimeout(() => {
                        closeProductModal();
                    }, 1500);
                });
            });
        </script>

@endpush
