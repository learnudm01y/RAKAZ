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
            max-height: 81vh;
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
            position: relative;
        }

        /* Sale Badge - Positioned on far left/right */
        .modal-sale-badge-container {
            position: absolute;
            top: 20px;
            z-index: 100;
        }

        [dir="rtl"] .modal-sale-badge-container {
            left: 20px;
            right: auto;
        }

        [dir="ltr"] .modal-sale-badge-container {
            left: 20px;
            right: auto;
        }

        .modal-sale-badge {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 4px;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
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
            position: relative;
        }

        .modal-thumbnail:hover,
        .modal-thumbnail.active {
            border-color: #1a1a1a;
        }

        .modal-thumbnail-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(26, 26, 26, 0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-thumbnail-overlay:hover {
            background: rgba(26, 26, 26, 0.95);
        }

        .modal-thumbnail-overlay-text {
            color: white;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .modal-thumbnails-extra {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #e5e5e5;
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

        /* Modal Color Images Styles */
        .modal-color-images-section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .modal-color-header {
            margin-bottom: 12px;
        }

        .modal-color-header .modal-option-label {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        .modal-selected-color-name {
            font-weight: 600;
            color: #000;
        }

        .modal-color-images-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .modal-color-image-thumb {
            width: 60px;
            height: 60px;
            border: 2px solid #e5e7eb;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #f9fafb;
        }

        .modal-color-image-thumb:hover {
            border-color: #9ca3af;
        }

        .modal-color-image-thumb.active {
            border-color: #000;
            border-width: 2px;
        }

        .modal-color-image-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
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

        /* Hide hover content and gallery only on very small mobile screens */
        @media (max-width: 640px) {
            .product-hover-content {
                display: none !important;
            }

            .product-gallery-section {
                display: none !important;
            }

            /* Force product brand to always show on mobile */
            .product-brand {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
                position: relative !important;
            }
        }

        /* ============================================
           Mobile Grid Layout - 2 Products Per Row (COMPACT & EQUAL HEIGHT)
           ============================================ */
        @media (max-width: 768px) {
            /* Override all grid views to show 2 products per row on mobile */
            .products-grid,
            .products-grid[data-view="grid-2"],
            .products-grid[data-view="grid-3"],
            .products-grid[data-view="grid-4"] {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                grid-auto-rows: 1fr !important; /* جميع الصفوف بنفس الارتفاع */
                gap: 6px !important;
                padding: 0 6px !important;
                margin: 0 0 15px 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                align-items: stretch !important; /* تمديد العناصر لنفس الارتفاع */
            }

            /* Product Card - Equal Height Container */
            .product-card {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                height: 100% !important; /* ارتفاع كامل */
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
                display: flex !important;
                flex-direction: column !important;
                background: #fff !important;
                border-radius: 6px !important;
                box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
            }

            /* Image Wrapper - Fixed Height Part */
            .product-image-wrapper,
            .product-card .product-image-wrapper {
                position: relative !important;
                width: 100% !important;
                padding-top: 125% !important; /* نسبة 5:4 */
                margin: 0 !important;
                overflow: hidden !important;
                border-radius: 6px 6px 0 0 !important;
                background: #f5f5f5 !important;
                box-sizing: border-box !important;
                flex-shrink: 0 !important; /* لا يتقلص */
            }

            /* Images - Cover the container */
            .product-image-primary,
            .product-image-secondary,
            .product-card .product-image-primary,
            .product-card .product-image-secondary {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
            }

            /* Product Info Container - Flexible Height */
            .product-info,
            .product-card .product-info {
                width: 100% !important;
                padding: 5px 4px 6px 4px !important;
                margin: 0 !important;
                box-sizing: border-box !important;
                display: flex !important;
                flex-direction: column !important;
                gap: 2px !important;
                flex: 1 !important; /* يأخذ المساحة المتبقية */
                min-height: 0 !important; /* يسمح بالانكماش */
            }

            /* Brand - Always Visible - Fixed Size */
            .product-brand,
            .product-card .product-brand {
                font-size: 9px !important;
                line-height: 1.1 !important;
                margin: 0 !important;
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
                color: #888 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.3px !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                flex-shrink: 0 !important; /* لا يتقلص */
            }

            /* Product Name - Fixed Height */
            .product-name,
            .product-card .product-name {
                font-size: 11px !important;
                line-height: 1.25 !important;
                margin: 0 !important;
                height: 27.5px !important; /* ارتفاع ثابت لسطرين */
                min-height: 27.5px !important;
                max-height: 27.5px !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                display: -webkit-box !important;
                -webkit-line-clamp: 2 !important;
                -webkit-box-orient: vertical !important;
                color: #333 !important;
                font-weight: 500 !important;
                flex-shrink: 0 !important; /* لا يتقلص */
            }

            /* Price - Fixed Height */
            .product-price,
            .product-card .product-price {
                font-size: 12px !important;
                font-weight: 700 !important;
                margin: 0 !important;
                color: #000 !important;
                line-height: 1.3 !important;
                min-height: 16px !important;
                flex-shrink: 0 !important; /* لا يتقلص */
            }

            /* Add to Cart Button - Fixed Height */
            .add-to-cart-btn,
            .product-card .add-to-cart-btn {
                font-size: 10px !important;
                padding: 6px 8px !important;
                width: 100% !important;
                margin: auto 0 0 0 !important; /* يدفع للأسفل */
                border-radius: 4px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 4px !important;
                min-height: 30px !important;
                height: 30px !important;
                flex-shrink: 0 !important; /* لا يتقلص */
            }

            .add-to-cart-btn svg {
                width: 12px !important; /* أصغر */
                height: 12px !important;
            }

            /* Wishlist Button - Smaller */
            .wishlist-btn,
            .product-card .wishlist-btn {
                width: 26px !important; /* أصغر */
                height: 26px !important;
                top: 4px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                background: rgba(255, 255, 255, 0.9) !important;
                border-radius: 50% !important;
            }

            /* RTL: wishlist button on right */
            [dir="rtl"] .wishlist-btn,
            [dir="rtl"] .product-card .wishlist-btn {
                right: 4px !important;
                left: auto !important;
            }

            /* LTR: wishlist button on left */
            [dir="ltr"] .wishlist-btn,
            [dir="ltr"] .product-card .wishlist-btn {
                left: 4px !important;
                right: auto !important;
            }

            .wishlist-btn svg {
                width: 12px !important; /* أصغر */
                height: 12px !important;
            }

            /* Discount Badge - Smaller */
            .discount-badge-wrapper {
                top: 4px !important;
                transform: scale(0.65) !important; /* أصغر بكثير */
                transform-origin: top left !important;
            }

            /* RTL: discount badge on left */
            [dir="rtl"] .discount-badge-wrapper {
                left: 4px !important;
                right: auto !important;
            }

            /* LTR: discount badge on right */
            [dir="ltr"] .discount-badge-wrapper {
                right: 4px !important;
                left: auto !important;
            }

            .badge {
                font-size: 8px !important; /* أصغر */
                padding: 2px 5px !important;
                border-radius: 3px !important;
            }
        }

        /* Mobile View Toggle - One product per row */
        @media (max-width: 768px) {
            .products-grid.mobile-view-one {
                grid-template-columns: 1fr !important;
                gap: 16px !important;
            }

            .products-grid.mobile-view-one .product-card {
                max-width: 100% !important;
            }
        }

        /* ============================================
           Mobile Modal - FIXED Structure for RTL/LTR
           ============================================ */
        @media (max-width: 768px) {
            /* SweetAlert Must Appear Above Modal on Mobile */
            .swal2-container {
                z-index: 999999 !important;
            }

            .swal2-popup {
                z-index: 999999 !important;
            }

            /* All Toast Notifications Must Appear Above Modal */
            .swal2-toast {
                z-index: 999999 !important;
            }

            /* Any other notification systems */
            .toast,
            .notification,
            .alert-popup,
            [role="alert"] {
                z-index: 999999 !important;
            }

            /* Specific classes for modal-triggered alerts */
            .modal-wishlist-alert,
            .modal-wishlist-toast {
                z-index: 999999 !important;
            }

            /* Ensure SweetAlert overlay doesn't block interactions */
            body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) .swal2-container {
                z-index: 999999 !important;
            }

            /* Max Width for All Container Elements */
            section, article, main, header, footer, div {
                max-width: 105%;
            }

            /* Modal Container - Safe Padding */
            .product-modal {
                z-index: 99999 !important;
                padding: 10px !important;
                box-sizing: border-box !important;
            }

            .product-modal-overlay {
                background: rgba(0, 0, 0, 0.85) !important;
            }

            /* Modal Content - Centered with Safe Margins */
            .product-modal-content {
                width: calc(100vw - 20px) !important; /* 10px من كل جهة */
                max-width: calc(100vw - 20px) !important;
                max-height: 85vh !important;
                height: auto !important;
                border-radius: 12px !important;
                overflow-y: auto !important;
                overflow-x: hidden !important;
                -webkit-overflow-scrolling: touch !important;
                position: fixed !important; /* fixed بدلاً من absolute */
                top: 50% !important;
                left: 50% !important;
                right: auto !important; /* إلغاء right */
                transform: translate(-50%, -50%) !important;
                box-sizing: border-box !important;
                margin: 0 !important;
            }

            /* RTL Support */
            [dir="rtl"] .product-modal-content {
                direction: rtl !important;
            }

            [dir="ltr"] .product-modal-content {
                direction: ltr !important;
            }

            /* Close Button - Top Right Corner - NO SPACING */
            .product-modal-close {
                position: absolute !important;
                width: 32px !important;
                height: 32px !important;
                top: 0 !important; /* بدون أي تباعد */
                z-index: 100 !important;
                background: white !important;
                border-radius: 0 12px 0 8px !important; /* فقط الزاوية اليمنى السفلية */
                box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
            }

            [dir="rtl"] .product-modal-close {
                left: auto !important;
                right: 0 !important; /* ملاصق للحافة */
            }

            [dir="ltr"] .product-modal-close {
                right: 0 !important; /* ملاصق للحافة */
                left: auto !important;
            }

            .product-modal-close svg {
                width: 16px !important;
                height: 16px !important;
            }

            /* Product Details Container - No Top Padding */
            .modal-product-details {
                display: block !important; /* block بدلاً من grid */
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 14px 14px 14px !important; /* لا padding من الأعلى أبداً */
                margin: 0 !important;
                box-sizing: border-box !important;
                overflow: visible !important;
                position: relative !important;
            }

            /* Ensure all child elements respect container */
            .modal-product-details > *:not(.modal-sale-badge-container) {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /* Sale Badge - Vertical Top Left - NO SPACING */
            .modal-sale-badge-container {
                position: absolute !important;
                top: 0 !important; /* بدون أي تباعد */
                z-index: 50 !important;
                width: auto !important;
                max-width: none !important;
                writing-mode: vertical-rl !important; /* طولي */
                text-orientation: mixed !important;
            }

            /* RTL: Sale badge on left, close button on right */
            [dir="rtl"] .modal-sale-badge-container {
                left: 0 !important;
                right: auto !important;
            }

            /* LTR: Sale badge on left side (same as RTL for consistency) */
            [dir="ltr"] .modal-sale-badge-container {
                left: 0 !important;
                right: auto !important;
            }

            .modal-sale-badge {
                font-size: 9px !important;
                padding: 13px 5px !important;
                display: inline-block !important;
                white-space: nowrap !important;
                background: #dc2626 !important;
                color: white !important;
                border-radius: 0 0 8px 8px !important; /* فقط من الأسفل */
                font-weight: 600 !important;
                letter-spacing: 0.3px !important;
                margin-left: 7px;
            }
            .modal-product-gallery {
                position: relative !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 0 12px 0 !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
            }

            .modal-main-image-wrapper {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 0 8px 0 !important;
                aspect-ratio: auto !important; /* تلقائي للحفاظ على النسبة الأصلية للصورة */
                border-radius: 8px !important; /* border radius عادي */
                overflow: hidden !important;
                box-sizing: border-box !important;
            }

            .modal-main-product-image {
                object-fit: contain !important; /* contain بدلاً من cover لعرض الصورة كاملة */
                width: 100% !important;
                height: auto !important;
                /* إزالة جميع الفلاتر للجوال فقط */
                filter: none !important;
                image-rendering: auto !important;
                -webkit-font-smoothing: subpixel-antialiased !important;
            }

            .modal-main-product-image {
                object-fit: cover !important;
            }

            .modal-thumbnail img {
                /* إزالة الفلاتر من الصور المصغرة أيضاً */
                filter: none !important;
                image-rendering: auto !important;
            }

            /* Image Navigation Buttons - Smaller */
            .modal-image-nav {
                width: 28px !important;
                height: 28px !important;
            }

            .modal-image-nav svg {
                width: 14px !important;
                height: 14px !important;
            }

            .modal-thumbnail {
                width: 50px !important;
                height: 50px !important;
                min-width: 50px !important;
                border-radius: 6px !important;
            }

            .modal-thumbnails-wrapper {
                display: flex !important;
                width: 100% !important;
                max-width: 100% !important;
                gap: 6px !important;
                padding: 0 !important;
                margin: 0 !important;
                box-sizing: border-box !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
            }

            /* Product Info Section - No Gap */
            .modal-product-info-section {
                gap: 0px !important;
            }

            /* Product Header - Full Width */
            .modal-product-header {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 0 10px 0 !important;
                margin: 0 !important;
                box-sizing: border-box !important;
            }

            /* Season Badge - Vertical Next to Sale Badge - NO SPACING */
            .modal-product-meta-top {
                position: absolute !important;
                top: 0 !important; /* بدون أي تباعد */
                left: 30px !important; /* بجانب sale badge */
                z-index: 50 !important;
                writing-mode: vertical-rl !important; /* طولي */
                text-orientation: mixed !important;
            }

            .modal-product-meta-top .modal-season-badge {
                font-size: 9px !important;
                padding: 8px 5px !important; /* padding للوضع الطولي */
                display: inline-block !important;
                background: #1a1a1a !important;
                color: white !important;
                border-radius: 0 0 8px 8px !important; /* فقط من الأسفل */
                font-weight: 500 !important;
                white-space: nowrap !important;
            }

            .modal-product-title {
                width: 100% !important;
                max-width: 100% !important;
                font-size: 10px !important;
                margin: 0 0 3px 0 !important;
                box-sizing: border-box !important;
                padding-top: 13px;
            }

            .modal-product-subtitle {
                width: 100% !important;
                max-width: 100% !important;
                font-size: 14px !important;
                line-height: 1.3 !important;
                margin: 0 0 6px 0 !important;
                box-sizing: border-box !important;
            }

            /* Pricing - Full Width */
            .modal-product-pricing {
                width: 100% !important;
                max-width: 100% !important;
                display: flex !important;
                gap: 6px !important;
                align-items: center !important;
                margin: 0 0 10px 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }

            .modal-current-price {
                font-size: 18px !important;
            }

            .modal-original-price {
                font-size: 13px !important;
            }

            /* Payment Options - Full Width */
            .modal-payment-options {
                width: 100% !important;
                max-width: 100% !important;
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 4px !important;
                padding: 6px 0 !important;
                margin: 0 !important;
                box-sizing: border-box !important;
            }

            .modal-payment-option {
                font-size: 9px !important;
                padding: 3px 6px !important;
            }

            .modal-payment-option .payment-icon {
                height: 12px !important;
            }

            /* Delivery Info - Full Width */
            .modal-delivery-info {
                width: 100% !important;
                max-width: 100% !important;
                padding: 8px !important;
                font-size: 10px !important;
                border-radius: 6px !important;
                margin: 0 0 10px 0 !important;
                box-sizing: border-box !important;
            }

            .modal-delivery-info svg {
                width: 12px !important;
                height: 12px !important;
            }

            /* Color Images Section - Full Width */
            .modal-color-images-section {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 0 10px 0 !important;
                margin: 0 !important;
                box-sizing: border-box !important;
            }

            .modal-color-image-thumb {
                width: 42px !important;
                height: 42px !important;
                border-radius: 6px !important;
            }

            /* Product Options - Compact Padding */
            .modal-product-options {
                padding: 9px 0 !important;
            }

            .modal-option-label {
                font-size: 12px !important;
                margin: 0 0 6px 0 !important;
                font-weight: 600 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }

            /* Size Selection - Full Width */
            .modal-sizes-wrapper {
                width: 100% !important;
                max-width: 100% !important;
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 6px !important;
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }

            .modal-size-btn {
                padding: 7px 12px !important;
                font-size: 11px !important;
                min-width: 45px !important;
            }

            /* Stock Notice - Smaller */
            .modal-stock-notice {
                padding: 8px !important;
                font-size: 10px !important;
                border-radius: 6px !important;
                margin: 8px 0 !important;
                box-sizing: border-box !important;
            }

            /* Action Buttons - Full Width Edge to Edge - Sticky at Bottom */
            .modal-product-actions {
                gap: 8px !important;
                padding: 12px 14px !important; /* padding كامل للتصميم الجيد */
                flex-direction: row !important;
                position: sticky !important; /* sticky للبقاء في أسفل المودال */
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                width: calc(100% + 28px) !important; /* عرض أكبر لتعويض الـ margin */
                background: white !important;
                z-index: 100 !important;
                margin: 0 -14px !important; /* تعويض سالب للإلتصاق بحدود المودال */
                box-shadow: 0 -3px 15px rgba(0,0,0,0.12) !important;
                border-radius: 0 0 12px 12px !important;
                box-sizing: border-box !important;
            }

            .modal-btn-add-to-bag {
                padding: 10px 12px !important;
                font-size: 12px !important;
                flex: 1 !important;
                height: 42px !important;
            }

            .modal-btn-add-to-bag svg {
                width: 16px !important;
                height: 16px !important;
            }

            .modal-btn-add-to-wishlist {
                width: 42px !important;
                height: 42px !important;
                flex-shrink: 0 !important;
            }

            .modal-btn-add-to-wishlist svg {
                width: 18px !important;
                height: 18px !important;
            }

            /* Tabs Section - Compact */
            .modal-product-tabs {
                padding: 10px 0 70px 0 !important; /* padding من الأعلى والأسفل فقط */
                border-top: 1px solid #eee !important;
                box-sizing: border-box !important;
            }

            .modal-tabs-header {
                width: 100% !important;
                max-width: 100% !important;
                display: flex !important;
                gap: 6px !important;
                overflow-x: auto !important;
                white-space: nowrap !important;
                -webkit-overflow-scrolling: touch !important;
                scrollbar-width: none !important;
                margin: 0 0 12px 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }

            .modal-tabs-header::-webkit-scrollbar {
                display: none !important;
            }

            .modal-tab-btn {
                padding: 7px 10px !important;
                font-size: 11px !important;
                flex-shrink: 0 !important;
            }

            .modal-tab-panel {
                font-size: 11px !important;
                line-height: 1.6 !important;
                padding: 8px 0 !important;
                margin: 0 !important;
                box-sizing: border-box !important;
            }

            .modal-tab-panel ul {
                padding-left: 18px !important;
            }

            .modal-tab-panel li {
                margin-bottom: 4px !important;
            }
        }

        /* Tablet Optimization (769px - 1024px) */
        @media (min-width: 769px) and (max-width: 1024px) {
            .product-modal-content {
                width: 95% !important;
                max-width: 900px !important;
                max-height: 85vh !important;
                border-radius: 12px !important;
            }

            .modal-product-details {
                padding: 20px !important;
                gap: 20px !important;
            }

            .modal-main-image-wrapper {
                aspect-ratio: 3/4 !important;
            }

            .modal-product-actions {
                flex-direction: row !important;
                gap: 12px !important;
            }

            .modal-btn-add-to-bag {
                flex: 1 !important;
            }

            .modal-btn-add-to-wishlist {
                width: 50px !important;
            }
        }

        @media (max-width: 480px) {
            .modal-product-details {
                padding: 10px !important;
                gap: 12px !important;
            }

            .modal-product-subtitle {
                font-size: 14px !important;
            }

            .modal-current-price {
                font-size: 18px !important;
            }

            .modal-thumbnail {
                width: 50px !important;
                height: 50px !important;
                min-width: 50px !important;
            }

            .modal-color-image-thumb {
                width: 40px !important;
                height: 40px !important;
            }

            .modal-size-btn {
                padding: 7px 12px !important;
                font-size: 11px !important;
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
                    <!-- Sale Badge - Absolute positioned on far left -->
                    <div class="modal-sale-badge-container" id="modalSaleBadgeContainer" style="display: none;">
                        <span class="modal-sale-badge" id="modalSaleBadge">{{ app()->getLocale() == 'ar' ? 'عرض خاص' : 'On Sale' }}</span>
                    </div>

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
                            <div class="modal-thumbnails-extra" id="modalThumbnailsExtra" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="modal-product-info-section">
                        <!-- Season Badge -->
                        <div class="modal-product-meta-top">
                            <span class="modal-season-badge" id="modalSeasonBadge" style="display: none;">{{ app()->getLocale() == 'ar' ? 'الموسم الجديد' : 'New season' }}</span>
                        </div>

                        <!-- Brand & Title -->
                        <div class="modal-product-header">
                            <h1 class="modal-product-title" id="modalBrand">{{ app()->getLocale() == 'ar' ? 'ركاز' : 'Rakaz' }}</h1>
                            <h2 class="modal-product-subtitle" id="modalProductName"></h2>
                        </div>

                        <!-- Price -->
                        <div class="modal-product-price-section">
                            <span class="modal-original-price" id="modalOriginalPrice" style="text-decoration: line-through; color: #999; margin-left: 10px; display: none;"></span>
                            <span class="modal-current-price" id="modalPrice"></span>
                        </div>

                        <!-- Payment Options -->
                        <div class="modal-payment-options">
                            <div class="modal-payment-option">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal"
                                    class="payment-icon">
                            </div>
                            <div class="modal-payment-option">
                                <span class="payment-text">{{ app()->getLocale() == 'ar' ? 'تمرا' : 'Tamara' }}</span>
                            </div>
                            <div class="modal-payment-option">
                                <span class="payment-text">tabby</span>
                            </div>
                            <div class="modal-payment-option">
                                <span class="payment-text">{{ app()->getLocale() == 'ar' ? 'تتوفر أقساط بدون فوائد' : 'Interest-free installments available' }}</span>
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
                            <span>{{ app()->getLocale() == 'ar' ? 'توصيل خلال ساعتين في نفس اليوم إلى أبي ظبي' : 'Same-day delivery in Abu Dhabi (within 2 hours)' }}</span>
                        </div>

                        <!-- Color Images Selection (Dynamic) -->
                        <div class="modal-color-images-section" id="modalColorImagesSection" style="display: none;">
                            <div class="modal-color-header">
                                <label class="modal-option-label">
                                    {{ app()->getLocale() == 'ar' ? 'اللون:' : 'Color:' }}
                                    <span class="modal-selected-color-name" id="modalSelectedColorName"></span>
                                </label>
                            </div>
                            <div class="modal-color-images-row" id="modalColorImagesRow">
                                <!-- Color image thumbnails will be dynamically inserted here -->
                            </div>
                        </div>

                        <!-- Size Selection -->
                        <div class="modal-product-options">
                            <div class="modal-option-group">
                                <div class="modal-size-header">
                                    <label class="modal-option-label" style="font-size: 16px; font-weight: bold;">{{ app()->getLocale() == 'ar' ? 'اختيار المقاس' : 'Select size' }}</label>
                                    <a href="#" class="modal-size-guide-link">{{ app()->getLocale() == 'ar' ? 'جدول المقاسات' : 'Size guide' }}</a>
                                </div>
                                <!-- Available Sizes Display - Hidden -->
                                <div id="modalAvailableSizes" style="display: none !important;">
                                    <div style="font-size: 14px; font-weight: 700; color: #28a745; margin-bottom: 10px;">
                                        ✅ <span id="modalSizesTitle">{{ app()->getLocale() == 'ar' ? 'المقاسات المتوفرة:' : 'Available sizes:' }}</span> <span id="modalSizesCount"></span>
                                    </div>
                                    <div id="modalSizesList" style="font-size: 16px; color: #212529; font-weight: 600; line-height: 1.8;"></div>
                                </div>
                                <!-- Size Dropdown -->
                                <select class="custom-select" id="modalSizeSelect" style="width: 100%; font-size: 16px; font-weight: 600;">
                                    <option value="">{{ app()->getLocale() == 'ar' ? 'اختر المقاس' : 'Choose size' }}</option>
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
                            <span>{{ app()->getLocale() == 'ar' ? 'التحقق من الإمارات العربية المتحدة - التوصيل متوفر' : 'UAE delivery check — delivery available' }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="modal-product-actions">
                            <button class="modal-btn-add-to-bag" id="modalAddToBag">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                                {{ app()->getLocale() == 'ar' ? 'إضافة إلى حقيبة التسوق' : 'Add to bag' }}
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
                    <span class="ar-text">الفلاتر</span>
                    <span class="en-text">Filters</span>
                </button>

                <!-- Mobile View Toggle (Only visible on mobile) -->
                <div class="mobile-view-toggle">
                    <button class="mobile-view-btn active" data-mobile-view="two">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="8" height="18"></rect>
                            <rect x="13" y="3" width="8" height="18"></rect>
                        </svg>
                        <span class="ar-text">منتجين</span>
                        <span class="en-text">Two products</span>
                    </button>
                    <button class="mobile-view-btn" data-mobile-view="one">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18"></rect>
                        </svg>
                        <span class="ar-text">منتج واحد</span>
                        <span class="en-text">One product</span>
                    </button>
                </div>

                <!-- Sidebar Filters -->
                @include('frontend.partials.shop-sidebar-skeleton')

                <!-- Products Grid -->
                <section class="shop-content">
                    <div class="shop-header">
                        <div class="shop-results">
                            <h1 id="shopTitle">
                                @if(isset($category))
                                    {{ app()->getLocale() == 'ar' ? $category->name['ar'] : $category->name['en'] }}
                                @else
                                    {{ app()->getLocale() == 'ar' ? 'جميع المنتجات' : 'All Products' }}
                                @endif
                            </h1>
                            <p class="results-count">
                                @php
                                    $productCount = $products instanceof \Illuminate\Pagination\LengthAwarePaginator ? $products->total() : $products->count();
                                    $currentPageNum = $products instanceof \Illuminate\Pagination\LengthAwarePaginator ? $products->currentPage() : 1;
                                    $lastPageNum = $products instanceof \Illuminate\Pagination\LengthAwarePaginator ? $products->lastPage() : 1;
                                @endphp
                                <span id="product-count">{{ $productCount }}</span>
                                {{ app()->getLocale() == 'ar' ? 'منتج' : 'Product' }}<span id="product-plural">{{ $productCount != 1 ? (app()->getLocale() == 'ar' ? '' : 's') : '' }}</span><span id="page-info">@if($currentPageNum > 1) ({{ app()->getLocale() == 'ar' ? 'صفحة' : 'Page' }} {{ $currentPageNum }} {{ app()->getLocale() == 'ar' ? 'من' : 'of' }} {{ $lastPageNum }})@endif</span>
                            </p>
                        </div>
                        <div class="shop-controls">
                            <button class="clear-filters-btn" id="clearFiltersBtn" style="display: none;" title="{{ app()->getLocale() == 'ar' ? 'مسح الفلاتر' : 'Clear Filters' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                    <line x1="16" y1="7" x2="19" y2="10" stroke-width="2.5"></line>
                                    <line x1="19" y1="7" x2="16" y2="10" stroke-width="2.5"></line>
                                </svg>
                            </button>
                            <select class="sort-select custom-select">
                                <option value="featured">{{ app()->getLocale() == 'ar' ? 'مميز' : 'Featured' }}</option>
                                <option value="price-low">{{ app()->getLocale() == 'ar' ? 'السعر: من الأقل للأعلى' : 'Price: low to high' }}</option>
                                <option value="price-high">{{ app()->getLocale() == 'ar' ? 'السعر: من الأعلى للأقل' : 'Price: high to low' }}</option>
                                <option value="newest">{{ app()->getLocale() == 'ar' ? 'الأحدث' : 'Newest' }}</option>
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
                        @include('frontend.partials.shop-products-grid')
                    </div>

                    <!-- Pagination - Show actual pagination if page > 1, otherwise show skeleton -->
                    @if(isset($isPaginated) && $isPaginated && $products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        @include('frontend.partials.shop-pagination')
                    @else
                        @include('frontend.partials.shop-pagination-skeleton')
                    @endif
                </section>
            </div>
        </main>
@endsection
@push('scripts')
        <script>
            // Define global variable before loading other scripts
            window.isArabic = '{{ app()->getLocale() }}' === 'ar';
        </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/pica@9.0.1/dist/pica.min.js"></script>
        <script>
            const isArabic = '{{ app()->getLocale() }}' === 'ar';

            // Load More Categories with AJAX
            document.addEventListener('DOMContentLoaded', function() {
                const showMoreBtn = document.getElementById('showMoreCategories');
                if (showMoreBtn) {
                    showMoreBtn.addEventListener('click', function() {
                        const btn = this;
                        const loaded = parseInt(btn.dataset.loaded);
                        const total = parseInt(btn.dataset.total);

                        // Prevent multiple clicks
                        if (btn.classList.contains('loading')) return;

                        btn.classList.add('loading');
                        btn.disabled = true;

                        fetch('{{ route("shop.loadMoreCategories") }}?skip=' + loaded + '&take=10')
                            .then(response => response.json())
                            .then(data => {
                                const categoriesList = document.getElementById('categoriesCheckboxList');
                                categoriesList.insertAdjacentHTML('beforeend', data.html);

                                const newLoaded = loaded + 10;
                                btn.dataset.loaded = newLoaded;

                                if (!data.hasMore || newLoaded >= total) {
                                    btn.remove();
                                } else {
                                    btn.classList.remove('loading');
                                    btn.disabled = false;
                                }

                                // Add event listeners to new checkboxes
                                attachCategoryCheckboxListeners();
                            })
                            .catch(error => {
                                console.error('Error loading categories:', error);
                                btn.classList.remove('loading');
                                btn.disabled = false;
                            });
                    });
                }

                // Attach checkbox listeners
                attachCategoryCheckboxListeners();
            });

            function attachCategoryCheckboxListeners() {
                // Restore categories from URL for newly loaded checkboxes
                const urlParams = new URLSearchParams(window.location.search);
                const categoriesParam = urlParams.getAll('categories[]');

                document.querySelectorAll('.category-checkbox-item input[type="checkbox"]').forEach(checkbox => {
                    if (!checkbox.dataset.listenerAttached) {
                        // Restore checked state if this category is in URL
                        if (categoriesParam.includes(checkbox.value)) {
                            checkbox.checked = true;
                        }

                        // Add change listener to update title
                        checkbox.addEventListener('change', function() {
                            updateShopTitle();
                            // Save to localStorage
                            saveCategorySelections();
                        });

                        checkbox.dataset.listenerAttached = 'true';
                    }
                });

                // Update title after restoring checkboxes
                updateShopTitle();
            }

            // Load More Brands with AJAX
            document.addEventListener('DOMContentLoaded', function() {
                const showMoreBrandsBtn = document.getElementById('showMoreBrands');
                if (showMoreBrandsBtn) {
                    showMoreBrandsBtn.addEventListener('click', function() {
                        const btn = this;
                        const loaded = parseInt(btn.dataset.loaded);
                        const total = parseInt(btn.dataset.total);

                        if (btn.classList.contains('loading')) return;

                        btn.classList.add('loading');
                        btn.disabled = true;

                        fetch('{{ route("shop.loadMoreBrands") }}?skip=' + loaded + '&take=10')
                            .then(response => response.json())
                            .then(data => {
                                const brandsList = document.getElementById('brandsCheckboxList');
                                brandsList.insertAdjacentHTML('beforeend', data.html);

                                const newLoaded = loaded + 10;
                                btn.dataset.loaded = newLoaded;

                                if (!data.hasMore || newLoaded >= total) {
                                    btn.remove();
                                } else {
                                    btn.classList.remove('loading');
                                    btn.disabled = false;
                                }

                                attachBrandCheckboxListeners();
                            })
                            .catch(error => {
                                console.error('Error loading brands:', error);
                                btn.classList.remove('loading');
                                btn.disabled = false;
                            });
                    });
                }

                attachBrandCheckboxListeners();
            });

            function attachBrandCheckboxListeners() {
                const urlParams = new URLSearchParams(window.location.search);
                const brandsParam = urlParams.getAll('brands[]');

                document.querySelectorAll('.brands-checkbox-list input[type="checkbox"]').forEach(checkbox => {
                    if (!checkbox.dataset.listenerAttached) {
                        if (brandsParam.includes(checkbox.value)) {
                            checkbox.checked = true;
                        }

                        checkbox.addEventListener('change', function() {
                            updateShopTitle();
                            saveBrandSelections();
                        });

                        checkbox.dataset.listenerAttached = 'true';
                    }
                });

                updateShopTitle();
            }

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
                    url.searchParams.delete('categories[]');
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

                    // Get selected categories
                    const selectedCategories = [];
                    document.querySelectorAll('input[name="category"]:checked').forEach(input => {
                        selectedCategories.push(input.value);
                    });
                    selectedCategories.forEach(category => {
                        url.searchParams.append('categories[]', category);
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

            // Function to save category selections to localStorage
            function saveCategorySelections() {
                const selectedCategories = [];
                const categoryNames = {};

                document.querySelectorAll('input[name="category"]:checked').forEach(cb => {
                    selectedCategories.push(cb.value);
                    const label = cb.closest('.category-checkbox-item');
                    if (label) {
                        const categoryText = label.querySelector('.category-text');
                        if (categoryText) {
                            categoryNames[cb.value] = categoryText.textContent.trim();
                        }
                    }
                });

                if (selectedCategories.length > 0) {
                    localStorage.setItem('shop_selected_categories', JSON.stringify(selectedCategories));
                    localStorage.setItem('shop_category_names', JSON.stringify(categoryNames));
                } else {
                    localStorage.removeItem('shop_selected_categories');
                    localStorage.removeItem('shop_category_names');
                }
            }

            // Function to save brand selections to localStorage
            function saveBrandSelections() {
                const selectedBrands = [];
                const brandNames = {};

                document.querySelectorAll('input[name="brand"]:checked').forEach(cb => {
                    selectedBrands.push(cb.value);
                    const label = cb.closest('.category-checkbox-item');
                    if (label) {
                        const brandText = label.querySelector('.category-text');
                        if (brandText) {
                            brandNames[cb.value] = brandText.textContent.trim();
                        }
                    }
                });

                if (selectedBrands.length > 0) {
                    localStorage.setItem('shop_selected_brands', JSON.stringify(selectedBrands));
                    localStorage.setItem('shop_brand_names', JSON.stringify(brandNames));
                } else {
                    localStorage.removeItem('shop_selected_brands');
                    localStorage.removeItem('shop_brand_names');
                }
            }

            // Function to restore from localStorage if URL doesn't have categories
            function restoreFromLocalStorage() {
                if (!urlParams.has('categories[]')) {
                    const savedCategories = localStorage.getItem('shop_selected_categories');
                    if (savedCategories) {
                        try {
                            const categories = JSON.parse(savedCategories);
                            categories.forEach(categoryId => {
                                const checkbox = document.querySelector(`input[name="category"][value="${categoryId}"]`);
                                if (checkbox) checkbox.checked = true;
                            });
                        } catch (e) {
                            console.error('Error parsing saved categories:', e);
                        }
                    }
                }
            }

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

            // Restore brands
            const brandsParam = urlParams.getAll('brands[]');
            brandsParam.forEach(brandId => {
                const checkbox = document.querySelector(`input[name="brand"][value="${brandId}"]`);
                if (checkbox) checkbox.checked = true;
            });

            // Restore categories with delay to ensure DOM is ready
            const categoriesParam = urlParams.getAll('categories[]');
            setTimeout(function() {
                categoriesParam.forEach(categoryId => {
                    const checkbox = document.querySelector(`input[name="category"][value="${categoryId}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });

                // If no categories in URL, try localStorage
                if (categoriesParam.length === 0) {
                    restoreFromLocalStorage();
                }

                // Update title after restoration
                updateShopTitle();
                saveCategorySelections();
            }, 100);

            // Clear Filters Button Functionality
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            const shopTitle = document.getElementById('shopTitle');

            // Function to check if any filters are active
            function checkActiveFilters() {
                const hasFilters =
                    urlParams.has('categories[]') ||
                    urlParams.has('brands[]') ||
                    urlParams.has('sizes[]') ||
                    urlParams.has('shoe_sizes[]') ||
                    urlParams.has('colors[]') ||
                    urlParams.has('min_price') ||
                    urlParams.has('max_price');

                if (clearFiltersBtn) {
                    clearFiltersBtn.style.display = hasFilters ? 'inline-flex' : 'none';
                }

                return hasFilters;
            }

            // Function to update title based on selected category
            function updateShopTitle() {
                if (!shopTitle) return;

                const selectedCategories = [];
                document.querySelectorAll('input[name="category"]:checked').forEach(cb => {
                    const label = cb.closest('.category-checkbox-item');
                    if (label) {
                        const categoryText = label.querySelector('.category-text');
                        if (categoryText) {
                            selectedCategories.push(categoryText.textContent.trim());
                        }
                    }
                });

                // If no categories found in DOM, try localStorage
                if (selectedCategories.length === 0) {
                    const savedNames = localStorage.getItem('shop_category_names');
                    if (savedNames) {
                        try {
                            const categoryNames = JSON.parse(savedNames);
                            const urlCategories = urlParams.getAll('categories[]');
                            urlCategories.forEach(catId => {
                                if (categoryNames[catId]) {
                                    selectedCategories.push(categoryNames[catId]);
                                }
                            });
                        } catch (e) {
                            console.error('Error parsing category names:', e);
                        }
                    }
                }

                if (selectedCategories.length === 1) {
                    shopTitle.textContent = selectedCategories[0];
                } else if (selectedCategories.length > 1) {
                    shopTitle.textContent = isArabic ? 'تصنيفات متعددة' : 'Multiple Categories';
                } else {
                    shopTitle.textContent = isArabic ? 'جميع المنتجات' : 'All Products';
                }
            }

            // Update title and check filters on page load (will be called again after restoration)
            checkActiveFilters();

            // Clear all filters when button is clicked
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    localStorage.removeItem('shop_selected_categories');
                    localStorage.removeItem('shop_category_names');
                    localStorage.removeItem('shop_selected_brands');
                    localStorage.removeItem('shop_brand_names');
                    window.location.href = '{{ route("shop") }}';
                });
            }

            // Product Image Hover Effect & Wishlist - Combined DOMContentLoaded
            document.addEventListener('DOMContentLoaded', function() {
                console.log('🚀 Shop page initialized - POWER MODE ACTIVATED');

                const isArabic = document.documentElement.getAttribute('dir') === 'rtl' || '{{ app()->getLocale() }}' === 'ar';
                const t = (ar, en) => (isArabic ? ar : en);

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

                // Product Card Click - Navigate to product details page
                function initializeProductCardClick() {
                    document.querySelectorAll('.product-card').forEach(card => {
                        // Skip if already initialized
                        if (card.hasAttribute('data-click-initialized')) return;
                        card.setAttribute('data-click-initialized', 'true');

                        card.addEventListener('click', function(e) {
                            // Don't navigate if clicking on buttons, links, or interactive elements
                            const clickedElement = e.target;
                            const isButton = clickedElement.closest('button');
                            const isLink = clickedElement.closest('a');
                            const isInput = clickedElement.closest('input');
                            const isSizeOption = clickedElement.closest('.size-option');
                            const isColorOption = clickedElement.closest('.color-option');

                            if (isButton || isLink || isInput || isSizeOption || isColorOption) {
                                return; // Let the default behavior happen
                            }

                            // Navigate to product details page
                            const productUrl = this.dataset.productUrl;
                            if (productUrl) {
                                window.location.href = productUrl;
                            }
                        });
                    });
                }

                // Initialize on load
                initializeProductCardClick();

                // Make it globally accessible for pagination
                window.initializeProductCardClick = initializeProductCardClick;

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

                        const productId = button.dataset.productId;

                        if (!productId) {
                            console.error('❌ Product ID not found on button');
                            Swal.fire({
                                icon: 'error',
                                title: t('خطأ!', 'Error'),
                                text: t('لم يتم العثور على معرف المنتج', 'Product ID not found.'),
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

                            const data = await response.json();
                            console.log('📦 Response data:', data);

                            // إذا كان يتطلب تسجيل دخول
                            if (response.status === 401 || data.requiresAuth) {
                                console.log('⚠️ User not logged in - saving to localStorage');

                                // حفظ المنتج في localStorage
                                const STORAGE_KEY = 'rakaz_pending_wishlist';
                                let pendingWishlist = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');

                                // إضافة المنتج إذا لم يكن موجود
                                if (!pendingWishlist.includes(productId)) {
                                    pendingWishlist.push(productId);
                                    localStorage.setItem(STORAGE_KEY, JSON.stringify(pendingWishlist));
                                    console.log('💾 Saved to localStorage:', pendingWishlist);
                                }

                                Swal.fire({
                                    icon: 'warning',
                                    title: t('يجب تسجيل الدخول', 'Sign in required'),
                                    html: t(
                                        'يجب عليك تسجيز الدخول أولاً لإضافة منتجات إلى قائمة الأمنيات<br><strong>سيتم حفظ اختيارك تلقائياً بعد تسجيل الدخول</strong>',
                                        'Please sign in to add items to your wishlist.<br><strong>Your selection will be saved automatically after login</strong>'
                                    ),
                                    confirmButtonText: t('تسجيل الدخول الآن', 'Sign in now'),
                                    showCancelButton: true,
                                    cancelButtonText: t('إلغاء', 'Cancel'),
                                    confirmButtonColor: '#1a1a1a',
                                    cancelButtonColor: '#666',
                                    background: '#ffffff',
                                    color: '#000000',
                                    iconColor: '#ffc107',
                                    customClass: {
                                        container: 'modal-wishlist-alert'
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "{{ route('login') }}";
                                    }
                                });
                                button.disabled = false;
                                button.style.opacity = '1';
                                return;
                            }

                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            if (data.success) {
                                // Toggle active class
                                button.classList.toggle('active');

                                console.log('✅ Success! isAdded:', data.isAdded);

                                // Show beautiful success message
                                Swal.fire({
                                    icon: data.isAdded ? 'success' : 'error',
                                    title: data.isAdded
                                        ? t('تمت الإضافة بنجاح', 'Added successfully')
                                        : t('تم الحذف', 'Removed'),
                                    text: data.isAdded
                                        ? t('تم إضافة المنتج إلى قائمة الأمنيات', 'Added to wishlist')
                                        : t('تم حذف المنتج من قائمة الأمنيات', 'Removed from wishlist'),
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
                                throw new Error(isArabic ? (data.message || 'حدث خطأ غير معروف') : 'An unknown error occurred.');
                            }
                        } catch (error) {
                            console.error('❌ Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: t('خطأ!', 'Error'),
                                text: isArabic
                                    ? (error.message || 'حدث خطأ أثناء الإضافة للمفضلة. يرجى المحاولة مرة أخرى.')
                                    : 'Something went wrong while updating your wishlist. Please try again.',
                                confirmButtonText: t('حسناً', 'OK'),
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
                                title: t('يجب تسجيل الدخول', 'Sign in required'),
                                text: t('يجب عليك تسجيل الدخول أولاً', 'Please sign in first.'),
                                confirmButtonText: t('تسجيل الدخول', 'Sign in'),
                                showCancelButton: true,
                                cancelButtonText: t('إلغاء', 'Cancel'),
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
                                    title: data.isAdded
                                        ? t('تمت الإضافة بنجاح', 'Added successfully')
                                        : t('تم الحذف', 'Removed'),
                                    text: data.isAdded
                                        ? t('تم إضافة المنتج إلى قائمة الأمنيات', 'Added to wishlist')
                                        : t('تم حذف المنتج من قائمة الأمنيات', 'Removed from wishlist'),
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
                                title: t('خطأ!', 'Error'),
                                text: t('حدث خطأ. يرجى المحاولة مرة أخرى.', 'Something went wrong. Please try again.'),
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
                            productsGrid.classList.remove('mobile-view-two');
                            productsGrid.classList.add('mobile-view-one');
                        } else {
                            // عرض منتجين بجانب بعض
                            productsGrid.classList.remove('mobile-view-one');
                            productsGrid.classList.add('mobile-view-two');
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
                function initializeAddToCartModalListeners() {
                    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                        // Skip if already initialized
                        if (button.hasAttribute('data-modal-initialized')) return;
                        button.setAttribute('data-modal-initialized', 'true');

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
                }

                // Initialize on load
                initializeAddToCartModalListeners();

                // Make it globally accessible for pagination
                window.initializeAddToCartModalListeners = initializeAddToCartModalListeners;

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

                    // Handle price and sale badge
                    const modalSaleBadgeContainer = document.getElementById('modalSaleBadgeContainer');
                    const modalOriginalPrice = document.getElementById('modalOriginalPrice');

                    // Check if product is on sale
                    if (product.is_on_sale && product.sale_price) {
                        // Show sale badge container
                        if (modalSaleBadgeContainer) {
                            modalSaleBadgeContainer.style.display = 'block';
                        }

                        // Show original price (crossed out)
                        if (modalOriginalPrice) {
                            modalOriginalPrice.textContent = product.price;
                            modalOriginalPrice.style.display = 'inline';
                        }

                        // Show sale price as current price
                        modalPrice.textContent = product.sale_price;

                        console.log('✅ Sale detected:', {
                            original: product.price,
                            sale: product.sale_price,
                            is_on_sale: product.is_on_sale
                        });
                    } else {
                        // Hide sale badge container
                        if (modalSaleBadgeContainer) {
                            modalSaleBadgeContainer.style.display = 'none';
                        }

                        // Hide original price
                        if (modalOriginalPrice) {
                            modalOriginalPrice.style.display = 'none';
                        }

                        // Show regular price
                        modalPrice.textContent = product.price;

                        console.log('❌ No sale:', {
                            price: product.price,
                            is_on_sale: product.is_on_sale
                        });
                    }

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
                    const modalThumbnailsExtra = document.getElementById('modalThumbnailsExtra');
                    modalThumbnailsExtra.innerHTML = '';
                    modalThumbnailsExtra.style.display = 'none';

                    console.log('Total images for product:', allImages.length);

                    const maxVisibleThumbnails = 5;
                    const visibleImages = allImages.slice(0, maxVisibleThumbnails);
                    const extraImages = allImages.slice(maxVisibleThumbnails);
                    const hasExtraImages = extraImages.length > 0;

                    visibleImages.forEach((img, index) => {
                        const thumbnailWrapper = document.createElement('div');
                        thumbnailWrapper.style.position = 'relative';
                        thumbnailWrapper.style.display = 'inline-block';

                        const thumbnail = document.createElement('img');
                        thumbnail.src = img;
                        thumbnail.alt = `صورة ${index + 1}`;
                        thumbnail.className = `modal-thumbnail ${index === 0 ? 'active' : ''}`;
                        thumbnail.addEventListener('click', function() {
                            updateModalImage(index);
                        });

                        thumbnailWrapper.appendChild(thumbnail);

                        // Add overlay on last visible thumbnail if there are extra images
                        if (hasExtraImages && index === visibleImages.length - 1) {
                            const overlay = document.createElement('div');
                            overlay.className = 'modal-thumbnail-overlay';
                            overlay.innerHTML = `<span class="modal-thumbnail-overlay-text">+${extraImages.length}</span>`;
                            overlay.addEventListener('click', function(e) {
                                e.stopPropagation();
                                loadExtraThumbnails(product.id, extraImages, allImages.length);
                            });
                            thumbnailWrapper.appendChild(overlay);
                        }

                        modalThumbnails.appendChild(thumbnailWrapper);
                    });

                    // Function to load extra thumbnails
                    function loadExtraThumbnails(productId, extraImages, totalImages) {
                        // Remove overlay
                        const overlay = document.querySelector('.modal-thumbnail-overlay');
                        if (overlay) overlay.remove();

                        // Show extra thumbnails container
                        modalThumbnailsExtra.style.display = 'flex';

                        // Add extra thumbnails
                        extraImages.forEach((img, index) => {
                            const actualIndex = maxVisibleThumbnails + index;
                            const thumbnail = document.createElement('img');
                            thumbnail.src = img;
                            thumbnail.alt = `صورة ${actualIndex + 1}`;
                            thumbnail.className = 'modal-thumbnail';
                            thumbnail.addEventListener('click', function() {
                                updateModalImage(actualIndex);
                            });
                            modalThumbnailsExtra.appendChild(thumbnail);
                        });
                    }

                    // Handle Color Images Section
                    const colorImagesSection = document.getElementById('modalColorImagesSection');
                    const colorImagesRow = document.getElementById('modalColorImagesRow');
                    const selectedColorNameSpan = document.getElementById('modalSelectedColorName');

                    // Clear previous color images
                    colorImagesRow.innerHTML = '';

                    // Check if product has color images
                    if (product.colorImages && product.colorImages.length > 0) {
                        colorImagesSection.style.display = 'block';

                        // Set first color name
                        const firstColor = product.colorImages[0];
                        const isArabic = '{{ app()->getLocale() }}' === 'ar';
                        selectedColorNameSpan.textContent = isArabic ? firstColor.color_ar : firstColor.color_en;

                        // Create color image thumbnails
                        product.colorImages.forEach((colorImg, index) => {
                            const thumb = document.createElement('div');
                            thumb.className = 'modal-color-image-thumb' + (index === 0 ? ' active' : '');
                            thumb.dataset.colorId = colorImg.color_id;
                            thumb.dataset.colorAr = colorImg.color_ar;
                            thumb.dataset.colorEn = colorImg.color_en;
                            thumb.dataset.image = colorImg.image;

                            const img = document.createElement('img');
                            img.src = colorImg.image;
                            img.alt = isArabic ? colorImg.color_ar : colorImg.color_en;

                            thumb.appendChild(img);
                            colorImagesRow.appendChild(thumb);

                            // Click handler
                            thumb.addEventListener('click', function() {
                                // Update active state
                                document.querySelectorAll('.modal-color-image-thumb').forEach(t => t.classList.remove('active'));
                                this.classList.add('active');

                                // Update color name
                                const colorName = isArabic ? this.dataset.colorAr : this.dataset.colorEn;
                                selectedColorNameSpan.textContent = colorName;

                                // Update main image
                                if (this.dataset.image && modalMainImage) {
                                    modalMainImage.src = this.dataset.image;
                                }
                            });
                        });
                    } else {
                        colorImagesSection.style.display = 'none';
                    }

                    // Add sizes to dropdown
                    const modalSizeSelect = document.getElementById('modalSizeSelect');
                    const modalSizesList = document.getElementById('modalSizesList');
                    const modalAvailableSizes = document.getElementById('modalAvailableSizes');

                    // Debug: Log sizes
                    console.log('🔍 Product sizes:', product.sizes);
                    console.log('📊 Sizes length:', product.sizes ? product.sizes.length : 0);

                    // Clear existing options - use locale-aware text
                    const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
                    const chooseSizeText = isRtl ? 'اختر المقاس' : 'Choose size';
                    modalSizeSelect.innerHTML = '<option value="">' + chooseSizeText + '</option>';

                    if (product.sizes && product.sizes.length > 0) {
                        console.log('✅ Displaying ' + product.sizes.length + ' sizes');

                        // Show size selection section
                        const modalProductOptions = modalSizeSelect.closest('.modal-product-options');
                        if (modalProductOptions) {
                            modalProductOptions.style.display = 'block';
                        }

                        // Update sizes count
                        document.getElementById('modalSizesCount').textContent = '(' + product.sizes.length + ')';

                        // Hide available sizes list (badges display)
                        modalAvailableSizes.style.display = 'none';

                        // Add sizes to dropdown
                        product.sizes.forEach(size => {
                            const option = document.createElement('option');
                            option.value = size;
                            option.textContent = size;
                            modalSizeSelect.appendChild(option);
                        });

                        console.log('✅ Added ' + product.sizes.length + ' options to dropdown');

                        // Destroy ALL old custom select wrappers if exists (more thorough cleanup)
                        const sizeOptionGroup = modalSizeSelect.closest('.modal-option-group');
                        if (sizeOptionGroup) {
                            sizeOptionGroup.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
                                if (wrapper.contains(modalSizeSelect)) {
                                    // Move the select out first
                                    sizeOptionGroup.insertBefore(modalSizeSelect, wrapper);
                                }
                                wrapper.remove();
                            });
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

                        // Hide size selection section completely when no sizes
                        const modalProductOptions = modalSizeSelect.closest('.modal-product-options');
                        if (modalProductOptions) {
                            modalProductOptions.style.display = 'none';
                        }
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

                    // Process modal images with Pica on mobile and tablet only
                    if (window.innerWidth <= 1024) {
                        setTimeout(() => {
                            processModalImages();
                        }, 100);
                    }
                }

                // Process modal images with Pica for mobile and tablet - DISABLED
                function processModalImages() {
                    // تم تعطيل Pica لأنها تسبب تكسير الصور
                    console.log('✅ Using original images without Pica processing');
                    return; // تعطيل كامل

                    /* DISABLED CODE:
                    if (typeof pica === 'undefined') {
                        console.log('Pica not available for modal images');
                        return;
                    }

                    const picaInstance = pica();
                    const modalImages = document.querySelectorAll('#modalMainImage, .modal-thumbnail img, .modal-color-image-thumb img');

                    modalImages.forEach(img => {
                        if (img.dataset.picaProcessed || img.dataset.picaProcessing) return;

                        img.dataset.picaProcessing = 'true';

                        function processImage() {
                            try {
                                const container = img.parentElement;
                                const containerRect = container.getBoundingClientRect();

                                if (!containerRect.width || !containerRect.height) {
                                    img.dataset.picaProcessed = 'true';
                                    delete img.dataset.picaProcessing;
                                    return;
                                }

                                // استخدام الحجم الفعلي المعروض على الشاشة
                                const displayedWidth = img.getBoundingClientRect().width;
                                const displayedHeight = img.getBoundingClientRect().height;

                                // استخدام DPR معتدل ومتوافق مع معظم الشاشات
                                const dpr = Math.min(window.devicePixelRatio || 1, 2);

                                let targetWidth = Math.round(displayedWidth * dpr);
                                let targetHeight = Math.round(displayedHeight * dpr);

                                // إذا كانت الصورة الأصلية أصغر، استخدم حجمها الأصلي
                                // إذا كانت أكبر، قم بعمل downscale للحجم المطلوب
                                if (targetWidth > img.naturalWidth) {
                                    targetWidth = img.naturalWidth;
                                    targetHeight = Math.round(targetWidth / (displayedWidth / displayedHeight));
                                }
                                if (targetHeight > img.naturalHeight) {
                                    targetHeight = img.naturalHeight;
                                    targetWidth = Math.round(targetHeight * (displayedWidth / displayedHeight));
                                }

                                // تخطي إذا كانت الصورة صغيرة جداً
                                if (img.naturalWidth < 300 || img.naturalHeight < 300) {
                                    img.dataset.picaProcessed = 'true';
                                    delete img.dataset.picaProcessing;
                                    return;
                                }

                                const canvas = document.createElement('canvas');
                                canvas.width = targetWidth;
                                canvas.height = targetHeight;

                                const tempImg = new Image();
                                tempImg.crossOrigin = 'anonymous';

                                tempImg.onload = function() {
                                    // استخدام أعلى جودة ممكنة للتنعيم الكامل
                                    picaInstance.resize(tempImg, canvas, {
                                        quality: 3,           // أعلى جودة خوارزمية (Lanczos)
                                        alpha: true,
                                        unsharpAmount: 0,     // بدون حدة للحصول على أقصى تنعيم
                                        unsharpRadius: 0,
                                        unsharpThreshold: 0
                                    }).then(result => {
                                        return picaInstance.toBlob(result, 'image/jpeg', 0.98);
                                    }).then(blob => {
                                        const url = URL.createObjectURL(blob);
                                        if (!img.dataset.originalSrc) {
                                            img.dataset.originalSrc = img.src;
                                        }
                                        img.src = url;
                                        img.dataset.picaProcessed = 'true';
                                        delete img.dataset.picaProcessing;

                                        if (img.dataset.picaBlobUrl) {
                                            setTimeout(() => URL.revokeObjectURL(img.dataset.picaBlobUrl), 100);
                                        }
                                        img.dataset.picaBlobUrl = url;
                                    }).catch(err => {
                                        console.warn('Pica modal processing failed:', err);
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
                                console.warn('Pica modal setup failed:', err);
                                img.dataset.picaProcessed = 'true';
                                delete img.dataset.picaProcessing;
                            }
                        }

                        if (!img.complete || img.naturalWidth === 0) {
                            img.addEventListener('load', processImage, { once: true });
                        } else {
                            processImage();
                        }
                    });
                    */ // END DISABLED CODE
                }

                function closeProductModal() {
                    const modal = document.getElementById('productModal');
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';

                    // Clean up Pica processed images
                    const modalImages = document.querySelectorAll('#modalMainImage, .modal-thumbnail img, .modal-color-image-thumb img');
                    modalImages.forEach(img => {
                        if (img.dataset.picaBlobUrl) {
                            URL.revokeObjectURL(img.dataset.picaBlobUrl);
                        }
                        delete img.dataset.picaProcessed;
                        delete img.dataset.picaProcessing;
                        delete img.dataset.picaBlobUrl;
                        if (img.dataset.originalSrc) {
                            img.src = img.dataset.originalSrc;
                            delete img.dataset.originalSrc;
                        }
                    });
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

                // Add to wishlist from modal
                document.querySelector('.modal-btn-add-to-wishlist').addEventListener('click', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (!currentProductId) {
                        console.error('❌ No product ID available');
                        Swal.fire({
                            icon: 'error',
                            title: t('خطأ!', 'Error'),
                            text: t('لم يتم العثور على معرف المنتج', 'Product ID not found.'),
                            confirmButtonColor: '#1a1a1a',
                            background: '#ffffff',
                            color: '#000000',
                            iconColor: '#dc3545'
                        });
                        return;
                    }

                    console.log('📤 Adding product to wishlist from modal:', currentProductId);

                    // Show loading state
                    const button = this;
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
                            body: JSON.stringify({ product_id: currentProductId })
                        });

                        console.log('📥 Response status:', response.status);

                        const data = await response.json();
                        console.log('📦 Response data:', data);

                        // إذا كان يتطلب تسجيل دخول
                        if (response.status === 401 || data.requiresAuth) {
                            console.log('⚠️ User not logged in - saving to localStorage');

                            // حفظ المنتج في localStorage
                            const STORAGE_KEY = 'rakaz_pending_wishlist';
                            let pendingWishlist = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');

                            // إضافة المنتج إذا لم يكن موجود
                            if (!pendingWishlist.includes(currentProductId)) {
                                pendingWishlist.push(currentProductId);
                                localStorage.setItem(STORAGE_KEY, JSON.stringify(pendingWishlist));
                                console.log('💾 Saved to localStorage:', pendingWishlist);
                            }

                            Swal.fire({
                                icon: 'warning',
                                title: t('يجب تسجيل الدخول', 'Sign in required'),
                                html: t(
                                    'يجب عليك تسجيز الدخول أولاً لإضافة منتجات إلى قائمة الأمنيات<br><strong>سيتم حفظ اختيارك تلقائياً بعد تسجيل الدخول</strong>',
                                    'Please sign in to add items to your wishlist.<br><strong>Your selection will be saved automatically after login</strong>'
                                ),
                                confirmButtonText: t('تسجيل الدخول الآن', 'Sign in now'),
                                showCancelButton: true,
                                cancelButtonText: t('إلغاء', 'Cancel'),
                                confirmButtonColor: '#1a1a1a',
                                cancelButtonColor: '#666',
                                background: '#ffffff',
                                color: '#000000',
                                iconColor: '#ffc107',
                                customClass: {
                                    container: 'modal-wishlist-alert'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('login') }}";
                                }
                            });
                            button.disabled = false;
                            button.style.opacity = '1';
                            return;
                        }

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        if (data.success) {
                            // Update button appearance
                            if (data.isAdded) {
                                button.style.background = '#dc2626';
                                button.style.borderColor = '#dc2626';
                            } else {
                                button.style.background = 'white';
                                button.style.borderColor = '#ddd';
                            }

                            console.log('✅ Success! isAdded:', data.isAdded);

                            // Show beautiful success message
                            Swal.fire({
                                icon: data.isAdded ? 'success' : 'info',
                                title: data.isAdded
                                    ? t('تمت الإضافة بنجاح', 'Added successfully')
                                    : t('تم الحذف', 'Removed'),
                                text: data.isAdded
                                    ? t('تم إضافة المنتج إلى قائمة الأمنيات', 'Added to wishlist')
                                    : t('تم حذف المنتج من قائمة الأمنيات', 'Removed from wishlist'),
                                timer: 2500,
                                showConfirmButton: false,
                                position: 'top-end',
                                toast: true,
                                background: '#ffffff',
                                color: '#000000',
                                iconColor: data.isAdded ? '#28a745' : '#6c757d',
                                customClass: {
                                    popup: 'animated fadeInRight',
                                    container: 'modal-wishlist-toast'
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

                            // Update all wishlist buttons for this product
                            document.querySelectorAll(`.wishlist-btn[data-product-id="${currentProductId}"]`).forEach(btn => {
                                if (data.isAdded) {
                                    btn.classList.add('active');
                                } else {
                                    btn.classList.remove('active');
                                }
                            });
                        } else {
                            throw new Error(isArabic ? (data.message || 'حدث خطأ غير معروف') : 'An unknown error occurred.');
                        }
                    } catch (error) {
                        console.error('❌ Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: t('خطأ!', 'Error'),
                            text: isArabic
                                ? (error.message || 'حدث خطأ أثناء الإضافة للمفضلة. يرجى المحاولة مرة أخرى.')
                                : 'Something went wrong while updating your wishlist. Please try again.',
                            confirmButtonText: t('حسناً', 'OK'),
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
                });

                document.getElementById('modalAddToBag').addEventListener('click', function() {
                    const modalSizeSelect = document.getElementById('modalSizeSelect');
                    const selectedSize = modalSizeSelect ? modalSizeSelect.value : null;
                    const productName = document.getElementById('modalProductName').textContent;

                    // التحقق من وجود مقاسات - فقط إذا كان هناك خيارات في القائمة المنسدلة
                    const hasSizes = modalSizeSelect && modalSizeSelect.options.length > 1;

                    if (hasSizes && !selectedSize) {
                        Swal.fire({
                            icon: 'warning',
                            title: t('اختر المقاس', 'Select size'),
                            text: t('الرجاء اختيار المقاس أولاً', 'Please select a size first.'),
                            confirmButtonText: t('حسناً', 'OK'),
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
                                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> ' + (isArabic ? 'تمت الإضافة' : 'Added');
                            button.style.background = '#4CAF50';

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: t('تمت الإضافة!', 'Added!'),
                                text: isArabic ? (data.message || 'تمت الإضافة!') : 'Added to bag successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // Close modal after adding
                            setTimeout(() => {
                                button.innerHTML =
                                    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg> ' + (isArabic ? 'إضافة إلى حقيبة التسوق' : 'Add to bag');
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
                            title: t('خطأ!', 'Error'),
                            text: t('حدث خطأ أثناء الإضافة للسلة', 'Something went wrong while adding to bag.'),
                            confirmButtonText: t('حسناً', 'OK')
                        });
                    });
                });
            });

            // ========================================
            // Product Hover - Sizes Scroll
            // ========================================
            document.addEventListener('DOMContentLoaded', function() {
                // Sizes Scroll Buttons
                document.querySelectorAll('.prev-size').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const productId = this.dataset.productId;
                        const wrapper = document.querySelector(`.product-sizes-wrapper[data-product-id="${productId}"]`);
                        if (wrapper) {
                            wrapper.scrollBy({ left: -40, behavior: 'smooth' });
                        }
                    });
                });

                document.querySelectorAll('.next-size').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const productId = this.dataset.productId;
                        const wrapper = document.querySelector(`.product-sizes-wrapper[data-product-id="${productId}"]`);
                        if (wrapper) {
                            wrapper.scrollBy({ left: 40, behavior: 'smooth' });
                        }
                    });
                });

                // Prevent clicks on hover content from triggering product link
                document.querySelectorAll('.product-hover-content').forEach(content => {
                    content.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                });

                // Gallery item click to change main image
                document.querySelectorAll('.product-gallery-item').forEach(galleryItem => {
                    galleryItem.addEventListener('click', function(e) {
                        // Check if the image is wrapped in an anchor tag with href (color link)
                        const parentLink = this.closest('a[href]');
                        if (parentLink && parentLink.getAttribute('href') !== '#') {
                            // Allow navigation to product page with color
                            console.log('🔗 Navigating to product with color:', parentLink.getAttribute('data-color-id'));
                            return; // Allow default link behavior
                        }

                        e.preventDefault();
                        e.stopPropagation();
                        const productCard = this.closest('.product-card');
                        const mainImage = productCard.querySelector('.product-image-primary');
                        if (mainImage) {
                            mainImage.src = this.src;
                        }
                    });
                });

                // Size item click to select
                document.querySelectorAll('.product-size-item').forEach(sizeItem => {
                    sizeItem.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const productCard = this.closest('.product-card');
                        const allSizes = productCard.querySelectorAll('.product-size-item');
                        allSizes.forEach(s => s.style.cssText = '');
                        this.style.cssText = 'background: #1a1a1a; color: #fff; border-color: #1a1a1a;';
                    });
                });

                // ========================================
                // Gallery Navigation - SUPER POWERFUL RTL/LTR Support
                // ========================================
                function isRTL() {
                    return document.documentElement.dir === 'rtl' ||
                           document.body.dir === 'rtl' ||
                           getComputedStyle(document.body).direction === 'rtl';
                }

                function normalizeScrollLeft(container) {
                    // Different browsers handle RTL scrollLeft differently
                    if (!isRTL()) return container.scrollLeft;

                    // For RTL, normalize to positive values
                    const scrollLeft = container.scrollLeft;
                    if (scrollLeft < 0) {
                        // Chrome/Edge RTL: negative values
                        return Math.abs(scrollLeft);
                    } else if (scrollLeft > 0) {
                        // Firefox RTL: positive values, starts from max
                        return container.scrollWidth - container.clientWidth - scrollLeft;
                    }
                    return 0;
                }

                function scrollGallery(container, amount, direction) {
                    const rtl = isRTL();

                    console.log('🎯 SCROLL DEBUG:', {
                        direction,
                        amount,
                        isRTL: rtl,
                        currentScrollLeft: container.scrollLeft,
                        scrollWidth: container.scrollWidth,
                        clientWidth: container.clientWidth,
                        normalizedScroll: normalizeScrollLeft(container)
                    });

                    if (direction === 'next') {
                        if (rtl) {
                            // RTL: scroll to the left (negative or decrease)
                            container.scrollBy({
                                left: -amount,
                                behavior: 'smooth'
                            });
                        } else {
                            // LTR: scroll to the right (positive)
                            container.scrollBy({
                                left: amount,
                                behavior: 'smooth'
                            });
                        }
                    } else {
                        if (rtl) {
                            // RTL: scroll to the right (positive or increase)
                            container.scrollBy({
                                left: amount,
                                behavior: 'smooth'
                            });
                        } else {
                            // LTR: scroll to the left (negative)
                            container.scrollBy({
                                left: -amount,
                                behavior: 'smooth'
                            });
                        }
                    }

                    setTimeout(() => {
                        console.log('✅ After scroll:', {
                            scrollLeft: container.scrollLeft,
                            normalized: normalizeScrollLeft(container)
                        });
                    }, 100);
                }

                function updateGalleryButtons() {
                    document.querySelectorAll('.product-gallery-container').forEach(container => {
                        const productId = container.getAttribute('data-product-id');
                        const prevBtn = document.querySelector(`.gallery-prev[data-product-id="${productId}"]`);
                        const nextBtn = document.querySelector(`.gallery-next[data-product-id="${productId}"]`);

                        if (!prevBtn || !nextBtn) return;

                        const normalized = normalizeScrollLeft(container);
                        const maxScroll = container.scrollWidth - container.clientWidth;

                        const isAtStart = normalized <= 5;
                        const isAtEnd = normalized >= (maxScroll - 5);

                        prevBtn.disabled = isAtStart;
                        nextBtn.disabled = isAtEnd;

                        console.log('🔘 Button Update:', {
                            productId,
                            normalized,
                            maxScroll,
                            isAtStart,
                            isAtEnd,
                            prevDisabled: prevBtn.disabled,
                            nextDisabled: nextBtn.disabled
                        });
                    });
                }

                // POWERFUL Click Handler with RTL Support
                document.querySelectorAll('.gallery-prev, .gallery-next').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const productId = this.getAttribute('data-product-id');
                        const container = document.querySelector(`.product-gallery-container[data-product-id="${productId}"]`);

                        if (!container) {
                            console.error('❌ Container not found for product:', productId);
                            return;
                        }

                        const items = container.querySelectorAll('.product-gallery-item');
                        if (!items || items.length === 0) {
                            console.error('❌ No gallery items found');
                            return;
                        }

                        const itemWidth = items[0].offsetWidth;
                        const gap = 6;
                        const scrollAmount = itemWidth + gap;

                        const isPrev = this.classList.contains('gallery-prev');
                        const isNext = this.classList.contains('gallery-next');

                        console.log('🖱️ CLICK:', {
                            button: isPrev ? 'PREV' : 'NEXT',
                            productId,
                            itemsCount: items.length,
                            itemWidth,
                            scrollAmount,
                            isRTL: isRTL()
                        });

                        if (isPrev) {
                            scrollGallery(container, scrollAmount, 'prev');
                        } else if (isNext) {
                            scrollGallery(container, scrollAmount, 'next');
                        }

                        setTimeout(() => updateGalleryButtons(), 400);
                    }, true); // Use capture phase
                });

                // Update buttons on scroll
                document.querySelectorAll('.product-gallery-container').forEach(container => {
                    container.addEventListener('scroll', function() {
                        updateGalleryButtons();
                    });
                });

                // Initial update
                setTimeout(() => {
                    console.log('🚀 Gallery initialized - RTL:', isRTL());
                    updateGalleryButtons();
                }, 200);

                // Color circle click to filter/highlight
                document.querySelectorAll('.product-color-circle').forEach(colorCircle => {
                    colorCircle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const productCard = this.closest('.product-card');
                        const allColors = productCard.querySelectorAll('.product-color-circle');
                        allColors.forEach(c => {
                            c.style.borderColor = '#fff';
                            c.style.borderWidth = '2px';
                        });
                        this.style.borderColor = '#1a1a1a';
                        this.style.borderWidth = '3px';
                    });
                });
            });
        </script>

        <!-- Shop Sidebar Lazy Loading -->
        <script src="{{ asset('assets/js/shop-sidebar-loader.js') }}"></script>

        <!-- Product Hover Lazy Loading (must load before pagination) -->
        <script src="{{ asset('assets/js/product-hover-loader.js') }}"></script>

        <!-- Pagination Lazy Loading (initial load with 1.5s delay) -->
        <script src="{{ asset('assets/js/shop-pagination-loader.js') }}"></script>

        <!-- Shop AJAX Pagination (load after product-hover-loader) -->
        <script src="{{ asset('assets/js/shop-pagination.js') }}"></script>

@endpush
