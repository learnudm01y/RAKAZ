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

    function positionDropdown(item, menu) {
        const header = document.querySelector('.main-header');

        if (header) {
            const headerRect = header.getBoundingClientRect();
            const topPosition = headerRect.bottom;

            menu.style.top = topPosition + 'px';
        }
    }

    navItems.forEach(item => {
        const trigger = item.querySelector('.dropdown-trigger');
        const menu = item.querySelector('.dropdown-menu');

        if (trigger && menu) {
            // Mouse events for desktop
            item.addEventListener('mouseenter', function() {
                positionDropdown(item, menu);
                menu.style.opacity = '1';
                menu.style.visibility = 'visible';
                menu.style.marginTop = '0';
            });

            item.addEventListener('mouseleave', function() {
                menu.style.opacity = '0';
                menu.style.visibility = 'hidden';
                menu.style.marginTop = '15px';
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
            if (badge.closest('.header-link').querySelector('span')?.textContent.includes('الحقيبة')) {
                badge.textContent = count;
            }
        });
    }

    // Update wishlist badge (placeholder)
    function updateWishlistBadge(count) {
        const badges = document.querySelectorAll('.header-link .badge');
        badges.forEach(badge => {
            if (badge.closest('.header-link').querySelector('span')?.textContent.includes('المفضلة')) {
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
