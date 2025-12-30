/**
 * Lazy Loading Manager with Skeleton Screens
 * This script manages progressive loading of heavy sections to improve initial page load
 * Data is fetched from the server AFTER page load using AJAX
 */

class LazyLoadingManager {
    constructor() {
        this.sections = [
            {
                name: 'featured',
                skeletonId: 'featured-skeleton',
                contentId: 'featured-content',
                delay: 2000, // 2 seconds
                url: '/api/lazy-load/featured-section'
            },
            {
                name: 'perfect-gift',
                skeletonId: 'perfect-gift-skeleton',
                contentId: 'perfect-gift-content',
                delay: 2500, // 2.5 seconds
                url: '/api/lazy-load/perfect-gift-section'
            },
            {
                name: 'footer',
                skeletonId: 'footer-skeleton',
                contentId: 'footer-content',
                delay: 7000, // 7 seconds
                url: '/api/lazy-load/footer'
            }
        ];

        this.init();
    }

    init() {
        // Start loading sections after DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.startLoading());
        } else {
            this.startLoading();
        }
    }

    startLoading() {
        console.log('🚀 Lazy Loading Manager initialized');
        this.sections.forEach(section => {
            setTimeout(() => {
                this.loadSection(section);
            }, section.delay);
        });
    }

    async loadSection(section) {
        const skeletonElement = document.getElementById(section.skeletonId);
        const contentElement = document.getElementById(section.contentId);

        if (!skeletonElement || !contentElement) {
            console.warn(`⚠️ Section elements not found: ${section.name}`);
            return;
        }

        try {
            console.log(`⏳ Loading ${section.name} from server...`);

            // Get current locale
            const currentLocale = document.documentElement.getAttribute('lang') || 'ar';

            // Build URL with locale parameter
            const url = section.url + (section.url.includes('?') ? '&' : '?') + 'locale=' + currentLocale;

            // Fetch real data from server
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept-Language': currentLocale
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success && data.html) {
                // Insert the fetched HTML
                contentElement.innerHTML = data.html;

                // Fade out skeleton
                skeletonElement.style.opacity = '0';
                skeletonElement.style.transition = 'opacity 0.5s ease';

                setTimeout(() => {
                    // Hide skeleton, show content
                    skeletonElement.style.display = 'none';
                    contentElement.style.display = 'block';
                    contentElement.style.opacity = '0';

                    // Fade in content
                    setTimeout(() => {
                        contentElement.style.opacity = '1';
                        contentElement.style.transition = 'opacity 0.5s ease';

                        // Re-initialize any section-specific JavaScript
                        this.reinitializeSection(section.name);

                        console.log(`✅ Section loaded: ${section.name}`);
                    }, 10);
                }, 500);
            } else {
                // No content, just hide skeleton
                skeletonElement.style.display = 'none';
                console.log(`ℹ️ No content for ${section.name}`);
            }

        } catch (error) {
            console.error(`❌ Error loading section ${section.name}:`, error);
            // On error, just hide the skeleton
            skeletonElement.style.display = 'none';
            contentElement.innerHTML = '<p style="text-align: center; padding: 20px;">حدث خطأ أثناء التحميل</p>';
            contentElement.style.display = 'block';
        }
    }

    reinitializeSection(sectionName) {
        // Re-initialize section-specific functionality after loading
        switch(sectionName) {
            case 'featured':
                // Featured section slider might need re-initialization
                if (typeof window.initializeFeaturedSlider === 'function') {
                    window.initializeFeaturedSlider();
                }
                // Reinitialize featured section scripts
                const featuredScript = document.querySelector('script[src*="featured-section.js"]');
                if (featuredScript) {
                    console.log('🔄 Reinitializing featured section scripts');
                }
                break;
            case 'perfect-gift':
                // Perfect gift section slider might need re-initialization
                if (typeof window.initializePerfectGiftSlider === 'function') {
                    window.initializePerfectGiftSlider();
                }
                // Reinitialize perfect gift section scripts
                const perfectGiftScript = document.querySelector('script[src*="perfect-gift-section.js"]');
                if (perfectGiftScript) {
                    console.log('🔄 Reinitializing perfect gift section scripts');
                }
                break;
            case 'footer':
                // Footer might have interactive elements
                console.log('✨ Footer loaded successfully');
                // Initialize footer accordion for mobile/tablet
                if (window.innerWidth <= 1024) {
                    this.initFooterAccordion();
                }
                break;
        }
    }

    initFooterAccordion() {
        const footerColumns = document.querySelectorAll('.footer-column');

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

        console.log('Footer accordion initialized for', footerColumns.length, 'columns (from lazy loader)');
    }
}

// Initialize lazy loading when script loads
const lazyLoadingManager = new LazyLoadingManager();

// Export for potential external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LazyLoadingManager;
}
