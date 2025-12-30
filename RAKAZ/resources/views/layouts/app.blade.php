@php use Illuminate\Support\Facades\Storage; @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1a1a1a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- User Authentication Status -->
    <meta name="user-authenticated" content="{{ auth()->check() ? 'true' : 'false' }}">
    @auth
        <meta name="user-name" content="{{ auth()->user()->name }}">
        <meta name="user-id" content="{{ auth()->user()->id }}">
    @endauth

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

    <!-- Skeleton Loading CSS -->
    <link rel="stylesheet" href="/assets/css/skeleton-loading.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Pica.js for Image Enhancement - DISABLED -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/pica@9.0.1/dist/pica.min.js"></script> -->

    <!-- JavaScript Files -->
    <script src="/assets/js/lazy-loading.js" defer></script>
    <script src="/assets/js/custom-select.js" defer></script>
    <script src="/assets/js/shared-components.js" defer></script>
    <script src="/assets/js/cart-sidebar.js" defer></script>
    <script src="/assets/js/featured-section.js" defer></script>
    <script src="/assets/js/perfect-gift-section.js" defer></script>
    <!-- Desktop Mega Menu must load before script.js -->
    <script src="/assets/js/desktop-mega-menu.js"></script>
    <script src="/assets/js/script.js" defer></script>
    <script src="/assets/js/script-mobile.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CRITICAL: Force SweetAlert above cart sidebar -->
    <style>
        .swal2-container {
            z-index: 99999 !important;
        }
    </style>

    @stack('styles')
</head>

