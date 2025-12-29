{{-- Overlay Info Content (Loaded via AJAX on hover) --}}
@if($product->brand)
    <p class="overlay-product-brand">{{ $product->brand->getName() }}</p>
@endif
<h3 class="overlay-product-name">{{ $product->getName() }}</h3>
<p class="overlay-product-price">
    @if($product->sale_price && $product->sale_price < $product->price)
        <span class="overlay-sale-price">{{ number_format($product->sale_price, 0) }} {{ app()->getLocale() == 'ar' ? 'درهم إماراتي' : 'AED' }}</span>
        <span class="overlay-original-price">{{ number_format($product->price, 0) }}</span>
    @else
        {{ number_format($product->price, 0) }} {{ app()->getLocale() == 'ar' ? 'درهم إماراتي' : 'AED' }}
    @endif
</p>

@php
    $hasRegularSizes = $product->productSizes && $product->productSizes->count() > 0;
    $hasShoeSizes = $product->productShoeSizes && $product->productShoeSizes->count() > 0;
    $hasAnySizes = $hasRegularSizes || $hasShoeSizes;
@endphp

@if($product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0)
    <div class="overlay-gallery-section">
        @php
            $totalGalleryImages = 1 + count($product->gallery_images);
        @endphp
        @if($totalGalleryImages > 3)
            <button class="overlay-gallery-nav prev-gallery" data-direction="prev">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        @endif
        <div class="overlay-gallery-images">
            @if($product->main_image)
                <div class="overlay-gallery-link" data-image-src="{{ asset('storage/' . $product->main_image) }}" data-image-index="0">
                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName() }}" class="overlay-gallery-thumb" data-image-index="0" loading="lazy">
                </div>
            @endif
            @foreach($product->gallery_images as $index => $galleryImage)
                <div class="overlay-gallery-link" data-image-src="{{ asset('storage/' . $galleryImage) }}" data-image-index="{{ $index + 1 }}">
                    <img src="{{ asset('storage/' . $galleryImage) }}" alt="{{ $product->getName() }}" class="overlay-gallery-thumb" data-image-index="{{ $index + 1 }}" loading="lazy">
                </div>
            @endforeach
        </div>
        @if($totalGalleryImages > 3)
            <button class="overlay-gallery-nav next-gallery" data-direction="next">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
        @endif
    </div>
@endif

@if($hasAnySizes)
    @php
        $totalSizes = ($hasRegularSizes ? $product->productSizes->count() : 0) + ($hasShoeSizes ? $product->productShoeSizes->count() : 0);
    @endphp
    <div class="overlay-sizes-wrapper">
        @if($totalSizes > 3)
            <button class="overlay-sizes-nav prev-sizes" data-direction="prev">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        @endif
        <div class="overlay-sizes-section">
            @if($hasRegularSizes)
                @foreach($product->productSizes as $size)
                    <span class="overlay-size-item">{{ $size->name }}</span>
                @endforeach
            @endif
            @if($hasShoeSizes)
                @foreach($product->productShoeSizes as $shoeSize)
                    <span class="overlay-size-item">{{ $shoeSize->size }}</span>
                @endforeach
            @endif
        </div>
        @if($totalSizes > 3)
            <button class="overlay-sizes-nav next-sizes" data-direction="next">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
        @endif
    </div>
@endif
