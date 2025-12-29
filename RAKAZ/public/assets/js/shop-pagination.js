/**
 * Shop Pagination AJAX Handler
 * Handles pagination without page reload using skeleton loading
 */

class ShopPagination {
    constructor() {
        this.productsGrid = document.querySelector('.products-grid');
        this.paginationContainer = document.querySelector('.pagination');
        this.isLoading = false;

        if (!this.productsGrid || !this.paginationContainer) {
            console.log('ℹ️ Shop pagination not initialized (not on shop page)');
            return;
        }

        this.init();
    }

    init() {
        console.log('🚀 Shop Pagination initialized');
        this.attachPaginationListeners();
    }

    attachPaginationListeners() {
        // Attach click listeners to all pagination links
        this.paginationContainer.addEventListener('click', (e) => {
            const target = e.target.closest('a.pagination-btn');

            if (!target || target.hasAttribute('disabled')) {
                return;
            }

            e.preventDefault();

            if (this.isLoading) {
                return;
            }

            const url = target.getAttribute('href');
            this.loadPage(url);
        });
    }

    async loadPage(url) {
        if (this.isLoading) return;

        this.isLoading = true;
        console.log('⏳ Loading products from:', url);

        try {
            // Show skeleton
            this.showSkeleton();

            // Scroll to products grid
            this.scrollToProducts();

            // Fetch data with AJAX header
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                // Update products grid (will call reinitializeProductScripts internally)
                this.updateProducts(data.productsHtml);

                // Update pagination
                this.updatePagination(data.paginationHtml);

                // Update product count in header
                if (data.total !== undefined) {
                    this.updateProductCount(data.total, data.currentPage, data.lastPage);
                }

                // Update URL without reload
                window.history.pushState({}, '', url);

                console.log('✅ Products loaded successfully');
            } else {
                throw new Error('Server returned success: false');
            }

        } catch (error) {
            console.error('❌ Error loading products:', error);
            this.showError();
        } finally {
            this.isLoading = false;
        }
    }

    showSkeleton() {
        const skeletonHTML = this.generateSkeleton();
        this.productsGrid.style.opacity = '0';

        setTimeout(() => {
            this.productsGrid.innerHTML = skeletonHTML;
            this.productsGrid.classList.add('skeleton-loading');
            this.productsGrid.style.opacity = '1';
        }, 150);
    }

    generateSkeleton() {
        let skeleton = '';
        for (let i = 0; i < 12; i++) {
            skeleton += `
                <div class="product-card skeleton-card">
                    <div class="skeleton-image skeleton"></div>
                    <div class="skeleton-content">
                        <div class="skeleton skeleton-text"></div>
                        <div class="skeleton skeleton-text-small"></div>
                        <div class="skeleton skeleton-price"></div>
                        <div class="skeleton skeleton-button"></div>
                    </div>
                </div>
            `;
        }
        return skeleton;
    }

    updateProducts(html) {
        console.log('📝 Updating products, HTML length:', html ? html.length : 0);
        this.productsGrid.style.opacity = '0';

        setTimeout(() => {
            this.productsGrid.innerHTML = html;
            this.productsGrid.classList.remove('skeleton-loading');

            console.log('✅ Products HTML inserted, product cards found:', this.productsGrid.querySelectorAll('.product-card').length);

            setTimeout(() => {
                this.productsGrid.style.opacity = '1';

                // Re-initialize product scripts after DOM is fully updated
                // Increased timeout to ensure all DOM operations complete
                setTimeout(() => {
                    this.reinitializeProductScripts();
                }, 150);
            }, 10);
        }, 300);
    }

    updatePagination(html) {
        if (html) {
            this.paginationContainer.innerHTML = html;
            // Re-attach listeners to new pagination
            this.attachPaginationListeners();
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

        console.log(`📊 Updated product count: ${total} products, page ${currentPage}/${lastPage}`);
    }

    scrollToProducts() {
        const productsSection = document.querySelector('.products-section');
        if (productsSection) {
            const offset = productsSection.offsetTop - 100;
            window.scrollTo({
                top: offset,
                behavior: 'smooth'
            });
        }
    }

    showError() {
        const errorHTML = `
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">
                    ${window.isArabic ? 'حدث خطأ أثناء التحميل. يرجى المحاولة مرة أخرى.' : 'An error occurred while loading. Please try again.'}
                </p>
                <button onclick="location.reload()" class="mt-4 px-6 py-2 bg-black text-white rounded">
                    ${window.isArabic ? 'إعادة المحاولة' : 'Retry'}
                </button>
            </div>
        `;
        this.productsGrid.innerHTML = errorHTML;
        this.productsGrid.classList.remove('skeleton-loading');
        this.productsGrid.style.opacity = '1';
    }

    reinitializeProductScripts() {
        console.log('🔄 Reinitializing product scripts after pagination...');

        // Re-initialize wishlist buttons
        if (typeof initializeWishlistButtons === 'function') {
            initializeWishlistButtons();
        }

        // Re-initialize add to cart buttons
        if (typeof initializeAddToCartButtons === 'function') {
            initializeAddToCartButtons();
        }

        // Re-initialize product hover effects
        if (typeof initializeProductHoverEffects === 'function') {
            initializeProductHoverEffects();
        }

        // Re-initialize gallery navigation
        if (typeof initializeGalleryNavigation === 'function') {
            initializeGalleryNavigation();
        }

        // Re-initialize product hover lazy loading - CRITICAL for hover to work after pagination
        if (typeof refreshProductHoverListeners === 'function') {
            console.log('✅ Calling refreshProductHoverListeners...');
            refreshProductHoverListeners();
        } else {
            console.warn('⚠️ refreshProductHoverListeners function not found!');
        }

        console.log('✅ Product scripts reinitialized');
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new ShopPagination();
    });
} else {
    new ShopPagination();
}

// Export for external use
window.ShopPagination = ShopPagination;
