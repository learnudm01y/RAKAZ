// Shared Components JavaScript - للاستخدام في جميع صفحات الموقع

// Sidebar Menu Functionality
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize Sidebar
    const menuBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('sidebar-menu');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const sidebarClose = document.querySelector('.sidebar-close');
    
    if (menuBtn && sidebar && sidebarOverlay) {
        // Open sidebar
        menuBtn.addEventListener('click', function() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        // Close sidebar
        const closeSidebar = function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        };
        
        if (sidebarClose) {
            sidebarClose.addEventListener('click', closeSidebar);
        }
        
        sidebarOverlay.addEventListener('click', closeSidebar);
        
        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                closeSidebar();
            }
        });
    }
    
    // Custom Select Dropdown Enhancement
    const customSelects = document.querySelectorAll('.custom-select, .option-select, select.form-select');
    
    customSelects.forEach(select => {
        // Add focus/blur effects
        select.addEventListener('focus', function() {
            this.style.borderColor = '#333';
            this.style.boxShadow = 'none';
            this.style.backgroundColor = 'white';
        });
        
        select.addEventListener('blur', function() {
            this.style.borderColor = '#333';
        });
        
        // Prevent card movement on select open
        select.addEventListener('mousedown', function(e) {
            e.stopPropagation();
        });
        
        // تحسين تصميم العناصر المحددة - رمادي فقط
        select.addEventListener('change', function() {
            // إزالة أي تنسيق أزرق افتراضي
            const options = this.querySelectorAll('option');
            options.forEach(option => {
                if (option.selected) {
                    option.style.backgroundColor = '#f5f5f5';
                    option.style.color = '#333';
                    option.style.fontWeight = '500';
                } else {
                    option.style.backgroundColor = 'white';
                    option.style.color = '#333';
                    option.style.fontWeight = 'normal';
                }
            });
        });
        
        // تطبيق التنسيق عند التحميل
        const selectedOption = select.querySelector('option:checked');
        if (selectedOption) {
            selectedOption.style.backgroundColor = '#f5f5f5';
            selectedOption.style.color = '#333';
            selectedOption.style.fontWeight = '500';
        }
        
        // منع أي لون أزرق عند hover
        select.addEventListener('mouseover', function() {
            this.style.backgroundColor = 'white';
        });
    });
    
    // Toast Notification System
    window.showToast = function(message, duration = 3000) {
        // Remove existing toast if any
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Create new toast
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        
        // Hide and remove toast
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, duration);
    };
    
    // Add to Bag Button Handler
    const addToBagButtons = document.querySelectorAll('.add-to-bag-btn');
    
    addToBagButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Get product info
            const productCard = this.closest('.wishlist-item, .product-card');
            const productName = productCard ? 
                (productCard.querySelector('.wishlist-item-name, .product-name')?.textContent || 'المنتج') : 
                'المنتج';
            
            // Show loading state
            const originalText = this.textContent;
            this.textContent = 'جاري الإضافة...';
            this.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                this.textContent = originalText;
                this.disabled = false;
                
                // Show success message
                showToast(`تمت إضافة ${productName} إلى حقيبة التسوق`);
                
                // Update cart count if exists
                const cartBadge = document.querySelector('.header-link .badge');
                if (cartBadge) {
                    const currentCount = parseInt(cartBadge.textContent) || 0;
                    cartBadge.textContent = currentCount + 1;
                }
            }, 500);
        });
    });
    
    // Remove from Wishlist Handler
    const removeButtons = document.querySelectorAll('.remove-btn');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productCard = this.closest('.wishlist-item');
            if (!productCard) return;
            
            // Confirm removal
            if (confirm('هل تريد إزالة هذا المنتج من قائمة الأمنيات؟')) {
                // Fade out animation
                productCard.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                productCard.style.opacity = '0';
                productCard.style.transform = 'scale(0.9)';
                
                setTimeout(() => {
                    productCard.remove();
                    
                    // Update wishlist count
                    const remainingItems = document.querySelectorAll('.wishlist-item').length;
                    const wishlistTitle = document.querySelector('.wishlist-title');
                    const wishlistCount = document.querySelector('.wishlist-count');
                    const wishlistBadge = document.querySelector('.header-link .badge');
                    
                    if (wishlistTitle) {
                        wishlistTitle.textContent = `قائمة أمنياتي (${remainingItems})`;
                    }
                    
                    if (wishlistCount) {
                        if (remainingItems === 0) {
                            wishlistCount.textContent = 'قائمة الأمنيات فارغة';
                        } else if (remainingItems === 1) {
                            wishlistCount.textContent = 'لديك منتج واحد في قائمة الأمنيات';
                        } else {
                            wishlistCount.textContent = `لديك ${remainingItems} منتجات في قائمة الأمنيات`;
                        }
                    }
                    
                    if (wishlistBadge) {
                        wishlistBadge.textContent = remainingItems;
                    }
                    
                    // Show empty state if no items
                    if (remainingItems === 0) {
                        const emptyState = document.querySelector('.empty-wishlist');
                        const wishlistGrid = document.querySelector('.wishlist-grid');
                        if (emptyState && wishlistGrid) {
                            wishlistGrid.style.display = 'none';
                            emptyState.style.display = 'block';
                        }
                    }
                    
                    showToast('تم إزالة المنتج من قائمة الأمنيات');
                }, 300);
            }
        });
    });
    
    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '#!') {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // Image Lazy Loading
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
    
    // Prevent card movement when interacting with dropdowns
    document.querySelectorAll('.wishlist-item, .product-card').forEach(card => {
        const selects = card.querySelectorAll('select');
        const buttons = card.querySelectorAll('button');
        
        [...selects, ...buttons].forEach(element => {
            element.addEventListener('mousedown', function(e) {
                e.stopPropagation();
            });
            
            element.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    });
    
    // Form Validation Helper
    window.validateForm = function(formElement) {
        const inputs = formElement.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.style.borderColor = '#ff4444';
                
                // Reset border color on input
                input.addEventListener('input', function() {
                    this.style.borderColor = '#ddd';
                }, { once: true });
            }
        });
        
        return isValid;
    };
    
    // Number Input Formatting (for prices, quantities, etc.)
    window.formatNumber = function(number) {
        return new Intl.NumberFormat('ar-AE').format(number);
    };
    
    // Currency Formatting
    window.formatCurrency = function(amount, currency = 'AED') {
        return `${formatNumber(amount)} د.إ`;
    };
    
    // Initialize tooltips if any
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltipText = this.dataset.tooltip;
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = tooltipText;
            tooltip.style.cssText = `
                position: absolute;
                background: #333;
                color: white;
                padding: 8px 12px;
                border-radius: 4px;
                font-size: 12px;
                white-space: nowrap;
                z-index: 10000;
                pointer-events: none;
            `;
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.top = `${rect.top - tooltip.offsetHeight - 8}px`;
            tooltip.style.right = `${rect.right}px`;
            
            this.addEventListener('mouseleave', function() {
                tooltip.remove();
            }, { once: true });
        });
    });
    
});

// Export functions for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        showToast: window.showToast,
        validateForm: window.validateForm,
        formatNumber: window.formatNumber,
        formatCurrency: window.formatCurrency
    };
}
