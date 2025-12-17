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

        /* توحيد ألوان جميع الحالات النشطة باللون الأخضر */
        .order-badge.pending,
        .order-badge.processing,
        .order-badge.on-delivery,
        .order-badge.delivered {
            background: #d4edda;
            color: #155724;
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
            left: 8%;
            right: 8%;
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
            text-align: right;
            font-weight: 600;
            font-size: 14px;
            color: #1a1a1a;
            border-bottom: 2px solid #e5e5e5;
        }

        .orders-table td {
            padding: 20px;
            text-align: right;
            font-size: 14px;
            color: #666;
            border-bottom: 1px solid #f0f0f0;
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

        .status-badge-table.delivered {
            background: #d4edda;
            color: #155724;
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

        /* Responsive */
        @media (max-width: 1200px) {
            .orders-grid {
                grid-template-columns: repeat(2, 1fr);
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
        }
    </style>
@endpush

<div class="orders-page">
    <!-- Tabs Navigation -->
    <div class="orders-tabs">
        <button class="tab-button active" onclick="switchTab('current')">الطلبات الحية</button>
        <button class="tab-button" onclick="switchTab('previous')">الطلبات السابقة</button>
    </div>

    <!-- Current Orders Tab -->
    <div id="current-orders" class="tab-content active">
        @php
            $currentOrders = $orders->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped']);
        @endphp

        @if($currentOrders->count() > 0)
            <div class="orders-grid">
            @foreach($currentOrders as $order)
                <div class="order-card">
                    <!-- Order Header -->
                    <div class="order-header">
                        <div class="order-info">
                            <h3>طلب #{{ $order->order_number }}</h3>
                            <p class="order-date">{{ $order->created_at->locale('ar')->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            @php
                                // توحيد عرض الحالات - جميع الحالات تستخدم نفس اللون الأخضر
                                $statusMap = [
                                    'pending' => ['label' => 'قيد التحضير', 'class' => 'processing'],
                                    'confirmed' => ['label' => 'قيد التحضير', 'class' => 'processing'],
                                    'processing' => ['label' => 'قيد التحضير', 'class' => 'processing'],
                                    'shipped' => ['label' => 'في الطريق للتوصيل', 'class' => 'on-delivery'],
                                    'delivered' => ['label' => 'تم التوصيل', 'class' => 'delivered'],
                                ];
                                $statusInfo = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'processing'];
                            @endphp
                            <span class="order-badge {{ $statusInfo['class'] }}">
                                ⏱ {{ $statusInfo['label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress Timeline -->
                    <div class="order-timeline">
                        <div class="timeline-wrapper">
                            @php
                                // تحديد الخطوات المكتملة والنشطة بناءً على حالة الطلب
                                $steps = [
                                    ['key' => 'pending', 'label' => 'تم الطلب', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ['key' => 'confirmed', 'label' => 'قيد التحضير', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                                    ['key' => 'processing', 'label' => 'تم الشحن', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                                    ['key' => 'shipped', 'label' => 'قيد التوصيل', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                    ['key' => 'delivered', 'label' => 'تم التوصيل', 'icon' => 'M5 13l4 4L19 7']
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
                                        <div class="item-name">{{ $item->product_name }}</div>
                                        <div class="item-meta">
                                            <span>الكمية: {{ $item->quantity }}</span>
                                            @if($item->size)
                                                <span>| المقاس: {{ $item->size }}</span>
                                            @endif
                                            @if($item->color)
                                                <span>| اللون: {{ $item->color }}</span>
                                            @endif
                                            @if($item->shoe_size)
                                                <span>| المقاس: {{ $item->shoe_size }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="item-price">
                                    {{ number_format($item->subtotal, 0) }} د.إ
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Footer -->
                    <div class="order-footer">
                        <div class="order-actions">
                            <button class="btn-order btn-details" onclick="showOrderDetails('{{ $order->id }}')">
                                التفاصيل
                            </button>
                            @if(in_array($order->status, ['pending', 'confirmed']))
                                <button class="btn-order btn-cancel" onclick="cancelOrder('{{ $order->id }}')">
                                    إلغاء الطلب
                                </button>
                            @else
                                <button class="btn-order btn-track" onclick="trackOrder('{{ $order->id }}')">
                                    تتبع الطلب
                                </button>
                            @endif
                        </div>
                        <div class="order-total">
                            <div class="total-label">المجموع:</div>
                            <div class="total-amount">{{ number_format($order->total, 0) }} د.إ</div>
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
                <h3>لا توجد طلبات حالية</h3>
                <p>جميع طلباتك قد تم تسليمها أو إلغاؤها</p>
                <a href="{{ route('shop') }}" class="btn-shop">تسوق الآن</a>
            </div>
        @endif
    </div>

    <!-- Previous Orders Tab -->
    <div id="previous-orders" class="tab-content">
        @php
            $previousOrders = $orders->whereIn('status', ['delivered', 'cancelled']);
        @endphp

        @if($previousOrders->count() > 0)
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>التاريخ</th>
                        <th>المنتجات</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($previousOrders as $order)
                        <tr>
                            <td><strong>#{{ $order->order_number }}</strong></td>
                            <td>{{ $order->created_at->locale('ar')->translatedFormat('d F Y') }}</td>
                            <td>
                                @php
                                    $productNames = $order->items->pluck('product_name')->take(2)->toArray();
                                    $moreCount = $order->items->count() - 2;
                                @endphp
                                {{ implode(' + ', $productNames) }}
                                @if($moreCount > 0)
                                    + {{ $moreCount }} أخرى
                                @endif
                            </td>
                            <td>{{ number_format($order->total, 0) }} د.إ</td>
                            <td>
                                <span class="status-badge-table {{ $order->status == 'delivered' ? 'delivered' : 'cancelled' }}">
                                    {{ $order->status == 'delivered' ? 'تم التوصيل' : 'تم الإلغاء' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button class="btn-table btn-reorder" onclick="reorder('{{ $order->id }}')">
                                        إعادة الطلب
                                    </button>
                                    <button class="btn-table btn-view" onclick="showOrderDetails('{{ $order->id }}')">
                                        عرض
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-orders">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3>لا توجد طلبات سابقة</h3>
                <p>ليس لديك أي طلبات مكتملة حتى الآن</p>
                <a href="{{ route('shop') }}" class="btn-shop">تسوق الآن</a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
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
        }
    }

    // Show Order Details
    function showOrderDetails(orderId) {
        window.location.href = '/order/' + orderId;
    }

    // Track Order
    function trackOrder(orderId) {
        // Implement tracking functionality
        alert('تتبع الطلب #' + orderId);
    }

    // Cancel Order
    function cancelOrder(orderId) {
        if (confirm('هل أنت متأكد من إلغاء هذا الطلب?')) {
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
                    alert('تم إلغاء الطلب بنجاح');
                    location.reload();
                }
            });
        }
    }

    // Reorder
    function reorder(orderId) {
        if (confirm('هل تريد إعادة طلب نفس المنتجات?')) {
            window.location.href = '/order/' + orderId + '/reorder';
        }
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
</script>
@endpush

@endsection
