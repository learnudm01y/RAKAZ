/**
 * Product Hover Content Lazy Loader
 * Loads product hover content (colors, gallery, sizes) on first hover via AJAX
 */
class ProductHoverLoader {
    constructor() {
        this.loadedProducts = new Set();
        this.loadingProducts = new Set();
        this.productCards = [];
        this.eventListeners = new Map(); // Store listeners for cleanup

        this.init();
    }

    /**
     * Initialize hover listeners
     */
    init() {
        console.log('🚀 Product Hover Loader initialized');
        this.attachHoverListeners();
    }

    /**
     * Attach hover listeners to all product cards
     */
    attachHoverListeners() {
        console.log('🔗 Attaching hover listeners...');

        // Clean up old listeners first
        this.cleanupListeners();

        // Get current product IDs in DOM
        const currentProductIds = new Set();

        this.productCards = document.querySelectorAll('.product-card');

        this.productCards.forEach(card => {
            const hoverContent = card.querySelector('.product-hover-skeleton');

            if (hoverContent) {
                const productId = hoverContent.dataset.productId;
                currentProductIds.add(productId);

                // Create handler function
                const handler = () => {
                    this.handleCardHover(productId, hoverContent);
                };

                // Store handler for cleanup
                this.eventListeners.set(card, handler);

                // Add listener
                card.addEventListener('mouseenter', handler);
            }
        });

        // Clean up loaded products cache - remove products that are no longer in DOM
        const loadedProductsArray = Array.from(this.loadedProducts);
        loadedProductsArray.forEach(productId => {
            if (!currentProductIds.has(productId)) {
                this.loadedProducts.delete(productId);
                console.log(`🗑️ Removed product ${productId} from loaded cache (no longer in DOM)`);
            }
        });

        console.log(`👀 Attached hover listeners to ${this.productCards.length} product cards`);
        console.log(`💾 Cached products: ${this.loadedProducts.size}, Current products: ${currentProductIds.size}`);
    }

    /**
     * Clean up old event listeners
     */
    cleanupListeners() {
        console.log('🧹 Cleaning up old listeners...');

        this.eventListeners.forEach((handler, card) => {
            card.removeEventListener('mouseenter', handler);
        });

        this.eventListeners.clear();
    }

    /**
     * Handle card hover event
     */
    async handleCardHover(productId, hoverElement) {
        // Check if element still exists in DOM
        if (!hoverElement || !hoverElement.isConnected) {
            console.log(`⚠️ Product ${productId} hover element no longer in DOM`);
            this.loadedProducts.delete(productId);
            this.loadingProducts.delete(productId);
            return;
        }

        // Check if already loaded or loading
        if (this.loadedProducts.has(productId)) {
            console.log(`✅ Product ${productId} hover content already loaded`);
            return;
        }

        if (this.loadingProducts.has(productId)) {
            console.log(`⏳ Product ${productId} hover content already loading`);
            return;
        }

        console.log(`🎯 Hover detected on product ${productId}, loading content...`);

        // Mark as loading
        this.loadingProducts.add(productId);

        try {
            await this.loadHoverContent(productId, hoverElement);
        } catch (error) {
            console.error(`❌ Failed to load hover content for product ${productId}:`, error);
            this.loadingProducts.delete(productId);
        }
    }

