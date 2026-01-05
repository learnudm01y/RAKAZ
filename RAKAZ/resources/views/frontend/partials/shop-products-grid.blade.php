@forelse($products as $product)
@php
    // Detect if user is on mobile device
    $isMobile = request()->header('User-Agent') && (stripos(request()->header('User-Agent'), 'mobile') !== false || stripos(request()->header('User-Agent'), 'tablet') !== false || stripos(request()->header('User-Agent'), 'android') !== false || stripos(request()->header('User-Agent'), 'iphone') !== false || stripos(request()->header('User-Agent'), 'ipad') !== false);

    // Use mobile images if available and on mobile device, otherwise use desktop images
    $mainImage = $isMobile && $product->mobile_main_image
        ? asset('storage/' . $product->mobile_main_image)
        : ($product->main_image ? asset('storage/' . $product->main_image) : asset('assets/images/placeholder.jpg'));
@endphp
<div class="product-card" data-product-url="{{ route('product.details', $product->getSlug()) }}" style="cursor: pointer;">
    <div class="product-image-wrapper" style="position: relative;">
        <a href="{{ route('product.details', $product->getSlug()) }}" style="display: block;" class="product-main-link" data-product-id="{{ $product->id }}">
            <img src="{{ $mainImage }}"
                alt="{{ $product->getName() }}" class="product-image-primary">
            <!-- Secondary image will be loaded on hover via AJAX -->
        </a>
        <button class="wishlist-btn {{ auth()->check() && \App\Models\Wishlist::isInWishlist(auth()->id(), $product->id) ? 'active' : '' }}" data-product-id="{{ $product->id }}">
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
                    <span class="discount-text">{{ app()->getLocale() == 'ar' ? 'تخفيض' : 'DISCOUNT' }}</span>
                    <span class="discount-percent">%{{ $discountPercent }}</span>
                </div>
            </div>
        @elseif($product->is_new)
            <span class="badge new-season">{{ app()->getLocale() == 'ar' ? 'موسم جديد' : 'New Season' }}</span>
        @elseif($product->is_on_sale)
            <span class="badge discount">{{ app()->getLocale() == 'ar' ? 'عرض خاص' : 'On Sale' }}</span>
        @endif

        <!-- Hover Content: Colors, Gallery, Sizes - Hidden on Mobile/Tablet -->
        @if(!$isMobile)
        @include('frontend.partials.product-hover-skeleton', ['product' => $product])
        @endif
    </div>

    <div class="product-info">
        @if($product->brand)
        <p class="product-brand">{{ $product->brand->getName() }}</p>
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
