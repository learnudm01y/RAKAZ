<!-- Modal Content (Regular View) -->
<div class="row h-100">
    <!-- Customer Info (Right Side) -->
    <div class="col-lg-4 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold"><i class="fas fa-user me-2"></i>{{ __('admin.orders.modal.customer_info') }}</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="text-muted small">{{ __('admin.orders.modal.name') }}</label>
                        <div class="fw-bold">{{ $order->customer_name }}</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">{{ __('admin.orders.modal.phone') }}</label>
                        <div dir="ltr" class="text-end">{{ $order->customer_phone }}</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">{{ __('admin.orders.modal.email') }}</label>
                        <div class="text-break">{{ $order->customer_email }}</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">{{ __('admin.orders.modal.payment_method') }}</label>
                        <div>
                            @if($order->payment_method == 'cash')
                                {{ __('admin.orders.modal.cash_on_delivery') }}
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">{{ __('admin.orders.modal.shipping_address') }}</label>
                        <div>{{ $order->shipping_address }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items (Left Side) -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold"><i class="fas fa-shopping-bag me-2"></i>{{ __('admin.orders.modal.products_count', ['count' => $order->items->count()]) }}</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;"></th>
                            <th>{{ __('admin.orders.modal.product') }}</th>
                            <th>{{ __('admin.orders.modal.attributes') }}</th>
                            <th class="text-center">{{ __('admin.orders.modal.quantity') }}</th>
                            <th class="text-end">{{ __('admin.orders.modal.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            @php
                                $product = $item->product;
                                $mainImage = null;
                                if ($product && $product->main_image) {
                                    $mainImage = asset('storage/' . $product->main_image);
                                } elseif ($product && $product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0) {
                                    $mainImage = asset('storage/' . $product->gallery_images[0]);
                                } else {
                                    $mainImage = asset('assets/images/placeholder.jpg');
                                }
                                $productName = $product ? (is_array($product->name) ? ($product->name[app()->getLocale()] ?? $product->name['ar'] ?? $product->name['en'] ?? 'Product') : $product->name) : __('admin.orders.modal.deleted_product');
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ $mainImage }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-bold small">{{ $productName }}</div>
                                    @if($product)<div class="text-muted x-small">SKU: {{ $product->sku }}</div>@endif
                                </td>
                                <td>
                                    @if($item->color)<span class="badge bg-light text-dark border me-1">{{ $item->color }}</span>@endif
                                    @if($item->size)<span class="badge bg-light text-dark border">{{ $item->size }}</span>@endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end small">{{ __('admin.orders.modal.subtotal') }}:</td>
                            <td class="text-end fw-bold">{{ number_format($order->total_amount - ($order->shipping_fee ?? 0) + ($order->discount_amount ?? 0), 2) }}</td>
                        </tr>
                        @if($order->shipping_fee > 0)
                        <tr>
                            <td colspan="4" class="text-end small">{{ __('admin.orders.modal.shipping') }}:</td>
                            <td class="text-end">{{ number_format($order->shipping_fee, 2) }}</td>
                        </tr>
                        @endif
                        @if($order->discount_amount > 0)
                        <tr>
                            <td colspan="4" class="text-end small text-success">{{ __('admin.orders.modal.discount') }}:</td>
                            <td class="text-end text-success">-{{ number_format($order->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end fw-bold">{{ __('admin.orders.modal.grand_total') }}:</td>
                            <td class="text-end fw-bold text-primary">{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer bg-white">
                <label class="text-muted small mb-2">{{ __('admin.orders.modal.change_status') }}</label>
                <form id="updateStatusForm" data-order-id="{{ $order->id }}">
                    <div class="d-flex gap-2 align-items-center">
                        <div style="width: 200px;">
                            <select class="form-select" id="modalStatusSelect">
                                @php
                                    $statuses = [
                                        'pending' => __('admin.orders.statuses.pending'),
                                        'confirmed' => __('admin.orders.statuses.confirmed'),
                                        'processing' => __('admin.orders.statuses.processing'),
                                        'shipped' => __('admin.orders.statuses.shipped'),
                                        'delivered' => __('admin.orders.statuses.delivered'),
                                        'cancelled' => __('admin.orders.statuses.cancelled')
                                    ];
                                @endphp
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" id="updateStatusBtn">
                            {{ __('admin.orders.modal.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
