/**
 * Capacitor App JavaScript
 * هذا الملف يعمل فقط عند تشغيل التطبيق من داخل Capacitor
 */

(function() {
    'use strict';

    // التحقق من أن التطبيق يعمل من داخل Capacitor
    if (!document.body.classList.contains('capacitor-app')) {
        return;
    }

    console.log('🚀 Capacitor App Mode Activated');

    // الجمل المتحركة للبحث
    const searchPlaceholders = {
        ar: [
            'ابحث عن كندورة فاخرة...',
            'اكتشف أحدث التصاميم...',
            'ابحث عن هديتك المثالية...',
            'تصفح مجموعاتنا الحصرية...',
            'ابحث عن منتج أو تصنيف...'
        ],
        en: [
            'Search for luxury kandora...',
            'Discover latest designs...',
            'Find your perfect gift...',
            'Browse exclusive collections...',
            'Search for product or category...'
        ]
    };

    let currentPlaceholderIndex = 0;
    let placeholderInterval = null;

    /**
     * إنشاء Header الجديد لـ Capacitor
     */
    function createCapacitorHeader() {
        const isArabic = document.documentElement.getAttribute('dir') === 'rtl';
        const wishlistCount = document.getElementById('wishlistBadge')?.textContent || '0';

        const header = document.createElement('header');
        header.className = 'capacitor-header';
        header.innerHTML = `
            <div class="capacitor-header-inner">
                <div class="capacitor-search-box" id="capacitorSearchTrigger">
                    <!-- أيقونة البحث -->
                    <div class="capacitor-search-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </div>

                    <!-- نص البحث المتحرك -->
                    <span class="capacitor-search-text" id="capacitorSearchPlaceholder">
                        ${isArabic ? searchPlaceholders.ar[0] : searchPlaceholders.en[0]}
                    </span>

                    <!-- الفاصل العمودي -->
                    <div class="capacitor-search-divider"></div>

                    <!-- أيقونة المفضلة -->
                    <a href="/wishlist" class="capacitor-search-wishlist" aria-label="${isArabic ? 'قائمة الأمنيات' : 'Wishlist'}" onclick="event.stopPropagation();">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span class="wishlist-badge" id="capacitorWishlistBadge" data-count="${wishlistCount}">${wishlistCount}</span>
                    </a>
                </div>
            </div>
        `;

        // إضافة Header في بداية body
        document.body.insertBefore(header, document.body.firstChild);

        // تفعيل البحث
        initSearchTrigger();

        // بدء تحريك النص
        startPlaceholderAnimation();
    }

    /**
     * إنشاء شريط التنقل السفلي
     */
    function createBottomNav() {
        const isArabic = document.documentElement.getAttribute('dir') === 'rtl';
        const cartCount = document.getElementById('cartBadge')?.textContent || '0';
        const currentPath = window.location.pathname;

        // التحقق من حالة تسجيل الدخول
        const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.content === 'true';

        const bottomNav = document.createElement('nav');
        bottomNav.className = 'capacitor-bottom-nav';

        let ordersButton = '';
        if (isAuthenticated) {
            ordersButton = `
                <!-- الطلبات - يظهر فقط بعد تسجيل الدخول -->
                <a href="/orders" class="capacitor-nav-item ${currentPath === '/orders' ? 'active' : ''}" data-nav="orders">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        <path d="M9 14l2 2 4-4"></path>
                    </svg>
                    <span>${isArabic ? 'طلباتي' : 'Orders'}</span>
                </a>
            `;
        }

        bottomNav.innerHTML = `
            <div class="capacitor-bottom-nav-inner">
                <!-- الصفحة الرئيسية -->
                <a href="/" class="capacitor-nav-item ${currentPath === '/' || currentPath === '/home' ? 'active' : ''}" data-nav="home">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>${isArabic ? 'الرئيسية' : 'Home'}</span>
                </a>

                <!-- القائمة - أيقونة محسنة -->
                <button class="capacitor-nav-item" data-nav="menu" id="capacitorMenuBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="4" width="18" height="4" rx="1"></rect>
                        <rect x="3" y="10" width="18" height="4" rx="1"></rect>
                        <rect x="3" y="16" width="18" height="4" rx="1"></rect>
                    </svg>
                    <span>${isArabic ? 'القائمة' : 'Menu'}</span>
                </button>

                <!-- الملف الشخصي -->
                <a href="/profile" class="capacitor-nav-item ${currentPath === '/profile' ? 'active' : ''}" data-nav="profile">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>${isArabic ? 'حسابي' : 'Profile'}</span>
                </a>

                <!-- الحقيبة -->
                <button class="capacitor-nav-item" data-nav="cart" id="capacitorCartBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span class="nav-badge" id="capacitorCartBadge" data-count="${cartCount}">${cartCount}</span>
                    <span>${isArabic ? 'الحقيبة' : 'Bag'}</span>
                </button>

                ${ordersButton}
            </div>
        `;

        document.body.appendChild(bottomNav);

        // تفعيل الأزرار
        initBottomNavEvents();
    }

    /**
     * تفعيل زر البحث
     */
    function initSearchTrigger() {
        const searchTrigger = document.getElementById('capacitorSearchTrigger');
        if (searchTrigger) {
            searchTrigger.addEventListener('click', function() {
                // فتح overlay البحث الموجود
                const searchOverlay = document.querySelector('.mobile-search-overlay');
                if (searchOverlay) {
                    searchOverlay.classList.add('active');
                    const searchInput = searchOverlay.querySelector('.mobile-search-input');
                    if (searchInput) {
                        setTimeout(() => searchInput.focus(), 100);
                    }
                } else {
                    // إذا لم يوجد overlay، نفتح صفحة البحث
                    const headerSearchBtn = document.querySelector('.header-search-btn');
                    if (headerSearchBtn) {
                        headerSearchBtn.click();
                    }
                }
            });
        }
    }

    /**
     * تفعيل أحداث شريط التنقل السفلي
     */
    function initBottomNavEvents() {
        // زر القائمة
        const menuBtn = document.getElementById('capacitorMenuBtn');
        if (menuBtn) {
            menuBtn.addEventListener('click', function() {
                const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
                if (mobileMenuBtn) {
                    mobileMenuBtn.click();
                }
            });
        }

        // زر الحقيبة
        const cartBtn = document.getElementById('capacitorCartBtn');
        if (cartBtn) {
            cartBtn.addEventListener('click', function() {
                const cartToggle = document.getElementById('cartToggle');
                if (cartToggle) {
                    cartToggle.click();
                } else {
                    // فتح sidebar مباشرة
                    const cartSidebar = document.getElementById('cartSidebar');
                    if (cartSidebar) {
                        cartSidebar.classList.add('active');
                        document.body.classList.add('sidebar-open');
                    }
                }
            });
        }
    }

    /**
     * تحريك نص البحث
     */
    function startPlaceholderAnimation() {
        const placeholder = document.getElementById('capacitorSearchPlaceholder');
        if (!placeholder) return;

        const isArabic = document.documentElement.getAttribute('dir') === 'rtl';
        const placeholders = isArabic ? searchPlaceholders.ar : searchPlaceholders.en;

        placeholderInterval = setInterval(() => {
            currentPlaceholderIndex = (currentPlaceholderIndex + 1) % placeholders.length;

            // تأثير التلاشي
            placeholder.style.opacity = '0';
            placeholder.style.transform = 'translateY(-5px)';

            setTimeout(() => {
                placeholder.textContent = placeholders[currentPlaceholderIndex];
                placeholder.style.opacity = '1';
                placeholder.style.transform = 'translateY(0)';
            }, 200);
        }, 3000);
    }

    /**
     * مزامنة badges
     */
    function syncBadges() {
        // مزامنة badge الحقيبة
        const originalCartBadge = document.getElementById('cartBadge');
        const capacitorCartBadge = document.getElementById('capacitorCartBadge');

        if (originalCartBadge && capacitorCartBadge) {
            const observer = new MutationObserver(() => {
                const count = originalCartBadge.textContent;
                capacitorCartBadge.textContent = count;
                capacitorCartBadge.setAttribute('data-count', count);
            });

            observer.observe(originalCartBadge, { childList: true, characterData: true, subtree: true });
        }

        // مزامنة badge المفضلة
        const originalWishlistBadge = document.getElementById('wishlistBadge');
        const capacitorWishlistBadge = document.getElementById('capacitorWishlistBadge');

        if (originalWishlistBadge && capacitorWishlistBadge) {
            const observer = new MutationObserver(() => {
                const count = originalWishlistBadge.textContent;
                capacitorWishlistBadge.textContent = count;
                capacitorWishlistBadge.setAttribute('data-count', count);
            });

            observer.observe(originalWishlistBadge, { childList: true, characterData: true, subtree: true });
        }
    }

    /**
     * إضافة CSS للتأثيرات الانتقالية
     */
    function addTransitionStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .capacitor-search-placeholder {
                transition: opacity 0.2s ease, transform 0.2s ease;
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * تعديل أزرار حقيبة التسوق
     */
    function modifyCartButtons() {
        const cartFooter = document.getElementById('cartFooter');
        if (!cartFooter) return;

        const checkoutBtn = cartFooter.querySelector('.cart-checkout-btn');
        const viewBtn = cartFooter.querySelector('.cart-view-btn');

        if (checkoutBtn && viewBtn) {
            // إنشاء wrapper للأزرار
            const wrapper = document.createElement('div');
            wrapper.className = 'cart-buttons-wrapper';
            wrapper.style.cssText = 'display: flex !important; gap: 8px !important; width: 100% !important;';

            // نقل الأزرار للـ wrapper
            wrapper.appendChild(checkoutBtn.cloneNode(true));
            wrapper.appendChild(viewBtn.cloneNode(true));

            // حذف الأزرار القديمة
            checkoutBtn.remove();
            viewBtn.remove();

            // إضافة الـ wrapper
            cartFooter.appendChild(wrapper);

            console.log('✅ Cart buttons modified for Capacitor');
        }
    }

    /**
     * تهيئة التطبيق
     */
    function init() {
        // إضافة الـ CSS للتأثيرات
        addTransitionStyles();

        // إنشاء العناصر
        createCapacitorHeader();
        createBottomNav();

        // مزامنة الـ badges
        syncBadges();

        // تعديل أزرار الحقيبة
        modifyCartButtons();

        // تسجيل نجاح المصافحة
        console.log('✅ Capacitor Handshake Verified: All components initialized');
    }

    // تشغيل عند تحميل الصفحة
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
