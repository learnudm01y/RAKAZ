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
            const logoSrc = logoImg ? logoImg.src : 'assets/images/logo.png';
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
                        <span class="ar-text">سجّل الدخول / اشترك</span>
                        <span class="en-text">Sign In / Register</span>
                    </div>
                    <p class="mobile-account-subtitle ar-text">للإتمام عملية الشراء بشكل أسرع</p>
                    <p class="mobile-account-subtitle en-text">To complete your purchase faster</p>
                </div>
                <svg class="mobile-account-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            `;
            content.appendChild(accountSection);
            
            // Add tabs for gender selection
            const tabsSection = document.createElement('div');
            tabsSection.className = 'mobile-tabs';
            tabsSection.innerHTML = `
                <button class="mobile-tab active" data-tab="women">
                    <span class="ar-text">النساء</span>
                    <span class="en-text">Women</span>
                </button>
                <button class="mobile-tab active" data-tab="men">
                    <span class="ar-text">الرجال</span>
                    <span class="en-text">Men</span>
                </button>
                <button class="mobile-tab" data-tab="kids">
                    <span class="ar-text">الأطفال</span>
                    <span class="en-text">Kids</span>
                </button>
            `;
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
                    
                    const linkText = navLink.querySelector('.ar-text') ? navLink.querySelector('.ar-text').textContent : navLink.textContent;
                    
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
                        const columns = dropdownMenu.querySelectorAll('.dropdown-column:not(.dropdown-image)');
                        columns.forEach(column => {
                            const title = column.querySelector('.dropdown-title');
                            if (title) {
                                const subTitle = document.createElement('div');
                                subTitle.className = 'mobile-submenu-title';
                                subTitle.textContent = title.textContent.trim();
                                submenu.appendChild(subTitle);
                            }
                            
                            const links = column.querySelectorAll('li a');
                            links.forEach(link => {
                                const subLink = document.createElement('a');
                                subLink.href = link.href;
                                subLink.className = 'mobile-submenu-link';
                                subLink.textContent = link.textContent.trim();
                                submenu.appendChild(subLink);
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
            
            // Add simple nav links
            const simpleLinks = document.querySelectorAll('.main-nav > .nav-link:not(.dropdown-trigger)');
            simpleLinks.forEach(link => {
                const mobileItem = document.createElement('div');
                mobileItem.className = 'mobile-nav-item';
                
                const mobileLink = document.createElement('a');
                mobileLink.href = link.href;
                mobileLink.className = 'mobile-nav-link';
                const linkText = link.querySelector('.ar-text') ? link.querySelector('.ar-text').textContent : link.textContent;
                mobileLink.textContent = linkText.trim();
                
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
                    <span class="ar-text">تغيير اللغة</span>
                    <span class="en-text">Change Language</span>
                </div>
                <button class="mobile-lang-toggle" onclick="toggleLanguage()">
                    <span class="ar-text">English</span>
                    <span class="en-text">العربية</span>
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
                    <span class="ar-text">العملة / الدولة</span>
                    <span class="en-text">Currency / Country</span>
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
                        <span class="ar-text">المملكة العربية السعودية</span>
                        <span class="en-text">Saudi Arabia</span>
                    </a>
                    <a href="#" class="mobile-currency-option" data-currency="Oman" data-flag="https://flagcdn.com/om.svg">
                        <img src="https://flagcdn.com/om.svg" alt="Oman" class="mobile-flag-icon">
                        <span class="ar-text">عُمان</span>
                        <span class="en-text">Oman</span>
                    </a>
                    <a href="#" class="mobile-currency-option" data-currency="Kuwait" data-flag="https://flagcdn.com/kw.svg">
                        <img src="https://flagcdn.com/kw.svg" alt="Kuwait" class="mobile-flag-icon">
                        <span class="ar-text">الكويت</span>
                        <span class="en-text">Kuwait</span>
                    </a>
                    <a href="#" class="mobile-currency-option" data-currency="Bahrain" data-flag="https://flagcdn.com/bh.svg">
                        <img src="https://flagcdn.com/bh.svg" alt="Bahrain" class="mobile-flag-icon">
                        <span class="ar-text">البحرين</span>
                        <span class="en-text">Bahrain</span>
                    </a>
                    <a href="#" class="mobile-currency-option" data-currency="Qatar" data-flag="https://flagcdn.com/qa.svg">
                        <img src="https://flagcdn.com/qa.svg" alt="Qatar" class="mobile-flag-icon">
                        <span class="ar-text">قطر</span>
                        <span class="en-text">Qatar</span>
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
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Close sidebar
            function closeSidebar() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
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
    
    // Swipe gesture for product sliders on mobile
    function initSwipeGestures() {
        const sliders = document.querySelectorAll('.products-container');
        
        sliders.forEach(slider => {
            let startX = 0;
            let scrollLeft = 0;
            let isDragging = false;
            let velocity = 0;
            let lastX = 0;
            let lastTime = Date.now();
            
            slider.addEventListener('touchstart', function(e) {
                isDragging = true;
                startX = e.touches[0].pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
                velocity = 0;
                lastX = e.touches[0].pageX;
                lastTime = Date.now();
                
                slider.style.scrollBehavior = 'auto';
            }, { passive: true });
            
            slider.addEventListener('touchmove', function(e) {
                if (!isDragging) return;
                
                const x = e.touches[0].pageX - slider.offsetLeft;
                const walk = (x - startX) * 1.5; // Multiplier for faster scrolling
                slider.scrollLeft = scrollLeft - walk;
                
                // Calculate velocity for momentum
                const now = Date.now();
                const timeDiff = now - lastTime;
                if (timeDiff > 0) {
                    velocity = (e.touches[0].pageX - lastX) / timeDiff;
                }
                lastX = e.touches[0].pageX;
                lastTime = now;
            }, { passive: true });
            
            slider.addEventListener('touchend', function() {
                isDragging = false;
                
                // Apply momentum scrolling
                if (Math.abs(velocity) > 0.5) {
                    const momentum = velocity * 200;
                    slider.scrollLeft -= momentum;
                }
                
                slider.style.scrollBehavior = 'smooth';
            }, { passive: true });
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
            initSwipeGestures();
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
