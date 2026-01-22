@extends('layouts.app')

@section('content')
    <!-- Hero Banner Slider - Loaded via AJAX -->
    <div id="hero-banner-wrapper">
        @include('frontend.partials.hero-banner-skeleton')
    </div>

    <!-- Cyber Sale Banner -->
    @if($homePage && $homePage->cyber_sale_active && $homePage->cyber_sale_image)
    @php
        $desktopImage = $homePage->cyber_sale_image;
        $tabletImage = !empty($homePage->cyber_sale_image_tablet) ? $homePage->cyber_sale_image_tablet : $desktopImage;
        $mobileImage = !empty($homePage->cyber_sale_image_mobile) ? $homePage->cyber_sale_image_mobile : $desktopImage;
    @endphp
    <a href="{{ $homePage->cyber_sale_link ?? '#' }}" class="cyber-sale-banner">
        <picture class="cyber-sale-picture">
            <source media="(max-width: 480px)" srcset="{{ $mobileImage }}" type="image/jpeg">
            <source media="(max-width: 767px)" srcset="{{ $mobileImage }}" type="image/jpeg">
            <source media="(max-width: 1024px)" srcset="{{ $tabletImage }}" type="image/jpeg">
            <img src="{{ $desktopImage }}"
                 alt="{{ $homePage->cyber_sale_alt ?? 'Cyber Sale' }}"
                 class="cyber-sale-image"
                 loading="lazy">
        </picture>
    </a>
    @endif

    <!-- Vertical Images Section (after Cyber Sale) -->
    @include('frontend.partials.vertical-images-section')

    <!-- Dolce & Gabbana Casa Banner -->
    @if($homePage && $homePage->dg_banner_active && $homePage->dg_banner_image)
    @php
        $currentLocale = app()->getLocale();
        $desktopImage = is_array($homePage->dg_banner_image) ? ($homePage->dg_banner_image[$currentLocale] ?? $homePage->dg_banner_image['ar'] ?? '') : $homePage->dg_banner_image;

        $tabletImage = $desktopImage;
        if (is_array($homePage->dg_banner_image_tablet) && !empty($homePage->dg_banner_image_tablet[$currentLocale])) {
            $tabletImage = $homePage->dg_banner_image_tablet[$currentLocale];
        }

        $mobileImage = $desktopImage;
        if (is_array($homePage->dg_banner_image_mobile) && !empty($homePage->dg_banner_image_mobile[$currentLocale])) {
            $mobileImage = $homePage->dg_banner_image_mobile[$currentLocale];
        }
    @endphp
    <a href="{{ $homePage->dg_banner_link ?? '#' }}" class="dg-banner">
        <picture>
            <source media="(max-width: 767px)" srcset="{{ $mobileImage }}">
            <source media="(max-width: 1024px)" srcset="{{ $tabletImage }}">
            <img src="{{ $desktopImage }}"
                 alt="Dolce & Gabbana Casa"
                 class="dg-banner-image">
        </picture>
        <div class="dg-content">
            <!-- Optional: يمكن إضافة عنوان ووصف هنا لاحقاً -->
        </div>
    </a>
    @endif

    <!-- Featured Products Section -->
    @if($featuredSection && $featuredSection->products && $featuredSection->products->count() > 0)
        @include('frontend.partials.featured-section-content')
    @endif

    <!-- Gucci Spotlight -->
    @if($homePage && $homePage->gucci_spotlight_active && $homePage->gucci_spotlight_image)
        @php
            $currentLocale = app()->getLocale();
            $gucciImage = null;
            if (is_array($homePage->gucci_spotlight_image)) {
                $gucciImage = $homePage->gucci_spotlight_image[$currentLocale] ?? $homePage->gucci_spotlight_image['ar'] ?? null;
            } else {
                $gucciImage = $homePage->gucci_spotlight_image;
            }

            $gucciTabletImage = $gucciImage;
            if (is_array($homePage->gucci_spotlight_image_tablet) && !empty($homePage->gucci_spotlight_image_tablet[$currentLocale])) {
                $gucciTabletImage = $homePage->gucci_spotlight_image_tablet[$currentLocale];
            }

            $gucciMobileImage = $gucciImage;
            if (is_array($homePage->gucci_spotlight_image_mobile) && !empty($homePage->gucci_spotlight_image_mobile[$currentLocale])) {
                $gucciMobileImage = $homePage->gucci_spotlight_image_mobile[$currentLocale];
            }
        @endphp
        @if($gucciImage)
        <a href="{{ $homePage->gucci_spotlight_link ?? '#' }}" class="gucci-spotlight">
            <picture>
                <source media="(max-width: 767px)" srcset="{{ $gucciMobileImage }}">
                <source media="(max-width: 1024px)" srcset="{{ $gucciTabletImage }}">
                <img src="{{ $gucciImage }}" alt="Gucci Spotlight" class="gucci-spotlight-image">
            </picture>
            <div class="gucci-content">
                <!-- Content overlay if needed -->
            </div>
        </a>
        @endif
    @endif

    <!-- Discover Section - Loaded via AJAX after 0.5s -->
    @include('frontend.partials.discover-section-skeleton')
    <div id="discover-content" style="display: none;">
        {{-- Content will be loaded via AJAX --}}
    </div>

    <!-- Perfect Gift Section -->
    @if($perfectGiftSection && $perfectGiftSection->products && $perfectGiftSection->products->count() > 0)
        @include('frontend.partials.perfect-gift-section-content')
    @endif

    <!-- Membership & App Section -->
    @if($homePage && ($homePage->membership_section_active || $homePage->app_section_active))
        @php
            $currentLocale = app()->getLocale();
        @endphp
        <section class="promo-section">
            <div class="promo-grid">
                <!-- Membership Card -->
                @if($homePage->membership_section_active)
                <div class="promo-card membership" @if($homePage->membership_image) style="background-image: url('{{ $homePage->membership_image }}'); background-size: cover; background-position: center; position: relative;" @endif>
                    @if($homePage->membership_image)
                    <div style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)); position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1;"></div>
                    @endif
                    <div style="position: relative; z-index: 2;">
                        <div class="promo-logo">
                            @if(isset($homePage->membership_title[$currentLocale]) && $homePage->membership_title[$currentLocale])
                                {{ $homePage->membership_title[$currentLocale] }}
                            @else
                                RAKAZ
                            @endif
                        </div>
                        <p class="promo-text">
                            @if(isset($homePage->membership_desc[$currentLocale]) && $homePage->membership_desc[$currentLocale])
                                {!! nl2br(e($homePage->membership_desc[$currentLocale])) !!}
                            @else
                                {{ $currentLocale === 'ar' ? 'استمتع بالمكافآت الحصرية والعروض المخصصة للأعضاء فقط' : 'Enjoy exclusive rewards and member-only offers' }}
                            @endif
                        </p>
                        <a href="{{ $homePage->membership_link ?? '#' }}" class="promo-btn">
                            {{ $currentLocale === 'ar' ? 'انضم أو سجل الدخول' : 'Join or Sign In' }}
                        </a>
                    </div>
                </div>
                @endif

                <!-- App Download Card -->
                @if($homePage->app_section_active)
                <div class="promo-card app-download">
                    <h3 class="promo-title">
                        @if(isset($homePage->app_download_title[$currentLocale]) && $homePage->app_download_title[$currentLocale])
                            {!! nl2br(e($homePage->app_download_title[$currentLocale])) !!}
                        @else
                            {{ $currentLocale === 'ar' ? 'احصل على خصم 10% على طلبك الأول<br>من التطبيق' : 'Get 10% off your first order<br>from the app' }}
                        @endif
                    </h3>
                    <p class="promo-subtitle">
                        @if(isset($homePage->app_download_subtitle[$currentLocale]) && $homePage->app_download_subtitle[$currentLocale])
                            {{ $homePage->app_download_subtitle[$currentLocale] }}
                        @else
                            {{ $currentLocale === 'ar' ? 'أدخل الرمز APP10 عند الدفع' : 'Enter code APP10 at checkout' }}
                        @endif
                    </p>
                    <div class="app-badges">
                        @php
                            // Default badge images
                            $googlePlayImage = 'https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg';
                            $appStoreImage = 'https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg';

                            // Use custom uploaded images if available
                            if (isset($homePage->app_image) && is_array($homePage->app_image)) {
                                if (!empty($homePage->app_image['android'])) {
                                    $googlePlayImage = $homePage->app_image['android'];
                                }
                                if (!empty($homePage->app_image['ios'])) {
                                    $appStoreImage = $homePage->app_image['ios'];
                                }
                            }
                        @endphp

                        @if($homePage->google_play_link && $homePage->google_play_link != '#')
                        <a href="{{ $homePage->google_play_link }}" class="app-badge" target="_blank" rel="noopener">
                            <img src="{{ $googlePlayImage }}" alt="Google Play">
                        </a>
                        @else
                        <a href="#" class="app-badge">
                            <img src="{{ $googlePlayImage }}" alt="Google Play">
                        </a>
                        @endif

                        @if($homePage->app_store_link && $homePage->app_store_link != '#')
                        <a href="{{ $homePage->app_store_link }}" class="app-badge" target="_blank" rel="noopener">
                            <img src="{{ $appStoreImage }}" alt="App Store">
                        </a>
                        @else
                        <a href="#" class="app-badge">
                            <img src="{{ $appStoreImage }}" alt="App Store">
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <!-- Home Product Overlay Lazy Loader -->
    <script src="{{ asset('assets/js/home-product-overlay-loader.js') }}"></script>

    <script>
        // Translation helper - GLOBAL
        const isArabic = document.documentElement.getAttribute('dir') === 'rtl' || '{{ app()->getLocale() }}' === 'ar';
        function t(ar, en) {
            return isArabic ? ar : en;
        }

        console.log('🚀 Home page wishlist system initialized');

        // Wishlist toggle function for home page - GLOBAL FUNCTION
        async function toggleWishlist(button, productId) {
            console.log('🔥 toggleWishlist called! Product ID:', productId);

            @auth
            // Show loading state
            button.disabled = true;
            button.style.opacity = '0.6';

            try {
                console.log('📤 Sending wishlist API request...');
                const response = await fetch("{{ route('wishlist.toggle') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ product_id: productId })
                });

                console.log('📤 Wishlist API Response status:', response.status);
                const data = await response.json();
                console.log('📥 Wishlist API Response data:', data);

                if (data.success) {
                    // Toggle active class - check for both 'action' and 'isAdded' for compatibility
                    const wasAdded = data.action === 'added' || data.isAdded === true;
                    if (wasAdded) {
                        button.classList.add('active');
                        Swal.fire({
                            title: t('تمت الإضافة!', 'Added!'),
                            text: t('تم إضافة المنتج إلى قائمة المفضلة', 'Product added to wishlist'),
                            icon: 'success',
                            confirmButtonText: t('حسناً', 'OK'),
                            confirmButtonColor: '#000',
                            timer: 2000,
                            timerProgressBar: true
                        });
                    } else {
                        button.classList.remove('active');
                        Swal.fire({
                            title: t('تمت الإزالة', 'Removed'),
                            text: t('تم إزالة المنتج من قائمة المفضلة', 'Product removed from wishlist'),
                            icon: 'info',
                            confirmButtonText: t('حسناً', 'OK'),
                            confirmButtonColor: '#000',
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }

                    // Update wishlist count if exists
                    const wishlistCount = document.querySelector('.wishlist-count');
                    if (wishlistCount && data.wishlist_count !== undefined) {
                        wishlistCount.textContent = data.wishlist_count;
                    }

                    // Update header wishlist badge
                    const wishlistBadge = document.getElementById('wishlistBadge');
                    if (wishlistBadge) {
                        let currentCount = parseInt(wishlistBadge.textContent) || 0;
                        if (wasAdded) {
                            currentCount++;
                        } else {
                            currentCount = Math.max(0, currentCount - 1);
                        }
                        wishlistBadge.textContent = currentCount;
                    }
                } else {
                    throw new Error(data.message || 'Failed');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: t('خطأ!', 'Error!'),
                    text: t('حدث خطأ أثناء تحديث المفضلة', 'Error updating wishlist'),
                    confirmButtonColor: '#d33'
                });
            } finally {
                button.disabled = false;
                button.style.opacity = '1';
            }
            @else
            // Not logged in - redirect to login
            Swal.fire({
                title: t('تسجيل الدخول مطلوب', 'Login Required'),
                text: t('يجب تسجيل الدخول لإضافة المنتجات للمفضلة', 'Please login to add products to wishlist'),
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: t('تسجيل الدخول', 'Login'),
                cancelButtonText: t('إلغاء', 'Cancel'),
                confirmButtonColor: '#000',
                cancelButtonColor: '#666'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("login") }}';
                }
            });
            @endauth
        }

        // Make it available globally
        window.toggleWishlist = toggleWishlist;

        // Wishlist functionality and Slider initialization
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Page loaded, initializing components...');

            // Load Hero Banner immediately
            loadHeroBanner();

            // Force reinitialize sliders after content is loaded
            setTimeout(function() {
                console.log('🔄 Reinitializing sliders...');

                // Dispatch custom event to reinit sliders
                window.dispatchEvent(new Event('reinit-sliders'));

                // Check if slider elements exist
                const perfectGiftSlider = document.querySelector('.perfect-gift-section .products-container');
                const featuredSlider = document.querySelector('.must-have-section .products-container');
                const perfectGiftPrev = document.querySelector('.perfect-gift-section .slider-btn.prev');
                const perfectGiftNext = document.querySelector('.perfect-gift-section .slider-btn.next');
                const featuredPrev = document.querySelector('.must-have-section .slider-btn.prev');
                const featuredNext = document.querySelector('.must-have-section .slider-btn.next');

                console.log('Perfect Gift Slider:', perfectGiftSlider);
                console.log('Perfect Gift Prev:', perfectGiftPrev);
                console.log('Perfect Gift Next:', perfectGiftNext);
                console.log('Featured Slider:', featuredSlider);
                console.log('Featured Prev:', featuredPrev);
                console.log('Featured Next:', featuredNext);
            }, 500);

            // Event delegation for wishlist buttons (both .wishlist-btn and .overlay-wishlist-btn)
            document.addEventListener('click', function(e) {
                const wishlistBtn = e.target.closest('.wishlist-btn, .overlay-wishlist-btn');
                if (wishlistBtn) {
                    console.log('💗 WISHLIST BUTTON CLICKED via delegation!', wishlistBtn);
                    console.log('💗 Product ID:', wishlistBtn.dataset.productId);

                    e.preventDefault();
                    e.stopPropagation();

                    const productId = wishlistBtn.dataset.productId;
                    if (productId) {
                        console.log('💗 Calling toggleWishlist for product:', productId);
                        toggleWishlist(wishlistBtn, productId);
                    } else {
                        console.error('❌ Product ID not found on wishlist button');
                    }
                }
            }, true); // Use capture phase for higher priority

            // Load Discover Section after 0.5s
            setTimeout(function() {
                loadDiscoverSection();
            }, 500);
        });

        // Load Discover Section via AJAX
        function loadDiscoverSection() {
            console.log('🔍 Loading discover section...');
            const currentLocale = document.documentElement.getAttribute('lang') || 'ar';

            fetch('/api/lazy-load/discover-section?locale=' + currentLocale, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept-Language': currentLocale
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.html) {
                    const discoverContent = document.getElementById('discover-content');
                    const discoverSkeleton = document.querySelector('.discover-section.skeleton-loading');

                    if (discoverContent && discoverSkeleton) {
                        // Insert content
                        discoverContent.innerHTML = data.html;
                        discoverContent.style.display = 'block';

                        // Remove skeleton
                        discoverSkeleton.style.opacity = '0';
                        setTimeout(() => {
                            discoverSkeleton.remove();
                        }, 300);

                        console.log('✅ Discover section loaded');
                    }
                }
            })
            .catch(error => {
                console.error('❌ Error loading discover section:', error);
            });
        }

        // Load Hero Banner via AJAX
        function loadHeroBanner() {
            console.log('🎬 Loading hero banner...');

            fetch('/api/lazy-load/hero-banner', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('📦 Hero banner response received');

                if (data.success && data.html) {
                    const wrapper = document.getElementById('hero-banner-wrapper');

                    if (wrapper) {
                        console.log('✅ Replacing hero banner skeleton with content...');

                        // Replace entire content
                        wrapper.innerHTML = data.html;

                        console.log('✅ Hero banner content replaced');

                        // Initialize slider after a short delay to ensure DOM is ready
                        setTimeout(() => {
                            initHeroSlider();
                        }, 100);
                    } else {
                        console.error('❌ Hero banner wrapper not found');
                    }
                } else {
                    console.error('❌ Invalid hero banner response:', data);
                }
            })
            .catch(error => {
                console.error('❌ Error loading hero banner:', error);
            });
        }

        // Initialize Hero Slider
        function initHeroSlider() {
            const heroSlider = document.querySelector('.hero-slider');
            const heroDots = document.querySelectorAll('.hero-dot');

            if (!heroSlider || heroDots.length === 0) {
                console.warn('⚠️ Hero slider elements not found');
                return;
            }

            const slides = heroSlider.querySelectorAll('.hero-slide');
            const totalSlides = slides.length;

            if (totalSlides === 0) {
                console.warn('⚠️ No slides found');
                return;
            }

            console.log(`✅ Hero slider initialized: ${totalSlides} slides found`);

            let currentSlide = 0;
            const loadedSlides = new Set([0]); // First slide already loaded

            // Load remaining slides progressively
            loadHeroSlides(slides, loadedSlides);

            // Update slide function
            function updateSlide(index) {
                // Load image if not loaded yet
                if (!loadedSlides.has(index)) {
                    loadSlideImage(slides[index], index, loadedSlides);
                }

                slides.forEach((slide, i) => {
                    if (i === index) {
                        slide.classList.add('active');
                    } else {
                        slide.classList.remove('active');
                    }
                });
                heroDots.forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            }

            // Auto slide every 5 seconds
            setInterval(() => {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlide(currentSlide);
            }, 5000);

            // Dot click handlers
            heroDots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    updateSlide(currentSlide);
                });
            });

            // Ensure first slide is active
            updateSlide(0);
        }

        // Load hero slides progressively
        function loadHeroSlides(slides, loadedSlides) {
            console.log('🔄 Loading hero slides progressively...');

            // Load slides one by one with delay
            let delay = 1000; // Start after 1 second

            slides.forEach((slide, index) => {
                if (index === 0) return; // Skip first slide (already loaded)

                setTimeout(() => {
                    loadSlideImage(slide, index, loadedSlides);
                }, delay);

                delay += 500; // Add 500ms between each image load
            });
        }

        // Load individual slide image
        function loadSlideImage(slide, index, loadedSlides) {
            const lazyPicture = slide.querySelector('.lazy-picture');

            if (!lazyPicture || loadedSlides.has(index)) return;

            const desktopSrc = lazyPicture.dataset.desktop;
            const tabletSrc = lazyPicture.dataset.tablet;
            const mobileSrc = lazyPicture.dataset.mobile;
            const alt = lazyPicture.dataset.alt;

            // Create actual picture element
            const picture = document.createElement('picture');

            const mobileSource = document.createElement('source');
            mobileSource.media = '(max-width: 767px)';
            mobileSource.srcset = mobileSrc;

            const tabletSource = document.createElement('source');
            tabletSource.media = '(max-width: 1024px)';
            tabletSource.srcset = tabletSrc;

            const img = document.createElement('img');
            img.src = desktopSrc;
            img.alt = alt;
            img.className = 'hero-banner-image';

            picture.appendChild(mobileSource);
            picture.appendChild(tabletSource);
            picture.appendChild(img);

            // Replace placeholder with actual image
            lazyPicture.replaceWith(picture);
            loadedSlides.add(index);

            console.log(`✅ Slide ${index} image loaded`);
        }
    </script>

@endpush
