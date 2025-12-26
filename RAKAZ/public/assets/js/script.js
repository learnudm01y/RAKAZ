// Slider functionality
document.addEventListener('DOMContentLoaded', function() {

    // Product Slider Navigation - Desktop Only
    function initProductSliderNavigation() {
        // Only initialize on desktop (screens larger than 1024px)
        if (window.innerWidth <= 1024) {
            return;
        }

        const sliders = document.querySelectorAll('.products-slider');

        sliders.forEach(slider => {
            const container = slider.querySelector('.products-container');
            const prevBtn = slider.querySelector('.slider-btn.prev');
            const nextBtn = slider.querySelector('.slider-btn.next');

            if (!container || !prevBtn || !nextBtn) return;

            // Scroll amount based on card width + gap
            const scrollAmount = 370; // 350px card + 20px gap

            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                container.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });

            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                container.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
        });
    }

    // Initialize slider navigation
    initProductSliderNavigation();

    // Re-initialize on window resize (if changing from mobile to desktop)
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            initProductSliderNavigation();
        }, 250);
    });

    // Debounce function for performance
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Throttle function for scroll events
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // Dropdown menus functionality with dynamic positioning
    const navItems = document.querySelectorAll('.nav-item.dropdown');

    // استخدام الدالة من desktop-mega-menu.js إذا كانت متاحة ونحن على سطح المكتب
    function buildMegaMenuIfNeeded(menu) {
        // إذا كنا على سطح المكتب واستخدام ملف desktop-mega-menu.js
        if (window.innerWidth > 1024 && typeof window.buildDesktopMegaMenu === 'function') {
            window.buildDesktopMegaMenu(menu);
            return;
        }

        // الكود الأصلي للهاتف
        if (!menu || menu.dataset.defer !== '1') return;
        if (menu.dataset.built === '1' || menu.dataset.built === 'building') return;

        const dataEl = menu.querySelector('.js-mobile-menu-data, .js-mega-menu-data');
        const contentEl = menu.querySelector('.js-mega-menu-content');
        if (!dataEl || !contentEl) {
            menu.dataset.built = '1';
            return;
        }

        let data;
        try {
            data = JSON.parse(dataEl.textContent || '[]');
        } catch (e) {
            menu.dataset.built = '1';
            return;
        }

        if (!Array.isArray(data) || data.length === 0) {
            menu.dataset.built = '1';
            return;
        }

        menu.dataset.built = 'building';
        contentEl.textContent = '';

        const frag = document.createDocumentFragment();
        const columnsState = [];

        function createTextSpans(arText, enText) {
            const wrap = document.createDocumentFragment();

            const ar = document.createElement('span');
            ar.className = 'ar-text';
            ar.textContent = arText || '';

            const en = document.createElement('span');
            en.className = 'en-text';
            en.textContent = enText || '';

            wrap.appendChild(ar);
            wrap.appendChild(en);
            return wrap;
        }

        // Create columns structure up-front (cheap), populate items in idle batches.
        for (const col of data) {
            const colEl = document.createElement('div');
            colEl.className = 'dropdown-column';

            const title = document.createElement('h4');
            title.className = 'dropdown-title';
            title.appendChild(createTextSpans(col.title_ar, col.title_en));
            colEl.appendChild(title);

            const ul = document.createElement('ul');
            colEl.appendChild(ul);

            frag.appendChild(colEl);
            columnsState.push({ ul, items: Array.isArray(col.items) ? col.items : [], idx: 0 });
        }

        contentEl.appendChild(frag);


        // Round-robin item population across columns to make the menu feel responsive quickly.
        const perChunk = 80;
        const immediate = 40;
        let remainingCols = columnsState.length;

        function appendItem(ul, item) {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = item.link || '#';
            a.appendChild(createTextSpans(item.name_ar, item.name_en));
            li.appendChild(a);

            if (Array.isArray(item.children) && item.children.length) {
                const childUl = document.createElement('ul');
                for (const child of item.children) {
                    const childLi = document.createElement('li');
                    const childA = document.createElement('a');
                    childA.href = child.link || '#';
                    childA.appendChild(createTextSpans(child.name_ar, child.name_en));
                    childLi.appendChild(childA);
                    childUl.appendChild(childLi);
                }
                li.appendChild(childUl);
            }

            ul.appendChild(li);
        }

        // Immediate small batch so the menu isn't empty.
        let added = 0;
        while (added < immediate) {
            let progressed = false;
            for (const st of columnsState) {
                if (st.idx < st.items.length) {
                    appendItem(st.ul, st.items[st.idx]);
                    st.idx++;
                    added++;
                    progressed = true;
                    if (added >= immediate) break;
                }
            }
            if (!progressed) break;
        }

        function work(deadline) {
            let count = 0;
            while (count < perChunk && columnsState.some(st => st.idx < st.items.length) && (!deadline || deadline.timeRemaining() > 4)) {
                for (const st of columnsState) {
                    if (st.idx < st.items.length) {
                        appendItem(st.ul, st.items[st.idx]);
                        st.idx++;
                        count++;
                        if (count >= perChunk) break;
                    }
                }
            }

            if (columnsState.some(st => st.idx < st.items.length)) {
                if (window.requestIdleCallback) {
                    window.requestIdleCallback(work, { timeout: 200 });
                } else {
                    window.requestAnimationFrame(() => work(null));
                }
            } else {
                menu.dataset.built = '1';
            }
        }

        if (window.requestIdleCallback) {
            window.requestIdleCallback(work, { timeout: 200 });
        } else {
            window.requestAnimationFrame(() => work(null));
        }
    }

    function closeAllDropdowns() {
        navItems.forEach(navItem => {
            navItem.classList.remove('is-open');
        });
    }

    function positionDropdown(item, menu) {
        const mainNav = document.querySelector('.main-nav');
        const anchorEl = mainNav || document.querySelector('.main-header');
        if (!anchorEl) return;

        const rect = anchorEl.getBoundingClientRect();
        const topPosition = rect.bottom;

        // Used by CSS to compute max-height and keep the menu scrollable
        menu.style.top = topPosition + 'px';
        menu.style.setProperty('--dropdown-top', topPosition + 'px');

        // Responsive horizontal alignment: match hero banner position/width when present.
        // This avoids non-responsive magic numbers like left: 753px.
        const hero = document.querySelector('.hero-banner');
        if (hero) {
            const heroRect = hero.getBoundingClientRect();
            if (heroRect.width > 0) {
                menu.style.left = heroRect.left + 'px';
                menu.style.right = 'auto';
                menu.style.transform = 'none';
                menu.style.width = heroRect.width + 'px';
                menu.style.maxWidth = heroRect.width + 'px';
            }
        }
    }

    function loadMenuImageAfterText(menu) {
        if (!menu) return;
        const img = menu.querySelector('.dropdown-image img[data-src]');
        if (!img) return;
        if (img.dataset.loaded === '1') return;

        // Ensure text is painted first, then start image loading.
        window.requestAnimationFrame(() => {
            if (img.dataset.loaded === '1') return;
            img.src = img.dataset.src;
            img.dataset.loaded = '1';
        });
    }

    navItems.forEach(item => {
        const trigger = item.querySelector('.dropdown-trigger');
        const menu = item.querySelector('.dropdown-menu');

        let hideTimer = null;

        function showMenu() {
            if (window.innerWidth < 1024) return;
            if (hideTimer) {
                clearTimeout(hideTimer);
                hideTimer = null;
            }
            closeAllDropdowns();
            positionDropdown(item, menu);
            item.classList.add('is-open');
            buildMegaMenuIfNeeded(menu);
            loadMenuImageAfterText(menu);
        }

        function hideMenu() {
            if (hideTimer) clearTimeout(hideTimer);
            hideTimer = setTimeout(() => {
                item.classList.remove('is-open');
            }, 140);
        }

        if (trigger && menu) {
            // Mouse events for desktop
            item.addEventListener('mouseenter', function() {
                showMenu();
            });

            item.addEventListener('mouseleave', function() {
                hideMenu();
            });

            menu.addEventListener('mouseenter', function() {
                if (hideTimer) {
                    clearTimeout(hideTimer);
                    hideTimer = null;
                }
            });

            menu.addEventListener('mouseleave', function() {
                hideMenu();
            });

            // Click events for mobile
            trigger.addEventListener('click', function(e) {
                if (window.innerWidth < 1024) {
                    e.preventDefault();
                    const isOpen = menu.style.display === 'block';

                    // Close all other menus
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        m.style.display = 'none';
                    });

                    // Toggle current menu
                    menu.style.display = isOpen ? 'none' : 'block';
                }
            });
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-item.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (window.innerWidth < 1024) {
                    menu.style.display = 'none';
                }
            });
        }
    });

    // Wishlist functionality
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');

    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent link navigation
            const svg = this.querySelector('svg');
            const path = svg.querySelector('path');

            if (path.getAttribute('fill') === 'currentColor') {
                path.setAttribute('fill', 'none');
                path.setAttribute('stroke', 'currentColor');
            } else {
                path.setAttribute('fill', 'currentColor');
                path.setAttribute('stroke', 'none');
            }
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Newsletter form
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        const button = newsletterForm.querySelector('button');
        const input = newsletterForm.querySelector('input');

        if (button && input) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const email = input.value.trim();

                if (email && validateEmail(email)) {
                    alert('شكراً لاشتراكك في نشرتنا الإخبارية!');
                    input.value = '';
                } else {
                    alert('الرجاء إدخال عنوان بريد إلكتروني صحيح');
                }
            });
        }
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Sticky header on scroll
    let lastScroll = 0;
    const header = document.querySelector('.main-header');

    const handleScroll = throttle(function() {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 100) {
            header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        } else {
            header.style.boxShadow = '';
        }

        lastScroll = currentScroll;
    }, 100);

    window.addEventListener('scroll', handleScroll, { passive: true });

    // Keep open dropdown aligned on resize.
    window.addEventListener('resize', throttle(function() {
        document.querySelectorAll('.nav-item.dropdown.is-open').forEach(item => {
            const menu = item.querySelector('.dropdown-menu');
            if (menu) positionDropdown(item, menu);
        });
    }, 100));

    // Image lazy loading
    const images = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

    // Product card hover effects - DISABLED
    const productCards = document.querySelectorAll('.product-card');

    // Remove all hover effects from product cards
    productCards.forEach(card => {
        card.style.transition = 'none';
        card.style.transform = 'none';
    });

    // Gift cards and discover cards hover effects - DISABLED
    const hoverCards = document.querySelectorAll('.gift-card, .discover-card, .discover-card-wide');

    // Remove all hover effects from gift and discover cards
    hoverCards.forEach(card => {
        const image = card.querySelector('.gift-image, .discover-image');
        if (image) {
            image.style.transition = 'none';
            image.style.transform = 'none';
        }
    });

    // Size selector functionality
    const sizeOptions = document.querySelectorAll('.size-option');

    sizeOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Remove selected class from siblings
            const siblings = this.parentElement.querySelectorAll('.size-option');
            siblings.forEach(sibling => sibling.classList.remove('selected'));

            // Add selected class to clicked option
            this.classList.add('selected');

            const size = this.getAttribute('data-size');
            console.log('تم اختيار المقاس:', size);
        });
    });

    // Size selector arrows functionality
    const sizeArrows = document.querySelectorAll('.size-arrow');

    sizeArrows.forEach(arrow => {
        arrow.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const sizeSelector = this.closest('.size-selector');
            const wrapper = sizeSelector.querySelector('.size-options-wrapper');
            const scrollAmount = 80;

            // Detect direction
            const isRTL = document.dir === 'rtl' || document.documentElement.dir === 'rtl';

            if (this.classList.contains('prev')) {
                // Prev should go left in LTR, right in RTL
                if (isRTL) {
                    wrapper.scrollLeft += scrollAmount;
                } else {
                    wrapper.scrollLeft -= scrollAmount;
                }
            } else if (this.classList.contains('next')) {
                // Next should go right in LTR, left in RTL
                if (isRTL) {
                    wrapper.scrollLeft -= scrollAmount;
                } else {
                    wrapper.scrollLeft += scrollAmount;
                }
            }
        });
    });

    // Search box functionality
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.parentElement.style.borderColor = '#1a1a1a';
        });

        searchInput.addEventListener('blur', function() {
            this.parentElement.style.borderColor = '';
        });

        searchInput.addEventListener('input', function() {
            // Add search functionality here
            console.log('البحث عن:', this.value);
        });
    }

    // Currency selector functionality
    const currencySelector = document.querySelector('.currency-selector');
    const currencyOptions = document.querySelectorAll('.currency-option');
    const selectedCurrency = document.getElementById('selected-currency');
    const selectedCurrencyFlag = document.getElementById('selected-currency-flag');

    if (currencyOptions.length > 0) {
        currencyOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const currency = this.getAttribute('data-currency');
                const flag = this.getAttribute('data-flag');

                selectedCurrency.textContent = currency;
                selectedCurrencyFlag.src = flag;

                // Save preference
                localStorage.setItem('selected-currency', currency);
                localStorage.setItem('selected-currency-flag', flag);

                console.log('تم اختيار العملة:', currency);
            });
        });

        // Load saved currency
        const savedCurrency = localStorage.getItem('selected-currency');
        const savedFlag = localStorage.getItem('selected-currency-flag');
        if (savedCurrency && savedFlag) {
            selectedCurrency.textContent = savedCurrency;
            selectedCurrencyFlag.src = savedFlag;
        }
    }

    // Language selector
    const languageSelector = document.querySelector('.language-selector');
    if (languageSelector) {
        languageSelector.addEventListener('click', function() {
            // Add language selection dropdown here
            console.log('فتح قائمة اللغات');
        });
    }

    // Search box functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.product-card') && !e.target.closest('.wishlist-btn')) {
            const productCard = e.target.closest('.product-card');
            const productName = productCard.querySelector('.product-name')?.textContent;
            console.log('عرض تفاصيل المنتج:', productName);
        }
    });

    // Promo buttons
    const promoBtns = document.querySelectorAll('.promo-btn, .hero-btn, .cyber-btn, .dg-btn, .gucci-btn');

    promoBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });

    // Update cart badge (placeholder)
    function updateCartBadge(count) {
        const badges = document.querySelectorAll('.header-link .badge');
        badges.forEach(badge => {
            if (badge.closest('a#cartToggle')) {
                badge.textContent = count;
            }
        });
    }

    // Update wishlist badge (placeholder)
    function updateWishlistBadge(count) {
        const badges = document.querySelectorAll('.header-link .badge');
        badges.forEach(badge => {
            if (badge.closest('a[href*="wishlist"]')) {
                badge.textContent = count;
            }
        });
    }

    // Console log for development
    console.log('موقع RAKAZ تم تحميله بنجاح');
    console.log('جميع الوظائف التفاعلية جاهزة');

    // Detect screen size and add class to body
    function updateScreenSize() {
        const width = window.innerWidth;
        document.body.classList.remove('mobile', 'tablet', 'desktop');

        if (width < 768) {
            document.body.classList.add('mobile');
        } else if (width < 1024) {
            document.body.classList.add('tablet');
        } else {
            document.body.classList.add('desktop');
        }
    }

    updateScreenSize();
    window.addEventListener('resize', debounce(updateScreenSize, 250));

    // Fix viewport height for mobile browsers
    function setVH() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }

    setVH();
    window.addEventListener('resize', debounce(setVH, 250));

    // Prevent zoom on input focus for iOS
    if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.fontSize = '16px';
            });
            input.addEventListener('blur', function() {
                this.style.fontSize = '';
            });
        });
    }
});

