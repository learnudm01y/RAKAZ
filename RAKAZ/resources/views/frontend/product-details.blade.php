@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="/assets/css/product-details.css">
    <link rel="stylesheet" href="/assets/css/product-dynamic.css">
    <style>
        .main-image-wrapper {
            position: relative;
        }

        .product-image-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .product-image-nav:hover {
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .product-image-nav.prev {
            left: 10px;
        }

        .product-image-nav.next {
            right: 10px;
        }

        .product-image-nav svg {
            width: 20px;
            height: 20px;
            stroke: #333;
        }

        .product-image-nav:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }
    </style>
@endpush

@push('scripts')
    <script src="/assets/js/product-details.js" defer></script>
    <script src="/assets/js/related-products.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/pica@9.0.1/dist/pica.min.js"></script>
    <script>
        const isArabic = '{{ app()->getLocale() }}' === 'ar';

        // CSS handles image quality optimization better without distortion

        document.addEventListener('DOMContentLoaded', function() {
            // Check for selected color or image from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const selectedColorId = urlParams.get('color');
            const imageFromUrl = urlParams.get('image');
            const imageFromSession = sessionStorage.getItem('selectedProductImage');
            let selectedImageIndex = imageFromUrl || imageFromSession;

            console.log('🎨 Selected color ID:', selectedColorId);
            console.log('🔍 Selected image from URL:', imageFromUrl);
            console.log('🔍 Selected image from session:', imageFromSession);

            // Clear session storage after reading
            if (imageFromSession) {
                sessionStorage.removeItem('selectedProductImage');
            }

            // Image navigation setup
            const allImages = [];
            const mainImage = document.getElementById('mainProductImage');
            const thumbnailsWrapper = document.getElementById('thumbnailsWrapper');
            const thumbnailsExtra = document.getElementById('thumbnailsExtra');
            let thumbnails = document.querySelectorAll('.thumbnail');

            // If color is selected, find the first image with that color
            if (selectedColorId && !selectedImageIndex) {
                const colorThumbnails = document.querySelectorAll('.thumbnail[data-color-id="' + selectedColorId + '"]');
                if (colorThumbnails.length > 0) {
                    // Find index of first color image
                    const allThumbnails = Array.from(thumbnails);
                    selectedImageIndex = allThumbnails.indexOf(colorThumbnails[0]);
                    console.log('🎨 Found color image at index:', selectedImageIndex);
                }
            }

            console.log('🔍 Final selected index:', selectedImageIndex);

            // Collect all image sources
            thumbnails.forEach(thumb => {
                allImages.push(thumb.src);
            });

            const maxVisibleThumbnails = 5;
            const totalImages = allImages.length;
            let currentIndex = selectedImageIndex ? parseInt(selectedImageIndex) : 0;

            // Function to reorganize thumbnails
            function organizeThumbnails() {
                if (totalImages <= maxVisibleThumbnails) return;

                const thumbsArray = Array.from(thumbnails);
                const visibleThumbs = thumbsArray.slice(0, maxVisibleThumbnails);
                const extraThumbs = thumbsArray.slice(maxVisibleThumbnails);

                // Clear wrapper
                thumbnailsWrapper.innerHTML = '';

                // Add visible thumbnails
                visibleThumbs.forEach((thumb, index) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'thumbnail-wrapper';
                    wrapper.appendChild(thumb.cloneNode(true));

                    // Add overlay on last visible thumbnail
                    if (index === visibleThumbs.length - 1) {
                        const overlay = document.createElement('div');
                        overlay.className = 'thumbnail-overlay';
                        overlay.innerHTML = `<span class="thumbnail-overlay-text">+${extraThumbs.length}</span>`;
                        overlay.addEventListener('click', function(e) {
                            e.stopPropagation();
                            loadExtraThumbnails(extraThumbs);
                        });
                        wrapper.appendChild(overlay);
                    }

                    thumbnailsWrapper.appendChild(wrapper);
                });

                // Update thumbnails reference
                thumbnails = document.querySelectorAll('.thumbnail');
                attachThumbnailEvents();
            }

            // Function to load extra thumbnails
            function loadExtraThumbnails(extraThumbs) {
                // Remove overlay
                const overlay = document.querySelector('.thumbnail-overlay');
                if (overlay) overlay.remove();

                // Show extra container
                thumbnailsExtra.style.display = 'flex';

                // Add extra thumbnails
                extraThumbs.forEach(thumb => {
                    const clonedThumb = thumb.cloneNode(true);
                    thumbnailsExtra.appendChild(clonedThumb);
                });

                // Update thumbnails reference and reattach events
                thumbnails = document.querySelectorAll('.thumbnail');
                attachThumbnailEvents();
            }

            function updateImage(index) {
                if (index >= 0 && index < totalImages) {
                    currentIndex = index;
                    mainImage.src = allImages[currentIndex];

                    // Update active thumbnail
                    thumbnails.forEach((thumb, i) => {
                        thumb.classList.toggle('active', i === currentIndex);
                    });

                    // Update button states
                    document.getElementById('prevImage').disabled = currentIndex === 0;
                    document.getElementById('nextImage').disabled = currentIndex === totalImages - 1;
                }
            }

            // Attach events to thumbnails
            function attachThumbnailEvents() {
                thumbnails.forEach((thumbnail, index) => {
                    thumbnail.addEventListener('click', function() {
                        updateImage(index);
                    });
                });
            }

            // Previous button
            document.getElementById('prevImage').addEventListener('click', function(e) {
                e.stopPropagation();
                if (currentIndex > 0) {
                    updateImage(currentIndex - 1);
                }
            });

            // Next button
            document.getElementById('nextImage').addEventListener('click', function(e) {
                e.stopPropagation();
                if (currentIndex < totalImages - 1) {
                    updateImage(currentIndex + 1);
                }
            });

            // Initialize
            organizeThumbnails();

            // Set the selected image (from URL or session)
            if (selectedImageIndex !== null && selectedImageIndex !== undefined) {
                const index = parseInt(selectedImageIndex);
                console.log('🎯 Setting initial image to index:', index);
                updateImage(index);
            } else {
                updateImage(0);
            }

            // Color selection
            const colorOptions = document.querySelectorAll('.color-option');
            const selectedColorSpan = document.getElementById('selectedColor');

            // Auto-select color from URL parameter
            if (selectedColorId && colorOptions.length > 0) {
                colorOptions.forEach(option => {
                    if (option.dataset.colorId === selectedColorId) {
                        option.classList.add('active');
                        if (selectedColorSpan) {
                            const colorName = isArabic ? option.dataset.colorAr : option.dataset.colorEn;
                            selectedColorSpan.textContent = colorName;
                        }
                        console.log('🎨 Auto-selected color:', option.dataset.colorAr || option.dataset.colorEn);
                    } else {
                        option.classList.remove('active');
                    }
                });
            }

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

            // Color Images Selection (New Style)
            const colorImageThumbs = document.querySelectorAll('.color-image-thumb');
            const selectedColorNameSpan = document.getElementById('selectedColorName');

            // Auto-select color image from URL parameter
            if (selectedColorId && colorImageThumbs.length > 0) {
                colorImageThumbs.forEach(thumb => {
                    if (thumb.dataset.colorId === selectedColorId) {
                        thumb.classList.add('active');
                        if (selectedColorNameSpan) {
                            const colorName = isArabic ? thumb.dataset.colorAr : thumb.dataset.colorEn;
                            selectedColorNameSpan.textContent = colorName;
                        }
                        // Update main image to color image
                        const colorImage = thumb.dataset.image;
                        if (colorImage && mainImage) {
                            mainImage.src = colorImage;
                        }
                        console.log('🎨 Auto-selected color image:', thumb.dataset.colorAr || thumb.dataset.colorEn);
                    } else {
                        thumb.classList.remove('active');
                    }
                });
            }

            colorImageThumbs.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    // Update active state
                    colorImageThumbs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Update color name
                    if (selectedColorNameSpan) {
                        const colorName = isArabic ? this.dataset.colorAr : this.dataset.colorEn;
                        selectedColorNameSpan.textContent = colorName;
                    }

                    // Optionally update main product image
                    const colorImage = this.dataset.image;
                    if (colorImage && mainImage) {
                        mainImage.src = colorImage;
                    }
                });
            });

            // Add to cart
            const addToCartBtn = document.getElementById('addToCartBtn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const sizeSelect = document.getElementById('sizeSelect');
                    const selectedSize = sizeSelect ? sizeSelect.value : null;

                    // Get selected color
                    const selectedColor = document.querySelector('.color-option.active');
                    const colorValue = selectedColor ? (isArabic ? selectedColor.dataset.colorAr : selectedColor.dataset.colorEn) : null;

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

                    // Add to cart via AJAX
                    const button = this;
                    button.disabled = true;

                    fetch('{{ route("cart.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1,
                            size: selectedSize,
                            color: colorValue
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update cart count
                            if (typeof updateCartCount === 'function') {
                                updateCartCount();
                            }
                            const cartBadge = document.getElementById('cartBadge');
                            if (cartBadge && data.cartCount !== undefined) {
                                cartBadge.textContent = data.cartCount;
                            }

                            // Update cart sidebar
                            if (window.cartSidebarInstance && typeof window.cartSidebarInstance.loadCartFromServer === 'function') {
                                window.cartSidebarInstance.loadCartFromServer();
                            }

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: isArabic ? 'تمت الإضافة!' : 'Added!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // Add visual feedback
                            button.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> ' + (isArabic ? 'تمت الإضافة' : 'Added');
                            button.style.background = '#4CAF50';

                            setTimeout(() => {
                                button.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg> ' + (isArabic ? 'إضافة إلى حقيبة التسوق' : 'Add to Shopping Bag');
                                button.style.background = '#1a1a1a';
                                button.disabled = false;
                            }, 2000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        button.disabled = false;
                        Swal.fire({
                            icon: 'error',
                            title: isArabic ? 'خطأ!' : 'Error!',
                            text: isArabic ? 'حدث خطأ أثناء الإضافة للسلة' : 'Error adding to cart',
                            confirmButtonText: isArabic ? 'حسناً' : 'OK'
                        });
                    });
                });
            }

            // Add to wishlist
            var addToWishlistBtn = document.getElementById('addToWishlistBtn');
            if (addToWishlistBtn) {
                addToWishlistBtn.addEventListener('click', function() {
                    @guest
                        Swal.fire({
                            icon: 'warning',
                            title: isArabic ? 'يجب تسجيل الدخول' : 'Login Required',
                            text: isArabic ? 'يجب عليك تسجيل الدخول لإضافة منتجات للمفضلة' : 'You need to login to add products to wishlist',
                            confirmButtonText: isArabic ? 'تسجيل الدخول' : 'Login',
                            showCancelButton: true,
                            cancelButtonText: isArabic ? 'إلغاء' : 'Cancel'
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route("login") }}';
                            }
                        });
                        return;
                    @endguest

                    var productId = this.dataset.productId;
                    var button = this;
                    var wasActive = button.classList.contains('active');

                    fetch('{{ route("wishlist.toggle") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_id: productId })
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        if (data.success) {
                            // Toggle button state
                            button.classList.toggle('active');

                            // Update wishlist count in header - Real-time update
                            var wishlistBadges = document.querySelectorAll('.header-link .badge');
                            for (var i = 0; i < wishlistBadges.length; i++) {
                                (function(badge) {
                                    var currentCount = parseInt(badge.textContent) || 0;
                                    if (wasActive) {
                                        // Removing from wishlist
                                        badge.textContent = Math.max(0, currentCount - 1);
                                    } else {
                                        // Adding to wishlist
                                        badge.textContent = currentCount + 1;
                                    }

                                    // Add pulse animation
                                    badge.style.animation = 'none';
                                    setTimeout(function() {
                                        badge.style.animation = 'pulse 0.3s ease-in-out';
                                    }, 10);
                                })(wishlistBadges[i]);
                            }

                            // Update heart icon fill
                            var heartIcon = button.querySelector('svg path');
                            if (heartIcon) {
                                if (!wasActive) {
                                    // Fill the heart
                                    heartIcon.setAttribute('fill', 'currentColor');
                                } else {
                                    // Unfill the heart
                                    heartIcon.setAttribute('fill', 'none');
                                }
                            }

                            Swal.fire({
                                icon: 'success',
                                title: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: isArabic ? 'خطأ!' : 'Error!',
                            text: isArabic ? 'حدث خطأ أثناء الإضافة للمفضلة' : 'Error adding to wishlist'
                        });
                    });
                });
            }

            // Wishlist buttons in related products
            var wishlistBtns = document.querySelectorAll('.wishlist-btn-small');
            for (var i = 0; i < wishlistBtns.length; i++) {
                wishlistBtns[i].addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.toggle('active');
                });
            }

            // Initialize custom select for size dropdown
            var sizeSelect = document.getElementById('sizeSelect');
            if (sizeSelect && typeof CustomSelect !== 'undefined') {
                new CustomSelect(sizeSelect);
            }

            // Lazy load related products sections after 3 seconds
            setTimeout(function() {
                loadRelatedProducts();
                loadBrandProducts();
            }, 3000);

            // Function to load related products
            function loadRelatedProducts() {
                var productId = {{ $product->id }};
                var section = document.getElementById('related-products-section');

                if (!section) return;

                fetch('/api/lazy-load/related-products/' + productId)
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (data.success && data.html) {
                            section.innerHTML = data.html;
                            initializeProductSlider(section);
                            initializeOverlays(section);
                        }
                    })
                    .catch(function(error) {
                        console.error('Error loading related products:', error);
                        section.style.display = 'none';
                    });
            }

            // Function to load brand products
            function loadBrandProducts() {
                var productId = {{ $product->id }};
                var section = document.getElementById('brand-products-section');

                if (!section) return;

                fetch('/api/lazy-load/brand-products/' + productId)
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (data.success && data.html) {
                            section.innerHTML = data.html;
                            initializeProductSlider(section);
                            initializeOverlays(section);
                        }
                    })
                    .catch(function(error) {
                        console.error('Error loading brand products:', error);
                        section.style.display = 'none';
                    });
            }

            // Function to initialize product slider for a section
            function initializeProductSlider(section) {
                var slider = section.querySelector('.products-slider');
                if (!slider) return;

                var container = slider.querySelector('.products-container');
                var prevBtn = slider.querySelector('.slider-btn.prev');
                var nextBtn = slider.querySelector('.slider-btn.next');

                if (!container || !prevBtn || !nextBtn) return;

                var scrollAmount = 300;

                prevBtn.addEventListener('click', function() {
                    container.scrollBy({
                        left: -scrollAmount,
                        behavior: 'smooth'
                    });
                });

                nextBtn.addEventListener('click', function() {
                    container.scrollBy({
                        left: scrollAmount,
                        behavior: 'smooth'
                    });
                });
            }

            // Function to initialize overlays for products
            function initializeOverlays(section) {
                var productCards = section.querySelectorAll('.product-card');

                for (var i = 0; i < productCards.length; i++) {
                    (function(card) {
                        card.addEventListener('mouseenter', function() {
                            var overlay = card.querySelector('.featured-product-overlay');
                            if (overlay) {
                                overlay.classList.add('active');
                            }
                        });

                        card.addEventListener('mouseleave', function() {
                            var overlay = card.querySelector('.featured-product-overlay');
                            if (overlay) {
                                overlay.classList.remove('active');
                            }
                        });
                    })(productCards[i]);
                }
            }
        });

        // Load related products
        function loadRelatedProducts() {
            var productId = {{ $product->id }};
            var section = document.getElementById('related-products-section');
            if (!section) return;

            fetch('/api/lazy-load/related-products/' + productId)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.success && data.html) {
                        section.innerHTML = data.html;

                        // Re-initialize sliders and overlays
                        if (typeof initializeProductSliders === 'function') {
                            initializeProductSliders();
                        }
                        if (typeof initializeProductOverlays === 'function') {
                            initializeProductOverlays();
                        }
                    } else {
                        section.style.display = 'none';
                    }
                })
                .catch(function(error) {
                    console.error('Error loading related products:', error);
                    section.style.display = 'none';
                });
        }

        // Load brand products
        function loadBrandProducts() {
            var productId = {{ $product->id }};
            var section = document.getElementById('brand-products-section');
            if (!section) return;

            fetch('/api/lazy-load/brand-products/' + productId)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.success && data.html) {
                        section.innerHTML = data.html;

                        // Re-initialize sliders and overlays
                        if (typeof initializeProductSliders === 'function') {
                            initializeProductSliders();
                        }
                        if (typeof initializeProductOverlays === 'function') {
                            initializeProductOverlays();
                        }
                    } else {
                        section.style.display = 'none';
                    }
                })
                .catch(function(error) {
                    console.error('Error loading brand products:', error);
                    section.style.display = 'none';
                });
        }
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
                    <button class="product-image-nav prev" id="prevImage">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('assets/images/placeholder.jpg') }}"
                         alt="{{ $product->getName() }}"
                         class="main-product-image"
                         id="mainProductImage">
                    <button class="product-image-nav next" id="nextImage">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <!-- Thumbnail Images -->
                <div class="thumbnail-gallery">
                    <div class="thumbnails-wrapper" id="thumbnailsWrapper">
                        {{-- Main Product Image --}}
                        @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}"
                             alt="{{ $product->getName() }}"
                             class="thumbnail active"
                             data-image-type="main">
                        @endif

                        {{-- Product Gallery Images --}}
                        @if($product->gallery_images && is_array($product->gallery_images))
                            @foreach($product->gallery_images as $galleryImage)
                            <img src="{{ asset('storage/' . $galleryImage) }}"
                                 alt="{{ $product->getName() }}"
                                 class="thumbnail"
                                 data-image-type="gallery">
                            @endforeach
                        @endif
                    </div>
                    <div class="thumbnails-extra" id="thumbnailsExtra" style="display: none;">
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
                    @if($product->productColors && $product->productColors->count() > 0 && $product->colorImages->count() > 0)
                    <!-- Color Images Selection (New Style) -->
                    <div class="option-group color-images-selection">
                        <label class="option-label">
                            {{ app()->getLocale() == 'ar' ? 'اللون:' : 'Color:' }}
                            <span class="selected-option selected-color-name" id="selectedColorName">
                                {{ $product->productColors->first()->translated_name }}
                            </span>
                        </label>
                        <div class="color-images-row">
                            @foreach($product->productColors as $index => $color)
                                @php
                                    $colorImage = $product->colorImages->where('color_id', $color->id)->first();
                                @endphp
                                @if($colorImage)
                                <div class="color-image-thumb {{ $index === 0 ? 'active' : '' }}"
                                     data-color-id="{{ $color->id }}"
                                     data-color-ar="{{ $color->name['ar'] ?? $color->translated_name }}"
                                     data-color-en="{{ $color->name['en'] ?? $color->translated_name }}"
                                     data-color-hex="{{ $color->hex_code }}"
                                     data-image="{{ $colorImage->image_url }}">
                                    <img src="{{ $colorImage->image_url }}" alt="{{ $color->translated_name }}">
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @elseif($product->colors && is_array($product->colors) && count($product->colors) > 0)
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
                    @php
                        $hasSizes = $product->sizes && is_array($product->sizes) && count($product->sizes) > 0;
                        // Debug
                        \Log::info('Product Sizes Check', [
                            'product_id' => $product->id,
                            'sizes' => $product->sizes,
                            'is_array' => is_array($product->sizes),
                            'count' => is_array($product->sizes) ? count($product->sizes) : 0,
                            'has_sizes' => $hasSizes
                        ]);
                    @endphp

                    @if($hasSizes)
                    <div class="option-group" style=" padding: 15px; border-radius: 8px; ">
                        <div class="size-header">
                            <label class="option-label" style="font-size: 16px; font-weight: bold; color: #000;">{{ app()->getLocale() == 'ar' ? 'اختيار المقاس:' : 'Select Size:' }}</label>
                            <a href="#" class="size-guide-link">{{ app()->getLocale() == 'ar' ? 'جدول المقاسات' : 'Size Guide' }}</a>
                        </div>
                        <!-- Available Sizes Display - Hidden -->
                        <div style="display: none !important;">
                            <div style="font-size: 14px; font-weight: 700; color: #28a745; margin-bottom: 10px;">
                                ✅ {{ app()->getLocale() == 'ar' ? 'المقاسات المتوفرة:' : 'Available Sizes:' }} ({{ count($product->sizes) }})
                            </div>
                            <div style="font-size: 16px; color: #212529; font-weight: 600; line-height: 1.8;">
                                @foreach($product->sizes as $index => $size)
                                    <span style="display: inline-block; padding: 8px 16px; margin: 4px; background: #007bff; color: white; border-radius: 4px; font-weight: bold;">
                                        {{ is_array($size) ? (app()->getLocale() == 'ar' ? ($size['ar'] ?? $size['en']) : ($size['en'] ?? $size['ar'])) : $size }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <select class="size-select custom-select" id="sizeSelect" required style="font-size: 16px; font-weight: 600;">
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
                    <button class="btn-add-to-wishlist {{ auth()->check() && \App\Models\Wishlist::isInWishlist(auth()->id(), $product->id) ? 'active' : '' }}" id="addToWishlistBtn" data-product-id="{{ $product->id }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Product Details Tabs -->
                <div class="product-tabs">
                    <div class="tabs-header">
                        <button class="tab-btn active" data-tab="description">{{ app()->getLocale() == 'ar' ? 'الوصف' : 'Description' }}</button>
                        @if($product->sizing_info && ((is_array($product->sizing_info) && !empty(array_filter($product->sizing_info))) || (!is_array($product->sizing_info) && $product->sizing_info)))
                        <button class="tab-btn" data-tab="sizing">{{ app()->getLocale() == 'ar' ? 'المقاسات والحجم' : 'Sizing & Fit' }}</button>
                        @endif
                        @if($product->design_details && ((is_array($product->design_details) && !empty(array_filter($product->design_details))) || (!is_array($product->design_details) && $product->design_details)))
                        <button class="tab-btn" data-tab="design">{{ app()->getLocale() == 'ar' ? 'تفاصيل التصميم' : 'Design Details' }}</button>
                        @endif
                        @if($product->specifications && is_array($product->specifications) && count($product->specifications) > 0)
                        <button class="tab-btn" data-tab="details">{{ app()->getLocale() == 'ar' ? 'المواصفات' : 'Specifications' }}</button>
                        @endif
                        <button class="tab-btn" data-tab="shipping">{{ app()->getLocale() == 'ar' ? 'التوصيل والإرجاع' : 'Delivery & Returns' }}</button>
                    </div>
                    <div class="tabs-content">
                        <div class="tab-panel active" id="description">
                            @if($product->description)
                            <div>{!! $product->getDescription() !!}</div>
                            @endif
                        </div>
                        @if($product->sizing_info && ((is_array($product->sizing_info) && !empty(array_filter($product->sizing_info))) || (!is_array($product->sizing_info) && $product->sizing_info)))
                        <div class="tab-panel" id="sizing">
                            <div>{!! app()->getLocale() == 'ar' ? ($product->sizing_info['ar'] ?? '') : ($product->sizing_info['en'] ?? $product->sizing_info['ar'] ?? '') !!}</div>
                        </div>
                        @endif
                        @if($product->design_details && ((is_array($product->design_details) && !empty(array_filter($product->design_details))) || (!is_array($product->design_details) && $product->design_details)))
                        <div class="tab-panel" id="design">
                            <div>{!! app()->getLocale() == 'ar' ? ($product->design_details['ar'] ?? '') : ($product->design_details['en'] ?? $product->design_details['ar'] ?? '') !!}</div>
                        </div>
                        @endif
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
        <section class="related-products-section" id="related-products-section">
            <!-- Skeleton Loader -->
            <div class="skeleton-loader">
                <div class="section-header">
                    <div class="skeleton skeleton-title" style="width: 200px; height: 32px;"></div>
                </div>
                <div class="products-slider">
                    <div class="products-container">
                        @for($i = 0; $i < 4; $i++)
                            <div class="product-card skeleton-card">
                                <div class="skeleton skeleton-image" style="width: 100%; height: 400px;"></div>
                                <div class="product-info">
                                    <div class="skeleton skeleton-text" style="width: 60%; height: 16px; margin-bottom: 8px;"></div>
                                    <div class="skeleton skeleton-text" style="width: 80%; height: 20px; margin-bottom: 8px;"></div>
                                    <div class="skeleton skeleton-text" style="width: 40%; height: 18px;"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </section>

        <!-- Additional Brand Products Section -->
        <section class="related-products-section" id="brand-products-section">
            <!-- Skeleton Loader -->
            <div class="skeleton-loader">
                <div class="section-header">
                    <div class="skeleton skeleton-title" style="width: 250px; height: 32px;"></div>
                </div>
                <div class="products-slider">
                    <div class="products-container">
                        @for($i = 0; $i < 4; $i++)
                            <div class="product-card skeleton-card">
                                <div class="skeleton skeleton-image" style="width: 100%; height: 400px;"></div>
                                <div class="product-info">
                                    <div class="skeleton skeleton-text" style="width: 60%; height: 16px; margin-bottom: 8px;"></div>
                                    <div class="skeleton skeleton-text" style="width: 80%; height: 20px; margin-bottom: 8px;"></div>
                                    <div class="skeleton skeleton-text" style="width: 40%; height: 18px;"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
