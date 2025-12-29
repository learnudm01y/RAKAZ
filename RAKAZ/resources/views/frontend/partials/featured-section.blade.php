@if($featuredSection && $featuredSection->products->count() > 0)
    <!-- Featured Products Section -->
    <section class="must-have-section">
        <div class="section-header">
            <h2 class="section-title">{{ $featuredSection->getTitle(app()->getLocale()) }}</h2>
            <a href="{{ $featuredSection->link_url }}" class="shop-all">{{ $featuredSection->getLinkText(app()->getLocale()) }}</a>
        </div>
        <div class="products-slider">
            <button class="slider-btn prev">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <div class="products-container">
                @foreach($featuredSection->products as $product)
                    <div class="product-card" data-product-id="{{ $product->id }}">
                        <a href="{{ route('product.details', $product->getSlug()) }}" class="product-image-wrapper">
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName() }}" class="product-image-primary main-product-image">
                            @endif
                            @if($product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0)
                                <img src="{{ asset('storage/' . $product->gallery_images[0]) }}" alt="{{ $product->getName() }}" class="product-image-secondary">
                            @elseif($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName() }}" class="product-image-secondary">
                            @endif
                            <button class="wishlist-btn" data-product-id="{{ $product->id }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                            @if($product->is_new)
                                <span class="badge new-season">{{ app()->getLocale() == 'ar' ? 'موسم جديد' : 'New Season' }}</span>
                            @endif
                        </a>
                        <div class="product-info">
                            @if($product->brand)
                                <p class="product-brand">{{ $product->brand->getName() }}</p>
                            @endif
                            <h3 class="product-name">{{ $product->getName() }}</h3>
                            <p class="product-price">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="sale-price">{{ number_format($product->sale_price, 0) }} {{ app()->getLocale() == 'ar' ? 'درهم إماراتي' : 'AED' }}</span>
                                    <span class="original-price">{{ number_format($product->price, 0) }}</span>
                                @else
                                    {{ number_format($product->price, 0) }} {{ app()->getLocale() == 'ar' ? 'درهم إماراتي' : 'AED' }}
                                @endif
                            </p>

                            @php
                                $hasColors = $product->productColors && $product->productColors->count() > 0;
                            @endphp

                            @if($hasColors)
                                <div class="featured-colors-wrapper">
                                    <div class="featured-color-dots">
                                        @foreach($product->productColors->take(3) as $color)
                                            <span class="featured-color-dot" style="background: {{ $color->hex_code }}; @if($color->hex_code == '#FFFFFF' || $color->hex_code == '#ffffff') border: 1px solid #ddd; @endif"></span>
                                        @endforeach
                                        @if($product->productColors->count() > 3)
                                            <span class="featured-color-more">+{{ $product->productColors->count() - 3 }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Hover Overlay - Outside product-card styling --}}
                        <div class="featured-product-overlay" data-overlay-for="{{ $product->id }}">
                            <a href="{{ route('product.details', $product->getSlug()) }}" class="overlay-link-wrapper">
                            <div class="overlay-image-section">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName() }}" class="overlay-main-image overlay-image-primary">
                                @endif
                                @if($product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0)
                                    <img src="{{ asset('storage/' . $product->gallery_images[0]) }}" alt="{{ $product->getName() }}" class="overlay-main-image overlay-image-secondary">
                                @elseif($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName() }}" class="overlay-main-image overlay-image-secondary">
                                @endif
                                @if($product->is_new)
                                    <span class="overlay-badge new-season">{{ app()->getLocale() == 'ar' ? 'موسم جديد' : 'New Season' }}</span>
                                @endif
                            </div>
                            </a>
                            <button class="overlay-wishlist-btn" data-product-id="{{ $product->id }}" type="button" onclick="event.stopPropagation(); event.preventDefault(); window.toggleWishlist(this, '{{ $product->id }}');">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                            <div class="overlay-info-section">
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
                                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName() }}" class="overlay-gallery-thumb" data-image-index="0">
                                                </div>
                                            @endif
                                            @foreach($product->gallery_images as $index => $galleryImage)
                                                <div class="overlay-gallery-link" data-image-src="{{ asset('storage/' . $galleryImage) }}" data-image-index="{{ $index + 1 }}">
                                                    <img src="{{ asset('storage/' . $galleryImage) }}" alt="{{ $product->getName() }}" class="overlay-gallery-thumb" data-image-index="{{ $index + 1 }}">
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
    </section>
@endif
