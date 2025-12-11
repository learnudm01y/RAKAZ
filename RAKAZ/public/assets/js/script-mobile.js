/* ========================================
   Mobile-Specific JavaScript Functionality
   ======================================== */

document.addEventListener('DOMContentLoaded', function() {

    // Create mobile sidebar menu
    function createMobileSidebar() {
        if (window.innerWidth <= 1024 && !document.querySelector('.mobile-sidebar')) {
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
            const accountSection = document.createElement('a');
            accountSection.className = 'mobile-account-section';
            accountSection.href = 'login.html';
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
                const dropdownImage = dropdownMenu ? dropdownMenu.querySelector('.dropdown-image img') : null;

                if (navLink) {
                    const mobileItem = document.createElement('div');
                    mobileItem.className = 'mobile-nav-item expandable';

                    // Get text based on current language
                    const isArabic = document.documentElement.getAttribute('dir') === 'rtl';
                    const linkText = isArabic
                        ? (navLink.querySelector('.ar-text') ? navLink.querySelector('.ar-text').textContent : navLink.textContent)
                        : (navLink.querySelector('.en-text') ? navLink.querySelector('.en-text').textContent : navLink.textContent);

                    // Create main link with image beside it (not inside dropdown)
                    const mobileLink = document.createElement('div');
                    mobileLink.className = 'mobile-nav-link-container';

                    // Add image beside the category name (outside dropdown)
                    if (dropdownImage) {
                        const imageContainer = document.createElement('div');
                        imageContainer.className = 'mobile-nav-image';
                        imageContainer.innerHTML = `<img src="${dropdownImage.src}" alt="${linkText}">`;
                        mobileLink.appendChild(imageContainer);
                    }

                    // Add the clickable text with arrow
                    const textContainer = document.createElement('div');
                    textContainer.className = 'mobile-nav-link';
                    textContainer.innerHTML = `
                        <span>${linkText.trim()}</span>
                        <svg class="mobile-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    `;
                    mobileLink.appendChild(textContainer);

                    // Create submenu (text only, NO image)
                    if (dropdownMenu) {
                        const submenu = document.createElement('div');
                        submenu.className = 'mobile-submenu';

                        // Add submenu content (text only)
                        const isArabic = document.documentElement.getAttribute('dir') === 'rtl';
                        const columns = dropdownMenu.querySelectorAll('.dropdown-column:not(.dropdown-image)');
                        columns.forEach(column => {
                            const title = column.querySelector('.dropdown-title');
                            if (title) {
                                const subTitle = document.createElement('div');
                                subTitle.className = 'mobile-submenu-title';
                                const titleText = isArabic
                                    ? (title.querySelector('.ar-text') ? title.querySelector('.ar-text').textContent : title.textContent)
                                    : (title.querySelector('.en-text') ? title.querySelector('.en-text').textContent : title.textContent);
                                subTitle.textContent = titleText.trim();
                                submenu.appendChild(subTitle);
                            }

                            // Get only direct links (exclude nested ul links)
                            const directLinks = [];
                            column.querySelectorAll('ul').forEach(ul => {
                                // Only process the main ul, not nested ones
                                if (!ul.parentElement.closest('ul')) {
                                    ul.querySelectorAll(':scope > li').forEach(li => {
                                        const mainLink = li.querySelector(':scope > a');
                                        if (mainLink) {
                                            directLinks.push({ link: mainLink, listItem: li });
                                        }
                                    });
                                }
                            });

                            // Add links to submenu
                            directLinks.forEach(({ link: mainLink, listItem }) => {
                                const subLink = document.createElement('a');
                                subLink.href = mainLink.href;
                                subLink.className = 'mobile-submenu-link';
                                const linkText = isArabic
                                    ? (mainLink.querySelector('.ar-text') ? mainLink.querySelector('.ar-text').textContent : mainLink.textContent)
                                    : (mainLink.querySelector('.en-text') ? mainLink.querySelector('.en-text').textContent : mainLink.textContent);
                                subLink.textContent = linkText.trim();
                                submenu.appendChild(subLink);

                                // Check if there are child categories (nested ul directly under this li)
                                const childUl = listItem.querySelector(':scope > ul');
                                if (childUl) {
                                    const childItems = childUl.querySelectorAll(':scope > li');
                                    childItems.forEach(childLi => {
                                        const childLink = childLi.querySelector(':scope > a');
                                        if (childLink) {
                                            const childSubLink = document.createElement('a');
                                            childSubLink.href = childLink.href;
                                            childSubLink.className = 'mobile-submenu-link mobile-submenu-child';
                                            const childLinkText = isArabic
                                                ? (childLink.querySelector('.ar-text') ? childLink.querySelector('.ar-text').textContent : childLink.textContent)
                                                : (childLink.querySelector('.en-text') ? childLink.querySelector('.en-text').textContent : childLink.textContent);
                                            childSubLink.textContent = childLinkText.trim();
                                            submenu.appendChild(childSubLink);
                                        }
                                    });
                                }
                            });
                        });

                        mobileItem.appendChild(mobileLink);
                        mobileItem.appendChild(submenu);
                    } else {
                        mobileItem.appendChild(mobileLink);
                    }

                    content.appendChild(mobileItem);
                }
            });

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

            sidebar.appendChild(header);
            sidebar.appendChild(content);
            document.body.appendChild(sidebar);

            // Toggle sidebar
            const menuBtn = document.querySelector('.mobile-menu-btn');
            if (menuBtn) {
                menuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.add('active');
                    overlay.classList.add('active');

                    // Prevent scrolling on body when sidebar is open
                    document.body.classList.add('sidebar-open');
                    document.documentElement.classList.add('sidebar-open');

                    // Additional inline styles for maximum compatibility
                    document.body.style.overflow = 'hidden';
                    document.body.style.position = 'fixed';
                    document.body.style.width = '100%';
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
            expandableItems.forEach(item => {
                const link = item.querySelector('.mobile-nav-link');
                const submenu = item.querySelector('.mobile-submenu');

                if (link && submenu) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Toggle current item
                        const isOpen = item.classList.contains('open');

                        // Close all other items
                        expandableItems.forEach(otherItem => {
                            otherItem.classList.remove('open');
                            const otherSubmenu = otherItem.querySelector('.mobile-submenu');
                            if (otherSubmenu) {
                                otherSubmenu.style.maxHeight = '0';
                            }
                        });

                        // Open current item if it was closed
                        if (!isOpen) {
                            item.classList.add('open');
                            submenu.style.maxHeight = submenu.scrollHeight + 'px';
                        }
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

    // Mobile product card touch interactions
    function initMobileProductInteractions() {
        const productCards = document.querySelectorAll('.product-card');

        productCards.forEach(card => {
            let touchStartTime = 0;
            let touchDuration = 0;

            card.addEventListener('touchstart', function() {
                touchStartTime = Date.now();
            }, { passive: true });

            card.addEventListener('touchend', function(e) {
                touchDuration = Date.now() - touchStartTime;

                // If touch duration is short (< 200ms), it's a tap
                if (touchDuration < 200) {
                    // Show size selector on mobile tap
                    const sizeSelector = this.querySelector('.size-selector');
                    if (sizeSelector) {
                        sizeSelector.style.opacity = '1';
                        sizeSelector.style.visibility = 'visible';
                        sizeSelector.style.transform = 'translateY(0)';

                        // Hide after 3 seconds
                        setTimeout(() => {
                            sizeSelector.style.opacity = '0';
                            sizeSelector.style.visibility = 'hidden';
                            sizeSelector.style.transform = 'translateY(-10px)';
                        }, 3000);
                    }
                }
            }, { passive: true });
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
        if (window.innerWidth <= 1024) {
            createMobileSidebar();
            initMobileMenu();
            optimizeImagesForMobile();
            preventDoubleTapZoom();
            handleMobileSearch();
            initMobileProductInteractions();
            initMobileSizeSelector();
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
    setMobileVH();
    initMobileFeatures();

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
