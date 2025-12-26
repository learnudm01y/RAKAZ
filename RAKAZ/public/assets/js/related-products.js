// Related Products Section Navigation
(function() {
    'use strict';

    // Initialize related products functionality
    function initRelatedProducts() {
        console.log('🚀 Initializing Related Products Section...');

        // Handle overlay positioning for related products
        const productCards = document.querySelectorAll('.related-products-section .product-card');
        console.log('🔍 Related product cards found:', productCards.length);

        productCards.forEach(card => {
            const overlay = card.querySelector('.featured-product-overlay');
            if (!overlay) return;

            card.addEventListener('mouseenter', function() {
                console.log('🖱️ Mouse entered related product card, showing overlay');

                // Position overlay relative to card (not fixed)
                overlay.style.width = '100%';
                overlay.style.height = '100%';
                overlay.style.top = '0';
                overlay.style.left = '0';

                // Set first gallery image as active
                const firstGalleryLink = overlay.querySelector('.overlay-gallery-link');
                if (firstGalleryLink && !overlay.querySelector('.overlay-gallery-link.active')) {
                    firstGalleryLink.classList.add('active');
                }
            });

            // No need to update position on scroll for absolute positioned overlay
        });

        // Handle gallery navigation
        document.addEventListener('click', function(e) {
            // Check if clicking on gallery image in related products section
            const galleryLink = e.target.closest('.related-products-section .overlay-gallery-link');
            if (galleryLink) {
                // If gallery link has href attribute (color image link), allow navigation
                if (galleryLink.hasAttribute('href') && galleryLink.getAttribute('href') !== '#') {
                    console.log('🔗 Navigating to product with color:', galleryLink.getAttribute('data-color-id'));
                    return; // Allow default link behavior
                }

                console.log('🖼️ Related products gallery image clicked');
                e.preventDefault();
                e.stopPropagation();

                const imageIndex = galleryLink.getAttribute('data-image-index');
                const imageSrc = galleryLink.getAttribute('data-image-src');
                const overlay = galleryLink.closest('.featured-product-overlay');

                if (overlay && imageSrc) {
                    const allGalleryLinks = overlay.querySelectorAll('.overlay-gallery-link');
                    allGalleryLinks.forEach(link => link.classList.remove('active'));
                    galleryLink.classList.add('active');

                    const primaryImage = overlay.querySelector('.overlay-image-primary');
                    if (primaryImage) {
                        primaryImage.src = imageSrc;
                    }

                    const overlayId = overlay.getAttribute('data-overlay-for');
                    const productCard = document.querySelector(`.related-products-section .product-card[data-product-id="${overlayId}"] .main-product-image`);
                    if (productCard) {
                        productCard.src = imageSrc;
                    }

                    sessionStorage.setItem('selectedProductImage', imageIndex);

                    setTimeout(() => {
                        const productLink = overlay.querySelector('.overlay-link-wrapper');
                        if (productLink && productLink.href) {
                            const url = new URL(productLink.href);
                            url.searchParams.set('image', imageIndex);
                            window.location.href = url.toString();
                        }
                    }, 300);
                }
                return;
            }

            // Handle gallery navigation buttons
            let galleryNav = e.target.closest('.related-products-section .overlay-gallery-nav');
            const tagName = e.target.tagName.toLowerCase();

            if (!galleryNav && (tagName === 'svg' || tagName === 'path' || tagName === 'polyline')) {
                galleryNav = e.target.closest('.related-products-section button.overlay-gallery-nav');
                if (!galleryNav) {
                    let parent = e.target.parentElement;
                    while (parent && !parent.classList.contains('overlay-gallery-nav')) {
                        parent = parent.parentElement;
                    }
                    galleryNav = parent;
                }
            }

            if (!galleryNav && e.target.classList && e.target.classList.contains('overlay-gallery-nav')) {
                galleryNav = e.target;
            }

            if (galleryNav) {
                console.log('✅ Related products GALLERY BUTTON CLICKED!');
                e.stopPropagation();
                e.preventDefault();

                const section = galleryNav.closest('.overlay-gallery-section');
                const wrapper = section ? section.querySelector('.overlay-gallery-images') : null;

                if (!wrapper) {
                    console.error('❌ Gallery wrapper not found!');
                    return;
                }

                const direction = galleryNav.getAttribute('data-direction');
                const thumbWidth = 60;
                const gap = 8;
                const scrollAmount = thumbWidth + gap;
                const moveAmount = direction === 'prev' ? scrollAmount : -scrollAmount;

                wrapper.scrollBy({
                    left: moveAmount,
                    behavior: 'smooth'
                });
            }
        });

        // DIRECT EVENT LISTENERS for overlay gallery buttons
        function attachGalleryListeners() {
            const galleryButtons = document.querySelectorAll('.related-products-section .overlay-gallery-nav');

            galleryButtons.forEach((btn) => {
                btn.addEventListener('click', function(e) {
                    console.log('🎯 DIRECT GALLERY BUTTON CLICKED!');
                    e.stopPropagation();
                    e.preventDefault();

                    const section = this.closest('.overlay-gallery-section');
                    const wrapper = section ? section.querySelector('.overlay-gallery-images') : null;

                    if (!wrapper) return;

                    const direction = this.getAttribute('data-direction');
                    const thumbWidth = 60;
                    const gap = 8;
                    const scrollAmount = thumbWidth + gap;
                    const moveAmount = direction === 'prev' ? scrollAmount : -scrollAmount;

                    wrapper.scrollBy({
                        left: moveAmount,
                        behavior: 'smooth'
                    });
                }, true);
            });
        }

        attachGalleryListeners();

        document.querySelectorAll('.related-products-section .product-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                setTimeout(attachGalleryListeners, 100);
            });
        });

        // Handle sizes navigation
        document.addEventListener('click', function(e) {
            let sizesNav = e.target.closest('.related-products-section .overlay-sizes-nav');
            const tagName = e.target.tagName.toLowerCase();

            if (!sizesNav && (tagName === 'svg' || tagName === 'path' || tagName === 'polyline')) {
                sizesNav = e.target.closest('.related-products-section button.overlay-sizes-nav');
                if (!sizesNav) {
                    let parent = e.target.parentElement;
                    while (parent && !parent.classList.contains('overlay-sizes-nav')) {
                        parent = parent.parentElement;
                    }
                    sizesNav = parent;
                }
            }

            if (!sizesNav && e.target.classList && e.target.classList.contains('overlay-sizes-nav')) {
                sizesNav = e.target;
            }

            if (sizesNav) {
                console.log('✅ Related products SIZES BUTTON CLICKED!');
                e.stopPropagation();
                e.preventDefault();

                const wrapper = sizesNav.closest('.overlay-sizes-wrapper');
                const section = wrapper ? wrapper.querySelector('.overlay-sizes-section') : null;

                if (!section) return;

                const direction = sizesNav.getAttribute('data-direction');
                const sizeWidth = 28;
                const gap = 8;
                const scrollAmount = sizeWidth + gap;
                const moveAmount = direction === 'prev' ? scrollAmount : -scrollAmount;

                section.scrollBy({
                    left: moveAmount,
                    behavior: 'smooth'
                });
            }
        });

        // DIRECT EVENT LISTENERS for overlay sizes buttons
        function attachSizesListeners() {
            const sizesButtons = document.querySelectorAll('.related-products-section .overlay-sizes-nav');

            sizesButtons.forEach((btn) => {
                btn.addEventListener('click', function(e) {
                    console.log('🎯 DIRECT SIZES BUTTON CLICKED!');
                    e.stopPropagation();
                    e.preventDefault();

                    const wrapper = this.closest('.overlay-sizes-wrapper');
                    const section = wrapper ? wrapper.querySelector('.overlay-sizes-section') : null;

                    if (!section) return;

                    const direction = this.getAttribute('data-direction');
                    const sizeWidth = 28;
                    const gap = 8;
                    const scrollAmount = sizeWidth + gap;
                    const moveAmount = direction === 'prev' ? scrollAmount : -scrollAmount;

                    section.scrollBy({
                        left: moveAmount,
                        behavior: 'smooth'
                    });
                }, true);
            });
        }

        attachSizesListeners();

        document.querySelectorAll('.related-products-section .product-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                setTimeout(attachSizesListeners, 100);
            });
        });

        // Handle main slider navigation for ALL related products sections
        const relatedSections = document.querySelectorAll('.related-products-section');

        relatedSections.forEach(section => {
            const sliderContainer = section.querySelector('.products-container');

            if (sliderContainer) {
                const prevSliderBtn = section.querySelector('.slider-btn.prev');
                const nextSliderBtn = section.querySelector('.slider-btn.next');

                if (prevSliderBtn) {
                    prevSliderBtn.addEventListener('click', function(e) {
                        console.log('⬅️ RELATED PRODUCTS PREV BUTTON CLICKED!');
                        e.preventDefault();
                        e.stopPropagation();

                        const cardWidth = sliderContainer.querySelector('.product-card')?.offsetWidth || 350;
                        const gap = 20;
                        const scrollAmount = cardWidth + gap;

                        sliderContainer.scrollBy({
                            left: scrollAmount,
                            behavior: 'smooth'
                        });
                    });
                }

                if (nextSliderBtn) {
                    nextSliderBtn.addEventListener('click', function(e) {
                        console.log('➡️ RELATED PRODUCTS NEXT BUTTON CLICKED!');
                        e.preventDefault();
                        e.stopPropagation();

                        const cardWidth = sliderContainer.querySelector('.product-card')?.offsetWidth || 350;
                        const gap = 20;
                        const scrollAmount = cardWidth + gap;

                        sliderContainer.scrollBy({
                            left: -scrollAmount,
                            behavior: 'smooth'
                        });
                    });
                }
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRelatedProducts);
    } else {
        initRelatedProducts();
    }
})();
