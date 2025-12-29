<!-- Perfect Gift Section -->
    <section class="perfect-gift-section">
        <div class="section-header">
            <h2 class="section-title">{{ $perfectGiftSection->getTitle(app()->getLocale()) }}</h2>
            <a href="{{ $perfectGiftSection->link_url }}" class="shop-all">{{ $perfectGiftSection->getLinkText(app()->getLocale()) }}</a>
        </div>
        <div class="products-slider">
            <button class="slider-btn prev">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <div class="products-container">
                @foreach($perfectGiftSection->products as $product)
                    <div class="product-card" data-product-id="{{ $product->id }}">
                        <a href="{{ route('product.details', $product->getSlug()) }}" class="product-image-wrapper home-product-link" data-product-id="{{ $product->id }}">
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName() }}" class="product-image-primary main-product-image">
                            @endif
                            {{-- Secondary image will be loaded on hover via AJAX --}}
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
                        <div class="perfect-gift-overlay" data-overlay-for="{{ $product->id }}">
                            <a href="{{ route('product.details', $product->getSlug()) }}" class="overlay-link-wrapper">
                            <div class="overlay-image-section">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName() }}" class="overlay-main-image overlay-image-primary">
                                @endif
                                {{-- Secondary image will be loaded via AJAX on hover --}}
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
                            {{-- Overlay info skeleton - will be loaded on hover --}}
                            <div class="overlay-info-skeleton" data-product-id="{{ $product->id }}">
                                <div class="skeleton-line" style="width: 60%; height: 12px; margin-bottom: 8px;"></div>
                                <div class="skeleton-line" style="width: 80%; height: 16px; margin-bottom: 8px;"></div>
                                <div class="skeleton-line" style="width: 40%; height: 14px;"></div>
                            </div>
                            {{-- Overlay info section will be loaded via AJAX on hover --}}
                            <div class="overlay-info-section" style="display: none;"></div>
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
