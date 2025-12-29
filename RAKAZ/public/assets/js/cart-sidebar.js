// ===================================
// Cart Sidebar Functionality
// ===================================

(function() {
    'use strict';

    // Check if already defined globally, otherwise define locally
    const __isArabic = window.__isArabic ||
        document.documentElement.getAttribute('dir') === 'rtl' ||
        (document.documentElement.getAttribute('lang') || '').toLowerCase().startsWith('ar');

    const __t = window.__t || ((ar, en) => (__isArabic ? ar : en));

class CartSidebar {
    constructor() {
        this.cart = [];
        this.sidebar = document.getElementById('cartSidebar');
        this.overlay = document.getElementById('cartOverlay');
        this.closeBtn = document.getElementById('cartClose');
        this.toggleBtn = document.getElementById('cartToggle');
        this.cartItemsContainer = document.getElementById('cartItems');
        this.cartEmpty = document.getElementById('cartEmpty');
        this.cartFooter = document.getElementById('cartFooter');
        this.cartBadge = document.getElementById('cartBadge');
        this.cartSubtotal = document.getElementById('cartSubtotal');

        this.init();
    }

    init() {
        // Event Listeners
        this.toggleBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            // Open cart immediately with skeleton
            this.openCart();
            this.showSkeleton();
            // Load data in background
            this.loadCartFromServer();
        });

        this.closeBtn?.addEventListener('click', () => this.closeCart());
        this.overlay?.addEventListener('click', () => this.closeCart());

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.sidebar.classList.contains('active')) {
                this.closeCart();
            }
        });

        // Load cart count only on init (lightweight)
        this.loadCartCount();
    }

    showSkeleton() {
        // Clear existing items
        this.cartItemsContainer.querySelectorAll('.cart-item').forEach(item => item.remove());
        this.cartEmpty.style.display = 'none';
        this.cartFooter.classList.remove('show');

        // Show 3 skeleton items
        for (let i = 0; i < 3; i++) {
            const skeleton = this.createSkeletonItem();
            this.cartItemsContainer.appendChild(skeleton);
        }
    }

    createSkeletonItem() {
        const skeleton = document.createElement('div');
        skeleton.className = 'cart-item cart-item-skeleton';
        skeleton.innerHTML = `
            <div class="skeleton-image"></div>
            <div class="cart-item-details">
                <div class="skeleton-text skeleton-brand"></div>
                <div class="skeleton-text skeleton-name"></div>
                <div class="skeleton-text skeleton-size"></div>
                <div class="skeleton-text skeleton-price"></div>
            </div>
        `;
        return skeleton;
    }

    async loadCartCount() {
        try {
            const response = await fetch('/api/cart/count', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (this.cartBadge) {
                    this.cartBadge.textContent = data.count || 0;
                }
            }
        } catch (error) {
            console.error('Error loading cart count:', error);
        }
    }

    async loadCartFromServer() {
        try {
            const response = await fetch('/api/cart', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.cart = data.items || [];
                // Remove skeleton and show real data
                this.cartItemsContainer.querySelectorAll('.cart-item-skeleton').forEach(item => item.remove());
                this.updateCartDisplay();
                console.log('Cart loaded:', this.cart.length, 'items');
            }
        } catch (error) {
            console.error('Error loading cart:', error);
            // Remove skeleton on error
            this.cartItemsContainer.querySelectorAll('.cart-item-skeleton').forEach(item => item.remove());
        }
    }

    attachProductListeners() {
        // Attach listeners to all size option buttons
        const sizeOptions = document.querySelectorAll('.size-option');
        sizeOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const size = option.getAttribute('data-size');
                const productCard = option.closest('.product-card');

                if (productCard) {
                    this.addToCart(productCard, size);
                }
            });
        });
    }

    addToCart(productCard, size) {
        // Note: This method is for legacy support only
        // Modern implementation uses AJAX cart.add endpoint
        console.log('addToCart called - please use the AJAX endpoint instead');
    }

    async removeFromCart(productId) {
        try {
            const response = await fetch(`/cart/${productId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    // Reload cart from server
                    await this.loadCartFromServer();

                    // Update cart count badge
                    if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }

                    // Show notification
                    this.showNotification(
                        __t('تم الحذف', 'Removed'),
                        __isArabic ? (data.message || 'تم إزالة المنتج من السلة') : 'Removed from bag.'
                    );
                }
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
            this.showNotification(__t('خطأ', 'Error'), __t('حدث خطأ أثناء حذف المنتج', 'Something went wrong while removing the item.'));
        }
    }

    async updateQuantity(productId, change) {
        const item = this.cart.find(item => item.id === productId);
        if (!item) return;

        const newQuantity = item.quantity + change;

        if (newQuantity <= 0) {
            await this.removeFromCart(productId);
            return;
        }

        try {
            const response = await fetch(`/cart/${productId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    quantity: newQuantity
                })
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    // Reload cart from server
                    await this.loadCartFromServer();

                    // Update cart count badge
                    if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }
                }
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
            this.showNotification(__t('خطأ', 'Error'), __t('حدث خطأ أثناء تحديث الكمية', 'Something went wrong while updating quantity.'));
        }
    }

    updateCartDisplay() {
        // Update badge
        const totalItems = this.cart.reduce((sum, item) => sum + item.quantity, 0);
        if (this.cartBadge) {
            this.cartBadge.textContent = totalItems;
        }

        // Update cart items display
        if (this.cart.length === 0) {
            this.cartEmpty.style.display = 'flex';
            this.cartFooter.classList.remove('show');
            this.cartItemsContainer.querySelectorAll('.cart-item').forEach(item => item.remove());
        } else {
            this.cartEmpty.style.display = 'none';
            this.cartFooter.classList.add('show');
            this.renderCartItems();
            this.updateSubtotal();

            // Enhance images after rendering
            setTimeout(() => this.enhanceCartImages(), 100);
        }
    }

    renderCartItems() {
        // Remove existing cart items
        this.cartItemsContainer.querySelectorAll('.cart-item').forEach(item => item.remove());

        // Add cart items
        this.cart.forEach(item => {
            const cartItem = this.createCartItemElement(item);
            this.cartItemsContainer.appendChild(cartItem);
        });
    }

    createCartItemElement(item) {
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="cart-item-image">
            <div class="cart-item-details">
                <p class="product-brand">${item.brand}</p>
                <h4 class="cart-item-name">${item.name}</h4>
                <p class="cart-item-size">${__t('المقاس', 'Size')}: ${item.size}</p>
                <p class="cart-item-price">${item.price}</p>
                <div class="cart-item-actions">
                    <div class="cart-item-quantity">
                        <button class="quantity-decrease" data-id="${item.id}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </button>
                        <span>${item.quantity}</span>
                        <button class="quantity-increase" data-id="${item.id}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </button>
                    </div>
                    <button class="cart-item-remove" data-id="${item.id}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        // Add event listeners
        cartItem.querySelector('.quantity-decrease')?.addEventListener('click', () => {
            this.updateQuantity(item.id, -1);
        });

        cartItem.querySelector('.quantity-increase')?.addEventListener('click', () => {
            this.updateQuantity(item.id, 1);
        });

        cartItem.querySelector('.cart-item-remove')?.addEventListener('click', () => {
            this.removeFromCart(item.id);
        });

        return cartItem;
    }

    updateSubtotal() {
        let total = 0;
        this.cart.forEach(item => {
            // Extract numeric value from price string
            const priceMatch = item.price.match(/[\d,]+/);
            if (priceMatch) {
                const price = parseFloat(priceMatch[0].replace(/,/g, ''));
                total += price * item.quantity;
            }
        });

        if (this.cartSubtotal) {
            this.cartSubtotal.textContent = `${total.toLocaleString(__isArabic ? 'ar-AE' : 'en-US')} ${__t('درهم', 'AED')}`;
        }
    }

    openCart() {
        this.sidebar?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeCart() {
        this.sidebar?.classList.remove('active');
        document.body.style.overflow = '';
    }

    showNotification(title, message) {
        // Using SweetAlert2 for notifications
        if (typeof Swal !== 'undefined') {
            // Position based on language direction
            const isRTL = document.documentElement.dir === 'rtl' || document.body.dir === 'rtl';
            const position = isRTL ? 'top-start' : 'top-end';

            Swal.fire({
                title: title,
                html: message,
                icon: 'success',
                confirmButtonText: __t('حسناً', 'OK'),
                confirmButtonColor: '#000',
                timer: 2000,
                timerProgressBar: true,
                toast: true,
                position: position,
                showConfirmButton: false
            });
        }
    }

    async clearCart() {
        try {
            const response = await fetch('/cart', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    // Reload cart from server
                    await this.loadCartFromServer();

                    // Update cart count badge
                    if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }

                    this.showNotification(__t('تم التفريغ', 'Cleared'), __isArabic ? (data.message || 'تم تفريغ السلة') : 'Cart cleared.');
                }
            }
        } catch (error) {
            console.error('Error clearing cart:', error);
            this.showNotification(__t('خطأ', 'Error'), __t('حدث خطأ أثناء تفريغ السلة', 'Something went wrong while clearing the cart.'));
        }
    }

    getCart() {
        return this.cart;
    }

    getCartCount() {
        return this.cart.reduce((sum, item) => sum + item.quantity, 0);
    }

    getCartTotal() {
        let total = 0;
        this.cart.forEach(item => {
            const priceMatch = item.price.match(/[\d,]+/);
            if (priceMatch) {
                const price = parseFloat(priceMatch[0].replace(/,/g, ''));
                total += price * item.quantity;
            }
        });
        return total;
    }

    // Pica Image Enhancement for Cart Sidebar
    enhanceCartImages() {
        if (typeof pica === 'undefined') {
            console.log('⚠️ Pica not loaded for cart sidebar');
            return;
        }

        const picaInstance = pica();
        const cartImages = this.cartItemsContainer.querySelectorAll('.cart-item-image');

        console.log('🛒 Processing', cartImages.length, 'cart images');

        cartImages.forEach(img => {
            if (img.dataset.picaProcessed || img.dataset.picaProcessing) return;
            img.dataset.picaProcessing = 'true';

            if (!img.complete || img.naturalWidth === 0) {
                img.addEventListener('load', () => this.processCartImage(img, picaInstance), { once: true });
                return;
            }

            this.processCartImage(img, picaInstance);
        });
    }

    processCartImage(img, picaInstance) {
        try {
            const originalSrc = img.src;
            const canvas = document.createElement('canvas');
            const naturalWidth = img.naturalWidth;
            const naturalHeight = img.naturalHeight;

            // Upscale by 4x for maximum smoothness
            canvas.width = naturalWidth * 4;
            canvas.height = naturalHeight * 4;

            picaInstance.resize(img, canvas, {
                unsharpAmount: 500,
                unsharpRadius: 2.0,
                unsharpThreshold: 0,
                quality: 3,
                alpha: true,
                filter: 'lanczos3'
            })
            .then(() => picaInstance.toBlob(canvas, 'image/jpeg', 0.98))
            .then(blob => {
                const url = URL.createObjectURL(blob);
                img.src = url;
                img.dataset.picaProcessed = 'true';
                delete img.dataset.picaProcessing;

                img.addEventListener('load', () => URL.revokeObjectURL(url), { once: true });
            })
            .catch(err => {
                console.error('Pica cart image error:', err);
                img.src = originalSrc;
                delete img.dataset.picaProcessing;
            });
        } catch (err) {
            console.error('Pica cart setup error:', err);
            delete img.dataset.picaProcessing;
        }
    }
}

// Initialize Cart Sidebar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.cartSidebarInstance = new CartSidebar();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartSidebar;
}

})(); // End IIFE
