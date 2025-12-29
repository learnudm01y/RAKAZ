/**
 * Shop Pagination Lazy Loader
 * Loads pagination data after 1.5 seconds with skeleton
 */
class ShopPaginationLoader {
    constructor() {
        this.paginationContainer = document.querySelector('.pagination');
        this.loaded = false;

        if (!this.paginationContainer) {
            console.log('ℹ️ Pagination container not found');
            return;
        }

        this.init();
    }

    init() {
        console.log('🚀 Shop Pagination Loader initialized');

        // Check if we're on initial page load (not AJAX)
        if (!this.isAjaxNavigation()) {
            this.loadPaginationWithDelay();
        }
    }

    isAjaxNavigation() {
        // Check if this is an AJAX navigation by looking for skeleton
        const hasSkeleton = this.paginationContainer.querySelector('.pagination-skeleton');
        return !hasSkeleton;
    }

    loadPaginationWithDelay() {
        console.log('⏰ Waiting 1.5 seconds before loading pagination...');

        setTimeout(() => {
            console.log('⏳ Loading pagination data...');
            this.loadPagination();
        }, 1500);
    }

    async loadPagination() {
        try {
            // Get current URL params (filters)
            const urlParams = new URLSearchParams(window.location.search);

            // Build query string with all filters
            const queryString = urlParams.toString();
            const url = '/api/lazy-load/shop-pagination' + (queryString ? '?' + queryString : '');

            console.log('📤 Fetching pagination from:', url);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success && data.html) {
                this.updatePagination(data.html);
                this.loaded = true;

                // Update product count in header
                if (data.total) {
                    this.updateProductCount(data.total, data.currentPage, data.lastPage);
                }

                console.log('✅ Pagination loaded successfully');
                console.log(`📊 Total products: ${data.total}, Pages: ${data.lastPage}`);
            } else {
                throw new Error('Invalid response data');
            }

        } catch (error) {
            console.error('❌ Error loading pagination:', error);
            this.showError();
        }
    }

    updateProductCount(total, currentPage, lastPage) {
        // Validate inputs
        if (total === undefined || currentPage === undefined || lastPage === undefined) {
            console.warn('⚠️ Invalid product count data:', { total, currentPage, lastPage });
            return;
        }

        const countElement = document.getElementById('product-count');
        const pluralElement = document.getElementById('product-plural');
        const pageInfoElement = document.getElementById('page-info');

        if (countElement) {
            countElement.textContent = String(total);
        }

        if (pluralElement) {
            const isArabic = document.documentElement.getAttribute('dir') === 'rtl';
            pluralElement.textContent = total != 1 ? (isArabic ? '' : 's') : '';
        }

        if (pageInfoElement) {
            if (total > 0 && currentPage && lastPage) {
                const isArabic = document.documentElement.getAttribute('dir') === 'rtl';
                const pageText = isArabic ? 'صفحة' : 'Page';
                const ofText = isArabic ? 'من' : 'of';
                pageInfoElement.textContent = ` (${pageText} ${currentPage} ${ofText} ${lastPage})`;
            } else {
                pageInfoElement.textContent = '';
            }
        }
    }

    updatePagination(html) {
        // Fade out skeleton
        this.paginationContainer.style.opacity = '0';
        this.paginationContainer.style.transition = 'opacity 0.3s ease';

        setTimeout(() => {
            this.paginationContainer.innerHTML = html;

            // Fade in real content
            setTimeout(() => {
                this.paginationContainer.style.opacity = '1';
            }, 10);
        }, 300);
    }

    showError() {
        console.warn('⚠️ Failed to load pagination, hiding skeleton');
        this.paginationContainer.style.display = 'none';
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        new ShopPaginationLoader();
    });
} else {
    new ShopPaginationLoader();
}

// Export for global access
window.ShopPaginationLoader = ShopPaginationLoader;