    /**
     * Load hover content via AJAX
     */
    async loadHoverContent(productId, hoverElement) {
        try {
            console.log(`📡 Fetching hover content for product ${productId}...`);

            const response = await fetch(`/api/lazy-load/product-hover/${productId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success && data.html) {
                this.updateHoverContent(productId, hoverElement, data.html, data.hoverImage);
                this.loadedProducts.add(productId);
                this.loadingProducts.delete(productId);
                console.log(`✅ Hover content loaded for product ${productId}`);
            } else {
                throw new Error('Invalid response data');
            }
        } catch (error) {
            console.error(`❌ Error loading hover content for product ${productId}:`, error);
            this.loadingProducts.delete(productId);
            // Don't remove skeleton on error - user can try again on next hover
        }
    }

    /**
     * Update hover content with loaded data
     */
    updateHoverContent(productId, hoverElement, html, hoverImage) {
        // Check if element still exists in DOM
        if (!hoverElement || !hoverElement.isConnected) {
            console.log(`⚠️ Product ${productId} hover element removed from DOM before update`);
            this.loadedProducts.delete(productId);
            return;
        }

        // Create a temporary container
        const temp = document.createElement('div');
        temp.innerHTML = html;

        // Get the parent element
        const parent = hoverElement.parentElement;

        if (!parent) {
            console.warn(`⚠️ Product ${productId} has no parent element`);
            return;
        }

        // Create the new hover content element
        const newHoverContent = document.createElement('div');
        newHoverContent.className = 'product-hover-content';
        newHoverContent.innerHTML = html;

        // Replace skeleton with actual content
        parent.replaceChild(newHoverContent, hoverElement);

        // Add secondary image to product link if provided
        if (hoverImage) {
            const productCard = parent.closest('.product-card');
            if (productCard) {
                const productLink = productCard.querySelector('.product-main-link');
                if (productLink && !productLink.querySelector('.product-image-secondary')) {
                    const secondaryImg = document.createElement('img');
                    secondaryImg.src = hoverImage;
                    secondaryImg.alt = productLink.querySelector('.product-image-primary')?.alt || '';
                    secondaryImg.className = 'product-image-secondary';
                    secondaryImg.style.opacity = '0';
                    productLink.appendChild(secondaryImg);
                    console.log(`🖼️ Secondary image added for product ${productId}`);
                }
            }
        }

        // Reinitialize scripts for this product
        this.reinitializeProductScripts(productId, newHoverContent);

        // Add fade-in animation
        newHoverContent.style.opacity = '0';
        newHoverContent.style.transition = 'opacity 0.2s ease';

        setTimeout(() => {
            newHoverContent.style.opacity = '1';
        }, 10);
    }

    /**
     * Reinitialize scripts for the loaded hover content
     */
    reinitializeProductScripts(productId, hoverElement) {
        // Reinitialize gallery navigation
        const galleryPrev = hoverElement.querySelector('.gallery-prev');
        const galleryNext = hoverElement.querySelector('.gallery-next');

        if (galleryPrev && galleryNext) {
            this.initializeGalleryNavigation(productId, hoverElement);
        }

        // Reinitialize sizes scroll
        const prevSizeBtn = hoverElement.querySelector('.prev-size');
        const nextSizeBtn = hoverElement.querySelector('.next-size');

        if (prevSizeBtn && nextSizeBtn) {
            this.initializeSizesScroll(productId, hoverElement);
        }

        // Reinitialize color click events
        const colorCircles = hoverElement.querySelectorAll('.product-color-circle');
        if (colorCircles.length > 0) {
            this.initializeColorClicks(colorCircles);
        }
    }

    /**
     * Initialize gallery navigation
     */
    initializeGalleryNavigation(productId, hoverElement) {
        const container = hoverElement.querySelector(`.product-gallery-container[data-product-id="${productId}"]`);
        const prevBtn = hoverElement.querySelector(`.gallery-prev[data-product-id="${productId}"]`);
        const nextBtn = hoverElement.querySelector(`.gallery-next[data-product-id="${productId}"]`);

        if (!container || !prevBtn || !nextBtn) return;

        let currentScroll = 0;
        const scrollAmount = 120;

        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            currentScroll = Math.max(0, currentScroll - scrollAmount);
            container.scrollTo({ left: currentScroll, behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const maxScroll = container.scrollWidth - container.clientWidth;
            currentScroll = Math.min(maxScroll, currentScroll + scrollAmount);
            container.scrollTo({ left: currentScroll, behavior: 'smooth' });
        });
    }

    /**
     * Initialize sizes scroll
     */
    initializeSizesScroll(productId, hoverElement) {
        const wrapper = hoverElement.querySelector(`.product-sizes-wrapper[data-product-id="${productId}"]`);
        const prevBtn = hoverElement.querySelector(`.prev-size[data-product-id="${productId}"]`);
        const nextBtn = hoverElement.querySelector(`.next-size[data-product-id="${productId}"]`);

        if (!wrapper || !prevBtn || !nextBtn) return;

        let currentScroll = 0;
        const scrollAmount = 100;

        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            currentScroll = Math.max(0, currentScroll - scrollAmount);
            wrapper.scrollTo({ left: currentScroll, behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const maxScroll = wrapper.scrollWidth - wrapper.clientWidth;
            currentScroll = Math.min(maxScroll, currentScroll + scrollAmount);
            wrapper.scrollTo({ left: currentScroll, behavior: 'smooth' });
        });
    }

    /**
     * Initialize color circle clicks
     */
    initializeColorClicks(colorCircles) {
        colorCircles.forEach(circle => {
            circle.addEventListener('click', function(e) {
                e.stopPropagation();

                // Reset all colors in this product card
                const productCard = this.closest('.product-card');
                const allColors = productCard.querySelectorAll('.product-color-circle');
                allColors.forEach(c => {
                    c.style.borderColor = '#fff';
                    c.style.borderWidth = '2px';
                });

                // Highlight selected color
                this.style.borderColor = '#1a1a1a';
                this.style.borderWidth = '3px';
            });
        });
    }

    /**
     * Refresh listeners (call this after AJAX pagination)
     */
    refresh() {
        console.log('🔄 Refreshing product hover listeners...');

        // Clear loading state for safety
        this.loadingProducts.clear();

        // Use setTimeout to ensure DOM is fully updated
        setTimeout(() => {
            this.attachHoverListeners();
            console.log('✅ Hover listeners refreshed successfully');
        }, 100);
    }

    /**
     * Clear loaded products cache (useful for testing)
     */
    clearCache() {
        console.log('🗑️ Clearing hover cache...');
        this.loadedProducts.clear();
        this.loadingProducts.clear();
        console.log('✅ Product hover cache cleared');
    }
}

// Initialize when DOM is ready
let productHoverLoader;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        productHoverLoader = new ProductHoverLoader();
    });
} else {
    // DOM already loaded
    productHoverLoader = new ProductHoverLoader();
}

// Export for global access
window.ProductHoverLoader = ProductHoverLoader;
window.refreshProductHoverListeners = function() {
    console.log('🔄 refreshProductHoverListeners called');
    if (productHoverLoader) {
        productHoverLoader.refresh();
    } else {
        console.warn('⚠️ productHoverLoader not initialized yet, creating new instance...');
        productHoverLoader = new ProductHoverLoader();
    }
};

