/* ========================================
   Mobile-Specific JavaScript Functionality
   ======================================== */

console.log('🎬 بدء تحميل script-mobile.js');

document.addEventListener('DOMContentLoaded', function() {
    console.log('✨ DOM جاهز، بدء تهيئة القائمة الجانبية للهاتف...');

    // إعدادات القائمة المنسدلة
    const MOBILE_SUBMENU_MAX_HEIGHT = 400; // الحد الأقصى لارتفاع القائمة المنسدلة (بكسل)

    // Create mobile sidebar menu
    function createMobileSidebar() {
        console.log('🔧 استدعاء دالة createMobileSidebar');
        console.log('📐 عرض الشاشة:', window.innerWidth);
        console.log('🔍 هل توجد قائمة جانبية بالفعل؟', !!document.querySelector('.mobile-sidebar'));

        if (window.innerWidth <= 1024 && !document.querySelector('.mobile-sidebar')) {
            console.log('✅ الشروط متحققة، سيتم إنشاء القائمة الجانبية...');
            // Create overlay
            const overlay = document.createElement('div');
            overlay.className = 'mobile-menu-overlay';
            document.body.appendChild(overlay);

            // Create sidebar
            const sidebar = document.createElement('div');
            sidebar.className = 'mobile-sidebar';

            // Sidebar header with logo
            const header = document.createElement('div');
            header.className = 'mobile-sidebar-header';
            const logoImg = document.querySelector('.logo-image');
            const logoSrc = logoImg ? logoImg.src : 'assets/images/logo-_1_.svg';
            header.innerHTML = `
                <div class="mobile-sidebar-logo">
                    <img src="${logoSrc}" alt="Logo" style="height: 40px;">
                </div>
                <button class="mobile-sidebar-close">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            `;

            // Sidebar content
            const content = document.createElement('div');
            content.className = 'mobile-sidebar-content';

            // Add account section as a link
            const isArabic = document.documentElement.getAttribute('dir') === 'rtl';

            // التحقق من حالة تسجيل الدخول
            const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.content === 'true';
            const userName = document.querySelector('meta[name="user-name"]')?.content || '';

            const accountSection = document.createElement('a');
            accountSection.className = 'mobile-account-section';

            if (isAuthenticated) {
                // إذا كان مسجل دخول
                accountSection.href = '/profile';
                accountSection.classList.add('authenticated');
                accountSection.innerHTML = `
                    <div class="mobile-account-content">
                        <div class="mobile-account-header">
                            <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>${userName || (isArabic ? 'حسابي' : 'My Account')}</span>
                        </div>
                        <p class="mobile-account-subtitle">${isArabic ? 'مرحباً بك مجدداً' : 'Welcome back'}</p>
                    </div>
                    <svg class="mobile-account-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                `;
            } else {
                // إذا لم يكن مسجل دخول
                accountSection.href = '/login';
                accountSection.innerHTML = `
                    <div class="mobile-account-content">
                        <div class="mobile-account-header">
                            <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>${isArabic ? 'سجّل الدخول / اشترك' : 'Sign In / Register'}</span>
                        </div>
                        <p class="mobile-account-subtitle">${isArabic ? 'للإتمام عملية الشراء بشكل أسرع' : 'To complete your purchase faster'}</p>
                    </div>
                    <svg class="mobile-account-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                `;
            }
            content.appendChild(accountSection);

            // Add tabs for gender selection
            const tabsSection = document.createElement('div');
            tabsSection.className = 'mobile-tabs';
            // tabsSection.innerHTML = `
            //     <button class="mobile-tab active" data-tab="women">
            //         <span class="ar-text">النساء</span>
            //         <span class="en-text">Women</span>
            //     </button>
            //     <button class="mobile-tab active" data-tab="men">
            //         <span class="ar-text">الرجال</span>
            //         <span class="en-text">Men</span>
            //     </button>
            //     <button class="mobile-tab" data-tab="kids">
            //         <span class="ar-text">الأطفال</span>
            //         <span class="en-text">Kids</span>
            //     </button>
            // `;
            content.appendChild(tabsSection);

            // Get dropdown menus with their images
            const dropdownItems = document.querySelectorAll('.main-nav .nav-item.dropdown');

            dropdownItems.forEach(item => {
                const navLink = item.querySelector('.nav-link');
                const dropdownMenu = item.querySelector('.dropdown-menu');

                if (!navLink) return;

                // Get text based on current language
                const linkText = isArabic
                    ? (navLink.querySelector('.ar-text') ? navLink.querySelector('.ar-text').textContent : navLink.textContent)
                    : (navLink.querySelector('.en-text') ? navLink.querySelector('.en-text').textContent : navLink.textContent);

                // Get menu data from JSON
                const menuDataScript = dropdownMenu ? dropdownMenu.querySelector('.js-mega-menu-data') : null;
                let menuData = [];
                let menuId = null;
                let totalItems = 0;
                let loadedItems = 0;

                if (menuDataScript) {
                    try {
                        menuData = JSON.parse(menuDataScript.textContent);
                        menuId = menuDataScript.getAttribute('data-menu-id');
                        totalItems = parseInt(menuDataScript.getAttribute('data-total-items')) || 0;
                        loadedItems = parseInt(menuDataScript.getAttribute('data-loaded-items')) || 0;
                        console.log(`📋 Menu "${linkText}" - Total: ${totalItems}, Loaded: ${loadedItems}, Remaining: ${totalItems - loadedItems}, menu_id: ${menuId}`);

                        if (!menuId) {
                            console.error(`❌ تحذير: menu_id غير موجود للقائمة "${linkText}"`);
                        }
                    } catch (e) {
                        console.error('Error parsing menu data:', e);
                    }
                } else {
                    console.warn(`⚠️ No menu data found for "${linkText}"`);
                }

                // Get menu image
                const dropdownImage = dropdownMenu ? dropdownMenu.querySelector('.dropdown-image img') : null;

                // Create mobile menu item
                const mobileItem = document.createElement('div');
                mobileItem.className = 'mobile-nav-item expandable';

                console.log(`🔨 إنشاء عنصر قائمة: "${linkText}"`);

                // Create main link with image
                const mobileLink = document.createElement('div');
                mobileLink.className = 'mobile-nav-link-container';

                // Add image
                if (dropdownImage) {
                    const imageContainer = document.createElement('div');
                    imageContainer.className = 'mobile-nav-image';
                    imageContainer.innerHTML = `<img src="${dropdownImage.src}" alt="${linkText}">`;
                    mobileLink.appendChild(imageContainer);
                }

                // Add clickable text with arrow
                const textContainer = document.createElement('div');
                textContainer.className = 'mobile-nav-link';
                textContainer.innerHTML = `
                    <span>${linkText.trim()}</span>
                    <svg class="mobile-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                `;
                mobileLink.appendChild(textContainer);

                // Create submenu
                const submenu = document.createElement('div');
                submenu.className = 'mobile-submenu';
                submenu.style.maxHeight = '0px'; // تعيين قيمة ابتدائية

                console.log(`📦 إنشاء submenu لـ "${linkText}"`);

                if (menuData && menuData.length > 0) {
                    console.log(`📊 البيانات موجودة: ${menuData.length} أعمدة`);

                    // حساب عدد العناصر المحملة
                    let currentlyShown = loadedItems;
                    console.log(`📊 العناصر المحملة: ${loadedItems} من ${totalItems}`);

                    const ITEMS_PER_LOAD = 5;

                    // عرض العناصر المحملة من Blade
                    menuData.forEach(column => {
                        // Add column title
                        if (column.title_ar || column.title_en) {
                            const subTitle = document.createElement('div');
                            subTitle.className = 'mobile-submenu-title';
                            subTitle.textContent = isArabic ? (column.title_ar || column.title_en) : (column.title_en || column.title_ar);
                            submenu.appendChild(subTitle);
                        }

                        // Add items
                        if (column.items && column.items.length > 0) {
                            column.items.forEach(item => {
                                appendItemToSubmenu(submenu, item, isArabic);
                            });
                        }
                    });

                    console.log(`✅ تم إضافة ${currentlyShown} عنصر فعلياً`);

                    // Add "Load More" button if there are more items
                    if (totalItems > currentlyShown && menuId) {
                        const loadMoreBtn = document.createElement('button');
                        loadMoreBtn.className = 'mobile-submenu-load-more';
                        loadMoreBtn.setAttribute('data-loading', 'false');
                        loadMoreBtn.innerHTML = isArabic
                            ? `تحميل المزيد (${totalItems - currentlyShown})`
                            : `Load More (${totalItems - currentlyShown})`;

                        loadMoreBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();

                            // منع النقر المتكرر
                            if (loadMoreBtn.getAttribute('data-loading') === 'true') {
                                return;
                            }

                            loadMoreBtn.setAttribute('data-loading', 'true');
                            const originalText = loadMoreBtn.innerHTML;
                            loadMoreBtn.innerHTML = isArabic ? 'جاري التحميل...' : 'Loading...';
                            loadMoreBtn.disabled = true;

                            // استخدام AJAX لجلب المزيد من العناصر من قاعدة البيانات
                            console.log(`📡 AJAX Request: menu_id=${menuId}, offset=${currentlyShown}, limit=${ITEMS_PER_LOAD}`);

                            fetch(`/api/mobile-menu/load-more?menu_id=${menuId}&offset=${currentlyShown}&limit=${ITEMS_PER_LOAD}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success && data.items) {
                                        console.log('✅ تم جلب المزيد من قاعدة البيانات:', data);

                                        // إضافة العناصر الجديدة
                                        let lastColumnTitle = '';
                                        data.items.forEach(itemData => {
                                            // إضافة عنوان العمود إذا كان مختلف
                                            const columnTitle = isArabic ? itemData.column_title_ar : itemData.column_title_en;
                                            if (columnTitle !== lastColumnTitle) {
                                                lastColumnTitle = columnTitle;
                                                if (columnTitle) {
                                                    const titleDiv = document.createElement('div');
                                                    titleDiv.className = 'mobile-submenu-title';
                                                    titleDiv.textContent = columnTitle;
                                                    submenu.insertBefore(titleDiv, loadMoreBtn);
                                                }
                                            }

                                            // إضافة العنصر
                                            appendItemToSubmenu(submenu, itemData.item, isArabic, loadMoreBtn);
                                        });

                                        currentlyShown += data.loaded;

                                        // Update or remove button
                                        if (!data.hasMore) {
                                            loadMoreBtn.remove();
                                            console.log('🎉 تم تحميل جميع العناصر!');
                                        } else {
                                            loadMoreBtn.innerHTML = isArabic
                                                ? `تحميل المزيد (${data.remaining})`
                                                : `Load More (${data.remaining})`;
                                            loadMoreBtn.disabled = false;
                                            loadMoreBtn.setAttribute('data-loading', 'false');
                                        }

                                        // Update submenu height
                                        const parentItem = submenu.closest('.mobile-nav-item');
                                        if (parentItem && parentItem.classList.contains('open')) {
                                            submenu.style.maxHeight = MOBILE_SUBMENU_MAX_HEIGHT + 'px';
                                            submenu.style.setProperty('max-height', MOBILE_SUBMENU_MAX_HEIGHT + 'px', 'important');
                                        }
                                    } else {
                                        console.error('❌ فشل في جلب البيانات:', data);
                                        alert(isArabic ? 'حدث خطأ في تحميل البيانات' : 'Error loading data');
                                        loadMoreBtn.innerHTML = originalText;
                                        loadMoreBtn.disabled = false;
                                        loadMoreBtn.setAttribute('data-loading', 'false');
                                    }
                                })
                                .catch(error => {
                                    console.error('❌ خطأ في AJAX:', error);
                                    alert(isArabic ? 'حدث خطأ في تحميل المزيد' : 'Error loading more items');
                                    loadMoreBtn.innerHTML = originalText;
                                    loadMoreBtn.disabled = false;
                                    loadMoreBtn.setAttribute('data-loading', 'false');
                                });
                        });

                        submenu.appendChild(loadMoreBtn);
                    }
                }

                mobileItem.appendChild(mobileLink);
                mobileItem.appendChild(submenu);
                content.appendChild(mobileItem);

                console.log(`✅ تم إضافة العنصر "${linkText}" إلى القائمة`, {
                    hasSubmenu: !!submenu,
                    submenuChildrenCount: submenu.children.length,
                    submenuHTML: submenu.innerHTML.substring(0, 150)
                });
            });

            // Helper function to append column to submenu
            // Helper function to add a single item to submenu
            function appendItemToSubmenu(submenu, item, isArabic, beforeElement = null) {
                // Add main item
                const subLink = document.createElement('a');
                subLink.href = item.link || '#';
                subLink.className = 'mobile-submenu-link';
                subLink.textContent = isArabic ? (item.name_ar || item.name_en) : (item.name_en || item.name_ar);

                if (beforeElement) {
                    submenu.insertBefore(subLink, beforeElement);
                } else {
                    submenu.appendChild(subLink);
                }

                // Add children if exist
                if (item.children && item.children.length > 0) {
                    item.children.forEach(child => {
                        const childLink = document.createElement('a');
                        childLink.href = child.link || '#';
                        childLink.className = 'mobile-submenu-link mobile-submenu-child';
                        childLink.textContent = isArabic ? (child.name_ar || child.name_en) : (child.name_en || child.name_ar);

                        if (beforeElement) {
                            submenu.insertBefore(childLink, beforeElement);
                        } else {
                            submenu.appendChild(childLink);
                        }
                    });
                }
            }

            function appendColumnToSubmenu(submenu, column, isArabic, beforeElement = null) {
                // Add column title
                if (column.title_ar || column.title_en) {
                    const subTitle = document.createElement('div');
                    subTitle.className = 'mobile-submenu-title';
                    subTitle.textContent = isArabic ? (column.title_ar || column.title_en) : (column.title_en || column.title_ar);

                    if (beforeElement) {
                        submenu.insertBefore(subTitle, beforeElement);
                    } else {
                        submenu.appendChild(subTitle);
                    }
                }

                // Add items
                if (column.items && column.items.length > 0) {
                    column.items.forEach(item => {
                        appendItemToSubmenu(submenu, item, isArabic, beforeElement);
                    });
                }
            }

            // Add simple nav links with same structure as dropdown items
            const simpleLinks = document.querySelectorAll('.main-nav > .nav-link:not(.dropdown-trigger)');
            simpleLinks.forEach(link => {
                const mobileItem = document.createElement('div');
                mobileItem.className = 'mobile-nav-item';

                // Create main link container with image (same structure as dropdown items)
                const mobileLink = document.createElement('div');
                mobileLink.className = 'mobile-nav-link-container';

                // Get text based on current language
                const linkText = isArabic
                    ? (link.querySelector('.ar-text') ? link.querySelector('.ar-text').textContent : link.textContent)
                    : (link.querySelector('.en-text') ? link.querySelector('.en-text').textContent : link.textContent);
                const menuImage = link.getAttribute('data-menu-image');

                // Always add image container
                const imageContainer = document.createElement('div');
                imageContainer.className = 'mobile-nav-image';
                if (menuImage) {
                    imageContainer.innerHTML = `<img src="${menuImage}" alt="${linkText.trim()}" style="object-fit: cover;">`;
                } else {
                    // Use a gray placeholder background if no image
                    imageContainer.innerHTML = `<div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; font-weight: 600;">${linkText.trim().substring(0, 2)}</div>`;
                }
                mobileLink.appendChild(imageContainer);

                // Add the clickable text (as link without arrow)
                const textContainer = document.createElement('a');
                textContainer.href = link.href;
                textContainer.className = 'mobile-nav-link';
                textContainer.innerHTML = `<span>${linkText.trim()}</span>`;
                mobileLink.appendChild(textContainer);

                mobileItem.appendChild(mobileLink);
                content.appendChild(mobileItem);
            });

            // Add quick links section (Wishlist, Bag, Track Orders, Shopping Cart)
            const quickLinksSection = document.createElement('div');
            quickLinksSection.className = 'mobile-quick-links';
            quickLinksSection.style.cssText = 'margin: 15px 0; padding: 15px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee;';

            // Wishlist Link
            const wishlistLink = document.createElement('a');
            wishlistLink.href = '/wishlist';
            wishlistLink.className = 'mobile-quick-link';
            wishlistLink.style.cssText = 'display: flex; align-items: center; gap: 12px; padding: 12px 15px; text-decoration: none; color: #333; transition: background 0.2s;';
            wishlistLink.innerHTML = `
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
                <span style="font-size: 15px; font-weight: 500;">${isArabic ? 'المفضلة' : 'Wishlist'}</span>
            `;
            wishlistLink.addEventListener('mouseenter', function() { this.style.background = '#f5f5f5'; });
            wishlistLink.addEventListener('mouseleave', function() { this.style.background = 'transparent'; });
            quickLinksSection.appendChild(wishlistLink);

            // Shopping Bag Link
            const bagLink = document.createElement('a');
            bagLink.href = '#';
            bagLink.className = 'mobile-quick-link';
            bagLink.id = 'mobileBagLink';
            bagLink.style.cssText = 'display: flex; align-items: center; gap: 12px; padding: 12px 15px; text-decoration: none; color: #333; transition: background 0.2s;';
            bagLink.innerHTML = `
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <span style="font-size: 15px; font-weight: 500;">${isArabic ? 'الحقيبة' : 'Bag'}</span>
            `;
            bagLink.addEventListener('click', function(e) {
                e.preventDefault();
                // Trigger cart sidebar
                const cartToggle = document.getElementById('cartToggle');
                if (cartToggle) cartToggle.click();
            });
            bagLink.addEventListener('mouseenter', function() { this.style.background = '#f5f5f5'; });
            bagLink.addEventListener('mouseleave', function() { this.style.background = 'transparent'; });
            quickLinksSection.appendChild(bagLink);

            // Track Orders Link
            const trackOrdersLink = document.createElement('a');
            trackOrdersLink.href = '/orders';
            trackOrdersLink.className = 'mobile-quick-link';
            trackOrdersLink.style.cssText = 'display: flex; align-items: center; gap: 12px; padding: 12px 15px; text-decoration: none; color: #333; transition: background 0.2s;';
            trackOrdersLink.innerHTML = `
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                </svg>
                <span style="font-size: 15px; font-weight: 500;">${isArabic ? 'تتبع الطلبات' : 'Track Orders'}</span>
            `;
            trackOrdersLink.addEventListener('mouseenter', function() { this.style.background = '#f5f5f5'; });
            trackOrdersLink.addEventListener('mouseleave', function() { this.style.background = 'transparent'; });
            quickLinksSection.appendChild(trackOrdersLink);

            // Shopping Cart Link
            const cartLink = document.createElement('a');
            cartLink.href = '/cart';
            cartLink.className = 'mobile-quick-link';
            cartLink.style.cssText = 'display: flex; align-items: center; gap: 12px; padding: 12px 15px; text-decoration: none; color: #333; transition: background 0.2s;';
            cartLink.innerHTML = `
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span style="font-size: 15px; font-weight: 500;">${isArabic ? 'سلة التسوق' : 'Shopping Cart'}</span>
            `;
            cartLink.addEventListener('mouseenter', function() { this.style.background = '#f5f5f5'; });
            cartLink.addEventListener('mouseleave', function() { this.style.background = 'transparent'; });
            quickLinksSection.appendChild(cartLink);

            content.appendChild(quickLinksSection);

            // Add language and currency selectors
            const settingsSection = document.createElement('div');
            settingsSection.className = 'mobile-settings';

            // Language selector
            const langSelector = document.createElement('div');
            langSelector.className = 'mobile-setting-item';
            langSelector.innerHTML = `
                <div class="mobile-setting-label">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                    <span>${isArabic ? 'تغيير اللغة' : 'Change Language'}</span>
                </div>
                <button class="mobile-lang-toggle" onclick="toggleLanguage()">
                    <span>${isArabic ? 'English' : 'العربية'}</span>
                </button>
            `;
            settingsSection.appendChild(langSelector);

            // Currency selector
            const currencySelector = document.createElement('div');
            currencySelector.className = 'mobile-setting-item';
            const selectedCurrency = document.querySelector('#selected-currency').textContent;
            const selectedFlag = document.querySelector('#selected-currency-flag').src;
            currencySelector.innerHTML = `
                <div class="mobile-setting-label">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    <span>${isArabic ? 'العملة / الدولة' : 'Currency / Country'}</span>
                </div>
                <div class="mobile-currency-display">
                    <img src="${selectedFlag}" alt="${selectedCurrency}" class="mobile-flag-icon">
                    <span id="mobile-selected-currency">${selectedCurrency}</span>
                    <svg class="mobile-arrow-down" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                <div class="mobile-currency-dropdown">
                    <a href="#" class="mobile-currency-option" data-currency="UAE" data-flag="https://flagcdn.com/ae.svg">
                        <img src="https://flagcdn.com/ae.svg" alt="UAE" class="mobile-flag-icon">
                        <span>UAE</span>
                    </a>
                    <a href="#" class="mobile-currency-option" data-currency="Saudi Arabia" data-flag="https://flagcdn.com/sa.svg">
                        <img src="https://flagcdn.com/sa.svg" alt="Saudi Arabia" class="mobile-flag-icon">
                        <span>${isArabic ? 'المملكة العربية السعودية' : 'Saudi Arabia'}</span>
                    </a>
                    <a href="#" class="mobile-currency-option" data-currency="Oman" data-flag="https://flagcdn.com/om.svg">
                        <img src="https://flagcdn.com/om.svg" alt="Oman" class="mobile-flag-icon">
                        <span>${isArabic ? 'عُمان' : 'Oman'}</span>
                    </a>
                    <a href="#" class="mobile-currency-option" data-currency="Kuwait" data-flag="https://flagcdn.com/kw.svg">
                        <img src="https://flagcdn.com/kw.svg" alt="Kuwait" class="mobile-flag-icon">
                        <span>${isArabic ? 'الكويت' : 'Kuwait'}</span>
                    </a>
                    <a href="#" class="mobile-currency-option" data-currency="Bahrain" data-flag="https://flagcdn.com/bh.svg">
                        <img src="https://flagcdn.com/bh.svg" alt="Bahrain" class="mobile-flag-icon">
                        <span>${isArabic ? 'البحرين' : 'Bahrain'}</span>
                    </a>
                    <a href="#" class="mobile-currency-option" data-currency="Qatar" data-flag="https://flagcdn.com/qa.svg">
                        <img src="https://flagcdn.com/qa.svg" alt="Qatar" class="mobile-flag-icon">
                        <span>${isArabic ? 'قطر' : 'Qatar'}</span>
                    </a>
                </div>
            `;
            settingsSection.appendChild(currencySelector);

            content.appendChild(settingsSection);

            // Add logout button for authenticated users
            if (isAuthenticated) {
                const logoutSection = document.createElement('div');
                logoutSection.className = 'mobile-logout-section';
                logoutSection.style.cssText = 'margin-top: 20px; padding: 15px; border-top: 1px solid #eee;';

                const logoutButton = document.createElement('button');
                logoutButton.className = 'mobile-logout-btn';
                logoutButton.style.cssText = `
                    width: 100%;
                    padding: 12px 20px;
                    background: #dc2626;
                    color: white;
                    border: none;
                    border-radius: 8px;
                    font-size: 16px;
                    font-weight: 600;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    transition: background 0.3s;
                `;
                logoutButton.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>${isArabic ? 'تسجيل الخروج' : 'Logout'}</span>
                `;

                logoutButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Check if handleLogout function exists
                    if (typeof handleLogout === 'function') {
                        handleLogout();
                    } else {
                        // Fallback: direct form submission
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/logout';

                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (csrfToken) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = '_token';
                            input.value = csrfToken.content;
                            form.appendChild(input);
                        }

                        document.body.appendChild(form);
                        form.submit();
                    }
                });

                // Hover effect
                logoutButton.addEventListener('mouseenter', function() {
                    this.style.background = '#b91c1c';
                });
                logoutButton.addEventListener('mouseleave', function() {
                    this.style.background = '#dc2626';
                });

                logoutSection.appendChild(logoutButton);
                content.appendChild(logoutSection);
            }

            sidebar.appendChild(header);
            sidebar.appendChild(content);
            document.body.appendChild(sidebar);

            // Toggle sidebar
            const menuBtn = document.querySelector('.mobile-menu-btn');
            if (menuBtn) {
                menuBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    console.log('🚀 فتح القائمة الجانبية...');
                    console.log('📱 العناصر القابلة للتوسع:', sidebar.querySelectorAll('.mobile-nav-item.expandable').length);

                    sidebar.classList.add('active');
                    overlay.classList.add('active');

                    // Prevent scrolling on body when sidebar is open
                    document.body.classList.add('sidebar-open');
                    document.documentElement.classList.add('sidebar-open');

                    // Additional inline styles for maximum compatibility
                    document.body.style.overflow = 'hidden';
                    document.body.style.position = 'fixed';
                    document.body.style.width = '100%';

                    console.log('✅ تم فتح القائمة الجانبية بنجاح');
                });
            }

            // Close sidebar
            function closeSidebar() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');

                // Restore scrolling
                document.body.classList.remove('sidebar-open');
                document.documentElement.classList.remove('sidebar-open');

                // Remove inline styles
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.width = '';
            }

            header.querySelector('.mobile-sidebar-close').addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);

            // Handle expandable menu items
            const expandableItems = sidebar.querySelectorAll('.mobile-nav-item.expandable');

            console.log('🔍 عدد العناصر القابلة للتوسع:', expandableItems.length);
            console.log('📝 العناصر المكتشفة:', expandableItems);

            expandableItems.forEach((item, index) => {
                const textContainer = item.querySelector('.mobile-nav-link');
                const submenu = item.querySelector('.mobile-submenu');

                console.log(`📋 العنصر ${index + 1}:`, {
                    hasTextContainer: !!textContainer,
                    hasSubmenu: !!submenu,
                    submenuContent: submenu ? submenu.innerHTML.substring(0, 100) : 'لا يوجد',
                    element: item
                });

                if (textContainer && submenu) {
                    console.log(`✅ إضافة event listener للعنصر ${index + 1}`);

                    // إضافة event listener على الـ text container مباشرة
                    textContainer.addEventListener('click', function(e) {
                        console.log('🖱️ تم النقر على القائمة:', {
                            index: index + 1,
                            isOpen: item.classList.contains('open'),
                            submenuHeight: submenu.scrollHeight,
                            currentMaxHeight: submenu.style.maxHeight,
                            classList: Array.from(item.classList)
                        });

                        e.preventDefault();
                        e.stopPropagation();

                        // Toggle current item
                        const isOpen = item.classList.contains('open');

                        console.log('📊 حالة القائمة قبل التغيير:', isOpen ? 'مفتوحة' : 'مغلقة');

                        // Close all other items
                        expandableItems.forEach(otherItem => {
                            if (otherItem !== item) {
                                otherItem.classList.remove('open');
                                const otherSubmenu = otherItem.querySelector('.mobile-submenu');
                                if (otherSubmenu) {
                                    otherSubmenu.style.maxHeight = '0px';
                                }
                            }
                        });

                        // Toggle current item
                        if (!isOpen) {
                            console.log('🔓 محاولة فتح القائمة...');
                            console.log('📏 scrollHeight:', submenu.scrollHeight);

                            item.classList.add('open');

                            // استخدام ارتفاع ثابت محدود مع scroll داخلي
                            setTimeout(() => {
                                const actualHeight = submenu.scrollHeight;

                                console.log('📐 القيم المحسوبة:', {
                                    actualHeight,
                                    maxAllowedHeight: MOBILE_SUBMENU_MAX_HEIGHT,
                                    willUseScroll: actualHeight > MOBILE_SUBMENU_MAX_HEIGHT
                                });

                                // استخدام الحد الأقصى المحدد فقط، والباقي scroll
                                submenu.style.maxHeight = MOBILE_SUBMENU_MAX_HEIGHT + 'px';
                                submenu.style.setProperty('max-height', MOBILE_SUBMENU_MAX_HEIGHT + 'px', 'important');

                                console.log('✨ تم تعيين maxHeight إلى:', submenu.style.maxHeight);
                                console.log('🎯 الأنماط الحالية:', {
                                    maxHeight: window.getComputedStyle(submenu).maxHeight,
                                    overflow: window.getComputedStyle(submenu).overflow,
                                    display: window.getComputedStyle(submenu).display
                                });
                            }, 10);
                        } else {
                            console.log('🔒 إغلاق القائمة...');
                            item.classList.remove('open');
                            submenu.style.maxHeight = '0px';
                            submenu.style.setProperty('max-height', '0px', 'important');
                        }

                        console.log('📊 حالة القائمة بعد التغيير:', item.classList.contains('open') ? 'مفتوحة' : 'مغلقة');
                    });
                } else {
                    console.warn(`⚠️ العنصر ${index + 1} لا يحتوي على textContainer أو submenu`, {
                        textContainer,
                        submenu,
                        item
                    });
                }
            });

            // Handle currency selector in mobile
            const mobileCurrencyDisplay = sidebar.querySelector('.mobile-currency-display');
            const mobileCurrencyDropdown = sidebar.querySelector('.mobile-currency-dropdown');

            if (mobileCurrencyDisplay && mobileCurrencyDropdown) {
                mobileCurrencyDisplay.addEventListener('click', function(e) {
                    e.preventDefault();
                    mobileCurrencyDropdown.classList.toggle('active');
                });

                // Handle currency option clicks
                const currencyOptions = mobileCurrencyDropdown.querySelectorAll('.mobile-currency-option');
                currencyOptions.forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.preventDefault();
                        const currency = this.getAttribute('data-currency');
                        const flag = this.getAttribute('data-flag');

                        // Update mobile display
                        mobileCurrencyDisplay.querySelector('img').src = flag;
                        mobileCurrencyDisplay.querySelector('#mobile-selected-currency').textContent = currency;

                        // Update desktop header
                        const desktopFlag = document.querySelector('#selected-currency-flag');
                        const desktopCurrency = document.querySelector('#selected-currency');
                        if (desktopFlag) desktopFlag.src = flag;
                        if (desktopCurrency) desktopCurrency.textContent = currency;

                        // Close dropdown
                        mobileCurrencyDropdown.classList.remove('active');
                    });
                });
            }
        }
    }

    // Detect if user is on mobile device
    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    // Mobile-specific touch handling
    if (isMobileDevice()) {
        console.log('Mobile device detected');

        // Improve touch responsiveness
        document.body.classList.add('touch-device');

        // Disable hover effects on mobile
        const style = document.createElement('style');
        style.innerHTML = `
            @media (hover: none) {
                .product-card:hover .product-image-secondary {
                    opacity: 0 !important;
                }
                .product-card:hover .product-image-primary {
                    opacity: 1 !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Mobile menu toggle
    function initMobileMenu() {
        const navItems = document.querySelectorAll('.nav-item.dropdown');

        navItems.forEach(item => {
            const trigger = item.querySelector('.dropdown-trigger');
            const menu = item.querySelector('.dropdown-menu');

            if (trigger && menu && window.innerWidth <= 1024) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Toggle current menu
                    const isOpen = menu.classList.contains('mobile-open');

                    // Close all menus
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        m.classList.remove('mobile-open');
                        m.style.display = 'none';
                    });

                    // Open clicked menu if it was closed
                    if (!isOpen) {
                        menu.classList.add('mobile-open');
                        menu.style.display = 'block';
                    }
                });
            }
        });

        // Close menus when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.nav-item.dropdown') && window.innerWidth <= 1024) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('mobile-open');
                    menu.style.display = 'none';
                });
            }
        });
    }

    // Optimize images for mobile
    function optimizeImagesForMobile() {
        if (window.innerWidth < 768) {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                // Add loading="lazy" if not present
                if (!img.hasAttribute('loading')) {
                    img.setAttribute('loading', 'lazy');
                }
            });
        }
    }

    // Mobile-specific viewport height fix
    function setMobileVH() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--mobile-vh', `${vh}px`);
    }

    // Prevent zoom on double tap for mobile
    function preventDoubleTapZoom() {
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(e) {
            const now = Date.now();
            if (now - lastTouchEnd <= 300) {
                e.preventDefault();
            }
            lastTouchEnd = now;
        }, { passive: false });
    }

    // Mobile search focus handling
    function handleMobileSearch() {
        const searchInput = document.querySelector('.search-box input');
        const searchBtn = document.querySelector('.header-search-btn');

        // Create mobile search overlay if it doesn't exist
        if (window.innerWidth <= 1024 && searchBtn && !document.querySelector('.mobile-search-overlay')) {
            createMobileSearchOverlay();
        }

        if (searchInput && window.innerWidth < 768) {
            searchInput.addEventListener('focus', function() {
                // Scroll to top on mobile when search is focused
                window.scrollTo({ top: 0, behavior: 'smooth' });

                // Expand search box on mobile
                this.parentElement.style.position = 'fixed';
                this.parentElement.style.top = '0';
                this.parentElement.style.left = '0';
                this.parentElement.style.right = '0';
                this.parentElement.style.zIndex = '9999';
                this.parentElement.style.padding = '15px';
                this.parentElement.style.background = '#ffffff';
            });

            searchInput.addEventListener('blur', function() {
                setTimeout(() => {
                    this.parentElement.style.position = '';
                    this.parentElement.style.top = '';
                    this.parentElement.style.left = '';
                    this.parentElement.style.right = '';
                    this.parentElement.style.zIndex = '';
                    this.parentElement.style.padding = '';
                    this.parentElement.style.background = '';
                }, 200);
            });
        }
    }

    // Create mobile search overlay
    function createMobileSearchOverlay() {
        const isArabic = document.documentElement.getAttribute('dir') === 'rtl';

        const overlay = document.createElement('div');
        overlay.className = 'mobile-search-overlay';
        overlay.innerHTML = `
            <div class="mobile-search-container">
                <div class="mobile-search-header">
                    <button class="mobile-search-close">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                    <form action="/search" method="GET" class="mobile-search-form">
                        <input type="text" name="q" class="mobile-search-input" placeholder="${isArabic ? 'ابحث عن منتج او تصنيف...' : 'Search for product or category...'}" autocomplete="off">
                    </form>
                </div>
                <div class="mobile-search-suggestions"></div>
            </div>
        `;

        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .mobile-search-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: #fff;
                z-index: 99999;
                display: none;
                flex-direction: column;
            }
            .mobile-search-overlay.active {
                display: flex;
            }
            .mobile-search-container {
                display: flex;
                flex-direction: column;
                height: 100%;
            }
            .mobile-search-header {
                display: flex;
                align-items: center;
                padding: 15px;
                gap: 10px;
                border-bottom: 1px solid #e5e5e5;
                background: #fff;
            }
            .mobile-search-close {
                background: none;
                border: none;
                padding: 8px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .mobile-search-close svg {
                stroke: #333;
            }
            .mobile-search-form {
                flex: 1;
                display: flex;
                align-items: center;
                background: #f5f5f5;
                border-radius: 8px;
                padding: 10px 15px;
                gap: 10px;
            }
            .mobile-search-form .search-icon {
                width: 20px;
                height: 20px;
                stroke: #666;
                flex-shrink: 0;
            }
            .mobile-search-input {
                flex: 1;
                border: none;
                background: none;
                font-size: 16px;
                outline: none;
                color: #333;
            }
            .mobile-search-input::placeholder {
                color: #999;
            }
            .mobile-search-suggestions {
                flex: 1;
                overflow-y: auto;
                padding: 15px;
            }
            .mobile-search-suggestion-item {
                display: flex;
                align-items: center;
                padding: 12px;
                gap: 12px;
                border-bottom: 1px solid #f0f0f0;
                text-decoration: none;
                color: #333;
            }
            .mobile-search-suggestion-item:hover {
                background: #f9f9f9;
            }
            .mobile-search-suggestion-img {
                width: 50px;
                height: 50px;
                object-fit: cover;
                border-radius: 6px;
            }
            .mobile-search-suggestion-info {
                flex: 1;
            }
            .mobile-search-suggestion-name {
                font-weight: 500;
                margin-bottom: 4px;
            }
            .mobile-search-suggestion-price {
                font-size: 14px;
                color: #666;
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(overlay);

        // Event handlers
        const searchBtn = document.querySelector('.header-search-btn');
        const closeBtn = overlay.querySelector('.mobile-search-close');
        const searchInput = overlay.querySelector('.mobile-search-input');
        const suggestionsContainer = overlay.querySelector('.mobile-search-suggestions');
        const searchForm = overlay.querySelector('.mobile-search-form');

        // Open search overlay
        if (searchBtn) {
            searchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                setTimeout(() => searchInput.focus(), 100);
            });
        }

        // Close search overlay
        closeBtn.addEventListener('click', function() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            searchInput.value = '';
            suggestionsContainer.innerHTML = '';
        });

        // Handle search input with live suggestions
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                suggestionsContainer.innerHTML = '';
                return;
            }

            // Get current locale from HTML dir attribute
            const currentLocale = document.documentElement.getAttribute('dir') === 'rtl' ? 'ar' : 'en';

            searchTimeout = setTimeout(() => {
                fetch('/api/search-suggestions?q=' + encodeURIComponent(query) + '&locale=' + currentLocale, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.suggestions && data.suggestions.length > 0) {
                        suggestionsContainer.innerHTML = data.suggestions.map(item => `
                            <a href="${item.url}" class="mobile-search-suggestion-item">
                                <img src="${item.image}" alt="${item.name}" class="mobile-search-suggestion-img">
                                <div class="mobile-search-suggestion-info">
                                    <div class="mobile-search-suggestion-name">${item.name}</div>
                                    <div class="mobile-search-suggestion-price">${item.price}</div>
                                </div>
                            </a>
                        `).join('');
                    } else {
                        const isArabic = document.documentElement.getAttribute('dir') === 'rtl';
                        suggestionsContainer.innerHTML = `<p style="text-align: center; color: #999; padding: 20px;">${isArabic ? 'لا توجد نتائج' : 'No results found'}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
            }, 300);
        });

        // Handle form submit
        searchForm.addEventListener('submit', function(e) {
            const query = searchInput.value.trim();
            if (query.length < 1) {
                e.preventDefault();
            }
        });

        console.log('✅ Mobile search overlay created');
    }

    // Mobile product card touch interactions
    function initMobileProductInteractions() {
        const productCards = document.querySelectorAll('.product-card');

        productCards.forEach(card => {
            let touchStartTime = 0;
            let touchDuration = 0;
            let isScrolling = false;
            let startX = 0;
            let startY = 0;

            card.addEventListener('touchstart', function(e) {
                touchStartTime = Date.now();
                isScrolling = false;
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;

                // Trigger preload of overlay content
                const productId = this.dataset.productId;
                if (productId && window.homeProductOverlayLoader) {
                    window.homeProductOverlayLoader.handleCardHover(productId, this);
                }
            }, { passive: true });

            card.addEventListener('touchmove', function(e) {
                // Detect if user is scrolling
                const diffX = Math.abs(e.touches[0].clientX - startX);
                const diffY = Math.abs(e.touches[0].clientY - startY);
                if (diffX > 10 || diffY > 10) {
                    isScrolling = true;
                }
            }, { passive: true });

            card.addEventListener('touchend', function(e) {
                touchDuration = Date.now() - touchStartTime;

                // Only if not scrolling and touch duration is short (< 300ms), it's a tap
                if (!isScrolling && touchDuration < 300) {
                    // Show overlay info section on mobile tap
                    const overlayInfoSection = this.querySelector('.overlay-info-section');
                    const sizeSelector = this.querySelector('.size-selector');

                    if (overlayInfoSection) {
                        overlayInfoSection.style.opacity = '1';
                        overlayInfoSection.style.visibility = 'visible';
                        overlayInfoSection.style.transform = 'translateY(0)';

                        // Hide after 4 seconds
                        setTimeout(() => {
                            overlayInfoSection.style.opacity = '';
                            overlayInfoSection.style.visibility = '';
                            overlayInfoSection.style.transform = '';
                        }, 4000);
                    }

                    if (sizeSelector) {
                        sizeSelector.style.opacity = '1';
                        sizeSelector.style.visibility = 'visible';
                        sizeSelector.style.transform = 'translateY(0)';

                        // Hide after 4 seconds
                        setTimeout(() => {
                            sizeSelector.style.opacity = '0';
                            sizeSelector.style.visibility = 'hidden';
                            sizeSelector.style.transform = 'translateY(-10px)';
                        }, 4000);
                    }
                }
            }, { passive: true });

            // Prevent image context menu (long press save)
            card.querySelectorAll('img').forEach(img => {
                img.addEventListener('contextmenu', (e) => e.preventDefault());
            });
        });
    }

    // Mobile-specific size selector behavior
    function initMobileSizeSelector() {
        const sizeSelectors = document.querySelectorAll('.size-selector');

        sizeSelectors.forEach(selector => {
            const wrapper = selector.querySelector('.size-options-wrapper');
            if (wrapper && window.innerWidth < 768) {
                // Make size options larger on mobile for easier tapping
                const sizeOptions = wrapper.querySelectorAll('.size-option');
                sizeOptions.forEach(option => {
                    option.style.fontSize = '16px';
                    option.style.padding = '8px 12px';
                    option.style.minWidth = '44px'; // Apple's recommended minimum tap target
                    option.style.minHeight = '44px';
                });
            }
        });
    }

    // Initialize mobile functionality based on screen size
    function initMobileFeatures() {
        console.log('🎯 استدعاء initMobileFeatures...');
        console.log('📐 عرض الشاشة الحالي:', window.innerWidth);

        if (window.innerWidth <= 1024) {
            console.log('✅ الشاشة صغيرة، سيتم تهيئة الميزات...');
            createMobileSidebar();
            initMobileMenu();
            optimizeImagesForMobile();
            preventDoubleTapZoom();
            handleMobileSearch();
            initMobileProductInteractions();
            initMobileSizeSelector();
            console.log('🎉 تم الانتهاء من تهيئة جميع الميزات!');
        } else {
            console.log('ℹ️ الشاشة كبيرة، لن يتم تهيئة ميزات الهاتف');
        }
    }

    // Handle orientation change
    window.addEventListener('orientationchange', function() {
        setTimeout(() => {
            setMobileVH();
            initMobileFeatures();
        }, 200);
    });

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            setMobileVH();
            initMobileFeatures();
        }, 250);
    });

    // Initial setup
    console.log('🚀 بدء الإعداد الأولي...');
    setMobileVH();
    initMobileFeatures();
    console.log('✅ انتهى الإعداد الأولي!');

    // Initialize footer accordion on mobile and tablet
    function initFooterAccordion() {
        if (window.innerWidth <= 1024) {
            const footerColumns = document.querySelectorAll('.footer-column');

            // Remove all existing event listeners by cloning
            footerColumns.forEach(column => {
                const title = column.querySelector('.footer-title');

                if (title && !title.hasAttribute('data-accordion-init')) {
                    title.setAttribute('data-accordion-init', 'true');

                    title.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const parentColumn = this.closest('.footer-column');
                        const isActive = parentColumn.classList.contains('active');

                        console.log('Footer accordion clicked:', isActive);

                        // Close all other columns
                        footerColumns.forEach(col => {
                            col.classList.remove('active');
                        });

                        // Toggle current column
                        if (!isActive) {
                            parentColumn.classList.add('active');
                        }
                    });
                }
            });

            console.log('Footer accordion initialized for', footerColumns.length, 'columns');
        }
    }

    // Call footer accordion init after DOM is ready
    setTimeout(() => {
        initFooterAccordion();
    }, 100);

    // Re-init on resize
    let resizeAccordionTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeAccordionTimer);
        resizeAccordionTimer = setTimeout(() => {
            // Remove all data attributes first
            document.querySelectorAll('.footer-title[data-accordion-init]').forEach(el => {
                el.removeAttribute('data-accordion-init');
            });
            initFooterAccordion();
        }, 250);
    });

    // Mobile performance monitoring
    if (isMobileDevice()) {
        console.log('Mobile optimizations loaded');

        // Report slow interactions on mobile
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                for (const entry of list.getEntries()) {
                    if (entry.duration > 100) {
                        console.warn('Slow interaction detected:', entry);
                    }
                }
            });

            try {
                observer.observe({ entryTypes: ['measure'] });
            } catch (e) {
                console.log('Performance monitoring not available');
            }
        }
    }
});
