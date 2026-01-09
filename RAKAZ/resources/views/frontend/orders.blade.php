@extends('layouts.app')

@section('content')
@push('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .orders-page {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        [dir="ltr"] .orders-page {
            direction: ltr;
        }

        [dir="rtl"] .orders-page {
            direction: rtl;
        }

        /* Tabs Navigation */
        .orders-tabs {
            display: flex;
            justify-content: center;
            gap: 0;
            margin-bottom: 40px;
            border-bottom: 2px solid #e5e5e5;
        }

        .tab-button {
            background: none;
            border: none;
            padding: 15px 40px;
            font-size: 16px;
            font-weight: 500;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            position: relative;
            bottom: -2px;
        }

        .tab-button.active {
            color: #1a1a1a;
            border-bottom-color: #1a1a1a;
        }

        .tab-button:hover {
            color: #1a1a1a;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Orders Grid */
        .orders-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Order Card */
        .order-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 25px;
            transition: box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* Order Header */
        .order-header {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .order-info h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .order-date {
            font-size: 12px;
            color: #999;
        }

        .order-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            align-self: flex-start;
        }

        /* LTR mode - badge on the right */
        [dir="ltr"] .order-badge {
            align-self: flex-end;
        }

        .order-badge svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        /* ألوان مختلفة لكل حالة */
        .order-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .order-badge.processing {
            background: #cce5ff;
            color: #004085;
        }

        .order-badge.shipped {
            background: #d4edda;
            color: #155724;
        }

        .order-badge.delivered {
            background: #28a745;
            color: white;
        }

        .order-badge.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        /* Progress Timeline */
        .order-timeline {
            margin: 20px 0;
            position: relative;
        }

        .timeline-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .timeline-line {
            position: absolute;
            top: 16px;
            /* 5 steps => centers are at 10% and 90% */
            left: 10%;
            right: 10%;
            height: 2px;
            background: #e5e5e5;
            z-index: 0;
        }

        .timeline-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: #28a745;
            transition: width 0.5s ease;
            border-radius: 2px;
        }

        /* RTL: progress should start from the right edge */
        html[dir="rtl"] .timeline-progress {
            right: 0;
            left: auto;
        }

        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .step-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #f5f5f5;
            border: 2px solid #e5e5e5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 6px;
            transition: all 0.3s ease;
        }

        .step-icon svg {
            width: 16px;
            height: 16px;
            stroke: #999;
            fill: none;
        }

        .timeline-step.completed .step-icon {
            background: #28a745;
            border-color: #28a745;
        }

        .timeline-step.completed .step-icon svg {
            stroke: #fff;
        }

        .timeline-step.completed .step-icon svg path {
            fill: none;
        }

        .timeline-step.active .step-icon {
            background: #fff;
            border-color: #28a745;
            border-width: 3px;
        }

        .timeline-step.active .step-icon svg {
            stroke: #28a745;
        }

        .step-label {
            font-size: 10px;
            color: #999;
            text-align: center;
            font-weight: 500;
        }

        .timeline-step.completed .step-label,
        .timeline-step.active .step-label {
            color: #1a1a1a;
        }

        /* Order Items */
        .order-items {
            margin: 20px 0;
            border-top: 1px solid #f0f0f0;
            padding-top: 15px;
            flex: 1;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f8f8f8;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-details {
            display: flex;
            gap: 12px;
            align-items: center;
            flex: 1;
        }

        .item-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #f0f0f0;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-size: 13px;
            font-weight: 500;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .item-meta {
            font-size: 11px;
            color: #999;
            display: flex;
            gap: 8px;
        }

        .item-meta span {
            display: inline-flex;
            align-items: center;
        }

        .item-price {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            text-align: left;
        }

        /* Order Footer */
        .order-footer {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: auto;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
        }

        .order-total {
            text-align: center;
        }

        .total-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }

        .total-amount {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .order-actions {
            display: flex;
            gap: 8px;
        }

        .btn-order {
            flex: 1;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-details {
            background: #1a1a1a;
            color: #fff;
        }

        .btn-details:hover {
            background: #333;
        }

        .btn-track,
        .btn-cancel {
            background: #fff;
            color: #1a1a1a;
            border: 2px solid #1a1a1a;
        }

        .btn-track:hover,
        .btn-cancel:hover {
            background: #f8f8f8;
        }

        /* Previous Orders Table */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .orders-table thead {
            background: #f8f9fa;
        }

        .orders-table th {
            padding: 18px 20px;
            font-weight: 600;
        }

        [dir="rtl"] .orders-table th {
            text-align: right;
        }

        [dir="ltr"] .orders-table th {
            text-align: left;
        }
            font-size: 14px;
            color: #1a1a1a;
            border-bottom: 2px solid #e5e5e5;
        }

        .orders-table td {
            padding: 20px;
            font-size: 14px;
            color: #666;
            border-bottom: 1px solid #f0f0f0;
        }

        [dir="rtl"] .orders-table td {
            text-align: right;
        }

        [dir="ltr"] .orders-table td {
            text-align: left;
        }

        .orders-table tbody tr:hover {
            background: #f8f9fa;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .btn-table {
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-reorder {
            background: #fff;
            color: #1a1a1a;
            border: 1.5px solid #1a1a1a;
        }

        .btn-reorder:hover {
            background: #1a1a1a;
            color: #fff;
        }

        .btn-view {
            background: #fff;
            color: #666;
            border: 1.5px solid #ddd;
        }

        .btn-view:hover {
            border-color: #999;
            color: #333;
        }

        .status-badge-table {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
        }

        .status-badge-table.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge-table.confirmed {
            background: #cce5ff;
            color: #004085;
        }

        .status-badge-table.processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-badge-table.shipped {
            background: #d4edda;
            color: #155724;
        }

        .status-badge-table.delivered {
            background: #28a745;
            color: white;
        }

        .status-badge-table.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        /* Empty State */
        .empty-orders {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-orders svg {
            width: 120px;
            height: 120px;
            margin-bottom: 25px;
            opacity: 0.3;
        }

        .empty-orders h3 {
            font-size: 24px;
            color: #1a1a1a;
            margin-bottom: 12px;
        }

        .empty-orders p {
            font-size: 16px;
            color: #999;
            margin-bottom: 30px;
        }

        .btn-shop {
            padding: 14px 35px;
            background: #1a1a1a;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .btn-shop:hover {
            background: #333;
        }

        /* Mobile Previous Orders Cards */
        .mobile-previous-orders {
            display: none;
        }

        .previous-order-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            transition: box-shadow 0.3s ease;
        }

        .previous-order-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .previous-order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .previous-order-number {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .previous-order-date {
            font-size: 12px;
            color: #999;
            margin-top: 4px;
        }

        .previous-order-products {
            font-size: 14px;
            color: #666;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .previous-order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid #f0f0f0;
        }

        .previous-order-total {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .previous-order-actions {
            display: flex;
            gap: 8px;
        }

        .previous-order-actions .btn-table {
            padding: 6px 14px;
            font-size: 12px;
        }

        /* Pagination Styles */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            padding: 30px 20px;
            flex-wrap: wrap;
        }

        .pagination-info {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            width: 100%;
            text-align: center;
        }

        .pagination-btn {
            padding: 10px 20px;
            background: #fff;
            color: #1a1a1a;
            border: 1.5px solid #1a1a1a;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination-btn:hover:not(:disabled) {
            background: #1a1a1a;
            color: #fff;
        }

        .pagination-btn:disabled {
            background: #f5f5f5;
            color: #999;
            border-color: #e5e5e5;
            cursor: not-allowed;
        }

        .pagination-btn.active {
            background: #1a1a1a;
            color: #fff;
        }

        .pagination-numbers {
            display: flex;
            gap: 5px;
        }

        .page-number {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #1a1a1a;
            border: 1.5px solid #e5e5e5;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .page-number:hover:not(.active) {
            border-color: #1a1a1a;
        }

        .page-number.active {
            background: #1a1a1a;
            color: #fff;
            border-color: #1a1a1a;
        }

        /* Loading Spinner */
        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e5e5e5;
            border-top-color: #1a1a1a;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .orders-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Tablet Landscape & Small Desktop */
        @media (max-width: 1024px) {
            .orders-table th,
            .orders-table td {
                padding: 14px 12px;
                font-size: 13px;
            }

            .table-actions {
                flex-direction: column;
                gap: 6px;
            }

            .btn-table {
                padding: 6px 12px;
                font-size: 12px;
            }
        }

        /* Tablet Portrait */
        @media (max-width: 900px) {
            .orders-table th,
            .orders-table td {
                padding: 12px 10px;
                font-size: 12px;
            }

            .orders-table th:nth-child(3),
            .orders-table td:nth-child(3) {
                max-width: 150px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        }

        @media (max-width: 768px) {
            .orders-page {
                padding: 20px 15px;
            }

            .orders-grid {
                grid-template-columns: 1fr;
            }

            .tab-button {
                padding: 12px 25px;
                font-size: 14px;
            }

            .order-card {
                padding: 20px;
            }

            .timeline-wrapper {
                flex-wrap: wrap;
            }

            .timeline-step {
                min-width: 20%;
            }

            .step-label {
                font-size: 9px;
            }

            .order-actions {
                flex-direction: column;
            }

            /* Hide table on mobile, show cards instead */
            .orders-table {
                display: none;
            }

            .mobile-previous-orders {
                display: block;
            }
        }

        /* تعديلات للهواتف الصغيرة جداً فقط */
        @media (max-width: 480px) {
            .order-timeline {
                margin: 0px 0;
                position: relative;
            }

            .order-items {
                margin: 6px 0;
                border-top: 1px solid #f0f0f0;
                padding-top: 15px;
                flex: 1;
            }

            .order-header {
                display: block;
                position: relative;
                min-height: 60px;
                margin-bottom: 0px;
            }

            .order-info {
                position: relative;
                z-index: 1;
            }

            .order-header > div:last-child {
                position: absolute;
                left: 0;
                top: 0;
                z-index: 2;
            }

            [dir="ltr"] .order-header > div:last-child {
                left: auto;
                right: 0;
            }

            .order-badge {
                margin-left: 0 !important;
            }
        }
    </style>
@endpush

<div class="orders-page">
    @php
        $ordersCount = $orders ? $orders->count() : 0;
        $isArabic = app()->getLocale() == 'ar';
        $ordersSubtitle = $ordersCount > 0
            ? ($isArabic ? ('لديك ' . $ordersCount . ' ' . ($ordersCount === 1 ? 'طلب' : 'طلبات')) : ('You have ' . $ordersCount . ' ' . ($ordersCount === 1 ? 'order' : 'orders')))
            : ($isArabic ? 'لا توجد طلبات حتى الآن' : 'No orders yet');
        $ordersTitle = $isArabic ? ('طلباتي (' . $ordersCount . ')') : ('My Orders (' . $ordersCount . ')');
    @endphp

    <x-account-nav-header
        :title="$ordersTitle"
        :subtitle="$ordersSubtitle"
    />

    <!-- Tabs Navigation -->
    <div class="orders-tabs">
        <button class="tab-button active" onclick="switchTab('current')">{{ app()->getLocale() == 'ar' ? 'الطلبات الحية' : 'Current Orders' }}</button>
        <button class="tab-button" onclick="switchTab('previous')">{{ app()->getLocale() == 'ar' ? 'الطلبات السابقة' : 'Previous Orders' }}</button>
    </div>

    <!-- Current Orders Tab -->
    <div id="current-orders" class="tab-content active">
        @php
            $currentOrders = $orders->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped']);
        @endphp

        @if($currentOrders->count() > 0)
            <div class="orders-grid">
            @foreach($currentOrders as $order)
                <div class="order-card" data-order-id="{{ $order->id }}">
                    <!-- Order Header -->
                    @php
                        // توحيد عرض الحالات مع لوحة الإدارة
                        $isAr = app()->getLocale() == 'ar';
                        $statusMap = [
                            'pending' => ['label' => $isAr ? 'قيد الانتظار' : 'Pending', 'class' => 'pending'],
                            'confirmed' => ['label' => $isAr ? 'قيد التحضير' : 'Confirmed', 'class' => 'processing'],
                            'processing' => ['label' => $isAr ? 'قيد المعالجة' : 'Processing', 'class' => 'processing'],
                            'shipped' => ['label' => $isAr ? 'تم الشحن' : 'Shipped', 'class' => 'shipped'],
                            'delivered' => ['label' => $isAr ? 'تم التوصيل' : 'Delivered', 'class' => 'delivered'],
                            'cancelled' => ['label' => $isAr ? 'ملغي' : 'Cancelled', 'class' => 'cancelled'],
                        ];
                        $statusInfo = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'processing'];

                        $statusIconMap = [
                            'pending' => 'M12 8v4l3 3',
                            'confirmed' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                            'processing' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                            'shipped' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',
                            'delivered' => 'M5 13l4 4L19 7',
                            'cancelled' => 'M6 18L18 6M6 6l12 12',
                        ];
                        $statusIconPath = $statusIconMap[$order->status] ?? 'M12 8v4l3 3';
                    @endphp
                    <div class="order-header">
                        <div class="order-info">
                            <h3>{{ app()->getLocale() == 'ar' ? 'طلب' : 'Order' }} #{{ $order->order_number }}</h3>
                            <p class="order-date">{{ $order->created_at->locale(app()->getLocale())->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="order-badge-wrapper">
                            <span class="order-badge {{ $statusInfo['class'] }}">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="{{ $statusIconPath }}" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                {{ $statusInfo['label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress Timeline -->
                    <div class="order-timeline">
                        <div class="timeline-wrapper">
                            @php
                                // تحديد الخطوات المكتملة والنشطة بناءً على حالة الطلب
                                $isArLang = app()->getLocale() == 'ar';
                                $steps = [
                                    ['key' => 'pending', 'label' => $isArLang ? 'قيد الانتظار' : 'Pending', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ['key' => 'confirmed', 'label' => $isArLang ? 'قيد التحضير' : 'Confirmed', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                                    ['key' => 'processing', 'label' => $isArLang ? 'قيد المعالجة' : 'Processing', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                    ['key' => 'shipped', 'label' => $isArLang ? 'تم الشحن' : 'Shipped', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                                    ['key' => 'delivered', 'label' => $isArLang ? 'تم التوصيل' : 'Delivered', 'icon' => 'M5 13l4 4L19 7']
                                ];

                                // ترتيب الحالات
                                $statusOrder = ['pending' => 0, 'confirmed' => 1, 'processing' => 2, 'shipped' => 3, 'delivered' => 4];
                                $currentStatusIndex = $statusOrder[$order->status] ?? 0;

                                // حساب النسبة المئوية للخط الأخضر
                                $progress = ($currentStatusIndex / (count($steps) - 1)) * 100;
                            @endphp

                            <div class="timeline-line">
                                <div class="timeline-progress" style="width: {{ $progress }}%"></div>
                            </div>

                            @foreach($steps as $index => $step)
                                @php
                                    $isCompleted = $index <= $currentStatusIndex;
                                    $isActive = $index == $currentStatusIndex;
                                @endphp
                                <div class="timeline-step {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }}">
                                    <div class="step-icon">
                                        <svg viewBox="0 0 24 24" stroke-width="2">
                                            <path d="{{ $step['icon'] }}" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <span class="step-label">{{ $step['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="order-items">
                        @foreach($order->items as $item)
                            <div class="order-item">
                                <div class="item-details">
                                    @if($item->product_image)
                                        <img src="{{ asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}" class="item-image">
                                    @else
                                        <img src="{{ asset('assets/images/placeholder.jpg') }}" alt="{{ $item->product_name }}" class="item-image">
                                    @endif

                                    <div class="item-info">
                                        @php
                                            $productName = $item->product_name;
                                            // Try to get English name if product exists and we're in English mode
                                            if (app()->getLocale() == 'en' && $item->product) {
                                                $productName = $item->product->name['en'] ?? $item->product->name['ar'] ?? $item->product_name;
                                            } elseif (app()->getLocale() == 'ar' && $item->product) {
                                                $productName = $item->product->name['ar'] ?? $item->product_name;
                                            }
                                        @endphp
                                        <div class="item-name">{{ $productName }}</div>
                                        <div class="item-meta">
                                            <span>{{ app()->getLocale() == 'ar' ? 'الكمية' : 'Qty' }}: {{ $item->quantity }}</span>
                                            @if($item->size)
                                                <span>| {{ app()->getLocale() == 'ar' ? 'المقاس' : 'Size' }}: {{ $item->size }}</span>
                                            @endif
                                            @if($item->color)
                                                <span>| {{ app()->getLocale() == 'ar' ? 'اللون' : 'Color' }}: {{ $item->color }}</span>
                                            @endif
                                            @if($item->shoe_size)
                                                <span>| {{ app()->getLocale() == 'ar' ? 'المقاس' : 'Size' }}: {{ $item->shoe_size }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="item-price">
                                    {{ number_format($item->subtotal, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Footer -->
                    <div class="order-footer">
                        <div class="order-actions">
                            <button class="btn-order btn-details" onclick="showOrderDetails('{{ $order->id }}')">
                                {{ app()->getLocale() == 'ar' ? 'التفاصيل' : 'Details' }}
                            </button>
                            @if(in_array($order->status, ['pending', 'confirmed']))
                                <button class="btn-order btn-cancel" onclick="cancelOrder('{{ $order->id }}')">
                                    {{ app()->getLocale() == 'ar' ? 'إلغاء الطلب' : 'Cancel Order' }}
                                </button>
                            @endif
                        </div>
                        <div class="order-total">
                            <div class="total-label">{{ app()->getLocale() == 'ar' ? 'المجموع:' : 'Total:' }}</div>
                            <div class="total-amount">{{ number_format($order->total, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        @else
            <div class="empty-orders">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3>{{ app()->getLocale() == 'ar' ? 'لا توجد طلبات حالية' : 'No Current Orders' }}</h3>
                <p>{{ app()->getLocale() == 'ar' ? 'جميع طلباتك قد تم تسليمها أو إلغاؤها' : 'All your orders have been delivered or cancelled' }}</p>
                <a href="{{ route('shop') }}" class="btn-shop">{{ app()->getLocale() == 'ar' ? 'تسوق الآن' : 'Shop Now' }}</a>
            </div>
        @endif
    </div>

    <!-- Previous Orders Tab -->
    <div id="previous-orders" class="tab-content">
        <!-- Loading Spinner -->
        <div id="previous-orders-loading" class="loading-spinner" style="display: none;">
            <div class="spinner"></div>
        </div>

        <!-- Desktop Table View -->
        <div id="previous-orders-table-container">
            <table class="orders-table" id="previous-orders-table">
                <thead>
                    <tr>
                        <th>{{ app()->getLocale() == 'ar' ? 'رقم الطلب' : 'Order #' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'التاريخ' : 'Date' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'المنتجات' : 'Products' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'المبلغ' : 'Amount' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody id="previous-orders-tbody">
                    <!-- Data loaded via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-previous-orders" id="previous-orders-mobile">
            <!-- Data loaded via AJAX -->
        </div>

        <!-- Pagination -->
        <div class="pagination-container" id="previous-orders-pagination" style="display: none;">
            <div class="pagination-info" id="pagination-info"></div>
            <button class="pagination-btn" id="prev-page-btn" onclick="loadPreviousOrders(currentPage - 1)">
                {{ app()->getLocale() == 'ar' ? 'السابق' : 'Previous' }}
            </button>
            <div class="pagination-numbers" id="pagination-numbers"></div>
            <button class="pagination-btn" id="next-page-btn" onclick="loadPreviousOrders(currentPage + 1)">
                {{ app()->getLocale() == 'ar' ? 'التالي' : 'Next' }}
            </button>
        </div>

        <!-- Empty State -->
        <div id="previous-orders-empty" class="empty-orders" style="display: none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3>{{ app()->getLocale() == 'ar' ? 'لا توجد طلبات سابقة' : 'No Previous Orders' }}</h3>
            <p>{{ app()->getLocale() == 'ar' ? 'ليس لديك أي طلبات مكتملة حتى الآن' : 'You have no completed orders yet' }}</p>
            <a href="{{ route('shop') }}" class="btn-shop">{{ app()->getLocale() == 'ar' ? 'تسوق الآن' : 'Shop Now' }}</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Pagination State
    let currentPage = 1;
    let lastPage = 1;
    let totalOrders = 0;
    let previousOrdersLoaded = false;

    // Tab Switching
    function switchTab(tab) {
        // Remove active class from all tabs and contents
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

        // Add active class to selected tab and content
        if (tab === 'current') {
            document.querySelector('.tab-button:first-child').classList.add('active');
            document.getElementById('current-orders').classList.add('active');
        } else {
            document.querySelector('.tab-button:last-child').classList.add('active');
            document.getElementById('previous-orders').classList.add('active');

            // Load previous orders if not loaded yet
            if (!previousOrdersLoaded) {
                loadPreviousOrders(1);
            }
        }
    }

    // Show Order Details
    function showOrderDetails(orderId) {
        window.location.href = '/order/' + orderId;
    }

    // Cancel Order
    function cancelOrder(orderId) {
        const isArabic = document.documentElement.getAttribute('dir') === 'rtl';

        Swal.fire({
            title: isArabic ? 'هل أنت متأكد؟' : 'Are you sure?',
            text: isArabic ? 'هل تريد إلغاء هذا الطلب؟ لن تتمكن من التراجع عن هذا الإجراء!' : 'Do you want to cancel this order? You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: isArabic ? 'نعم، إلغاء الطلب' : 'Yes, cancel it!',
            cancelButtonText: isArabic ? 'تراجع' : 'Cancel',
            reverseButtons: isArabic
        }).then((result) => {
            if (result.isConfirmed) {
                // Implement cancel functionality
                fetch('/order/' + orderId + '/cancel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: isArabic ? 'تم الإلغاء!' : 'Cancelled!',
                            text: isArabic ? 'تم إلغاء الطلب بنجاح' : 'Your order has been cancelled.',
                            icon: 'success',
                            confirmButtonColor: '#28a745',
                            confirmButtonText: isArabic ? 'حسناً' : 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: isArabic ? 'خطأ!' : 'Error!',
                            text: data.message || (isArabic ? 'حدث خطأ أثناء إلغاء الطلب' : 'An error occurred while cancelling the order'),
                            icon: 'error',
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: isArabic ? 'حسناً' : 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: isArabic ? 'خطأ!' : 'Error!',
                        text: isArabic ? 'حدث خطأ في الاتصال بالخادم' : 'Connection error occurred',
                        icon: 'error',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: isArabic ? 'حسناً' : 'OK'
                    });
                });
            }
        });
    }

    // Reorder
    function reorder(orderId) {
        const isArabic = document.documentElement.getAttribute('dir') === 'rtl';

        Swal.fire({
            title: isArabic ? 'إعادة الطلب' : 'Reorder',
            text: isArabic ? 'هل تريد إعادة طلب نفس المنتجات؟' : 'Do you want to reorder the same products?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: isArabic ? 'نعم، إعادة الطلب' : 'Yes, reorder!',
            cancelButtonText: isArabic ? 'إلغاء' : 'Cancel',
            reverseButtons: isArabic
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/order/' + orderId + '/reorder';
            }
        });
    }

    // Load Previous Orders via AJAX
    function loadPreviousOrders(page) {
        if (page < 1 || page > lastPage && lastPage > 0) return;

        currentPage = page;

        // Show loading
        document.getElementById('previous-orders-loading').style.display = 'flex';
        document.getElementById('previous-orders-table-container').style.display = 'none';
        document.getElementById('previous-orders-mobile').innerHTML = '';
        document.getElementById('previous-orders-pagination').style.display = 'none';
        document.getElementById('previous-orders-empty').style.display = 'none';

        fetch(`/orders?type=previous&page=${page}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            previousOrdersLoaded = true;
            document.getElementById('previous-orders-loading').style.display = 'none';

            if (data.success && data.data.length > 0) {
                lastPage = data.last_page;
                totalOrders = data.total;

                renderPreviousOrders(data.data);
                renderPagination(data);

                document.getElementById('previous-orders-table-container').style.display = 'block';
                document.getElementById('previous-orders-pagination').style.display = 'flex';
            } else {
                document.getElementById('previous-orders-empty').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error loading previous orders:', error);
            document.getElementById('previous-orders-loading').style.display = 'none';
            document.getElementById('previous-orders-empty').style.display = 'block';
        });
    }

    // Render Previous Orders (Table & Mobile Cards)
    function renderPreviousOrders(orders) {
        const tbody = document.getElementById('previous-orders-tbody');
        const mobileContainer = document.getElementById('previous-orders-mobile');
        const isArabic = document.documentElement.getAttribute('dir') === 'rtl';

        let tableHtml = '';
        let mobileHtml = '';

        const statusLabels = isArabic ? {
            'pending': 'قيد الانتظار',
            'confirmed': 'مؤكد',
            'processing': 'قيد المعالجة',
            'shipped': 'تم الشحن',
            'delivered': 'تم التوصيل',
            'cancelled': 'ملغي'
        } : {
            'pending': 'Pending',
            'confirmed': 'Confirmed',
            'processing': 'Processing',
            'shipped': 'Shipped',
            'delivered': 'Delivered',
            'cancelled': 'Cancelled'
        };

        const currency = isArabic ? 'د.إ' : 'AED';
        const reorderText = isArabic ? 'إعادة الطلب' : 'Reorder';
        const viewText = isArabic ? 'عرض' : 'View';
        const moreText = isArabic ? 'أخرى' : 'more';

        orders.forEach(order => {
            const statusLabel = statusLabels[order.status] || order.status;
            const formattedDate = new Date(order.created_at).toLocaleDateString(isArabic ? 'ar-EG' : 'en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Get product names - use correct language
            let productNames = '';
            let moreCount = 0;
            if (order.items && order.items.length > 0) {
                const names = order.items.slice(0, 2).map(item => {
                    // Use language-specific product name if available
                    if (isArabic) {
                        return item.product_name_ar || item.product_name;
                    } else {
                        return item.product_name_en || item.product_name;
                    }
                });
                productNames = names.join(' + ');
                moreCount = order.items.length - 2;
                if (moreCount > 0) {
                    productNames += ` + ${moreCount} ${moreText}`;
                }
            }

            // Table row
            tableHtml += `
                <tr>
                    <td><strong>#${order.order_number}</strong></td>
                    <td>${formattedDate}</td>
                    <td>${productNames}</td>
                    <td>${Number(order.total).toLocaleString()} ${currency}</td>
                    <td>
                        <span class="status-badge-table ${order.status}">
                            ${statusLabel}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <button class="btn-table btn-reorder" onclick="reorder('${order.id}')">
                                ${reorderText}
                            </button>
                            <button class="btn-table btn-view" onclick="showOrderDetails('${order.id}')">
                                ${viewText}
                            </button>
                        </div>
                    </td>
                </tr>
            `;

            // Mobile card
            mobileHtml += `
                <div class="previous-order-card">
                    <div class="previous-order-header">
                        <div>
                            <div class="previous-order-number">#${order.order_number}</div>
                            <div class="previous-order-date">${formattedDate}</div>
                        </div>
                        <span class="status-badge-table ${order.status}">
                            ${statusLabel}
                        </span>
                    </div>
                    <div class="previous-order-products">${productNames}</div>
                    <div class="previous-order-footer">
                        <div class="previous-order-total">${Number(order.total).toLocaleString()} ${currency}</div>
                        <div class="previous-order-actions">
                            <button class="btn-table btn-reorder" onclick="reorder('${order.id}')">
                                ${reorderText}
                            </button>
                            <button class="btn-table btn-view" onclick="showOrderDetails('${order.id}')">
                                ${viewText}
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        tbody.innerHTML = tableHtml;
        mobileContainer.innerHTML = mobileHtml;
    }

    // Render Pagination
    function renderPagination(data) {
        const paginationInfo = document.getElementById('pagination-info');
        const paginationNumbers = document.getElementById('pagination-numbers');
        const prevBtn = document.getElementById('prev-page-btn');
        const nextBtn = document.getElementById('next-page-btn');
        const isArabic = document.documentElement.getAttribute('dir') === 'rtl';

        // Update info
        const start = (data.current_page - 1) * data.per_page + 1;
        const end = Math.min(data.current_page * data.per_page, data.total);

        if (isArabic) {
            paginationInfo.textContent = `عرض ${start} - ${end} من ${data.total} طلب`;
        } else {
            paginationInfo.textContent = `Showing ${start} - ${end} of ${data.total} orders`;
        }

        // Update prev/next buttons
        prevBtn.disabled = data.current_page <= 1;
        nextBtn.disabled = data.current_page >= data.last_page;

        // Render page numbers
        let numbersHtml = '';
        const maxVisible = 5;
        let startPage = Math.max(1, data.current_page - Math.floor(maxVisible / 2));
        let endPage = Math.min(data.last_page, startPage + maxVisible - 1);

        if (endPage - startPage + 1 < maxVisible) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        if (startPage > 1) {
            numbersHtml += `<button class="page-number" onclick="loadPreviousOrders(1)">1</button>`;
            if (startPage > 2) {
                numbersHtml += `<span style="padding: 0 5px;">...</span>`;
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === data.current_page ? 'active' : '';
            numbersHtml += `<button class="page-number ${activeClass}" onclick="loadPreviousOrders(${i})">${i}</button>`;
        }

        if (endPage < data.last_page) {
            if (endPage < data.last_page - 1) {
                numbersHtml += `<span style="padding: 0 5px;">...</span>`;
            }
            numbersHtml += `<button class="page-number" onclick="loadPreviousOrders(${data.last_page})">${data.last_page}</button>`;
        }

        paginationNumbers.innerHTML = numbersHtml;
    }

    // Animate progress bars on load
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.timeline-progress');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    });

    // ========================================
    // Capacitor Real-time Order Status Updates
    // ========================================
    (function() {
        // يعمل في جميع الأوضاع (Capacitor والمتصفح العادي)
        const isCapacitor = document.body.classList.contains('capacitor-app');

        console.log('📱 Real-time Order Status: Initializing...');
        console.log('📱 Is Capacitor Mode:', isCapacitor);

        const isArabic = document.documentElement.getAttribute('dir') === 'rtl';
        const UPDATE_INTERVAL = isCapacitor ? 10000 : 20000; // تحديث كل 10 ثواني في Capacitor، 20 ثانية في المتصفح
        let updateTimer = null;
        let lastUpdateHash = ''; // لتجنب التحديثات غير الضرورية
        let retryCount = 0;
        const MAX_RETRIES = 3;

        // خريطة الحالات
        const statusOrder = {
            'pending': 0,
            'confirmed': 1,
            'processing': 2,
            'shipped': 3,
            'delivered': 4
        };

        const statusLabels = {
            'pending': isArabic ? 'قيد الانتظار' : 'Pending',
            'confirmed': isArabic ? 'قيد التحضير' : 'Confirmed',
            'processing': isArabic ? 'قيد المعالجة' : 'Processing',
            'shipped': isArabic ? 'تم الشحن' : 'Shipped',
            'delivered': isArabic ? 'تم التوصيل' : 'Delivered',
            'cancelled': isArabic ? 'ملغي' : 'Cancelled'
        };

        const statusClasses = {
            'pending': 'pending',
            'confirmed': 'processing',
            'processing': 'processing',
            'shipped': 'shipped',
            'delivered': 'delivered',
            'cancelled': 'cancelled'
        };

        // جلب جميع IDs الطلبات من الصفحة
        function getOrderIds() {
            const orderCards = document.querySelectorAll('.order-card[data-order-id]');
            return Array.from(orderCards).map(card => card.getAttribute('data-order-id'));
        }

        // تحديث Timeline لطلب معين
        function updateOrderTimeline(orderId, statusData) {
            // تحويل orderId إلى string للمقارنة
            const orderIdStr = String(orderId);
            const orderCard = document.querySelector(`.order-card[data-order-id="${orderIdStr}"]`);

            if (!orderCard) {
                console.warn(`⚠️ Order card not found for ID: ${orderIdStr}`);
                return;
            }

            const timeline = orderCard.querySelector('.order-timeline');
            if (!timeline) {
                console.warn(`⚠️ Timeline not found for order: ${orderIdStr}`);
                return;
            }

            console.log(`🔧 Updating order ${orderIdStr}: status=${statusData.status}, progress=${statusData.progress}%`);

            const currentStatusIndex = statusData.status_index;
            const progress = statusData.progress;
            const isCancelled = statusData.is_cancelled || statusData.status === 'cancelled';

            // تحديث شريط التقدم مع تأثير بصري
            const progressBar = timeline.querySelector('.timeline-progress');
            if (progressBar) {
                const currentWidth = parseFloat(progressBar.style.width) || 0;
                if (currentWidth !== progress) {
                    progressBar.style.transition = 'width 0.5s ease-in-out';
                    progressBar.style.width = progress + '%';

                    // تغيير لون الشريط للأحمر في حالة الإلغاء
                    if (isCancelled) {
                        progressBar.style.backgroundColor = '#ef4444';
                    } else {
                        progressBar.style.backgroundColor = '';
                    }

                    // إضافة تأثير وميض للإشارة للتحديث
                    orderCard.style.transition = 'box-shadow 0.3s ease';
                    const glowColor = isCancelled ? 'rgba(239, 68, 68, 0.5)' : 'rgba(0, 200, 83, 0.5)';
                    orderCard.style.boxShadow = `0 0 15px ${glowColor}`;
                    setTimeout(() => {
                        orderCard.style.boxShadow = '';
                    }, 1000);
                }
            }

            // تحديث حالة كل خطوة
            const steps = timeline.querySelectorAll('.timeline-step');
            if (!isCancelled) {
                steps.forEach((step, index) => {
                    step.classList.remove('completed', 'active');
                    if (index <= currentStatusIndex) {
                        step.classList.add('completed');
                    }
                    if (index === currentStatusIndex) {
                        step.classList.add('active');
                    }
                });
            } else {
                // في حالة الإلغاء، إزالة كل الحالات
                steps.forEach(step => {
                    step.classList.remove('completed', 'active');
                });
            }

            // تحديث Badge الحالة
            const badge = orderCard.querySelector('.order-badge');
            if (badge) {
                // إزالة جميع classes الحالة
                Object.values(statusClasses).forEach(cls => badge.classList.remove(cls));
                // إضافة class الحالة الجديدة
                badge.classList.add(statusClasses[statusData.status] || 'pending');
                // تحديث النص - البحث عن النص داخل الـ span
                const badgeSpan = badge.querySelector('span') || badge;
                const textNodes = Array.from(badgeSpan.childNodes).filter(n => n.nodeType === Node.TEXT_NODE);
                if (textNodes.length > 0) {
                    textNodes[textNodes.length - 1].textContent = statusData.status_label;
                } else {
                    // إذا لم يوجد نص، ابحث عن آخر text node
                    const allTextNodes = Array.from(badge.childNodes).filter(n => n.nodeType === Node.TEXT_NODE);
                    if (allTextNodes.length > 0) {
                        allTextNodes[allTextNodes.length - 1].textContent = ' ' + statusData.status_label;
                    }
                }
            }

            console.log(`✅ Updated order ${orderId}: ${statusData.status}`);
        }

        // جلب حالات الطلبات من الخادم
        async function fetchOrderStatuses() {
            try {
                const orderIds = getOrderIds();
                if (orderIds.length === 0) {
                    console.log('⚠️ No orders found on page');
                    return;
                }

                console.log('📡 Fetching status for orders:', orderIds);

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                };
                if (csrfToken) {
                    headers['X-CSRF-TOKEN'] = csrfToken.content;
                }

                const apiUrl = '/api/orders/status?order_ids=' + orderIds.join(',') + '&locale=' + (isArabic ? 'ar' : 'en') + '&_t=' + Date.now();
                console.log('📡 Calling API:', apiUrl);

                const response = await fetch(apiUrl, {
                    method: 'GET',
                    headers: headers,
                    credentials: 'include',
                    cache: 'no-store'
                });

                console.log('📥 Response status:', response.status);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('❌ API Error:', errorText);
                    retryCount++;
                    if (retryCount >= MAX_RETRIES) {
                        console.error('❌ Max retries reached, will try again on next interval');
                        retryCount = 0;
                    }
                    throw new Error('Network response was not ok: ' + response.status);
                }

                // إعادة تعيين عداد المحاولات عند النجاح
                retryCount = 0;

                const data = await response.json();
                console.log('📦 Received data:', data);

                if (data.success && data.orders) {
                    data.orders.forEach(order => {
                        updateOrderTimeline(order.id, order);
                    });
                    console.log(`🔄 Real-time update completed at ${new Date().toLocaleTimeString()}`);
                }
            } catch (error) {
                console.error('❌ Error fetching order statuses:', error);
            }
        }

        // بدء التحديث التلقائي
        function startRealTimeUpdates() {
            // تحديث فوري عند التحميل
            console.log('🚀 Starting real-time updates...');
            const orderIds = getOrderIds();
            console.log('📋 Found orders on page:', orderIds);

            if (orderIds.length === 0) {
                console.log('⚠️ No orders found, skipping real-time updates');
                return;
            }

            // تحديث أول مباشرة
            fetchOrderStatuses();

            // تحديث دوري
            updateTimer = setInterval(fetchOrderStatuses, UPDATE_INTERVAL);
            console.log(`⏱️ Real-time updates started (every ${UPDATE_INTERVAL / 1000}s)`);
        }

        // إيقاف التحديث عند مغادرة الصفحة
        function stopRealTimeUpdates() {
            if (updateTimer) {
                clearInterval(updateTimer);
                updateTimer = null;
                console.log('⏹️ Real-time updates stopped');
            }
        }

        // الاستماع لأحداث الصفحة
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopRealTimeUpdates();
            } else {
                startRealTimeUpdates();
            }
        });

        // بدء التحديثات
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', startRealTimeUpdates);
        } else {
            startRealTimeUpdates();
        }
    })();
</script>
@endpush

@endsection
