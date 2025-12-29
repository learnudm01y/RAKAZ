/**
 * Shop Sidebar Lazy Loader
 * Loads shop sidebar content via AJAX after 1 second delay
 */
class ShopSidebarLoader {
    constructor() {
        this.sidebar = document.getElementById('shopSidebar');
        this.isLoaded = false;

        if (!this.sidebar) {
            console.warn('⚠️ Shop sidebar element not found');
            return;
        }

        this.init();
    }

    /**
     * Initialize the lazy loader
     */
    init() {
        console.log('🚀 Shop Sidebar Lazy Loader initialized');

        // Load sidebar after 1 second
        setTimeout(() => {
            this.loadSidebar();
        }, 1000);
    }

    /**
     * Load sidebar content via AJAX
     */
    async loadSidebar() {
        if (this.isLoaded) {
            console.log('✅ Sidebar already loaded');
            return;
        }

        console.log('⏳ Loading shop sidebar content...');

        try {
            const response = await fetch('/api/lazy-load/shop-sidebar', {
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
                this.updateSidebar(data.html);
                this.isLoaded = true;
                console.log('✅ Shop sidebar loaded successfully');
            } else {
                console.error('❌ Invalid response data:', data);
                this.showError();
            }
        } catch (error) {
            console.error('❌ Error loading sidebar:', error);
            this.showError();
        }
    }

    /**
     * Update sidebar content
     */
    updateSidebar(html) {
        // Remove skeleton-loading class
        this.sidebar.classList.remove('skeleton-loading');

        // Update content with fade effect
        this.sidebar.style.opacity = '0';

        setTimeout(() => {
            this.sidebar.innerHTML = html;

            // Fade in
            this.sidebar.style.transition = 'opacity 0.3s ease';
            this.sidebar.style.opacity = '1';

            // Reinitialize sidebar scripts
            this.reinitializeSidebarScripts();

            // Dispatch custom event to notify that sidebar is loaded
            const event = new CustomEvent('sidebarLoaded', { detail: { sidebar: this.sidebar } });
            document.dispatchEvent(event);
            console.log('📢 sidebarLoaded event dispatched');
        }, 150);
    }

    /**
     * Show error state
     */
    showError() {
        this.sidebar.classList.remove('skeleton-loading');
        this.sidebar.innerHTML = `
            <div class="sidebar-error">
                <p style="text-align: center; padding: 20px; color: #999;">
                    ${window.isArabic ? 'حدث خطأ أثناء تحميل الفلاتر' : 'Error loading filters'}
                </p>
            </div>
        `;
    }

    /**
     * Reinitialize sidebar event listeners and scripts
     */
    reinitializeSidebarScripts() {
        // Reinitialize close button
        this.reinitializeCloseButton();

        // Reinitialize "Show More Categories" button
        this.reinitializeShowMoreCategories();

        // Reinitialize "Show More Brands" button
        this.reinitializeShowMoreBrands();

        // Reinitialize custom selects
        this.reinitializeCustomSelects();

        // Reinitialize filter checkboxes
        this.reinitializeFilterCheckboxes();

        // Reinitialize apply filters button
        this.reinitializeApplyFiltersBtn();

        // Reinitialize clear filters button
        this.reinitializeClearFiltersBtn();

        // Restore selections from URL
        this.restoreSelectionsFromURL();

        console.log('🔄 Sidebar scripts reinitialized');
    }

    /**
     * Reinitialize close button
     */
    reinitializeCloseButton() {
        const closeBtn = document.getElementById('sidebarClose');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.sidebar.classList.remove('active');
            });
        }
    }

    /**
     * Initialize "Show More Categories" functionality
     */
    reinitializeShowMoreCategories() {
        const btn = document.getElementById('showMoreCategories');
        if (!btn) return;

        btn.addEventListener('click', function() {
            if (btn.classList.contains('loading')) return;

            const loaded = parseInt(btn.dataset.loaded);
            const total = parseInt(btn.dataset.total);

            btn.classList.add('loading');
            btn.disabled = true;

            fetch(`/shop/load-more-categories?skip=${loaded}&take=10`)
                .then(response => response.json())
                .then(data => {
                    const categoriesList = document.getElementById('categoriesCheckboxList');
                    categoriesList.insertAdjacentHTML('beforeend', data.html);

                    const newLoaded = loaded + data.count;
                    btn.dataset.loaded = newLoaded;

                    if (newLoaded >= total) {
                        btn.remove();
                    } else {
                        btn.classList.remove('loading');
                        btn.disabled = false;
                    }

                    // Reinitialize checkboxes for new items
                    document.dispatchEvent(new CustomEvent('categoriesUpdated'));
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.classList.remove('loading');
                    btn.disabled = false;
                });
        });
    }

    /**
     * Initialize "Show More Brands" functionality
     */
    reinitializeShowMoreBrands() {
        const btn = document.getElementById('showMoreBrands');
        if (!btn) return;

        btn.addEventListener('click', function() {
            if (btn.classList.contains('loading')) return;

            const loaded = parseInt(btn.dataset.loaded);
            const total = parseInt(btn.dataset.total);

            btn.classList.add('loading');
            btn.disabled = true;

            fetch(`/shop/load-more-brands?skip=${loaded}&take=10`)
                .then(response => response.json())
                .then(data => {
                    const brandsList = document.getElementById('brandsCheckboxList');
                    brandsList.insertAdjacentHTML('beforeend', data.html);

                    const newLoaded = loaded + data.count;
                    btn.dataset.loaded = newLoaded;

                    if (newLoaded >= total) {
                        btn.remove();
                    } else {
                        btn.classList.remove('loading');
                        btn.disabled = false;
                    }

                    // Reinitialize checkboxes for new items
                    document.dispatchEvent(new CustomEvent('brandsUpdated'));
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.classList.remove('loading');
                    btn.disabled = false;
                });
        });
    }

    /**
     * Reinitialize custom select dropdowns
     */
    reinitializeCustomSelects() {
        const customSelects = this.sidebar.querySelectorAll('.custom-select');
        if (typeof CustomSelect !== 'undefined') {
            customSelects.forEach(select => {
                if (!select.parentElement.classList.contains('custom-select-wrapper')) {
                    new CustomSelect(select);
                }
            });
        }
    }

    /**
     * Reinitialize filter checkboxes with proper event handling
     */
    reinitializeFilterCheckboxes() {
        const self = this;

        // Size checkboxes
        this.sidebar.querySelectorAll('input[name="size"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                self.updateClearFiltersVisibility();
            });
        });

        // Shoe size checkboxes
        this.sidebar.querySelectorAll('input[name="shoe-size"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                self.updateClearFiltersVisibility();
            });
        });

        // Color checkboxes
        this.sidebar.querySelectorAll('input[name="color"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                self.updateClearFiltersVisibility();
            });
        });

        // Category checkboxes
        this.sidebar.querySelectorAll('input[name="category"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                self.updateClearFiltersVisibility();
                // Update shop title if function exists
                if (typeof updateShopTitle === 'function') {
                    updateShopTitle();
                }
                // Save to localStorage if function exists
                if (typeof saveCategorySelections === 'function') {
                    saveCategorySelections();
                }
            });
        });

        // Brand checkboxes
        this.sidebar.querySelectorAll('input[name="brand"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                self.updateClearFiltersVisibility();
                // Update shop title if function exists
                if (typeof updateShopTitle === 'function') {
                    updateShopTitle();
                }
                // Save to localStorage if function exists
                if (typeof saveBrandSelections === 'function') {
                    saveBrandSelections();
                }
            });
        });
    }

    /**
     * Update clear filters button visibility
     */
    updateClearFiltersVisibility() {
        const anyChecked = this.sidebar.querySelector('input[type="checkbox"]:checked');
        const clearBtn = document.getElementById('clearFiltersBtn');
        if (clearBtn) {
            clearBtn.style.display = anyChecked ? 'inline-flex' : 'none';
        }
    }

    /**
     * Reinitialize apply filters button
     */
    reinitializeApplyFiltersBtn() {
        const applyBtn = this.sidebar.querySelector('.apply-filters-btn');
        if (!applyBtn) return;

        applyBtn.addEventListener('click', () => {
            const url = new URL(window.location.href);

            // Clear previous filter params
            url.searchParams.delete('sizes[]');
            url.searchParams.delete('shoe_sizes[]');
            url.searchParams.delete('colors[]');
            url.searchParams.delete('categories[]');
            url.searchParams.delete('brands[]');
            url.searchParams.delete('min_price');
            url.searchParams.delete('max_price');

            // Get selected sizes
            this.sidebar.querySelectorAll('input[name="size"]:checked').forEach(input => {
                url.searchParams.append('sizes[]', input.value);
            });

            // Get selected shoe sizes
            this.sidebar.querySelectorAll('input[name="shoe-size"]:checked').forEach(input => {
                url.searchParams.append('shoe_sizes[]', input.value);
            });

            // Get selected colors
            this.sidebar.querySelectorAll('input[name="color"]:checked').forEach(input => {
                url.searchParams.append('colors[]', input.value);
            });

            // Get selected categories
            this.sidebar.querySelectorAll('input[name="category"]:checked').forEach(input => {
                url.searchParams.append('categories[]', input.value);
            });

            // Get selected brands
            this.sidebar.querySelectorAll('input[name="brand"]:checked').forEach(input => {
                url.searchParams.append('brands[]', input.value);
            });

            // Get price range
            const minPrice = document.getElementById('minPrice');
            const maxPrice = document.getElementById('maxPrice');

            if (minPrice && minPrice.value) {
                url.searchParams.set('min_price', minPrice.value);
            }
            if (maxPrice && maxPrice.value) {
                url.searchParams.set('max_price', maxPrice.value);
            }

            // Redirect to filtered URL
            window.location.href = url.toString();
        });
    }

    /**
     * Reinitialize clear filters button
     */
    reinitializeClearFiltersBtn() {
        const clearBtn = document.getElementById('clearFiltersBtn');
        if (!clearBtn) return;

        clearBtn.addEventListener('click', () => {
            // Uncheck all checkboxes
            this.sidebar.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
                checkbox.checked = false;
            });

            // Clear price inputs
            const minPrice = document.getElementById('minPrice');
            const maxPrice = document.getElementById('maxPrice');
            if (minPrice) minPrice.value = '';
            if (maxPrice) maxPrice.value = '';

            // Hide clear button
            clearBtn.style.display = 'none';

            // Clear localStorage
            localStorage.removeItem('shop_selected_categories');
            localStorage.removeItem('shop_category_names');
            localStorage.removeItem('shop_selected_brands');
            localStorage.removeItem('shop_brand_names');

            // Redirect to clean shop URL
            window.location.href = '/shop';
        });
    }

    /**
     * Restore filter selections from URL parameters
     */
    restoreSelectionsFromURL() {
        const urlParams = new URLSearchParams(window.location.search);

        // Restore sizes
        const sizes = urlParams.getAll('sizes[]');
        sizes.forEach(size => {
            const checkbox = this.sidebar.querySelector(`input[name="size"][value="${size}"]`);
            if (checkbox) checkbox.checked = true;
        });

        // Restore shoe sizes
        const shoeSizes = urlParams.getAll('shoe_sizes[]');
        shoeSizes.forEach(size => {
            const checkbox = this.sidebar.querySelector(`input[name="shoe-size"][value="${size}"]`);
            if (checkbox) checkbox.checked = true;
        });

        // Restore colors
        const colors = urlParams.getAll('colors[]');
        colors.forEach(color => {
            const checkbox = this.sidebar.querySelector(`input[name="color"][value="${color}"]`);
            if (checkbox) checkbox.checked = true;
        });

        // Restore categories
        const categories = urlParams.getAll('categories[]');
        categories.forEach(category => {
            const checkbox = this.sidebar.querySelector(`input[name="category"][value="${category}"]`);
            if (checkbox) checkbox.checked = true;
        });

        // Restore brands
        const brands = urlParams.getAll('brands[]');
        brands.forEach(brand => {
            const checkbox = this.sidebar.querySelector(`input[name="brand"][value="${brand}"]`);
            if (checkbox) checkbox.checked = true;
        });

        // Restore price range
        const minPrice = urlParams.get('min_price');
        const maxPrice = urlParams.get('max_price');

        if (minPrice) {
            const minPriceInput = document.getElementById('minPrice');
            if (minPriceInput) minPriceInput.value = minPrice;
        }

        if (maxPrice) {
            const maxPriceInput = document.getElementById('maxPrice');
            if (maxPriceInput) maxPriceInput.value = maxPrice;
        }

        // Update clear button visibility
        this.updateClearFiltersVisibility();

        // Update shop title if function exists
        if (typeof updateShopTitle === 'function') {
            updateShopTitle();
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    new ShopSidebarLoader();
});

// Export for potential external use
window.ShopSidebarLoader = ShopSidebarLoader;
