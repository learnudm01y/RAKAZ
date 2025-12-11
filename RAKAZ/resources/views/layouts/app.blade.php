@php use Illuminate\Support\Facades\Storage; @endphp
{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html> --}}

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1a1a1a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ app()->getLocale() == 'ar' ? 'ركاز - الكندورة الإماراتية الفاخرة' : 'RAKAZ - Luxury Emirati Kandora' }}</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/shared-components.css">
    <link rel="stylesheet" href="/assets/css/custom-select.css">
    <link rel="stylesheet" href="/assets/css/cart-sidebar.css">
    <link rel="stylesheet" href="/assets/css/styles-mobile.css">
    <link rel="stylesheet" href="/assets/css/styles-tablet.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- JavaScript Files -->
    <script src="/assets/js/custom-select.js" defer></script>
    <script src="/assets/js/shared-components.js" defer></script>
    <script src="/assets/js/cart-sidebar.js" defer></script>
    <script src="/assets/js/script.js" defer></script>
    <script src="/assets/js/script-mobile.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>

<body dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-sidebar-overlay" id="cartOverlay"></div>
        <div class="cart-sidebar-content">
            <div class="cart-sidebar-header">
                <h2 class="cart-sidebar-title">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    حقيبة التسوق
                </h2>
                <button class="cart-sidebar-close" id="cartClose">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div class="cart-sidebar-items" id="cartItems">
                <!-- Cart items will be dynamically added here -->
                <div class="cart-empty" id="cartEmpty">
                    <svg class="cart-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <p>حقيبتك فارغة</p>
                    <span>ابدأ بإضافة بعض المنتجات الرائعة!</span>
                </div>
            </div>

            <div class="cart-sidebar-footer" id="cartFooter">
                <div class="cart-subtotal">
                    <span>المجموع الفرعي:</span>
                    <span class="cart-subtotal-amount" id="cartSubtotal">0 درهم</span>
                </div>
                <p class="cart-shipping-note">الشحن والضرائب يتم حسابها عند الدفع</p>
                <a href="{{ route('checkout') }}" class="cart-checkout-btn">إتمام الشراء</a>
                <a href="{{ route('cart') }}" class="cart-view-btn">عرض الحقيبة</a>
            </div>
        </div>
    </div>

    <!-- Header Top Bar -->
    <!-- <div class="top-bar">
        <div class="top-bar-content">
            <div class="top-bar-item">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M13 16V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h1m8-1a1 1 0 0 1-1 1H9m4-1V8a1 1 0 0 1 1-1h2.586a1 1 0 0 1 .707.293l3.414 3.414a1 1 0 0 1 .293.707V16a1 1 0 0 1-1 1h-1m-6-1a1 1 0 0 0 1 1h1M5 17a2 2 0 1 0 4 0m-4 0h4m0 0h6m0 0a2 2 0 1 0 4 0m-4 0h4m0 0h4"></path>
                </svg>
                <span class="ar-text">التوصيل خلال ساعتين في دبي</span>
                <span class="en-text">2 HOUR DELIVERY IN DUBAI</span>
            </div>
            <div class="top-bar-item">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 7h-4V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v3H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zm-6 0H10V4h4v3z"></path>
                </svg>
                <span class="ar-text">تغليف مجاني للهدايا</span>
                <span class="en-text">FREE GIFT PACKAGING</span>
            </div>
            <div class="top-bar-item">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <path d="M2 10h20"></path>
                </svg>
                <span class="ar-text">الدفع بسلاسة مع Apple Pay</span>
                <span class="en-text">GO SEAMLESS WITH APPLE PAY</span>
            </div>
        </div>
    </div> -->

    <!-- Main Header -->
    <header class="main-header">
        <div class="header-container">
            <!-- Top Row -->
            <div class="header-top">
                <div class="header-right">
                    <div class="currency-selector">
                        <img src="https://flagcdn.com/ae.svg" alt="UAE" class="flag-icon"
                            id="selected-currency-flag">
                        <span id="selected-currency">UAE</span>
                        <svg class="arrow-down" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                        <div class="currency-dropdown">
                            <a href="#" class="currency-option" data-currency="UAE"
                                data-flag="https://flagcdn.com/ae.svg">
                                <img src="https://flagcdn.com/ae.svg" alt="UAE" class="flag-icon">
                                <span>UAE</span>
                            </a>
                            <a href="#" class="currency-option" data-currency="Saudi Arabia"
                                data-flag="https://flagcdn.com/sa.svg">
                                <img src="https://flagcdn.com/sa.svg" alt="Saudi Arabia" class="flag-icon">
                                <span class="ar-text">المملكة العربية السعودية</span>
                                <span class="en-text">Saudi Arabia</span>
                            </a>
                            <a href="#" class="currency-option" data-currency="Oman"
                                data-flag="https://flagcdn.com/om.svg">
                                <img src="https://flagcdn.com/om.svg" alt="Oman" class="flag-icon">
                                <span class="ar-text">عُمان</span>
                                <span class="en-text">Oman</span>
                            </a>
                            <a href="#" class="currency-option" data-currency="Kuwait"
                                data-flag="https://flagcdn.com/kw.svg">
                                <img src="https://flagcdn.com/kw.svg" alt="Kuwait" class="flag-icon">
                                <span class="ar-text">الكويت</span>
                                <span class="en-text">Kuwait</span>
                            </a>
                            <a href="#" class="currency-option" data-currency="Bahrain"
                                data-flag="https://flagcdn.com/bh.svg">
                                <img src="https://flagcdn.com/bh.svg" alt="Bahrain" class="flag-icon">
                                <span class="ar-text">البحرين</span>
                                <span class="en-text">Bahrain</span>
                            </a>
                            <a href="#" class="currency-option" data-currency="Qatar"
                                data-flag="https://flagcdn.com/qa.svg">
                                <img src="https://flagcdn.com/qa.svg" alt="Qatar" class="flag-icon">
                                <span class="ar-text">قطر</span>
                                <span class="en-text">Qatar</span>
                            </a>
                        </div>
                    </div>
                    <span class="divider">|</span>
                    <button class="lang-toggle" onclick="toggleLanguage()">
                        <span class="ar-text">English</span>
                        <span class="en-text">العربية</span>
                    </button>
                </div>

                <div class="header-center">

                    <div class="search-box">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" placeholder="ابحث عن مصمم أو منتج">
                    </div>
                </div>

                <div class="header-left">
                    <a href="{{ route('wishlist') }}" class="header-link">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                            </path>
                        </svg>
                        <span>المفضلة</span>
                        <span class="badge">0</span>
                    </a>
                    <div class="header-link account-dropdown-wrapper">
                        <a href="#" class="header-link account-link">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>حسابي</span>
                        </a>
                        <div class="account-dropdown">
                            <div class="account-dropdown-content">
                                @guest
                                    <a href="{{ route('user.login') }}" class="account-dropdown-item account-login-btn">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                            <polyline points="10 17 15 12 10 7"></polyline>
                                            <line x1="15" y1="12" x2="3" y2="12"></line>
                                        </svg>
                                        <span>تسجيل الدخول</span>
                                    </a>
                                    <div class="account-dropdown-divider"></div>
                                    <p class="account-dropdown-text">ليس لديك حساب حتى الآن؟</p>
                                    <a href="{{ route('user.login') }}?mode=register" class="account-dropdown-item account-register-btn">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="8.5" cy="7" r="4"></circle>
                                            <line x1="20" y1="8" x2="20" y2="14"></line>
                                            <line x1="23" y1="11" x2="17" y2="11"></line>
                                        </svg>
                                        <span>إنشاء حساب</span>
                                    </a>
                                    <div class="account-dropdown-divider"></div>
                                @else
                                    <div class="account-dropdown-user" style="padding: 15px; border-bottom: 1px solid #eee;">
                                        <p style="margin: 0 0 5px 0;"><strong>مرحباً، {{ Auth::user()->name }}</strong></p>
                                        <p style="font-size: 12px; color: #666; margin: 0;">{{ Auth::user()->email }}</p>
                                    </div>
                                    <a href="{{ route('orders') }}" class="account-dropdown-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                        </svg>
                                        <span>طلباتي</span>
                                    </a>
                                    <a href="{{ route('wishlist') }}" class="account-dropdown-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                        </svg>
                                        <span>المفضلة</span>
                                    </a>
                                    <div class="account-dropdown-divider"></div>
                                    <a href="#" onclick="event.preventDefault(); handleLogout();" class="account-dropdown-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <span>تسجيل الخروج</span>
                                    </a>
                                    <form id="logoutForm" method="POST" action="{{ route('user.logout') }}" style="display: none;">
                                        @csrf
                                    </form>
                                @endguest
                                <a href="#" class="account-dropdown-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path
                                            d="M12 1v6m0 6v6m8.66-10.5l-5.2 3M8.54 14l-5.2 3m13.32 0l-5.2-3M8.54 10l-5.2-3">
                                        </path>
                                    </svg>
                                    <span>المساعدة والأسئلة المتكررة</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="header-link" id="cartToggle">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span>الحقيبة</span>
                        <span class="badge" id="cartBadge">0</span>
                    </a>
                    <a href="#" class="logo mobile-logo-between">
                        <img src="/assets/images/ركاز بني copy (1).png" alt="Logo" class="logo-image">
                    </a>
                    <a href="#" class="header-link header-search-btn">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <span>بحث</span>
                    </a>
                    <a href="#" class="header-link mobile-menu-btn">
                        <svg class="icon menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                        <span>القائمة</span>
                    </a>
                    <!-- <a href="#" class="header-link">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>الحساب</span>
                    </a> -->
                </div>
            </div>

            <!-- Logo -->
            <div class="logo-container">
                <a href="#" class="logo">
                    <img src="/assets/images/ركاز بني copy (1).png" alt="Logo" class="logo-image">
                    <!-- <p class="logo-tagline">THE DEFINITIVE HOME OF LUXURY</p> -->
                </a>
            </div>

            <!-- Navigation -->
            <nav class="main-nav">
                @if(isset($menus))
                    @foreach($menus as $menu)
                        @if($menu->activeColumns->count() > 0)
                            <div class="nav-item dropdown">
                                <a href="{{ $menu->link ?? '#' }}" class="nav-link dropdown-trigger">
                                    <span class="ar-text">{{ $menu->getName('ar') }}</span>
                                    <span class="en-text">{{ $menu->getName('en') }}</span>
                                    <svg class="arrow-down" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </a>
                                <div class="dropdown-menu mega-menu">
                                    <div class="dropdown-wrapper">
                                        <div class="dropdown-content">
                                            @foreach($menu->activeColumns as $column)
                                                <div class="dropdown-column">
                                                    <h4 class="dropdown-title">
                                                        <span class="ar-text">{{ $column->getTitle('ar') }}</span>
                                                        <span class="en-text">{{ $column->getTitle('en') }}</span>
                                                    </h4>
                                                    <ul>
                                                        @foreach($column->items as $item)
                                                            @if($item->is_active && $item->category)
                                                                <li>
                                                                    <a href="{{ $item->getLink() }}">
                                                                        <span class="ar-text">{{ $item->getName('ar') }}</span>
                                                                        <span class="en-text">{{ $item->getName('en') }}</span>
                                                                    </a>
                                                                    @if($item->category->children->where('is_active', true)->count() > 0)
                                                                        <ul style="padding-right: 15px; margin-top: 8px;">
                                                                            @foreach($item->category->children->where('is_active', true)->sortBy('sort_order') as $childCategory)
                                                                                <li>
                                                                                    <a href="{{ route('category.show', $childCategory->slug[app()->getLocale()] ?? $childCategory->slug['ar']) }}" style="font-size: 13px; color: #666;">
                                                                                        <span class="ar-text">{{ $childCategory->name['ar'] ?? '' }}</span>
                                                                                        <span class="en-text">{{ $childCategory->name['en'] ?? '' }}</span>
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if($menu->image)
                                            <div class="dropdown-image-wrapper">
                                                <div class="dropdown-image">
                                                    <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->getName(app()->getLocale()) }}">
                                                    <div class="dropdown-image-content">
                                                        <h3>
                                                            <span class="ar-text">{{ $menu->getImageTitle('ar') ?? $menu->getName('ar') }}</span>
                                                            <span class="en-text">{{ $menu->getImageTitle('en') ?? $menu->getName('en') }}</span>
                                                        </h3>
                                                        <p>
                                                            <span class="ar-text">{{ $menu->getImageDescription('ar') }}</span>
                                                            <span class="en-text">{{ $menu->getImageDescription('en') }}</span>
                                                        </p>
                                                        <a href="{{ $menu->link ?? '#' }}" class="dropdown-btn">
                                                            <span class="ar-text">تسوق الآن</span>
                                                            <span class="en-text">SHOP NOW</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ $menu->link ?? '#' }}" class="nav-link"
                               @if($menu->image) data-menu-image="{{ Storage::url($menu->image) }}" @endif>
                                <span class="ar-text">{{ $menu->getName('ar') }}</span>
                                <span class="en-text">{{ $menu->getName('en') }}</span>
                            </a>
                        @endif
                    @endforeach
                @endif
            </nav>
        </div>
    </header>

    @yield('content')

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-newsletter">
            <h3 class="newsletter-title">اشترك في نشرتنا الإخبارية</h3>
            <div class="newsletter-form">
                <input type="email" placeholder="أدخل عنوان بريدك الإلكتروني هنا">
                <button type="submit">اشترك</button>
            </div>
        </div>

        <div class="footer-social">
            <p class="social-title">تابع ركاز</p>
            <div class="social-icons">
                <a href="#" class="social-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                </a>
                <a href="#" class="social-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                    </svg>
                </a>
                <a href="#" class="social-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                    </svg>
                </a>
                <a href="#" class="social-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                    </svg>
                </a>
                <a href="#" class="social-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="footer-content">
            <div class="footer-column">
                <h4 class="footer-title">مجموعات الكندورة</h4>
                <ul class="footer-links">
                    <li><a href="#">كندورة كلاسيكية</a></li>
                    <li><a href="#">كندورة فاخرة</a></li>
                    <li><a href="#">كندورة الصيف</a></li>
                    <li><a href="#">كندورة الشتاء</a></li>
                    <li><a href="#">تفصيل حسب الطلب</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4 class="footer-title">الفئات الرائجة</h4>
                <ul class="footer-links">
                    <li><a href="#">الكنادير الإماراتية</a></li>
                    <li><a href="#">الكنادير السعودية</a></li>
                    <li><a href="#">الشالات القطنية</a></li>
                    <li><a href="#">الفانيل الفاخرة</a></li>
                    <li><a href="#">مستلزمات العناية</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4 class="footer-title">العناية بالعملاء</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('contact') }}">اتصل بنا</a></li>
                    <li><a href="#">الأسئلة الشائعة</a></li>
                    <li><a href="#">الدفع</a></li>
                    <li><a href="#">تتبع الطلب</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4 class="footer-title">معلومات عنا</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">من نحن</a></li>
                    <li><a href="#">الوظائف</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4 class="footer-title">الشحن والإرجاع</h4>
                <ul class="footer-links">
                    <li><a href="#">الشحن والتوصيل</a></li>
                    <li><a href="#">الإرجاع عبر الإنترنت</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4 class="footer-title">القانوني</h4>
                <ul class="footer-links">
                    <li><a href="#">الشروط والأحكام</a></li>
                    <li><a href="{{ route('privacy.policy') }}">سياسة الخصوصية والكوكيز</a></li>
                    <li><a href="#">مطابقة الأسعار</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-apps">
                <div class="app-badges">
                    <p class="apps-title">تطبيقات ركاز</p>
                    <div class="app-badges">
                        <a href="#" class="app-badge-small">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg"
                                alt="App Store">
                        </a>
                        <a href="#" class="app-badge-small">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                                alt="Google Play">
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-info">
                <div class="footer-contact">
                    <p>خدمة العملاء: <a href="tel:800717171">800 717171</a></p>
                    <p>خدمة واتساب: <a href="https://wa.me/971553007879">+971 55 300 7879</a></p>
                </div>
                <div class="footer-copyright">
                    <img src="https://placehold.co/150x40/000000/ffffff?text=بيت+الكندورة" alt="ركاز"
                        class="tayer-logo">
                    <p>ركاز LLC. 2025. جميع الحقوق محفوظة</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // تفعيل القائمة المنسدلة المخصصة
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.custom-select-init').forEach(select => {
                new CustomSelect(select);
            });
        });

        // Handle logout - simple submit
        function handleLogout() {
            const form = document.getElementById('logoutForm');
            if (form) {
                form.submit();
            }
        }
    </script>
    @stack('scripts')
</body>

</html>