// Language Toggle Function (Global)
function toggleLanguage() {
    const html = document.documentElement;
    const body = document.body;
    const currentDir = html.getAttribute('dir') || 'rtl';
    const newDir = currentDir === 'rtl' ? 'ltr' : 'rtl';
    const newLocale = newDir === 'rtl' ? 'ar' : 'en';

    // Send request to server to change locale
    fetch('/locale/' + newLocale, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update direction
            html.setAttribute('dir', newDir);
            html.setAttribute('lang', newLocale);
            body.setAttribute('dir', newDir);

            // Save preference
            localStorage.setItem('preferred-language', newDir);

            // Update page title
            document.title = newDir === 'rtl'
                ? 'RAKAZ - البيت النهائي للرفاهية'
                : 'RAKAZ - The Definitive Home of Luxury';

            // Reload page to apply language changes
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error changing language:', error);
        // Fallback to client-side only change
        html.setAttribute('dir', newDir);
        html.setAttribute('lang', newLocale);
        body.setAttribute('dir', newDir);
        localStorage.setItem('preferred-language', newDir);
        window.location.reload();
    });
}

// No need for localStorage - server handles locale preference
// Language direction is set by server in HTML tag

// RTL Slider fix for scrollBy
if (document.dir === 'rtl' || document.documentElement.dir === 'rtl') {
    Element.prototype.scrollBy = function(options) {
        if (options.right !== undefined) {
            this.scrollLeft -= options.right;
        } else if (options.left !== undefined) {
            this.scrollLeft += options.left;
        }
    };
}

// ========================================
// Hero Banner Slider
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    let currentSlide = 0;
    let slideInterval;

    function showSlide(index) {
        // Remove active class from all slides and dots
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        // Add active class to current slide and dot
        slides[index].classList.add('active');
        dots[index].classList.add('active');
        currentSlide = index;
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function startSlideshow() {
        slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    }

    function stopSlideshow() {
        clearInterval(slideInterval);
    }

    // Dot click handlers
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopSlideshow();
            showSlide(index);
            startSlideshow(); // Restart slideshow after manual navigation
        });
    });

    // Start automatic slideshow
    if (slides.length > 0 && dots.length > 0) {
        startSlideshow();

        // Pause slideshow on hover
        const heroBanner = document.querySelector('.hero-banner');
        if (heroBanner) {
            heroBanner.addEventListener('mouseenter', stopSlideshow);
            heroBanner.addEventListener('mouseleave', startSlideshow);
        }
    }
});
