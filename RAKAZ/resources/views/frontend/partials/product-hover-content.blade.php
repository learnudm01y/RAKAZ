<!-- Colors Display -->
@if(count($productColors) > 0)
<div class="product-colors-display">
    @foreach($productColors->take(3) as $color)
    @php
        if(is_object($color)) {
            $hexCode = $color->hex_code ?? '#cccccc';
            $colorName = is_array($color->name)
                ? (app()->getLocale() == 'ar' ? ($color->name['ar'] ?? $color->name['en'] ?? '') : ($color->name['en'] ?? $color->name['ar'] ?? ''))
                : ($color->name ?? '');
        } else {
            $hexCode = is_array($color) ? ($color['hex'] ?? $color['hex_code'] ?? '#cccccc') : '#cccccc';
            $colorName = is_array($color)
                ? (app()->getLocale() == 'ar' ? ($color['name_ar'] ?? $color['name_en'] ?? $color['name'] ?? '') : ($color['name_en'] ?? $color['name_ar'] ?? $color['name'] ?? ''))
                : '';
        }
    @endphp
    <div class="product-color-circle"
         style="background-color: {{ $hexCode }}"
         title="{{ $colorName }}">
    </div>
    @endforeach
    @if(count($productColors) > 3)
        <div class="product-color-more">+{{ count($productColors) - 3 }}</div>
    @endif
</div>
@endif

<!-- Gallery Section -->
@if($product->colorImages && $product->colorImages->count() > 0)
<div class="product-gallery-section">
    <div class="product-gallery-wrapper">
        <button class="gallery-nav-btn gallery-prev" data-product-id="{{ $product->id }}" type="button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
        <div class="product-gallery-container" data-product-id="{{ $product->id }}">
            @foreach($product->colorImages as $colorImage)
            <a href="{{ route('product.details', $product->getSlug()) }}?color={{ $colorImage->color_id }}" data-product-id="{{ $product->id }}" data-color-id="{{ $colorImage->color_id }}">
                <img src="{{ asset('storage/' . $colorImage->image) }}"
                     alt="{{ $product->getName() }} - {{ $colorImage->color?->translated_name ?? '' }}"
                     class="product-gallery-item"
                     data-product-id="{{ $product->id }}"
                     data-color-id="{{ $colorImage->color_id }}">
            </a>
            @endforeach
        </div>
        <button class="gallery-nav-btn gallery-next" data-product-id="{{ $product->id }}" type="button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
    </div>
</div>
@endif

<!-- Sizes Display -->
@if($hasSizes)
<div class="product-sizes-display">
    <button class="sizes-scroll-btn prev-size" data-product-id="{{ $product->id }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    <div class="product-sizes-wrapper" data-product-id="{{ $product->id }}">
        @foreach($product->sizes as $size)
        <div class="product-size-item" data-size="{{ is_array($size) ? ($size['value'] ?? $size['ar'] ?? $size['en']) : $size }}">
            {{ is_array($size) ? (app()->getLocale() == 'ar' ? ($size['ar'] ?? $size['en']) : ($size['en'] ?? $size['ar'])) : $size }}
        </div>
        @endforeach
    </div>
    <button class="sizes-scroll-btn next-size" data-product-id="{{ $product->id }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 5l7 7-7 7" />
        </svg>
    </button>
</div>
@endif
