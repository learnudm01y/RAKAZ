@extends('layouts.app')

@section('content')
@push('styles')

    <style>
        /* Wishlist Page Specific Styles */
        .wishlist-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 40px;
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
        }

        /* إذا كان هناك Wrapper خارجي يسبب Scroll داخلي */
        .page-wishlist,
        .page-wishlist.desktop,
        body.page-wishlist,
        body.page-wishlist.desktop {
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
        }

        .wishlist-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .wishlist-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 400;
            margin-bottom: 15px;
            color: #1a1a1a;
        }

        .wishlist-count {
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

        .wishlist-action-btn.primary {
            background: #333;
            color: #ffffff;
            border-color: #333;
        }

        .wishlist-action-btn.primary:hover {
            background: #555;
            border-color: #555;
        }

        .wishlist-action-btn svg {
            width: 18px;
            height: 18px;
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .wishlist-item {
            position: relative;
            background: #f8f8f8;
            border: none;
            transition: box-shadow 0.3s ease;
        }

        .wishlist-item:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .wishlist-item-image {
            position: relative;
            width: 100%;
            padding-top: 133.33%; /* نسبة 4:3 مثل shop بالضبط */
            overflow: hidden;
            background: #ffffff;
            contain: layout style paint;
        }

        .wishlist-item-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            box-sizing: border-box;
            /* تنعيم قوي جداً */
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            backface-visibility: hidden;
            transform: translateZ(0);
            will-change: transform;
            filter: blur(0.2px) contrast(1.03) saturate(1.08) brightness(1.01);
        }

        .remove-btn {
            position: absolute;
            top: 12px;
            left: 12px;
            width: 30px;
            height: 30px;
            background: transparent;
            border: none;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.2s ease;
            box-shadow: none;
        }

        .remove-btn:hover {
            background: transparent;
            transform: scale(1.1);
        }

        .remove-btn:hover svg {
            stroke: #333;
        }

        .remove-btn svg {
            width: 20px;
            height: 20px;
            stroke: #666;
            stroke-width: 1.5;
        }

        .wishlist-item-info {
            padding: 8px 10px;
            text-align: center;
            background: white;
        }

        .wishlist-item-brand {
            font-size: 11px;
            font-weight: 400;
            letter-spacing: 0.5px;
            text-transform: none;
            margin-bottom: 2px;
            color: #999;
        }

        .wishlist-item-name {
            font-size: 15px;
            font-weight: 500;
            line-height: 1.2;
            margin-bottom: 3px;
            color: #000;
            min-height: 32px;
            max-height: 32px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .wishlist-item-description {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .wishlist-item-price {
            font-size: 16px;
            font-weight: 600;
            color: #000;
            margin-bottom: 6px;
        }

        .add-to-bag-btn {
            width: 100%;
            padding: 12px;
            background: #c9947a;
            border: none;
            border-radius: 4px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .add-to-bag-btn:hover {
            background: #b8856a;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .wishlist-item-stock {
            font-size: 13px;
            margin-top: 4px;
        }

        .in-stock {
            color: #4caf50;
        }

        .low-stock {
            color: #ff9800;
        }

        .out-of-stock {
            color: #f44336;
        }

        .empty-wishlist {
            text-align: center;
            padding: 100px 20px;
        }

        .empty-wishlist-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            opacity: 0.3;
        }

        .empty-wishlist-title {
            font-size: 28px;
            margin-bottom: 15px;
            color: #1a1a1a;
        }

        .empty-wishlist-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        .empty-wishlist-btn {
            display: inline-block;
            padding: 15px 40px;
            background: #1a1a1a;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .empty-wishlist-btn:hover {
            background: #333;
        }

        /* Tablet Styles */
        @media (max-width: 1024px) {
            .wishlist-container {
                padding: 40px 25px;
            }

            .wishlist-title {
                font-size: 36px;
            }

            .wishlist-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
        }

        /* Mobile Styles */
        @media (max-width: 767px) {
            .wishlist-container {
                padding: 30px 15px;
            }

            .wishlist-title {
                font-size: 28px;
            }

            .wishlist-count {
                font-size: 14px;
            }

            .wishlist-actions {
                flex-direction: column;
            }

            .wishlist-action-btn {
                width: 100%;
                justify-content: center;
            }

            .wishlist-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .wishlist-item-info {
                padding: 15px 10px;
            }

            .wishlist-item-name {
                font-size: 13px;
                min-height: 36px;
                max-height: 36px;
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                line-height: 1.4;
            }

            .wishlist-item-price {
                font-size: 14px;
            }

            .empty-wishlist {
                padding: 60px 20px;
            }

            .empty-wishlist-title {
                font-size: 22px;
            }

            /* إزالة جميع الفلاتر من الصور للجوال فقط */
            .wishlist-item-image img {
                filter: none !important;
                image-rendering: auto !important;
            }
        }

        /* Product Modal Styles - مطابق 100% لصفحة Shop */
        .product-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9998;
            display: none;
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
        }

        [dir="ltr"] .modal-sale-badge-container {
            right: 20px;
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

        .modal-thumbnail-gallery {
            margin-top: 15px;
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

        .modal-original-price {
            font-size: 20px;
            color: #999;
            text-decoration: line-through;
            margin-left: 10px;
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
            flex-shrink: 0;
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
            cursor: pointer;
        }

        .modal-size-guide-link:hover {
            color: #1a1a1a;
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

        /* Mobile Responsive for Modal */
        @media (max-width: 768px) {
            /* Close Button - Top Right Corner NO SPACING */
            .product-modal-close {
                position: absolute !important;
                top: 0 !important;
                right: auto !important;
                left: auto !important;
                width: 32px !important;
                height: 32px !important;
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
                left: 0 !important; /* ملاصق للحافة */
                z-index: 50 !important;
                width: auto !important;
                max-width: none !important;
                writing-mode: vertical-rl !important; /* طولي */
                text-orientation: mixed !important;
            }

            .modal-sale-badge {
                font-size: 9px !important;
                padding: 8px 5px !important; /* padding للوضع الطولي */
                display: inline-block !important;
                white-space: nowrap !important;
                background: #dc2626 !important;
                color: white !important;
                border-radius: 0 0 8px 8px !important; /* فقط من الأسفل */
                font-weight: 600 !important;
                letter-spacing: 0.3px !important;
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
                object-fit: cover !important;
                width: 100% !important;
                height: auto !important;
                /* إزالة جميع الفلاتر للجوال فقط */
                filter: none !important;
                image-rendering: auto !important;
                -webkit-font-smoothing: subpixel-antialiased !important;
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

            .product-modal-content {
                width: 95% !important;
                max-height: 95vh !important;
            }
        }
    </style>
    @endpush

    <!-- Wishlist Content -->
    <div class="wishlist-container">
        @php
            $wishlistCount = $wishlistItems ? count($wishlistItems) : 0;
        @endphp

        <x-account-nav-header
            :title="(app()->getLocale() == 'ar' ? 'قائمة أمنياتي' : 'My wishlist') . ' (' . $wishlistCount . ')'"
            :subtitle="app()->getLocale() == 'ar'
                ? ('لديك ' . $wishlistCount . ' ' . ($wishlistCount === 1 ? 'منتج' : 'منتجات') . ' في قائمة الأمنيات')
                : ('You have ' . $wishlistCount . ' ' . ($wishlistCount === 1 ? 'item' : 'items') . ' in your wishlist')"
        />

        <!-- Wishlist Products Grid -->
        @if($wishlistItems && count($wishlistItems) > 0)
        <div class="wishlist-grid">
            @foreach($wishlistItems as $item)
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

                    $productName = is_array($product->name)
                        ? ($product->name[app()->getLocale()] ?? $product->name['ar'] ?? $product->name['en'] ?? (app()->getLocale() == 'ar' ? 'منتج' : 'Product'))
                        : $product->name;

                    $productBrand = $product->brand ? (is_array($product->brand) ? ($product->brand[app()->getLocale()] ?? $product->brand['ar'] ?? '') : $product->brand) : '';

                    $productCategory = $product->category ? (is_array($product->category->name) ? ($product->category->name[app()->getLocale()] ?? $product->category->name['ar'] ?? '') : $product->category->name) : '';
                @endphp

                <div class="wishlist-item" data-wishlist-id="{{ $item->id }}">
                    <div class="wishlist-item-image">
                        <img src="{{ $mainImage }}"
                             alt="{{ $productName }}"
                             id="wishlist-img-{{ $item->id }}"
                             class="wishlist-product-image product-image-primary"
                             loading="lazy"
                             data-product-id="{{ $product->id }}">
                        <button class="remove-btn" onclick="removeFromWishlist({{ $item->id }})" title="{{ app()->getLocale() == 'ar' ? 'إزالة من المفضلة' : 'Remove from wishlist' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="wishlist-item-info">
                        <p class="wishlist-item-brand">{{ $productCategory }}</p>
                        <h3 class="wishlist-item-name">{{ $productName }}</h3>
                        @if($productBrand)
                            <p class="wishlist-item-description">{{ $productBrand }}</p>
                        @endif
                        <p class="wishlist-item-price">
                            @if($product->sale_price && $product->sale_price > 0)
                                <span style="text-decoration: line-through; color: #999; margin-left: 10px;">{{ number_format($product->price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                                <span style="color: #d33;">{{ number_format($product->sale_price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                            @else
                                {{ number_format($product->price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}
                            @endif
                        </p>
                        <button class="add-to-bag-btn" onclick="openWishlistProductModal({{ $product->id }}, {{ $item->id }})">
                            {{ app()->getLocale() == 'ar' ? 'إضافة للسلة' : 'Add to bag' }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <!-- Empty Wishlist State -->
        <div class="empty-wishlist">
            <svg class="empty-wishlist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
            <h2 class="empty-wishlist-title">{{ app()->getLocale() == 'ar' ? 'قائمة الأمنيات فارغة' : 'Your wishlist is empty' }}</h2>
            <p class="empty-wishlist-text">{{ app()->getLocale() == 'ar' ? 'ابدأ بإضافة المنتجات التي تعجبك إلى قائمة الأمنيات' : 'Start adding products you love to your wishlist.' }}</p>
            <a href="{{ route('shop') }}" class="empty-wishlist-btn">{{ app()->getLocale() == 'ar' ? 'تسوق الآن' : 'Shop now' }}</a>
        </div>
        @endif

        <!-- Empty Wishlist State (مخفي افتراضياً) -->
        <!-- <div class="empty-wishlist" style="display: none;">
            <svg class="empty-wishlist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
            <h2 class="empty-wishlist-title">قائمة الأمنيات فارغة</h2>
            <p class="empty-wishlist-text">ابدأ بإضافة المنتجات التي تعجبك إلى قائمة الأمنيات</p>
            <a href="{{ route('home') }}" class="empty-wishlist-btn">تسوق الآن</a>
        </div> -->
    </div>

    <!-- Product Modal for Wishlist -->
    <div class="product-modal" id="wishlistProductModal" style="display: none;">
        <div class="product-modal-overlay"></div>
        <div class="product-modal-content">
            <button class="product-modal-close" id="wishlistModalClose">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <div class="modal-product-details">
                <!-- Sale Badge - Absolute positioned on far left -->
                <div class="modal-sale-badge-container" id="wishlistModalSaleBadgeContainer" style="display: none;">
                    <span class="modal-sale-badge" id="wishlistModalSaleBadge">{{ app()->getLocale() == 'ar' ? 'عرض خاص' : 'On Sale' }}</span>
                </div>

                <!-- Product Gallery -->
                <div class="modal-product-gallery">
                    <div class="modal-main-image-wrapper">
                        <button class="modal-image-nav prev" id="wishlistModalPrevImage">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <img src="" alt="" class="modal-main-product-image" id="wishlistModalMainImage">
                        <button class="modal-image-nav next" id="wishlistModalNextImage">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-thumbnail-gallery">
                        <div class="modal-thumbnails-wrapper" id="wishlistModalThumbnails">
                        </div>
                        <div class="modal-thumbnails-extra" id="wishlistModalThumbnailsExtra" style="display: none;">
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="modal-product-info-section">
                    <!-- Season Badge -->
                    <div class="modal-product-meta-top">
                        <span class="modal-season-badge" id="wishlistModalSeasonBadge" style="display: none;">{{ app()->getLocale() == 'ar' ? 'الموسم الجديد' : 'New season' }}</span>
                    </div>

                    <!-- Brand & Title -->
                    <div class="modal-product-header">
                        <h1 class="modal-product-title" id="wishlistModalBrand">{{ app()->getLocale() == 'ar' ? 'ركاز' : 'Rakaz' }}</h1>
                        <h2 class="modal-product-subtitle" id="wishlistModalProductName"></h2>
                    </div>

                    <!-- Price -->
                    <div class="modal-product-price-section">
                        <span class="modal-original-price" id="wishlistModalOriginalPrice" style="text-decoration: line-through; color: #999; margin-left: 10px; display: none;"></span>
                        <span class="modal-current-price" id="wishlistModalPrice"></span>
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
                    <div class="modal-color-images-section" id="wishlistModalColorImagesSection" style="display: none;">
                        <div class="modal-color-header">
                            <label class="modal-option-label">
                                {{ app()->getLocale() == 'ar' ? 'اللون:' : 'Color:' }}
                                <span class="modal-selected-color-name" id="wishlistModalSelectedColorName"></span>
                            </label>
                        </div>
                        <div class="modal-color-images-row" id="wishlistModalColorImagesRow">
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
                            <div id="wishlistModalAvailableSizes" style="display: none !important;">
                                <div style="font-size: 14px; font-weight: 700; color: #28a745; margin-bottom: 10px;">
                                    ✅ <span id="wishlistModalSizesTitle">{{ app()->getLocale() == 'ar' ? 'المقاسات المتوفرة:' : 'Available sizes:' }}</span> <span id="wishlistModalSizesCount"></span>
                                </div>
                                <div id="wishlistModalSizesList" style="font-size: 16px; color: #212529; font-weight: 600; line-height: 1.8;"></div>
                            </div>
                            <!-- Size Dropdown -->
                            <select class="custom-select" id="wishlistModalSizeSelect" style="width: 100%; font-size: 16px; font-weight: 600;">
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
                        <button class="modal-btn-add-to-bag" id="wishlistModalAddToBag">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            {{ app()->getLocale() == 'ar' ? 'إضافة إلى حقيبة التسوق' : 'Add to bag' }}
                        </button>
                        <button class="modal-btn-add-to-wishlist" style="display: none;">
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
                            <button class="modal-tab-btn active" data-tab="wishlist-modal-description">{{ app()->getLocale() == 'ar' ? 'وصف المنتج' : 'Description' }}</button>
                            <button class="modal-tab-btn" data-tab="wishlist-modal-sizing" style="display: none;">{{ app()->getLocale() == 'ar' ? 'المقاسات والحجم' : 'Sizing Info' }}</button>
                            <button class="modal-tab-btn" data-tab="wishlist-modal-design" style="display: none;">{{ app()->getLocale() == 'ar' ? 'تفاصيل التصميم' : 'Design Details' }}</button>
                            <button class="modal-tab-btn" data-tab="wishlist-modal-shipping">{{ app()->getLocale() == 'ar' ? 'التوصيل والإرجاع' : 'Delivery & Returns' }}</button>
                        </div>
                        <div class="modal-tabs-content">
                            <div class="modal-tab-panel active" id="wishlist-modal-description">
                                <div id="wishlistModalDescription"></div>
                            </div>
                            <div class="modal-tab-panel" id="wishlist-modal-sizing">
                                <div id="wishlistModalSizingInfo"></div>
                            </div>
                            <div class="modal-tab-panel" id="wishlist-modal-design">
                                <div id="wishlistModalDesignDetails"></div>
                            </div>
                            <div class="modal-tab-panel" id="wishlist-modal-shipping">
                                <p>{{ app()->getLocale() == 'ar' ? 'نوفر شحن مجاني لجميع الطلبات. التوصيل خلال 2-3 أيام عمل.' : 'We offer free shipping on all orders. Delivery in 2-3 business days.' }}</p>
                                <p>{{ app()->getLocale() == 'ar' ? 'الإرجاع مجاني خلال 14 يوم من تاريخ الاستلام.' : 'Free returns within 14 days from receipt date.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
    <script>
    // Auto-save pending wishlist items from localStorage after login
    document.addEventListener('DOMContentLoaded', async function() {
        const STORAGE_KEY = 'rakaz_pending_wishlist';
        const pendingWishlist = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');

        if (pendingWishlist.length > 0) {
            console.log('💾 Found pending wishlist items on wishlist page:', pendingWishlist);
            console.log('📤 Auto-saving to database...');

            try {
                const response = await fetch("{{ route('wishlist.savePending') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ product_ids: pendingWishlist })
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('✅ Auto-saved response:', data);

                    if (data.success) {
                        localStorage.removeItem(STORAGE_KEY);
                        console.log('🗑️ Cleared localStorage after auto-save');

                        // Reload page to show new items
                        if (data.savedCount > 0) {
                            console.log('🔄 Reloading page to show new items...');
                            window.location.reload();
                        }
                    }
                }
            } catch (error) {
                console.error('❌ Error auto-saving wishlist:', error);
            }
        }
    });
    </script>
    @endauth
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/pica@9.0.1/dist/pica.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var __isArabic = document.documentElement.getAttribute('dir') === 'rtl' || '{{ app()->getLocale() }}' === 'ar';
function __t(ar, en) {
    return __isArabic ? ar : en;
}

var currentWishlistProductId = null;
var currentWishlistItemId = null;
var currentWishlistImageIndex = 0;

// Open wishlist product modal
function openWishlistProductModal(productId, wishlistItemId) {
    currentWishlistProductId = productId;
    currentWishlistItemId = wishlistItemId;

    fetch('/api/products/' + productId)
        .then(function(response) { return response.json(); })
        .then(function(product) {
            if (!product || product.error) {
                throw new Error(product.message || 'Product not found');
            }

            console.log('🔍 Product data:', product);

            var modal = document.getElementById('wishlistProductModal');
            var modalMainImage = document.getElementById('wishlistModalMainImage');
            var modalBrand = document.getElementById('wishlistModalBrand');
            var modalProductName = document.getElementById('wishlistModalProductName');
            var modalPrice = document.getElementById('wishlistModalPrice');
            var modalOriginalPrice = document.getElementById('wishlistModalOriginalPrice');
            var modalSizeSelect = document.getElementById('wishlistModalSizeSelect');
            var modalThumbnails = document.getElementById('wishlistModalThumbnails');
            var modalSaleBadgeContainer = document.getElementById('wishlistModalSaleBadgeContainer');
            var modalSeasonBadge = document.getElementById('wishlistModalSeasonBadge');

            // Set brand and name
            modalBrand.textContent = product.brand || '';
            modalProductName.textContent = product.name || '';

            // Handle images
            var allImages = product.images || [];
            if (allImages.length > 0) {
                modalMainImage.src = allImages[0];
                modalMainImage.alt = product.name;
            }

            // Handle price and sale badge
            if (product.is_on_sale && product.sale_price) {
                modalSaleBadgeContainer.style.display = 'block';
                modalOriginalPrice.textContent = product.price;
                modalOriginalPrice.style.display = 'inline';
                modalPrice.textContent = product.sale_price;
            } else {
                modalSaleBadgeContainer.style.display = 'none';
                modalOriginalPrice.style.display = 'none';
                modalPrice.textContent = product.price;
            }

            // Show/hide season badge
            if (product.is_new) {
                modalSeasonBadge.style.display = 'inline-block';
            } else {
                modalSeasonBadge.style.display = 'none';
            }

            // Update description
            var modalDescription = document.getElementById('wishlistModalDescription');
            if (modalDescription) {
                modalDescription.innerHTML = product.description || '<p>' + __t('لا يوجد وصف متاح', 'No description available') + '</p>';
            }

            // Update sizing info tab
            var modalSizingInfo = document.getElementById('wishlistModalSizingInfo');
            var sizingTabBtn = document.querySelector('.modal-tab-btn[data-tab="wishlist-modal-sizing"]');
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
            var modalDesignDetails = document.getElementById('wishlistModalDesignDetails');
            var designTabBtn = document.querySelector('.modal-tab-btn[data-tab="wishlist-modal-design"]');
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

            // Reset tabs
            document.querySelectorAll('#wishlistProductModal .modal-tab-panel').forEach(function(panel) {
                panel.classList.remove('active');
            });
            document.querySelectorAll('#wishlistProductModal .modal-tab-btn').forEach(function(btn) {
                btn.classList.remove('active');
            });

            // Activate first visible tab
            var allTabBtns = document.querySelectorAll('#wishlistProductModal .modal-tab-btn');
            var firstVisibleTab = null;
            for (var i = 0; i < allTabBtns.length; i++) {
                var btn = allTabBtns[i];
                var btnStyle = window.getComputedStyle(btn);
                if (btnStyle.display !== 'none' && btnStyle.visibility !== 'hidden') {
                    firstVisibleTab = btn;
                    break;
                }
            }

            if (firstVisibleTab) {
                firstVisibleTab.classList.add('active');
                var firstTabId = firstVisibleTab.getAttribute('data-tab');
                var firstPanel = document.getElementById(firstTabId);
                if (firstPanel) firstPanel.classList.add('active');
            }

            // Add thumbnails
            modalThumbnails.innerHTML = '';
            var maxVisibleThumbnails = 5;
            var visibleImages = allImages.slice(0, maxVisibleThumbnails);
            var extraImages = allImages.slice(maxVisibleThumbnails);

            visibleImages.forEach(function(img, index) {
                var thumbnailWrapper = document.createElement('div');
                thumbnailWrapper.style.position = 'relative';
                thumbnailWrapper.style.display = 'inline-block';

                var thumbnail = document.createElement('img');
                thumbnail.src = img;
                thumbnail.alt = __t('صورة', 'Image') + ' ' + (index + 1);
                thumbnail.className = 'modal-thumbnail' + (index === 0 ? ' active' : '');
                thumbnail.onclick = function() {
                    updateWishlistModalImage(index);
                };

                thumbnailWrapper.appendChild(thumbnail);

                if (extraImages.length > 0 && index === visibleImages.length - 1) {
                    var overlay = document.createElement('div');
                    overlay.className = 'modal-thumbnail-overlay';
                    overlay.innerHTML = '<span class="modal-thumbnail-overlay-text">+' + extraImages.length + '</span>';
                    overlay.onclick = function(e) {
                        e.stopPropagation();
                        loadExtraWishlistThumbnails(extraImages, allImages.length);
                    };
                    thumbnailWrapper.appendChild(overlay);
                }

                modalThumbnails.appendChild(thumbnailWrapper);
            });

            function loadExtraWishlistThumbnails(extraImages, totalImages) {
                var overlay = document.querySelector('#wishlistModalThumbnails .modal-thumbnail-overlay');
                if (overlay) overlay.remove();

                var modalThumbnailsExtra = document.getElementById('wishlistModalThumbnailsExtra');
                modalThumbnailsExtra.style.display = 'flex';

                extraImages.forEach(function(img, index) {
                    var actualIndex = maxVisibleThumbnails + index;
                    var thumbnail = document.createElement('img');
                    thumbnail.src = img;
                    thumbnail.alt = __t('صورة', 'Image') + ' ' + (actualIndex + 1);
                    thumbnail.className = 'modal-thumbnail';
                    thumbnail.onclick = function() {
                        updateWishlistModalImage(actualIndex);
                    };
                    modalThumbnailsExtra.appendChild(thumbnail);
                });
            }

            // Handle Color Images Section
            var colorImagesSection = document.getElementById('wishlistModalColorImagesSection');
            var colorImagesRow = document.getElementById('wishlistModalColorImagesRow');
            var selectedColorNameSpan = document.getElementById('wishlistModalSelectedColorName');

            // Clear previous color images
            colorImagesRow.innerHTML = '';

            // Check if product has color images
            if (product.colorImages && product.colorImages.length > 0) {
                colorImagesSection.style.display = 'block';

                // Set first color name
                var firstColor = product.colorImages[0];
                selectedColorNameSpan.textContent = __isArabic ? firstColor.color_ar : firstColor.color_en;

                // Create color image thumbnails
                product.colorImages.forEach(function(colorImg, index) {
                    var thumb = document.createElement('div');
                    thumb.className = 'modal-color-image-thumb' + (index === 0 ? ' active' : '');
                    thumb.dataset.colorId = colorImg.color_id;
                    thumb.dataset.colorAr = colorImg.color_ar;
                    thumb.dataset.colorEn = colorImg.color_en;
                    thumb.dataset.image = colorImg.image;

                    var img = document.createElement('img');
                    img.src = colorImg.image;
                    img.alt = __isArabic ? colorImg.color_ar : colorImg.color_en;

                    thumb.appendChild(img);
                    colorImagesRow.appendChild(thumb);

                    // Click handler
                    thumb.addEventListener('click', function() {
                        // Update active state
                        document.querySelectorAll('.modal-color-image-thumb').forEach(function(t) {
                            t.classList.remove('active');
                        });
                        this.classList.add('active');

                        // Update color name
                        var colorName = __isArabic ? this.dataset.colorAr : this.dataset.colorEn;
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

            // Populate sizes
            modalSizeSelect.innerHTML = '<option value="">' + __t('اختر المقاس', 'Choose size') + '</option>';
            if (product.sizes && product.sizes.length > 0) {
                console.log('✅ Adding ' + product.sizes.length + ' sizes');
                product.sizes.forEach(function(size) {
                    var option = document.createElement('option');
                    option.value = size;
                    option.textContent = size;
                    modalSizeSelect.appendChild(option);
                });

                // Reinitialize CustomSelect
                if (modalSizeSelect.parentElement && modalSizeSelect.parentElement.classList.contains('custom-select-wrapper')) {
                    var wrapper = modalSizeSelect.parentElement;
                    var parent = wrapper.parentElement;
                    parent.insertBefore(modalSizeSelect, wrapper);
                    wrapper.remove();
                }

                if (typeof CustomSelect !== 'undefined') {
                    new CustomSelect(modalSizeSelect);
                }
            }

            // Setup image navigation
            currentWishlistImageIndex = 0;
            var totalImages = allImages.length;

            function updateWishlistModalImage(index) {
                if (index >= 0 && index < totalImages) {
                    currentWishlistImageIndex = index;
                    modalMainImage.src = allImages[currentWishlistImageIndex];

                    document.querySelectorAll('#wishlistModalThumbnails .modal-thumbnail, #wishlistModalThumbnailsExtra .modal-thumbnail').forEach(function(t, i) {
                        if (i === currentWishlistImageIndex) {
                            t.classList.add('active');
                        } else {
                            t.classList.remove('active');
                        }
                    });

                    document.getElementById('wishlistModalPrevImage').disabled = currentWishlistImageIndex === 0;
                    document.getElementById('wishlistModalNextImage').disabled = currentWishlistImageIndex === totalImages - 1;
                }
            }

            document.getElementById('wishlistModalPrevImage').onclick = function(e) {
                e.stopPropagation();
                if (currentWishlistImageIndex > 0) {
                    updateWishlistModalImage(currentWishlistImageIndex - 1);
                }
            };

            document.getElementById('wishlistModalNextImage').onclick = function(e) {
                e.stopPropagation();
                if (currentWishlistImageIndex < totalImages - 1) {
                    updateWishlistModalImage(currentWishlistImageIndex + 1);
                }
            };

            updateWishlistModalImage(0);

            // Show modal
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        })
        .catch(function(error) {
            console.error('Error loading product:', error);
            Swal.fire({
                icon: 'error',
                title: __t('خطأ', 'Error'),
                text: __t('حدث خطأ أثناء تحميل المنتج', 'Error loading product'),
                confirmButtonColor: '#d33'
            });
        });
}

// Close modal
function closeWishlistModal() {
    var modal = document.getElementById('wishlistProductModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    currentWishlistProductId = null;
    currentWishlistItemId = null;
}

// Remove from wishlist
function removeFromWishlist(wishlistId, skipConfirmation) {
    if (skipConfirmation) {
        performDelete(wishlistId);
    } else {
        Swal.fire({
            title: __t('إزالة من المفضلة', 'Remove from wishlist'),
            text: __t('هل تريد إزالة هذا المنتج من قائمة الأمنيات؟', 'Do you want to remove this product from your wishlist?'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: __t('نعم، احذفه', 'Yes, remove it'),
            cancelButtonText: __t('إلغاء', 'Cancel'),
            confirmButtonColor: '#d33',
            cancelButtonColor: '#666'
        }).then(function(result) {
            if (result.isConfirmed) {
                performDelete(wishlistId);
            }
        });
    }
}

function performDelete(wishlistId) {
    fetch('{{ url("/wishlist") }}/' + wishlistId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            var item = document.querySelector('[data-wishlist-id="' + wishlistId + '"]');
            item.style.opacity = '0';
            item.style.transform = 'scale(0.8)';

            setTimeout(function() {
                item.remove();
                var remainingItems = document.querySelectorAll('.wishlist-item').length;
                if (remainingItems === 0) {
                    location.reload();
                }

                Swal.fire({
                    title: __t('تم الحذف!', 'Removed!'),
                    text: data.message || __t('تم الحذف من المفضلة', 'Removed from wishlist'),
                    icon: 'success',
                    confirmButtonText: __t('حسناً', 'OK'),
                    confirmButtonColor: '#000',
                    timer: 2000,
                    timerProgressBar: true
                });
            }, 300);
        } else {
            throw new Error(data.message || 'Failed');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: __t('خطأ!', 'Error'),
            text: __t('حدث خطأ أثناء حذف المنتج', 'Error removing product'),
            confirmButtonColor: '#d33'
        });
    });
}

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    // Close button handlers
    var wishlistModalClose = document.getElementById('wishlistModalClose');
    if (wishlistModalClose) {
        wishlistModalClose.addEventListener('click', closeWishlistModal);
    }

    var wishlistModalOverlay = document.querySelector('#wishlistProductModal .product-modal-overlay');
    if (wishlistModalOverlay) {
        wishlistModalOverlay.addEventListener('click', closeWishlistModal);
    }

    // Modal tabs functionality
    document.querySelectorAll('#wishlistProductModal .modal-tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var targetTab = this.getAttribute('data-tab');

            document.querySelectorAll('#wishlistProductModal .modal-tab-btn').forEach(function(b) {
                b.classList.remove('active');
            });
            document.querySelectorAll('#wishlistProductModal .modal-tab-panel').forEach(function(p) {
                p.classList.remove('active');
            });

            this.classList.add('active');
            var panel = document.getElementById(targetTab);
            if (panel) panel.classList.add('active');
        });
    });

    // Add to bag button
    var wishlistModalAddToBag = document.getElementById('wishlistModalAddToBag');
    if (wishlistModalAddToBag) {
        wishlistModalAddToBag.addEventListener('click', function() {
            var sizeSelect = document.getElementById('wishlistModalSizeSelect');
            var selectedSize = sizeSelect.value;

            if (!selectedSize) {
                Swal.fire({
                    icon: 'warning',
                    title: __t('تنبيه', 'Warning'),
                    text: __t('يرجى اختيار المقاس أولاً', 'Please select a size first'),
                    confirmButtonColor: '#b78953'
                });
                return;
            }

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: currentWishlistProductId,
                    size_id: selectedSize,
                    quantity: 1
                })
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    removeFromWishlist(currentWishlistItemId, true);
                    closeWishlistModal();

                    Swal.fire({
                        title: __t('تمت الإضافة!', 'Added!'),
                        text: __t('تم إضافة المنتج إلى السلة وحذفه من المفضلة', 'Product added to cart and removed from wishlist'),
                        icon: 'success',
                        confirmButtonText: __t('متابعة التسوق', 'Continue shopping'),
                        showCancelButton: true,
                        cancelButtonText: __t('عرض السلة', 'View cart'),
                        confirmButtonColor: '#000',
                        cancelButtonColor: '#666'
                    }).then(function(result) {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = '{{ route("cart") }}';
                        }
                    });

                    var cartCount = document.querySelector('.cart-count');
                    if (cartCount && data.cart_count) {
                        cartCount.textContent = data.cart_count;
                    }
                } else {
                    throw new Error(data.message || 'Failed to add to cart');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: __t('خطأ', 'Error'),
                    text: error.message || __t('حدث خطأ أثناء إضافة المنتج', 'Error adding product'),
                    confirmButtonColor: '#d33'
                });
            });
        });
    }

    // Pica Image Enhancement - DISABLED FOR BETTER IMAGE QUALITY
    // تم تعطيل Pica لأنها تسبب تكسير وبكسلة الصور
    // الصور الآن تعرض بجودتها الأصلية مباشرة
    console.log('✅ Using original images without Pica processing');
    return; // تعطيل كامل لمعالجة Pica

    /* DISABLED CODE:
    if (typeof pica === 'undefined') {
        console.warn('⚠️ Pica.js not loaded - images will not be enhanced');
        return;
    }

    console.log('✅ Pica.js loaded - initializing image enhancement');
    var picaInstance = pica();

    function processWishlistImage(img) {
        if (img.dataset.picaProcessed || img.dataset.picaProcessing) return;
        img.dataset.picaProcessing = 'true';

        if (!img.complete || img.naturalWidth === 0) {
            img.addEventListener('load', function() {
                processWishlistImage(img);
            }, { once: true });
            return;
        }

        console.log('🖼️ Processing image:', img.src.substring(0, 50) + '...');
        enhanceImageWithPica(img);
    }

    function enhanceImageWithPica(img) {
        try {
            var originalSrc = img.src;
            var canvas = document.createElement('canvas');
            var naturalWidth = img.naturalWidth;
            var naturalHeight = img.naturalHeight;

            canvas.width = naturalWidth;
            canvas.height = naturalHeight;

            picaInstance.resize(img, canvas, {
                unsharpAmount: 80,
                unsharpRadius: 0.5,
                unsharpThreshold: 2,
                quality: 3,
                alpha: true,
                filter: 'lanczos3'
            })
            .then(function() {
                return picaInstance.toBlob(canvas, 'image/jpeg', 0.95);
            })
            .then(function(blob) {
                var url = URL.createObjectURL(blob);
                img.src = url;
                img.dataset.picaProcessed = 'true';
                delete img.dataset.picaProcessing;
                console.log('✨ Image enhanced successfully');

                img.addEventListener('load', function() {
                    URL.revokeObjectURL(url);
                }, { once: true });
            })
            .catch(function(err) {
                console.error('❌ Pica error:', err);
                img.src = originalSrc;
                delete img.dataset.picaProcessing;
            });
        } catch (err) {
            console.error('❌ Pica setup error:', err);
            delete img.dataset.picaProcessing;
        }
    }

    function processAllWishlistImages() {
        var wishlistImages = document.querySelectorAll('.wishlist-product-image, .product-image-primary');
        console.log('🔍 Found ' + wishlistImages.length + ' wishlist images to process');
        wishlistImages.forEach(function(img) {
            processWishlistImage(img);
        });
    }

    setTimeout(processAllWishlistImages, 100);

    var resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            document.querySelectorAll('.wishlist-product-image, .product-image-primary').forEach(function(img) {
                delete img.dataset.picaProcessed;
                delete img.dataset.picaProcessing;
            });
            processAllWishlistImages();
        }, 250);
    });

    if (window.MutationObserver) {
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) {
                        var images = node.querySelectorAll ? node.querySelectorAll('.wishlist-product-image, .product-image-primary') : [];
                        images.forEach(function(img) {
                            processWishlistImage(img);
                        });
                    }
                });
            });
        });

        var wishlistContainer = document.querySelector('.wishlist-grid');
        if (wishlistContainer) {
            observer.observe(wishlistContainer, { childList: true, subtree: true });
        }
    }
});
</script>
@endpush