<body dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Cart Sidebar -->
    @php $isAr = app()->getLocale() == 'ar'; @endphp
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
                    {{ $isAr ? 'حقيبة التسوق' : 'Shopping Bag' }}
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
                    <p>{{ $isAr ? 'حقيبتك فارغة' : 'Your bag is empty' }}</p>
                    <span>{{ $isAr ? 'ابدأ بإضافة بعض المنتجات الرائعة!' : 'Start adding some great products!' }}</span>
                </div>
            </div>

            <div class="cart-sidebar-footer" id="cartFooter">
                <div class="cart-subtotal">
                    <span>{{ $isAr ? 'المجموع الفرعي:' : 'Subtotal:' }}</span>
                    <span class="cart-subtotal-amount" id="cartSubtotal">{{ $isAr ? '0 درهم' : '0 AED' }}</span>
                </div>
                <p class="cart-shipping-note">{{ $isAr ? 'الشحن والضرائب يتم حسابها عند الدفع' : 'Shipping and taxes calculated at checkout' }}</p>
                <a href="{{ route('checkout.index') }}" class="cart-checkout-btn">{{ $isAr ? 'إتمام الشراء' : 'Checkout' }}</a>
                <a href="{{ route('cart.index') }}" class="cart-view-btn">{{ $isAr ? 'عرض الحقيبة' : 'View Bag' }}</a>
            </div>
        </div>
    </div>

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
                        <div class="currency-dropdown" id="currency-dropdown">
                            {{-- Content will be loaded via AJAX on first open --}}
                            <div class="currency-dropdown-skeleton">
                                @for($i = 0; $i < 6; $i++)
                                <div class="skeleton-currency-item" style="display: flex; align-items: center; padding: 10px; gap: 10px;">
                                    <div class="skeleton" style="width: 20px; height: 15px; border-radius: 2px;"></div>
                                    <div class="skeleton" style="width: 120px; height: 14px;"></div>
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <span class="divider">|</span>
                    <button class="lang-toggle" onclick="toggleLanguage()">
                        <span class="ar-text">English</span>
                        <span class="en-text">العربية</span>
                    </button>
                </div>

                <div class="header-center">

                    <form action="{{ route('search') }}" method="GET" class="search-box" id="searchForm">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" name="q" id="searchInput" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث عن منتج او تصنيف' : 'Search for product or category' }}" value="{{ request('q') }}" autocomplete="off">
                        <div class="search-suggestions" id="searchSuggestions" style="display: none;"></div>
                    </form>
                </div>

                <div class="header-left">
                    <a href="{{ route('wishlist') }}" class="header-link">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                            </path>
                        </svg>
                        <span class="ar-text">المفضلة</span>
                        <span class="en-text">Wishlist</span>
                        <span class="badge" id="wishlistBadge">{{ auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : 0 }}</span>
                    </a>
                    <div class="header-link account-dropdown-wrapper">
                        <a href="#" class="header-link account-link">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="ar-text">حسابي</span>
                            <span class="en-text">My account</span>
                        </a>
                        <div class="account-dropdown">
                            <div class="account-dropdown-content">
                                @guest
                                    <a href="{{ route('login') }}" class="account-dropdown-item account-login-btn">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                            <polyline points="10 17 15 12 10 7"></polyline>
                                            <line x1="15" y1="12" x2="3" y2="12"></line>
                                        </svg>
                                        <span class="ar-text">تسجيل الدخول</span>
                                        <span class="en-text">Sign in</span>
                                    </a>
                                    <div class="account-dropdown-divider"></div>
                                    <p class="account-dropdown-text">
                                        <span class="ar-text">ليس لديك حساب حتى الآن؟</span>
                                        <span class="en-text">Don't have an account yet?</span>
                                    </p>
                                    <a href="{{ route('login') }}?mode=register" class="account-dropdown-item account-register-btn">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="8.5" cy="7" r="4"></circle>
                                            <line x1="20" y1="8" x2="20" y2="14"></line>
                                            <line x1="23" y1="11" x2="17" y2="11"></line>
                                        </svg>
                                        <span class="ar-text">إنشاء حساب</span>
                                        <span class="en-text">Create account</span>
                                    </a>
                                    <div class="account-dropdown-divider"></div>
                                @else
                                    <div class="account-dropdown-user" style="padding: 15px; border-bottom: 1px solid #eee;">
                                        <p style="margin: 0 0 5px 0;">
                                            <strong>
                                                <span class="ar-text">مرحباً،</span>
                                                <span class="en-text">Welcome,</span>
                                                {{ Auth::user()->name }}
                                            </strong>
                                        </p>
                                        <p style="font-size: 12px; color: #666; margin: 0;">{{ Auth::user()->email }}</p>
                                    </div>
                                    <a href="{{ route('orders.index') }}" class="account-dropdown-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                        </svg>
                                        <span class="ar-text">طلباتي</span>
                                        <span class="en-text">My orders</span>
                                    </a>
                                    <a href="{{ route('profile') }}" class="account-dropdown-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <span class="ar-text">الملف الشخصي</span>
                                        <span class="en-text">My Profile</span>
                                    </a>
                                    <a href="{{ route('wishlist') }}" class="account-dropdown-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                        </svg>
                                        <span class="ar-text">المفضلة</span>
                                        <span class="en-text">Wishlist</span>
                                    </a>
                                    <div class="account-dropdown-divider"></div>
                                    <a href="#" onclick="event.preventDefault(); handleLogout();" class="account-dropdown-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <span class="ar-text">تسجيل الخروج</span>
                                        <span class="en-text">Sign out</span>
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
                                    <span class="ar-text">المساعدة والأسئلة المتكررة</span>
                                    <span class="en-text">Help & FAQs</span>
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
                        <span class="ar-text">الحقيبة</span>
                        <span class="en-text">Bag</span>
                        <span class="badge" id="cartBadge">0</span>
                    </a>
                    <a href="{{ route('home') }}" class="logo mobile-logo-between">
                        <img src="/assets/images/rakazLogo.png" alt="Logo" class="logo-image">
                    </a>
                    <a href="#" class="header-link header-search-btn">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <span class="ar-text">بحث</span>
                        <span class="en-text">Search</span>
                    </a>
                    <a href="#" class="header-link mobile-menu-btn">
                        <svg class="icon menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>


                    </a>

                </div>
            </div>

            <!-- Logo -->
            <div class="logo-container">
                <a href="{{ route('home') }}" class="logo">
                    <img src="/assets/images/rakazLogo.png" alt="Logo" class="logo-image">

                </a>
            </div>

            <!-- Navigation -->
            <nav class="main-nav">
                @if(isset($menus))
                    @foreach($menus as $menu)
                        @php
                            // بناء بيانات القائمة وفلترتها أولاً
                            $allMenuData = $menu->activeColumns->map(function ($column) {
                                $items = $column->items
                                    ->filter(function ($item) {
                                        // التحقق من أن العنصر نشط وله تصنيف ويحتوي التصنيف على منتجات
                                        return $item->is_active &&
                                               $item->category &&
                                               $item->category->products_count > 0;
                                    })
                                    ->map(function ($item) {
                                        $children = [];
                                        if ($item->category && $item->category->children) {
                                            // فلترة التصنيفات الفرعية التي تحتوي على منتجات فقط
                                            $children = $item->category->children
                                                ->where('is_active', true)
                                                ->filter(function ($childCategory) {
                                                    return $childCategory->products_count > 0;
                                                })
                                                ->sortBy('sort_order')
                                                ->map(function ($childCategory) {
                                                    return [
                                                        'name_ar' => $childCategory->name['ar'] ?? '',
                                                        'name_en' => $childCategory->name['en'] ?? '',
                                                        'link' => route('category.show', $childCategory->slug[app()->getLocale()] ?? $childCategory->slug['ar']),
                                                    ];
                                                })
                                                ->values()
                                                ->all();
                                        }

                                        return [
                                            'name_ar' => $item->getName('ar'),
                                            'name_en' => $item->getName('en'),
                                            'link' => $item->getLink(),
                                            'children' => $children,
                                        ];
                                    })
                                    ->values()
                                    ->all();

                                return [
                                    'title_ar' => $column->getTitle('ar'),
                                    'title_en' => $column->getTitle('en'),
                                    'items' => $items,
                                ];
                            })
                            // فلترة الأعمدة التي تحتوي على عناصر (تصنيفات بها منتجات)
                            ->filter(function ($column) {
                                return count($column['items']) > 0;
                            })
                            ->values()
                            ->all();

                            // التحقق من وجود بيانات فعلية لعرضها
                            $hasMenuData = count($allMenuData) > 0;
                        @endphp

                        @if($hasMenuData)
                            {{-- عرض القائمة المنسدلة فقط إذا كان هناك بيانات --}}
                            <div class="nav-item dropdown">
                                <a href="{{ $menu->link ?? '#' }}" class="nav-link dropdown-trigger">
                                    <span class="ar-text">{{ $menu->getName('ar') }}</span>
                                    <span class="en-text">{{ $menu->getName('en') }}</span>
                                    <svg class="arrow-down" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </a>
                                <div class="dropdown-menu mega-menu" data-defer="1" data-menu-id="{{ $menu->id }}">
                                    <div class="dropdown-wrapper">
                                        @php

                                            // حفظ جميع البيانات في DB للـ AJAX
                                            $menu->menu_data = json_encode($allMenuData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                            $menu->save();

                                            // للهاتف: تحميل 5 عناصر فقط
                                            $allItems = [];
                                            foreach ($allMenuData as $column) {
                                                foreach ($column['items'] as $item) {
                                                    $allItems[] = [
                                                        'column_title_ar' => $column['title_ar'],
                                                        'column_title_en' => $column['title_en'],
                                                        'item' => $item
                                                    ];
                                                }
                                            }

                                            $initialItems = array_slice($allItems, 0, 5);
                                            $totalItems = count($allItems);

                                            // إعادة بناء menuData للهاتف (5 عناصر فقط)
                                            $mobileMenuData = [];
                                            foreach ($initialItems as $itemData) {
                                                $columnTitle = $itemData['column_title_ar'];
                                                if (!isset($mobileMenuData[$columnTitle])) {
                                                    $mobileMenuData[$columnTitle] = [
                                                        'title_ar' => $itemData['column_title_ar'],
                                                        'title_en' => $itemData['column_title_en'],
                                                        'items' => []
                                                    ];
                                                }
                                                $mobileMenuData[$columnTitle]['items'][] = $itemData['item'];
                                            }
                                            $mobileMenuData = array_values($mobileMenuData);

                                            // لسطح المكتب: تحميل 13 صف (بما في ذلك التصنيفات الفرعية) من كل عمود
                                            $desktopMenuData = [];
                                            foreach ($allMenuData as $column) {
                                                $limitedItems = [];
                                                $rowCount = 0;
                                                $maxRows = 13;

                                                foreach ($column['items'] as $item) {
                                                    // حساب عدد الصفوف لهذا العنصر (1 للعنصر الرئيسي + عدد التصنيفات الفرعية)
                                                    $itemRows = 1 + (isset($item['children']) ? count($item['children']) : 0);

                                                    // إذا كان إضافة هذا العنصر لن يتجاوز 13 صف
                                                    if ($rowCount + $itemRows <= $maxRows) {
                                                        $limitedItems[] = $item;
                                                        $rowCount += $itemRows;
                                                    } else {
                                                        // وصلنا للحد الأقصى
                                                        break;
                                                    }
                                                }

                                                $desktopMenuData[] = [
                                                    'title_ar' => $column['title_ar'],
                                                    'title_en' => $column['title_en'],
                                                    'items' => $limitedItems,
                                                    'total_items' => count($column['items']),
                                                    'has_more' => count($column['items']) > count($limitedItems)
                                                ];
                                            }
                                        @endphp

                                        <div class="dropdown-content js-mega-menu-content" data-menu-id="{{ $menu->id }}" data-columns-count="{{ count($allMenuData) }}"></div>
                                        <!-- Data for mobile (5 items total) -->
                                        <script type="application/json" class="js-mega-menu-data js-mobile-menu-data" data-menu-id="{{ $menu->id }}" data-total-items="{{ $totalItems }}" data-loaded-items="{{ count($initialItems) }}">@json($mobileMenuData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
                                        <!-- Desktop: سيتم التحميل عبر AJAX -->
                                        <div class="js-desktop-menu-trigger" data-menu-id="{{ $menu->id }}" style="display:none;"></div>

                                        @if($menu->image)
                                            <div class="dropdown-image-wrapper">
                                                <div class="dropdown-image">
                                                    <img
                                                        src="{{ Storage::url($menu->image) }}"
                                                        loading="lazy"
                                                        decoding="async"
                                                        fetchpriority="low"
                                                        alt="{{ $menu->getName(app()->getLocale()) }}">
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
                        @elseif($menu->link)
                            {{-- عرض القائمة كرابط بسيط إذا كانت لا تحتوي على تصنيفات مع منتجات ولكن لها رابط --}}
                            <a href="{{ $menu->link }}" class="nav-link"
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

    <!-- Footer Skeleton -->
    @include('frontend.partials.footer-skeleton')

    <!-- Footer Content -->
    <div id="footer-content">
        {{-- Content will be loaded via AJAX --}}
    </div>

    <script>
        // تفعيل القائمة المنسدلة المخصصة
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.custom-select-init').forEach(select => {
                new CustomSelect(select);
            });

            // Load cart count on page load
            updateCartCount();

            // عرض إشعار المنتجات المحفوظة بعد تسجيل الدخول
            @if(session('wishlist_saved'))
                const savedCount = {{ session('wishlist_saved') }};
                const isArabic = '{{ app()->getLocale() }}' === 'ar';

                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: isArabic ? 'تم الحفظ بنجاح!' : 'Saved Successfully!',
                        html: isArabic
                            ? `<p>تم إضافة <strong>${savedCount}</strong> ${savedCount === 1 ? 'منتج' : 'منتجات'} إلى قائمة الأمنيات</p>`
                            : `<p><strong>${savedCount}</strong> ${savedCount === 1 ? 'item' : 'items'} added to your wishlist</p>`,
                        confirmButtonText: isArabic ? 'عرض قائمة الأمنيات' : 'View Wishlist',
                        showCancelButton: true,
                        cancelButtonText: isArabic ? 'متابعة التسوق' : 'Continue Shopping',
                        confirmButtonColor: '#1a1a1a',
                        cancelButtonColor: '#666',
                        background: '#ffffff',
                        color: '#000000',
                        iconColor: '#28a745'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/wishlist';
                        }
                    });
                }, 500);
            @endif
        });

        // Handle logout - simple submit
        function handleLogout() {
            const form = document.getElementById('logoutForm');
            if (form) {
                form.submit();
            }
        }

        // Update cart count badge
        function updateCartCount() {
            fetch('{{ route("cart.count") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.getElementById('cartBadge');
                if (cartBadge && data.count !== undefined) {
                    cartBadge.textContent = data.count;
                    console.log('Cart count updated:', data.count);
                }
            })
            .catch(error => {
                console.error('Error fetching cart count:', error);
            });
        }
    </script>

    <!-- Live Search Suggestions Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchSuggestions = document.getElementById('searchSuggestions');
            const searchForm = document.getElementById('searchForm');
            let searchTimeout;
            const isArabic = '{{ app()->getLocale() }}' === 'ar';

            if (searchInput && searchSuggestions) {
                // Handle input with debounce
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();

                    clearTimeout(searchTimeout);

                    if (query.length < 2) {
                        searchSuggestions.style.display = 'none';
                        return;
                    }

                    searchTimeout = setTimeout(() => {
                        fetchSuggestions(query);
                    }, 300);
                });

                // Hide suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchForm.contains(e.target)) {
                        searchSuggestions.style.display = 'none';
                    }
                });

                // Show suggestions when focusing on input with text
                searchInput.addEventListener('focus', function() {
                    if (this.value.trim().length >= 2 && searchSuggestions.innerHTML !== '') {
                        searchSuggestions.style.display = 'block';
                    }
                });
            }

            function fetchSuggestions(query) {
                fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        displaySuggestions(data);
                    })
                    .catch(error => {
                        console.error('Error fetching suggestions:', error);
                        searchSuggestions.style.display = 'none';
                    });
            }

            function displaySuggestions(data) {
                if (!data.products.length && !data.categories.length) {
                    searchSuggestions.innerHTML = `
                        <div class=\"search-no-results\">
                            ${isArabic ? 'لا توجد نتائج' : 'No results found'}
                        </div>
                    `;
                    searchSuggestions.style.display = 'block';
                    return;
                }

                let html = '';

                // Categories
                if (data.categories.length > 0) {
                    html += `<div class=\"search-suggestions-header\">${isArabic ? 'التصنيفات' : 'Categories'}</div>`;
                    data.categories.forEach(category => {
                        html += `
                            <a href=\"${category.url}\" class=\"search-suggestion-item\">
                                <svg class=\"suggestion-icon\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\">
                                    <path d=\"M4 4h7l2 2h6a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z\"></path>
                                </svg>
                                <div class=\"suggestion-content\">
                                    <div class=\"suggestion-name\">${category.name}</div>
                                    <div class=\"suggestion-meta\">${category.products_count} ${isArabic ? 'منتج' : 'products'}</div>
                                </div>
                            </a>
                        `;
                    });
                }

                // Products
                if (data.products.length > 0) {
                    html += `<div class=\"search-suggestions-header\">${isArabic ? 'المنتجات' : 'Products'}</div>`;
                    data.products.forEach(product => {
                        html += `
                            <a href=\"${product.url}\" class=\"search-suggestion-item\">
                                ${product.image ?
                                    `<img src=\"${product.image}\" alt=\"${product.name}\" class=\"suggestion-image\">` :
                                    `<svg class=\"suggestion-icon\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\">
                                        <rect x=\"3\" y=\"3\" width=\"18\" height=\"18\" rx=\"2\" ry=\"2\"></rect>
                                        <circle cx=\"8.5\" cy=\"8.5\" r=\"1.5\"></circle>
                                        <polyline points=\"21 15 16 10 5 21\"></polyline>
                                    </svg>`
                                }
                                <div class=\"suggestion-content\">
                                    <div class=\"suggestion-name\">${product.name}</div>
                                    ${product.price ? `<div class=\"suggestion-meta\">${product.price}</div>` : ''}
                                </div>
                            </a>
                        `;
                    });
                }

                searchSuggestions.innerHTML = html;
                searchSuggestions.style.display = 'block';
            }
        });
    </script>


    <!-- Note: English text hiding for mobile is handled via CSS in styles-mobile.css -->

    <!-- Currency Dropdown Lazy Loading -->
    <script>
        (function() {
            let currencyDropdownLoaded = false;
            let isLoading = false;
            const currencySelector = document.querySelector('.currency-selector');

            if (!currencySelector) return;

            // Load on hover (mouseenter)
            currencySelector.addEventListener('mouseenter', function(e) {
                if (!currencyDropdownLoaded && !isLoading) {
                    loadCurrencyDropdown();
                }
            });

            // Also load on click as backup
            currencySelector.addEventListener('click', function(e) {
                if (!currencyDropdownLoaded && !isLoading) {
                    loadCurrencyDropdown();
                }
            });

            function loadCurrencyDropdown() {
                console.log('💱 Loading currency dropdown via AJAX...');
                isLoading = true;

                const startTime = performance.now();

                fetch('/api/lazy-load/currency-dropdown', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const endTime = performance.now();
                    const loadTime = (endTime - startTime).toFixed(2);

                    console.log(`📊 Currency dropdown loaded in ${loadTime}ms`);

                    if (data.success && data.html) {
                        const dropdown = document.getElementById('currency-dropdown');
                        const skeleton = dropdown ? dropdown.querySelector('.currency-dropdown-skeleton') : null;

                        if (dropdown) {
                            // Remove skeleton
                            if (skeleton) {
                                skeleton.remove();
                            }

                            // Insert content
                            dropdown.innerHTML = data.html;

                            currencyDropdownLoaded = true;
                            console.log('✅ Currency dropdown content inserted successfully');

                            // Reinitialize currency option handlers
                            initCurrencyOptions();
                        } else {
                            console.error('❌ Currency dropdown element not found');
                        }
                    } else {
                        console.error('❌ Invalid response from server:', data);
                    }
                })
                .catch(error => {
                    console.error('❌ Error loading currency dropdown:', error);
                    isLoading = false;
                });
            }

            function initCurrencyOptions() {
                const currencyOptions = document.querySelectorAll('.currency-option');
                console.log(`🔧 Initializing ${currencyOptions.length} currency options`);

                currencyOptions.forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.preventDefault();
                        const currency = this.getAttribute('data-currency');
                        const flag = this.getAttribute('data-flag');

                        console.log(`💱 Currency selected: ${currency}`);

                        const selectedCurrency = document.getElementById('selected-currency');
                        const selectedFlag = document.getElementById('selected-currency-flag');

                        if (selectedCurrency) selectedCurrency.textContent = currency;
                        if (selectedFlag) selectedFlag.src = flag;

                        // Close dropdown
                        const dropdown = document.querySelector('.currency-dropdown');
                        if (dropdown) {
                            dropdown.classList.remove('active');
                        }
                    });
                });

                console.log('✅ Currency options initialized');
            }
        })();
    </script>

    @stack('scripts')
</body>

</html>
