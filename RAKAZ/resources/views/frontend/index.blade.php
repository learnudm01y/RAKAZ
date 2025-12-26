@extends('layouts.app')

@section('content')
    <!-- Hero Banner Slider -->
    @if($homePage && $homePage->hero_slides && count($homePage->hero_slides) > 0)
    <section class="hero-banner">
        <div class="hero-slider">
            @foreach($homePage->hero_slides as $index => $slide)
            @php
                $desktopImage = $slide['image'];
                $tabletImage = isset($homePage->hero_slides_tablet[$index]['image']) && !empty($homePage->hero_slides_tablet[$index]['image'])
                    ? $homePage->hero_slides_tablet[$index]['image']
                    : $desktopImage;
                $mobileImage = isset($homePage->hero_slides_mobile[$index]['image']) && !empty($homePage->hero_slides_mobile[$index]['image'])
                    ? $homePage->hero_slides_mobile[$index]['image']
                    : $desktopImage;
            @endphp
            <div class="hero-slide {{ $index === 0 ? 'active' : '' }}">
                <a href="{{ $slide['link'] ?? '#' }}" class="hero-slide-link" rel="noopener">
                    <picture>
                        <source media="(max-width: 767px)" srcset="{{ $mobileImage }}">
                        <source media="(max-width: 1024px)" srcset="{{ $tabletImage }}">
                        <img src="{{ $desktopImage }}" alt="{{ $slide['alt'] ?? 'Hero Banner ' . ($index + 1) }}" class="hero-banner-image" loading="eager">
                    </picture>
                </a>
            </div>
            @endforeach
        </div>
        <div class="hero-dots">
            @foreach($homePage->hero_slides as $index => $slide)
            <span class="hero-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></span>
            @endforeach
        </div>
        <div class="hero-content">
            <div class="hero-text">
                <!-- <a href="#" class="hero-btn">تسوق التشكيلة</a> -->
            </div>
        </div>
    </section>
    @endif

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
    @include('frontend.partials.featured-section-skeleton')
    <div id="featured-content">
        {{-- Content will be loaded via AJAX --}}
    </div>

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

    <!-- Discover More -->
    @if($homePage && $homePage->discover_section_active && isset($discoverItems) && count($discoverItems) > 0)
        @php
            $currentLocale = app()->getLocale();
            $discoverTitle = $currentLocale === 'ar' ? 'اكتشف المزيد' : 'Discover More';

            // Split items for grid layout: first 3 items in grid, remaining in row
            $gridItems = $discoverItems->take(3);
            $rowItems = $discoverItems->slice(3);
        @endphp
        <section class="discover-section">
            <h2 class="section-title">{{ $discoverTitle }}</h2>

            @if($gridItems->count() > 0)
            <div class="discover-grid">
                @foreach($gridItems as $item)
                <div class="discover-card">
                    <a href="{{ $item->link }}">
                        <img src="{{ $item->image }}" alt="{{ $item->getTitle($currentLocale) }}" class="discover-image">
                        <h3 class="discover-title">{{ $item->getTitle($currentLocale) }}</h3>
                    </a>
                </div>
                @endforeach
            </div>
            @endif

            @if($rowItems->count() > 0)
            <div class="discover-row">
                @foreach($rowItems as $index => $item)
                <div class="discover-card-wide {{ $index === 0 ? 'fragrance' : 'watches' }}">
                    <a href="{{ $item->link }}">
                        <img src="{{ $item->image }}" alt="{{ $item->getTitle($currentLocale) }}" class="discover-wide-image">
                        <h3 class="discover-title-wide">{{ $item->getTitle($currentLocale) }}</h3>
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </section>
    @endif

    <!-- Perfect Gift Section (from database) -->
    @if($perfectGiftSection && $perfectGiftSection->products && $perfectGiftSection->products->count() > 0)
        @include('frontend.partials.perfect-gift-section-content')
    @endif

    <!-- Featured Section (Must Have Items) - Loaded via AJAX in featured-content div above -->
    {{-- @if($featuredSection && $featuredSection->products && $featuredSection->products->count() > 0)
        @include('frontend.partials.featured-section-content')
    @endif --}}

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
    <script>
        // Wishlist functionality and Slider initialization
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Page loaded, initializing components...');

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

            // Wishlist buttons
            const wishlistButtons = document.querySelectorAll('.wishlist-btn');
            wishlistButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    Swal.fire({
                        title: 'تمت الإضافة!',
                        text: 'تم إضافة المنتج إلى قائمة المفضلة',
                        icon: 'success',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#000',
                        timer: 2000,
                        timerProgressBar: true
                    });
                });
            });
        });
    </script>

@endpush
