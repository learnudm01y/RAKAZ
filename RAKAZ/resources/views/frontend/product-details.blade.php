@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="/assets/css/product-details.css">
    <link rel="stylesheet" href="/assets/css/product-dynamic.css">
@endpush

@push('scripts')
    <script src="/assets/js/product-details.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/pica@9.0.1/dist/pica.min.js"></script>
    <script>
        const isArabic = '{{ app()->getLocale() }}' === 'ar';

        // CSS handles image quality optimization better without distortion

        document.addEventListener('DOMContentLoaded', function() {
            // Color selection
            const colorOptions = document.querySelectorAll('.color-option');
            const selectedColorSpan = document.getElementById('selectedColor');

            colorOptions.forEach(option => {
                option.addEventListener('click', function() {
                    colorOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');

                    if (selectedColorSpan) {
                        const colorName = isArabic ? this.dataset.colorAr : this.dataset.colorEn;
                        selectedColorSpan.textContent = colorName;
                    }
                });
            });

            // Add to cart
            const addToCartBtn = document.getElementById('addToCartBtn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function() {
                    const sizeSelect = document.getElementById('sizeSelect');
                    const selectedSize = sizeSelect ? sizeSelect.value : null;

                    // Check if size is required and selected
                    if (sizeSelect && !selectedSize) {
                        Swal.fire({
                            icon: 'warning',
                            title: isArabic ? 'اختر المقاس' : 'Select Size',
                            text: isArabic ? 'الرجاء اختيار المقاس أولاً' : 'Please select a size first',
                            confirmButtonText: isArabic ? 'حسناً' : 'OK',
                            confirmButtonColor: '#1a1a1a'
                        });
                        return;
                    }

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: isArabic ? 'تمت الإضافة!' : 'Added!',
                        text: isArabic ? 'تم إضافة المنتج للسلة بنجاح' : 'Product added to cart successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Add visual feedback
                    this.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> ' + (isArabic ? 'تمت الإضافة' : 'Added');
                    this.style.background = '#4CAF50';

                    setTimeout(() => {
                        this.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg> ' + (isArabic ? 'إضافة إلى حقيبة التسوق' : 'Add to Shopping Bag');
                        this.style.background = '#1a1a1a';
                    }, 2000);
                });
            }

            // Add to wishlist
            const addToWishlistBtn = document.getElementById('addToWishlistBtn');
            if (addToWishlistBtn) {
                addToWishlistBtn.addEventListener('click', function() {
                    this.classList.toggle('active');

                    Swal.fire({
                        icon: 'success',
                        title: isArabic ? 'تمت الإضافة للمفضلة!' : 'Added to Wishlist!',
                        timer: 1000,
                        showConfirmButton: false
                    });
                });
            }

            // Wishlist buttons in related products
            const wishlistBtns = document.querySelectorAll('.wishlist-btn-small');
            wishlistBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.toggle('active');
                });
            });
        });
    </script>
@endpush

