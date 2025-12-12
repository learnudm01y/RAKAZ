@extends('layouts.app')

@section('content')
    <!-- Hero Banner Slider -->
    @if($homePage && $homePage->hero_slides && count($homePage->hero_slides) > 0)
    <section class="hero-banner">
        <div class="hero-slider">
            @foreach($homePage->hero_slides as $index => $slide)
            <div class="hero-slide {{ $index === 0 ? 'active' : '' }}">
                <a href="{{ $slide['link'] ?? '#' }}">
                    <img src="{{ $slide['image'] }}" alt="{{ $slide['alt'] ?? 'Hero Banner ' . ($index + 1) }}" class="hero-banner-image">
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
    <a href="{{ $homePage->cyber_sale_link ?? '#' }}" class="cyber-sale-banner">
        <img src="{{ $homePage->cyber_sale_image }}"
             alt="{{ $homePage->cyber_sale_alt ?? 'Cyber Sale' }}"
             class="cyber-sale-image">
    </a>
    @endif

    <!-- Gifts Section -->
    @if($homePage && $homePage->gifts_section_active && $homePage->gifts_items && count($homePage->gifts_items) > 0)
    <section class="gifts-section">
        <h2 class="section-title">{{ $giftsTitle ?? 'الهدايا' }}</h2>
        <div class="gifts-grid">
            @foreach($homePage->gifts_items as $gift)
            <div class="gift-card">
                <a href="{{ $gift['link'] ?? '#' }}">
                    <img src="{{ is_array($gift['image'] ?? null) ? ($gift['image'][app()->getLocale()] ?? $gift['image']['ar'] ?? '') : ($gift['image'] ?? '') }}"
                         alt="{{ $gift['title'][app()->getLocale()] ?? 'Gift' }}"
                         class="gift-image">
                    <h3 class="gift-title">{{ $gift['title'][app()->getLocale()] ?? '' }}</h3>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Dolce & Gabbana Casa Banner -->
    @if($homePage && $homePage->dg_banner_active && $homePage->dg_banner_image)
    <a href="{{ $homePage->dg_banner_link ?? '#' }}" class="dg-banner">
        <img src="{{ is_array($homePage->dg_banner_image) ? ($homePage->dg_banner_image[app()->getLocale()] ?? $homePage->dg_banner_image['ar'] ?? '') : $homePage->dg_banner_image }}"
             alt="Dolce & Gabbana Casa"
             class="dg-banner-image">
        <div class="dg-content">
            <!-- Optional: يمكن إضافة عنوان ووصف هنا لاحقاً -->
        </div>
    </a>
    @endif

    <!-- Must-Have Styles -->
    <section class="must-have-section">
        <div class="section-header">
            <h2 class="section-title">كنادر مميزة</h2>
            <a href="{{ route('shop') }}" class="shop-all">تسوق الكل</a>
        </div>
        <div class="products-slider">
            <button class="slider-btn prev">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <div class="products-container">
                <div class="product-card">
                    <a href="{{ route('shop') }}" class="product-image-wrapper">
                        <img src="/assets/images/New folder/Emirati_Gold_Edition_Blue.jpg" alt="Product" class="product-image-primary">
                        <img src="/assets/images/New folder/Emirati_Gold_Edition_White.jpg" alt="Product" class="product-image-secondary">
                        <button class="wishlist-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                        <span class="badge new-season">موسم جديد</span>
                    </a>
                    <div class="product-info">
                        <p class="product-brand">ركاز</p>
                        <h3 class="product-name">كندورة إماراتية فاخرة - لون أزرق</h3>
                        <p class="product-price">850 درهم إماراتي</p>
                        <div class="size-selector">
                            <button class="size-arrow prev">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="size-options-wrapper">
                                <button class="size-option" data-size="S">S</button>
                                <button class="size-option" data-size="M">M</button>
                                <button class="size-option" data-size="L">L</button>
                                <button class="size-option" data-size="XL">XL</button>
                                <button class="size-option" data-size="XXL">XXL</button>
                            </div>
                            <button class="size-arrow next">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <a href="{{ route('shop') }}" class="product-image-wrapper">
                        <img src="/assets/images/New folder/Kuwaiti_Gold_Edition_White.jpg" alt="Product" class="product-image-primary">
                        <img src="/assets/images/New folder/Kuwaiti_blue_image_3_treated.jpg" alt="Product" class="product-image-secondary">
                        <button class="wishlist-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                    </a>
                    <div class="product-info">
                        <p class="product-brand">ركاز</p>
                        <h3 class="product-name">شماغ أحمر فاخر - قطن سويسري</h3>
                        <p class="product-price">180 درهم إماراتي</p>
                        <div class="size-selector">
                            <button class="size-arrow prev">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="size-options-wrapper">
                                <button class="size-option" data-size="واحد">مقاس واحد</button>
                            </div>
                            <button class="size-arrow next">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <a href="{{ route('shop') }}" class="product-image-wrapper">
                        <img src="/assets/images/New folder/Treated_1.jpg" alt="Product" class="product-image-primary">
                        <img src="/assets/images/New folder/Treated_image_2.jpg" alt="Product" class="product-image-secondary">
                        <button class="wishlist-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                        <span class="badge new-season">موسم جديد</span>
                    </a>
                    <div class="product-info">
                        <p class="product-brand">ركاز</p>
                        <h3 class="product-name">فانيلة قطن - أبيض</h3>
                        <p class="product-price">120 درهم إماراتي</p>
                        <div class="size-selector">
                            <button class="size-arrow prev">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="size-options-wrapper">
                                <button class="size-option" data-size="S">S</button>
                                <button class="size-option" data-size="M">M</button>
                                <button class="size-option" data-size="L">L</button>
                                <button class="size-option" data-size="XL">XL</button>
                                <button class="size-option" data-size="XXL">XXL</button>
                            </div>
                            <button class="size-arrow next">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <a href="{{ route('shop') }}" class="product-image-wrapper">
                        <img src="/assets/images/New folder/Image_treated_4.jpg" alt="Product" class="product-image-primary">
                        <img src="/assets/images/New folder/Updated_size.jpg" alt="Product" class="product-image-secondary">
                        <button class="wishlist-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                    </a>
                    <div class="product-info">
                        <p class="product-brand">ركاز</p>
                        <h3 class="product-name">سروال تقليدي - بيج</h3>
                        <p class="product-price">280 درهم إماراتي</p>
                        <div class="size-selector">
                            <button class="size-arrow prev">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="size-options-wrapper">
                                <button class="size-option" data-size="28">28</button>
                                <button class="size-option" data-size="30">30</button>
                                <button class="size-option" data-size="32">32</button>
                                <button class="size-option" data-size="34">34</button>
                                <button class="size-option" data-size="36">36</button>
                            </div>
                            <button class="size-arrow next">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <a href="{{ route('shop') }}" class="product-image-wrapper">
                        <img src="/assets/images/New folder/2_0665cfeb-d5d6-429a-b541-168097c14ccb.jpg" alt="Product" class="product-image-primary">
                        <img src="/assets/images/New folder/2_98b84880-82eb-4ddf-8923-63083d78ffbf.jpg" alt="Product" class="product-image-secondary">
                        <button class="wishlist-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                        <span class="badge new-season">موسم جديد</span>
                    </a>
                    <div class="product-info">
                        <p class="product-brand">ركاز</p>
                        <h3 class="product-name">وزارة فاخرة - قطن مصري</h3>
                        <p class="product-price">320 درهم إماراتي</p>
                        <div class="size-selector">
                            <button class="size-arrow prev">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="size-options-wrapper">
                                <button class="size-option" data-size="S">S</button>
                                <button class="size-option" data-size="M">M</button>
                                <button class="size-option" data-size="L">L</button>
                                <button class="size-option" data-size="XL">XL</button>
                                <button class="size-option" data-size="XXL">XXL</button>
                            </div>
                            <button class="size-arrow next">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <button class="slider-btn next">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </section>

    <!-- Gucci Spotlight -->
    @if($homePage && $homePage->gucci_spotlight_active && $homePage->gucci_spotlight_image)
        @php
            $gucciImage = null;
            if (is_array($homePage->gucci_spotlight_image)) {
                $currentLocale = app()->getLocale();
                $gucciImage = $homePage->gucci_spotlight_image[$currentLocale] ?? $homePage->gucci_spotlight_image['ar'] ?? null;
            } else {
                $gucciImage = $homePage->gucci_spotlight_image;
            }
        @endphp
        @if($gucciImage)
        <a href="{{ $homePage->gucci_spotlight_link ?? '#' }}" class="gucci-spotlight">
            <img src="{{ $gucciImage }}" alt="Gucci Spotlight" class="gucci-spotlight-image">
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

    <!-- Perfect Present -->
    <section class="perfect-present-section">
        <div class="section-header">
            <h2 class="section-title">الهدية المثالية</h2>
            <a href="{{ route('shop') }}" class="shop-all">تسوق الكل</a>
        </div>
        <div class="products-slider">
            <button class="slider-btn prev">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <div class="products-container">
                <div class="product-card">
                    <a href="{{ route('shop') }}" class="product-image-wrapper">
                        <img src="/assets/images/New folder/2_fa42623b-b79c-423e-be3d-a63ede9ff974.jpg" alt="Product" class="product-image-primary">
                        <img src="/assets/images/New folder/3_36dc3051-f361-4a0a-bfd5-3c984f283a01.jpg" alt="Product" class="product-image-secondary">
                        <button class="wishlist-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                        <span class="badge new-season">موسم جديد</span>
                    </a>
                    <div class="product-info">
                        <p class="product-brand">ركاز</p>
                        <h3 class="product-name">كندورة كويتية فاخرة</h3>
                        <p class="product-price">950 درهم إماراتي</p>
                        <div class="size-selector">
                            <button class="size-arrow prev">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="size-options-wrapper">
                                <button class="size-option" data-size="S">S</button>
                                <button class="size-option" data-size="M">M</button>
                                <button class="size-option" data-size="L">L</button>
                                <button class="size-option" data-size="XL">XL</button>
                                <button class="size-option" data-size="XXL">XXL</button>
                            </div>
                            <button class="size-arrow next">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <a href="{{ route('shop') }}" class="product-image-wrapper">
                        <img src="/assets/images/New folder/3_5d1a45bd-0fb0-46aa-9bf1-0251f1e8a513.jpg" alt="Product" class="product-image-primary">
                        <img src="/assets/images/New folder/3_70b1a53a-2b15-4598-8e56-eb5b979e18fd.jpg" alt="Product" class="product-image-secondary">
                        <button class="wishlist-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                        <span class="badge new-season">موسم جديد</span>
                    </a>
                    <div class="product-info">
                        <p class="product-brand">ركاز</p>
                        <h3 class="product-name">شماغ أبيض قطن فاخر</h3>
                        <p class="product-price">220 درهم إماراتي</p>
                        <div class="size-selector">
                            <button class="size-arrow prev">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="size-options-wrapper">
                                <button class="size-option" data-size="موحد">موحد</button>
                            </div>
                            <button class="size-arrow next">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <a href="{{ route('shop') }}" class="product-image-wrapper">
                        <img src="/assets/images/New folder/3_a0c29748-9d72-43bc-8fbd-9704da8d885f.jpg" alt="Product" class="product-image-primary">
                        <img src="/assets/images/New folder/3_ac5cf230-1e12-4c93-9db4-bfb900263827.jpg" alt="Product" class="product-image-secondary">
                        <button class="wishlist-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                        <span class="badge new-season">موسم جديد</span>
                    </a>
                    <div class="product-info">
                        <p class="product-brand">ركاز</p>
                        <h3 class="product-name">فانيلة قطن ناعمة بيضاء</h3>
                        <p class="product-price">95 درهم إماراتي</p>
                        <div class="size-selector">
                            <button class="size-arrow prev">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="size-options-wrapper">
                                <button class="size-option" data-size="S">S</button>
                                <button class="size-option" data-size="M">M</button>
                                <button class="size-option" data-size="L">L</button>
                                <button class="size-option" data-size="XL">XL</button>
                                <button class="size-option" data-size="XXL">XXL</button>
                            </div>
                            <button class="size-arrow next">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <a href="{{ route('shop') }}" class="product-image-wrapper">
                        <img src="/assets/images/New folder/3_c079fc2f-cb5e-4337-a2fb-d4055f9dd23c.jpg" alt="Product" class="product-image-primary">
                        <img src="/assets/images/New folder/3_c379f95c-dc6b-4058-b6d4-962885eec798.jpg" alt="Product" class="product-image-secondary">
                        <button class="wishlist-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                        <span class="badge new-season">موسم جديد</span>
                    </a>
                    <div class="product-info">
                        <p class="product-brand">ركاز</p>
                        <h3 class="product-name">وزارة عمانية مطرزة</h3>
                        <p class="product-price">380 درهم إماراتي</p>
                        <div class="size-selector">
                            <button class="size-arrow prev">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="size-options-wrapper">
                                <button class="size-option" data-size="S">S</button>
                                <button class="size-option" data-size="M">M</button>
                                <button class="size-option" data-size="L">L</button>
                                <button class="size-option" data-size="XL">XL</button>
                                <button class="size-option" data-size="XXL">XXL</button>
                            </div>
                            <button class="size-arrow next">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <a href="{{ route('shop') }}" class="product-image-wrapper">
                        <img src="/assets/images/New folder/51.jpg" alt="Product" class="product-image-primary">
                        <img src="/assets/images/New folder/Updated_10943a34-f761-4c6b-b807-a21f83974ed1.jpg" alt="Product" class="product-image-secondary">
                        <button class="wishlist-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                        <span class="badge new-season">موسم جديد</span>
                    </a>
                    <div class="product-info">
                        <p class="product-brand">ركاز</p>
                        <h3 class="product-name">سروال تقليدي قطن خالص</h3>
                        <p class="product-price">295 درهم إماراتي</p>
                        <div class="size-selector">
                            <button class="size-arrow prev">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="size-options-wrapper">
                                <button class="size-option" data-size="S">S</button>
                                <button class="size-option" data-size="M">M</button>
                                <button class="size-option" data-size="L">L</button>
                                <button class="size-option" data-size="XL">XL</button>
                                <button class="size-option" data-size="XXL">XXL</button>
                            </div>
                            <button class="size-arrow next">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="slider-btn next">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </section>

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
        // Wishlist functionality
        document.addEventListener('DOMContentLoaded', function() {
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
