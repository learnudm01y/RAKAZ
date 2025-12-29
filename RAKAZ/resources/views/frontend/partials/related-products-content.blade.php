<!-- Related Products Content -->
<div class="section-header">
    <h2 class="section-title">{{ $title }}</h2>
</div>
<div class="products-slider">
    <button class="slider-btn prev">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>
    <div class="products-container">
        @foreach($relatedProducts as $relatedProduct)
            <div class="product-card" data-product-id="{{ $relatedProduct->id }}">
                <a href="{{ route('product.details', $relatedProduct->getSlug()) }}" class="product-image-wrapper">
                    @if($relatedProduct->main_image)
                        <img src="{{ asset('storage/' . $relatedProduct->main_image) }}" alt="{{ $relatedProduct->getName() }}" class="product-image-primary main-product-image">
                    @endif
                    @if($relatedProduct->gallery_images && is_array($relatedProduct->gallery_images) && count($relatedProduct->gallery_images) > 0)
                        <img src="{{ asset('storage/' . $relatedProduct->gallery_images[0]) }}" alt="{{ $relatedProduct->getName() }}" class="product-image-secondary">
                    @elseif($relatedProduct->main_image)
                        <img src="{{ asset('storage/' . $relatedProduct->main_image) }}" alt="{{ $relatedProduct->getName() }}" class="product-image-secondary">
                    @endif
                    <button class="wishlist-btn" data-product-id="{{ $relatedProduct->id }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                    @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                        @php
                            $discountPercent = round((($relatedProduct->price - $relatedProduct->sale_price) / $relatedProduct->price) * 100);
                        @endphp
                        <div class="discount-badge-wrapper">
                            <img src="{{ asset('assets/images/discount.png') }}" alt="Discount" class="discount-badge-image">
                            <div class="discount-badge-text">
                                <span class="discount-text-ar">تخفيض</span>
                                <span class="discount-text-en">DISCOUNT</span>
                                <span class="discount-percent">%{{ $discountPercent }}</span>
                            </div>
                        </div>
                    @elseif($relatedProduct->is_new)
                        <span class="badge new-season">{{ app()->getLocale() == 'ar' ? 'موسم جديد' : 'New Season' }}</span>
                    @elseif($relatedProduct->is_on_sale)
                        <span class="badge discount">{{ app()->getLocale() == 'ar' ? 'عرض خاص' : 'On Sale' }}</span>
                    @endif
                </a>
                <div class="product-info">
                    @if($relatedProduct->brand)
                        <p class="product-brand">{{ $relatedProduct->brand->getName() }}</p>
                    @endif
                    <h3 class="product-name">{{ $relatedProduct->getName() }}</h3>
                    <p class="product-price">
                        @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                            <span class="sale-price">{{ number_format($relatedProduct->sale_price, 0) }} {{ app()->getLocale() == 'ar' ? 'درهم إماراتي' : 'AED' }}</span>
                            <span class="original-price">{{ number_format($relatedProduct->price, 0) }}</span>
                        @else
                            {{ number_format($relatedProduct->price, 0) }} {{ app()->getLocale() == 'ar' ? 'درهم إماراتي' : 'AED' }}
                        @endif
                    </p>

                    @php
                        $hasColors = $relatedProduct->productColors && $relatedProduct->productColors->count() > 0;
                    @endphp

                    @if($hasColors)
                        <div class="featured-colors-wrapper">
                            <div class="featured-color-dots">
                                @foreach($relatedProduct->productColors->take(3) as $color)
                                    <span class="featured-color-dot" style="background: {{ $color->hex_code }}; @if($color->hex_code == '#FFFFFF' || $color->hex_code == '#ffffff') border: 1px solid #ddd; @endif"></span>
                                @endforeach
                                @if($relatedProduct->productColors->count() > 3)
                                    <span class="featured-color-more">+{{ $relatedProduct->productColors->count() - 3 }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Hover Overlay --}}
                <div class="featured-product-overlay" data-overlay-for="{{ $relatedProduct->id }}">
                    <a href="{{ route('product.details', $relatedProduct->getSlug()) }}" class="overlay-link-wrapper">
                    <div class="overlay-image-section">
                        @if($relatedProduct->main_image)
                            <img src="{{ asset('storage/' . $relatedProduct->main_image) }}" alt="{{ $relatedProduct->getName() }}" class="overlay-main-image overlay-image-primary">
                        @endif
                        @if($relatedProduct->gallery_images && is_array($relatedProduct->gallery_images) && count($relatedProduct->gallery_images) > 0)
                            <img src="{{ asset('storage/' . $relatedProduct->gallery_images[0]) }}" alt="{{ $relatedProduct->getName() }}" class="overlay-main-image overlay-image-secondary">
                        @elseif($relatedProduct->main_image)
                            <img src="{{ asset('storage/' . $relatedProduct->main_image) }}" alt="{{ $relatedProduct->getName() }}" class="overlay-main-image overlay-image-secondary">
                        @endif
                        @if($relatedProduct->is_new)
                            <span class="overlay-badge new-season">{{ app()->getLocale() == 'ar' ? 'موسم جديد' : 'New Season' }}</span>
                        @endif
                    </div>
                    </a>
                    <button class="overlay-wishlist-btn" data-product-id="{{ $relatedProduct->id }}" type="button" onclick="event.stopPropagation(); event.preventDefault(); window.toggleWishlist(this, '{{ $relatedProduct->id }}');">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                    <div class="overlay-info-section">
                        @if($relatedProduct->brand)
                            <p class="overlay-product-brand">{{ $relatedProduct->brand->getName() }}</p>
                        @endif
                        <h3 class="overlay-product-name">{{ $relatedProduct->getName() }}</h3>
                        <p class="overlay-product-price">
                            @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                <span class="overlay-sale-price">{{ number_format($relatedProduct->sale_price, 0) }} {{ app()->getLocale() == 'ar' ? 'درهم إماراتي' : 'AED' }}</span>
                                <span class="overlay-original-price">{{ number_format($relatedProduct->price, 0) }}</span>
                            @else
                                {{ number_format($relatedProduct->price, 0) }} {{ app()->getLocale() == 'ar' ? 'درهم إماراتي' : 'AED' }}
                            @endif
                        </p>

                        @php
                            $hasRegularSizes = $relatedProduct->productSizes && $relatedProduct->productSizes->count() > 0;
                            $hasShoeSizes = $relatedProduct->productShoeSizes && $relatedProduct->productShoeSizes->count() > 0;
                            $hasAnySizes = $hasRegularSizes || $hasShoeSizes;
                        @endphp

                        @if($relatedProduct->gallery_images && is_array($relatedProduct->gallery_images) && count($relatedProduct->gallery_images) > 0)
                            <div class="overlay-gallery-section">
                                @php
                                    $totalGalleryImages = 1 + count($relatedProduct->gallery_images);
                                @endphp
                                @if($totalGalleryImages > 3)
                                    <button class="overlay-gallery-nav prev-gallery" data-direction="prev">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                @endif
                                <div class="overlay-gallery-images">
                                    @if($relatedProduct->main_image)
                                        <div class="overlay-gallery-link" data-image-src="{{ asset('storage/' . $relatedProduct->main_image) }}" data-image-index="0">
                                            <img src="{{ asset('storage/' . $relatedProduct->main_image) }}" alt="{{ $relatedProduct->getName() }}" class="overlay-gallery-thumb" data-image-index="0">
                                        </div>
                                    @endif
                                    @foreach($relatedProduct->gallery_images as $index => $galleryImage)
                                        <div class="overlay-gallery-link" data-image-src="{{ asset('storage/' . $galleryImage) }}" data-image-index="{{ $index + 1 }}">
                                            <img src="{{ asset('storage/' . $galleryImage) }}" alt="{{ $relatedProduct->getName() }}" class="overlay-gallery-thumb" data-image-index="{{ $index + 1 }}">
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
                                $totalSizes = ($hasRegularSizes ? $relatedProduct->productSizes->count() : 0) + ($hasShoeSizes ? $relatedProduct->productShoeSizes->count() : 0);
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
                                        @foreach($relatedProduct->productSizes as $size)
                                            <span class="overlay-size-item">{{ $size->name }}</span>
                                        @endforeach
                                    @endif
                                    @if($hasShoeSizes)
                                        @foreach($relatedProduct->productShoeSizes as $shoeSize)
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
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <button class="slider-btn next">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </button>
</div>