@section('content')

    <!-- Product Details Page -->
    <main class="product-details-page">
        <div class="product-details-container">
            <!-- Product Gallery -->
            <div class="product-gallery">
                <!-- Main Image -->
                <div class="main-image-wrapper">
                    <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('assets/images/placeholder.jpg') }}"
                         alt="{{ $product->getName() }}"
                         class="main-product-image"
                         id="mainProductImage">
                </div>

                <!-- Thumbnail Images -->
                <div class="thumbnail-gallery">
                    <div class="thumbnails-wrapper">
                        @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}"
                             alt="{{ $product->getName() }}"
                             class="thumbnail active">
                        @endif

                        @if($product->gallery_images && is_array($product->gallery_images))
                            @foreach($product->gallery_images as $image)
                            <img src="{{ asset('storage/' . $image) }}"
                                 alt="{{ $product->getName() }}"
                                 class="thumbnail">
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info-section">
                <!-- Breadcrumb & Season Badge -->
                <div class="product-meta-top">
                    @if($product->is_new)
                    <span class="season-badge">{{ app()->getLocale() == 'ar' ? 'الموسم الجديد' : 'New Season' }}</span>
                    @endif
                    @if($product->is_on_sale)
                    <span class="season-badge" style="background: #dc2626;">{{ app()->getLocale() == 'ar' ? 'عرض خاص' : 'On Sale' }}</span>
                    @endif
                </div>

                <!-- Brand & Title -->
                <div class="product-header">
                    @if($product->brand)
                    <h1 class="product-title">{!! $product->brand !!}</h1>
                    @endif
                    <h2 class="product-subtitle">{!! $product->getName() !!}</h2>
                </div>

                <!-- Price -->
                <div class="product-price-section">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="original-price" style="text-decoration: line-through; color: #999; margin-left: 10px;">{{ number_format($product->price, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                        <span class="current-price">{{ number_format($product->sale_price, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                    @else
                        <span class="current-price">{{ number_format($product->price, 2) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                    @endif
                </div>

                <!-- Payment Options -->
                <div class="payment-options">
                    <div class="payment-option">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="payment-icon">
                    </div>
                    <div class="payment-option">
                        <span class="payment-text">تمرا</span>
                    </div>
                    <div class="payment-option">
                        <span class="payment-text">tabby</span>
                    </div>
                    <div class="payment-option">
                        <span class="payment-text">تتوفر أقساط بدون فوائد</span>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="delivery-info">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="3" width="15" height="13"></rect>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                    </svg>
                    <span>احصل على 738 نقطة أمز (ن) <a href="#" class="info-link">اعرف المزيد</a></span>
                </div>

                <div class="price-match-info">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                    <span>نحن نقدم لك <strong>خدمة السعر المثالي</strong></span>
                </div>

                <!-- Color & Size Selection -->
                <div class="product-options">
                    @if($product->colors && is_array($product->colors) && count($product->colors) > 0)
                    <div class="option-group">
                        <label class="option-label">
                            {{ app()->getLocale() == 'ar' ? 'اللون:' : 'Color:' }}
                            <span class="selected-option" id="selectedColor">{{ app()->getLocale() == 'ar' ? $product->colors[0]['ar'] ?? '' : $product->colors[0]['en'] ?? '' }}</span>
                        </label>
                        <div class="color-options">
                            @foreach($product->colors as $index => $color)
                            <button class="color-option {{ $index === 0 ? 'active' : '' }}"
                                    data-color-ar="{{ $color['ar'] ?? '' }}"
                                    data-color-en="{{ $color['en'] ?? '' }}"
                                    style="background-color: {{ $color['hex'] ?? '#ccc' }}; width: 40px; height: 40px; border-radius: 50%; border: 2px solid #ddd;">
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Size Selection -->
                    @if($product->sizes && is_array($product->sizes) && count($product->sizes) > 0)
                    <div class="option-group">
                        <div class="size-header">
                            <label class="option-label">{{ app()->getLocale() == 'ar' ? 'اختيار المقاس:' : 'Select Size:' }}</label>
                            <a href="#" class="size-guide-link">{{ app()->getLocale() == 'ar' ? 'جدول المقاسات' : 'Size Guide' }}</a>
                        </div>
                        <select class="size-select custom-select" id="sizeSelect" required>
                            <option value="">{{ app()->getLocale() == 'ar' ? 'اختر المقاس' : 'Select Size' }}</option>
                            @foreach($product->sizes as $size)
                            <option value="{{ is_array($size) ? ($size['value'] ?? $size['ar'] ?? $size['en']) : $size }}">
                                {{ is_array($size) ? (app()->getLocale() == 'ar' ? ($size['ar'] ?? $size['en']) : ($size['en'] ?? $size['ar'])) : $size }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                <!-- Stock & Delivery Notice -->
                <div class="stock-notice">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>
                        @if($product->stock_status == 'in_stock')
                            <strong style="color: #16a34a;">{{ app()->getLocale() == 'ar' ? '✓ متوفر في المخزون' : '✓ In Stock' }}</strong>
                        @elseif($product->stock_status == 'out_of_stock')
                            <strong style="color: #dc2626;">{{ app()->getLocale() == 'ar' ? '✗ غير متوفر' : '✗ Out of Stock' }}</strong>
                        @else
                            <strong style="color: #f59e0b;">{{ app()->getLocale() == 'ar' ? 'طلب مسبق' : 'Pre-Order' }}</strong>
                        @endif
                        <br>
                        {{ app()->getLocale() == 'ar' ? 'التوصيل خلال 2-3 أيام عمل' : 'Delivery in 2-3 business days' }}
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="product-actions">
                    <button class="btn-add-to-bag" id="addToCartBtn" data-product-id="{{ $product->id }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        {{ app()->getLocale() == 'ar' ? 'إضافة إلى حقيبة التسوق' : 'Add to Shopping Bag' }}
                    </button>
                    <button class="btn-add-to-wishlist" id="addToWishlistBtn" data-product-id="{{ $product->id }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Product Details Tabs -->
                <div class="product-tabs">
                    <div class="tabs-header">
                        <button class="tab-btn active" data-tab="description">{{ app()->getLocale() == 'ar' ? 'الوصف' : 'Description' }}</button>
                        @if($product->specifications && is_array($product->specifications) && count($product->specifications) > 0)
                        <button class="tab-btn" data-tab="details">{{ app()->getLocale() == 'ar' ? 'المواصفات' : 'Specifications' }}</button>
                        @endif
                        <button class="tab-btn" data-tab="shipping">{{ app()->getLocale() == 'ar' ? 'التوصيل والإرجاع' : 'Delivery & Returns' }}</button>
                    </div>
                    <div class="tabs-content">
                        <div class="tab-panel active" id="description">
                            @if($product->short_description)
                            <div>{!! $product->getShortDescription() !!}</div>
                            @endif
                            @if($product->description)
                            <div>{!! $product->getDescription() !!}</div>
                            @endif
                        </div>
                        @if($product->specifications && is_array($product->specifications) && count($product->specifications) > 0)
                        <div class="tab-panel" id="details">
                            <p><strong>{{ app()->getLocale() == 'ar' ? 'رمز المنتج:' : 'SKU:' }}</strong> {{ $product->sku }}</p>
                            <ul>
                                @foreach($product->specifications as $spec)
                                <li>
                                    <strong>{!! app()->getLocale() == 'ar' ? ($spec['name_ar'] ?? $spec['name_en'] ?? '') : ($spec['name_en'] ?? $spec['name_ar'] ?? '') !!}:</strong>
                                    {!! app()->getLocale() == 'ar' ? ($spec['value_ar'] ?? $spec['value_en'] ?? '') : ($spec['value_en'] ?? $spec['value_ar'] ?? '') !!}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="tab-panel" id="shipping">
                            <p>{{ app()->getLocale() == 'ar' ? 'نوفر شحن مجاني لجميع الطلبات. التوصيل خلال 2-3 أيام عمل.' : 'We offer free shipping on all orders. Delivery in 2-3 business days.' }}</p>
                            <p>{{ app()->getLocale() == 'ar' ? 'الإرجاع مجاني خلال 14 يوم من تاريخ الاستلام.' : 'Free returns within 14 days from receipt date.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- You May Also Like Section -->
        @if($relatedProducts && $relatedProducts->count() > 0)
        <section class="related-products-section">
            <div class="section-header">
                <h2 class="section-title">{{ app()->getLocale() == 'ar' ? 'قد يعجبك أيضاً' : 'You May Also Like' }}</h2>
            </div>
            <div class="products-carousel">
                <button class="carousel-nav prev">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div class="products-slider">
                    @foreach($relatedProducts as $relatedProduct)
                    <div class="product-card-small">
                        <a href="{{ route('product.details', $relatedProduct->getSlug()) }}">
                            <div class="product-image-wrapper-small">
                                <img src="{{ $relatedProduct->main_image ? asset('storage/' . $relatedProduct->main_image) : asset('assets/images/placeholder.jpg') }}"
                                     alt="{{ $relatedProduct->getName() }}"
                                     class="product-image-small">
                                <button class="wishlist-btn-small" onclick="event.preventDefault();" data-product-id="{{ $relatedProduct->id }}">
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
                                            <span class="discount-percent">{{ $discountPercent }}%</span>
                                        </div>
                                    </div>
                                @elseif($relatedProduct->is_new)
                                    <span class="badge-new">{{ app()->getLocale() == 'ar' ? 'جديد' : 'New' }}</span>
                                @elseif($relatedProduct->is_on_sale)
                                    <span class="badge-discount">{{ app()->getLocale() == 'ar' ? 'عرض خاص' : 'Sale' }}</span>
                                @endif
                            </div>
                            <div class="product-info-small">
                                @if($relatedProduct->brand)
                                <p class="product-brand-small">{{ $relatedProduct->brand }}</p>
                                @endif
                                <h3 class="product-name-small">{{ Str::limit($relatedProduct->getName(), 50) }}</h3>
                                @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                <div class="price-group-small">
                                    <span class="price-original">{{ number_format($relatedProduct->price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                                    <span class="price-sale">{{ number_format($relatedProduct->sale_price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                                </div>
                                @else
                                <p class="product-price-small">{{ number_format($relatedProduct->price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</p>
                                @endif
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <button class="carousel-nav next">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </section>
        @endif

        <!-- Additional Brand Products Section -->
        @if($product->brand && $brandProducts && $brandProducts->count() > 0)
        <section class="related-products-section">
            <div class="section-header">
                <h2 class="section-title">
                    {{ app()->getLocale() == 'ar' ? 'منتجات إضافية من ' . $product->brand : 'More from ' . $product->brand }}
                </h2>
                <a href="#" class="view-all-link">{{ app()->getLocale() == 'ar' ? 'عرض الكل' : 'View All' }}</a>
            </div>
            <div class="products-carousel">
                <button class="carousel-nav prev">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div class="products-slider">
                    @foreach($brandProducts as $brandProduct)
                    <div class="product-card-small">
                        <a href="{{ route('product.details', $brandProduct->getSlug()) }}">
                            <div class="product-image-wrapper-small">
                                <img src="{{ $brandProduct->main_image ? asset('storage/' . $brandProduct->main_image) : asset('assets/images/placeholder.jpg') }}"
                                     alt="{{ $brandProduct->getName() }}"
                                     class="product-image-small">
                                <button class="wishlist-btn-small" onclick="event.preventDefault();" data-product-id="{{ $brandProduct->id }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                </button>
                                @if($brandProduct->sale_price && $brandProduct->sale_price < $brandProduct->price)
                                    @php
                                        $discountPercent = round((($brandProduct->price - $brandProduct->sale_price) / $brandProduct->price) * 100);
                                    @endphp
                                    <div class="discount-badge-wrapper">
                                        <img src="{{ asset('assets/images/discount.png') }}" alt="Discount" class="discount-badge-image">
                                        <div class="discount-badge-text">
                                            <span class="discount-text-ar">تخفيض</span>
                                            <span class="discount-text-en">DISCOUNT</span>
                                            <span class="discount-percent">{{ $discountPercent }}%</span>
                                        </div>
                                    </div>
                                @elseif($brandProduct->is_new)
                                    <span class="badge-new">{{ app()->getLocale() == 'ar' ? 'جديد' : 'New' }}</span>
                                @elseif($brandProduct->is_on_sale)
                                    <span class="badge-new" style="background: #dc2626;">{{ app()->getLocale() == 'ar' ? 'عرض' : 'Sale' }}</span>
                                @endif
                            </div>
                            <div class="product-info-small">
                                <p class="product-brand-small">{{ $brandProduct->brand }}</p>
                                <h3 class="product-name-small">{{ Str::limit($brandProduct->getName(), 50) }}</h3>
                                @if($brandProduct->sale_price && $brandProduct->sale_price < $brandProduct->price)
                                <div class="price-group-small">
                                    <span class="price-original">{{ number_format($brandProduct->price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                                    <span class="price-sale">{{ number_format($brandProduct->sale_price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</span>
                                </div>
                                @else
                                <p class="product-price-small">{{ number_format($brandProduct->price, 0) }} {{ app()->getLocale() == 'ar' ? 'د.إ' : 'AED' }}</p>
                                @endif

                                @if($brandProduct->colors && is_array($brandProduct->colors) && count($brandProduct->colors) > 0)
                                <div class="color-dots">
                                    @foreach(array_slice($brandProduct->colors, 0, 4) as $color)
                                    <span class="color-dot" style="background: {{ $color['hex'] ?? '#ccc' }};"></span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <button class="carousel-nav next">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </section>
        @endif
    </main>

@endsection
