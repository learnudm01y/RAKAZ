// ===================================
// Cart Sidebar Functionality
// ===================================

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
            this.loadCartFromServer().then(() => {
                this.openCart();
            });
        });

        this.closeBtn?.addEventListener('click', () => this.closeCart());
        this.overlay?.addEventListener('click', () => this.closeCart());

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.sidebar.classList.contains('active')) {
                this.closeCart();
            }
        });

        // Load cart on init
        this.loadCartFromServer();
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
                this.updateCartDisplay();
                console.log('Cart loaded:', this.cart.length, 'items');
            }
        } catch (error) {
            console.error('Error loading cart:', error);
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
                    this.showNotification('تم الحذف', data.message || 'تم إزالة المنتج من السلة');
                }
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
            this.showNotification('خطأ', 'حدث خطأ أثناء حذف المنتج');
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
            this.showNotification('خطأ', 'حدث خطأ أثناء تحديث الكمية');
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
                <p class="cart-item-brand">${item.brand}</p>
                <h4 class="cart-item-name">${item.name}</h4>
                <p class="cart-item-size">المقاس: ${item.size}</p>
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
            this.cartSubtotal.textContent = `${total.toLocaleString('ar-AE')} درهم`;
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
            Swal.fire({
                title: title,
                html: message,
                icon: 'success',
                confirmButtonText: 'حسناً',
                confirmButtonColor: '#000',
                timer: 2000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
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

                    this.showNotification('تم التفريغ', data.message || 'تم تفريغ السلة');
                }
            }
        } catch (error) {
            console.error('Error clearing cart:', error);
            this.showNotification('خطأ', 'حدث خطأ أثناء تفريغ السلة');
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
}

// Initialize Cart Sidebar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.cartSidebarInstance = new CartSidebar();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartSidebar;
}
