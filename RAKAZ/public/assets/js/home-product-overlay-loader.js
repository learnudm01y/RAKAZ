/**
 * Home Product Overlay Lazy Loader
 * Loads overlay content (secondary image + info section) on hover/touch for homepage products
 */
class HomeProductOverlayLoader {
    constructor() {
        this.loadedProducts = new Set();
        this.loadingProducts = new Set();
        this.productCards = [];
        this.eventListeners = new Map();
        this.isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

        this.init();
    }

    init() {
        console.log('🏠 Home Product Overlay Loader initialized (Touch:', this.isTouchDevice, ')');
        this.attachHoverListeners();
    }

    attachHoverListeners() {
        console.log('🔗 Attaching home product hover/touch listeners...');

        // Clean up old listeners
        this.cleanupListeners();

        // Get current product IDs
        const currentProductIds = new Set();

        // Select product cards from featured and perfect gift sections
        this.productCards = document.querySelectorAll('.must-have-section .product-card, .perfect-gift-section .product-card');

        this.productCards.forEach(card => {
            const productId = card.dataset.productId;

            if (!productId) return;

            currentProductIds.add(productId);

            // Create hover handler
            const hoverHandler = () => {
                this.handleCardHover(productId, card);
            };

            // Create touch handler
            const touchHandler = (e) => {
                // Preload on first touch
                this.handleCardHover(productId, card);
            };

            // Store handlers for cleanup
            this.eventListeners.set(card, { hover: hoverHandler, touch: touchHandler });

            // Add mouse listener for desktop
            card.addEventListener('mouseenter', hoverHandler);

            // Add touch listener for mobile - preload on touchstart
            card.addEventListener('touchstart', touchHandler, { passive: true });

            // Prevent image download on long press
            const images = card.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('contextmenu', (e) => e.preventDefault());
                img.style.webkitUserSelect = 'none';
                img.style.userSelect = 'none';
                img.style.webkitTouchCallout = 'none';
                img.setAttribute('draggable', 'false');
            });
        });

        // Clean up loaded products cache
        const loadedProductsArray = Array.from(this.loadedProducts);
        loadedProductsArray.forEach(productId => {
            if (!currentProductIds.has(productId)) {
                this.loadedProducts.delete(productId);
                console.log(`🗑️ Removed product ${productId} from home cache`);
            }
        });

        console.log(`👀 Attached hover/touch listeners to ${this.productCards.length} home product cards`);
        console.log(`💾 Home cached products: ${this.loadedProducts.size}`);
    }

    cleanupListeners() {
        this.eventListeners.forEach((handlers, card) => {
            if (handlers.hover) card.removeEventListener('mouseenter', handlers.hover);
            if (handlers.touch) card.removeEventListener('touchstart', handlers.touch);
        });
        this.eventListeners.clear();
    }

    async handleCardHover(productId, card) {
        const skeletonElement = card.querySelector('.overlay-info-skeleton');

        // Check if skeleton still exists
        if (!skeletonElement || !skeletonElement.isConnected) {
            this.loadedProducts.delete(productId);
            this.loadingProducts.delete(productId);
            return;
        }

        // Check if already loaded
        if (this.loadedProducts.has(productId)) {
            return;
        }

        // Check if loading
        if (this.loadingProducts.has(productId)) {
            return;
        }

        console.log(`🎯 Home hover detected on product ${productId}`);

        this.loadingProducts.add(productId);

        try {
            await this.loadOverlayContent(productId, card);
        } catch (error) {
            console.error(`❌ Failed to load home overlay for product ${productId}:`, error);
            this.loadingProducts.delete(productId);
        }
    }

    async loadOverlayContent(productId, card) {
        try {
            console.log(`📡 Fetching home overlay for product ${productId}...`);

            // Get current locale
            const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
            const locale = isRtl ? 'ar' : 'en';

            const response = await fetch(`/api/lazy-load/home-product-overlay/${productId}?locale=${locale}`, {
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

            if (data.success && data.overlayHtml) {
                this.updateOverlayContent(productId, card, data.overlayHtml, data.secondaryImage);
                this.loadedProducts.add(productId);
                this.loadingProducts.delete(productId);
                console.log(`✅ Home overlay loaded for product ${productId}`);
            } else {
                throw new Error('Invalid response data');
            }
        } catch (error) {
            console.error(`❌ Error loading home overlay for product ${productId}:`, error);
            this.loadingProducts.delete(productId);
        }
    }

    updateOverlayContent(productId, card, overlayHtml, secondaryImage) {
        // Check if card still exists
        if (!card || !card.isConnected) {
            this.loadedProducts.delete(productId);
            return;
        }

        const skeletonElement = card.querySelector('.overlay-info-skeleton');
        const overlayInfoSection = card.querySelector('.overlay-info-section');

        if (!skeletonElement || !overlayInfoSection) {
            console.warn(`⚠️ Elements not found for product ${productId}`);
            return;
        }

        // Update overlay info section
        overlayInfoSection.innerHTML = overlayHtml;
        overlayInfoSection.style.display = '';

        // Hide skeleton
        skeletonElement.style.display = 'none';

        // Add secondary image to overlay image section
        if (secondaryImage) {
            const overlayImageSection = card.querySelector('.overlay-image-section');
            const primaryImage = overlayImageSection?.querySelector('.overlay-image-primary');

            if (overlayImageSection && primaryImage && !overlayImageSection.querySelector('.overlay-image-secondary')) {
                const secondaryImg = document.createElement('img');
                secondaryImg.src = secondaryImage;
                secondaryImg.alt = primaryImage.alt || '';
                secondaryImg.className = 'overlay-main-image overlay-image-secondary';
                secondaryImg.loading = 'lazy';

                // Insert after primary image
                primaryImage.after(secondaryImg);
                console.log(`🖼️ Secondary image added for home product ${productId}`);
            }
        }

        // Reinitialize scripts for overlay
        this.reinitializeOverlayScripts(card);
    }

    reinitializeOverlayScripts(card) {
        // Reinitialize gallery navigation
        const galleryPrevBtn = card.querySelector('.prev-gallery');
        const galleryNextBtn = card.querySelector('.next-gallery');

        if (galleryPrevBtn && galleryNextBtn) {
            this.initializeGalleryNavigation(card);
        }

        // Reinitialize sizes navigation
        const sizesPrevBtn = card.querySelector('.prev-sizes');
        const sizesNextBtn = card.querySelector('.next-sizes');

        if (sizesPrevBtn && sizesNextBtn) {
            this.initializeSizesNavigation(card);
        }
    }

    initializeGalleryNavigation(card) {
        const galleryContainer = card.querySelector('.overlay-gallery-images');
        const prevBtn = card.querySelector('.prev-gallery');
        const nextBtn = card.querySelector('.next-gallery');

        if (!galleryContainer || !prevBtn || !nextBtn) return;

        const scrollAmount = 100;

        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            galleryContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            galleryContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
    }

    initializeSizesNavigation(card) {
        const sizesContainer = card.querySelector('.overlay-sizes-section');
        const prevBtn = card.querySelector('.prev-sizes');
        const nextBtn = card.querySelector('.next-sizes');

        if (!sizesContainer || !prevBtn || !nextBtn) return;

        const scrollAmount = 80;

        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            sizesContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            sizesContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
    }

    refresh() {
        console.log('🔄 Refreshing home product overlay listeners...');
        this.loadingProducts.clear();

        setTimeout(() => {
            this.attachHoverListeners();
            console.log('✅ Home overlay listeners refreshed');
        }, 100);
    }

    clearCache() {
        console.log('🗑️ Clearing home overlay cache...');
        this.loadedProducts.clear();
        this.loadingProducts.clear();
        console.log('✅ Home overlay cache cleared');
    }
}

// Initialize when DOM is ready
let homeProductOverlayLoader;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        // Only initialize if we're on homepage with featured/perfect gift sections
        if (document.querySelector('.must-have-section, .perfect-gift-section')) {
            homeProductOverlayLoader = new HomeProductOverlayLoader();
        }
    });
} else {
    // DOM already loaded
    if (document.querySelector('.must-have-section, .perfect-gift-section')) {
        homeProductOverlayLoader = new HomeProductOverlayLoader();
    }
}

// Export for global access
window.HomeProductOverlayLoader = HomeProductOverlayLoader;
window.refreshHomeProductOverlayListeners = function() {
    if (homeProductOverlayLoader) {
        homeProductOverlayLoader.refresh();
    } else if (document.querySelector('.must-have-section, .perfect-gift-section')) {
        homeProductOverlayLoader = new HomeProductOverlayLoader();
    }
};
