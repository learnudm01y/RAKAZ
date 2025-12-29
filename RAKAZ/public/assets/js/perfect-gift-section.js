// Perfect Gift Section Navigation
(function() {
    'use strict';

    // Initialize Perfect Gift Section functionality
    function initFeaturedSection() {
        console.log('🚀 Initializing Perfect Gift Section...');

        // Check for overlay buttons on page load
        const galleryButtons = document.querySelectorAll('.overlay-gallery-nav');
        const sizesButtons = document.querySelectorAll('.overlay-sizes-nav');
        console.log('🔍 Initial check - Gallery buttons:', galleryButtons.length);
        console.log('🔍 Initial check - Sizes buttons:', sizesButtons.length);

        // Handle overlay positioning
        const productCards = document.querySelectorAll('.perfect-gift-section .product-card');
        console.log('🔍 Product cards found:', productCards.length);

        productCards.forEach(card => {
            const overlay = card.querySelector('.perfect-gift-overlay');
            if (!overlay) return;

            card.addEventListener('mouseenter', function() {
                console.log('🖱️ Mouse entered card, showing overlay');

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

                // Check buttons again after overlay is shown
                setTimeout(() => {
                    const visibleGalleryButtons = overlay.querySelectorAll('.overlay-gallery-nav');
                    const visibleSizesButtons = overlay.querySelectorAll('.overlay-sizes-nav');
                    console.log('🔍 Overlay visible - Gallery buttons:', visibleGalleryButtons.length);
                    console.log('🔍 Overlay visible - Sizes buttons:', visibleSizesButtons.length);
                }, 100);
            });

            // No need to update position on scroll for absolute positioned overlay
        });

        // Handle gallery navigation - FORCE IT TO WORK
        // Use both event delegation AND direct listeners
        document.addEventListener('click', function(e) {
            // IMPORTANT: Only handle clicks within .perfect-gift-section to avoid conflicts
            const perfectGiftSection = e.target.closest('.perfect-gift-section');
            if (!perfectGiftSection) return; // Ignore clicks outside perfect gift section

            console.log('🔥 DOCUMENT CLICK EVENT:', e.target);
            console.log('🔥 Target tagName:', e.target.tagName);
            console.log('🔥 Target className:', e.target.className);
            console.log('🔥 Target classList:', e.target.classList);

            // Check if clicking on gallery image - change main image and navigate
            const galleryLink = e.target.closest('.overlay-gallery-link');
            if (galleryLink) {
                // If gallery link has href attribute (color image link), allow navigation
                if (galleryLink.hasAttribute('href') && galleryLink.getAttribute('href') !== '#') {
                    console.log('🔗 Navigating to product with color:', galleryLink.getAttribute('data-color-id'));
                    return; // Allow default link behavior
                }

                console.log('🖼️ Gallery image clicked');
                e.preventDefault();
                e.stopPropagation();

                // Get the image index and product info
                const imageIndex = galleryLink.getAttribute('data-image-index');
                const imageSrc = galleryLink.getAttribute('data-image-src');

                // Find the overlay container
                const overlay = galleryLink.closest('.perfect-gift-overlay');
                if (overlay && imageSrc) {
                    // Remove active class from all gallery items in this overlay
                    const allGalleryLinks = overlay.querySelectorAll('.overlay-gallery-link');
                    allGalleryLinks.forEach(link => link.classList.remove('active'));

                    // Add active class to clicked item
                    galleryLink.classList.add('active');

                    // Update the primary image in the overlay
                    const primaryImage = overlay.querySelector('.overlay-image-primary');
                    if (primaryImage) {
                        primaryImage.src = imageSrc;
                        console.log('✅ Main image updated to:', imageSrc);
                    }

                    // Also update the main product card image
                    const overlayId = overlay.getAttribute('data-overlay-for');
                    const productCard = document.querySelector(`.product-card[data-product-id="${overlayId}"] .main-product-image`);
                    if (productCard) {
                        productCard.src = imageSrc;
                        console.log('✅ Product card image updated');
                    }

                    // Store selected image index in sessionStorage
                    sessionStorage.setItem('selectedProductImage', imageIndex);
                    console.log('💾 Stored image index in session:', imageIndex);

                    // Navigate to product details after a short delay (to show the image change)
                    setTimeout(() => {
                        const productLink = overlay.querySelector('.overlay-link-wrapper');
                        if (productLink && productLink.href) {
                            // Add image index to URL as query parameter
                            const url = new URL(productLink.href);
                            url.searchParams.set('image', imageIndex);
                            console.log('🔗 Navigating to:', url.toString());
                            window.location.href = url.toString();
                        }
                    }, 300);
                }
                return;
            }

            // Try multiple ways to find the button
            let galleryNav = e.target.closest('.overlay-gallery-nav');

            // If clicking on SVG or path, go up to button (check both upper and lowercase)
            const tagName = e.target.tagName.toLowerCase();
            if (!galleryNav && (tagName === 'svg' || tagName === 'path' || tagName === 'polyline')) {
                galleryNav = e.target.closest('button.overlay-gallery-nav');
                if (!galleryNav) {
                    galleryNav = e.target.parentElement;
                    while (galleryNav && !galleryNav.classList.contains('overlay-gallery-nav')) {
                        galleryNav = galleryNav.parentElement;
                    }
                }
            }

            // If still not found, check if target IS the button
            if (!galleryNav && e.target.classList && e.target.classList.contains('overlay-gallery-nav')) {
                galleryNav = e.target;
            }

            console.log('🔥 Gallery nav button found:', galleryNav);

            if (galleryNav) {
                console.log('✅ GALLERY BUTTON CLICKED!');
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

                console.log('📊 Gallery Info:');
                console.log('   Direction:', direction);
                console.log('   Current scroll:', wrapper.scrollLeft);
                console.log('   Scroll amount:', scrollAmount);
                console.log('   Total width:', wrapper.scrollWidth);
                console.log('   Visible width:', wrapper.clientWidth);

                // REVERSED: prev scrolls RIGHT (positive), next scrolls LEFT (negative)
                const moveAmount = direction === 'prev' ? scrollAmount : -scrollAmount;
                console.log('   Moving by:', moveAmount);

                wrapper.scrollBy({
                    left: moveAmount,
                    behavior: 'smooth'
                });

                setTimeout(() => {
                    console.log('   After scroll:', wrapper.scrollLeft);
                }, 300);
            }
        });

        // DIRECT EVENT LISTENERS for overlay gallery buttons
        function attachGalleryListeners() {
            const galleryButtons = document.querySelectorAll('.overlay-gallery-nav');
            console.log('📌 Found gallery buttons:', galleryButtons.length);

            galleryButtons.forEach((btn, index) => {
                console.log(`📌 Attaching listener to gallery button ${index}:`, btn);

                btn.addEventListener('click', function(e) {
                    console.log('🎯 DIRECT GALLERY BUTTON CLICKED!', this);
                    e.stopPropagation();
                    e.preventDefault();

                    const section = this.closest('.overlay-gallery-section');
                    const wrapper = section ? section.querySelector('.overlay-gallery-images') : null;

                    if (!wrapper) {
                        console.error('❌ Gallery wrapper not found!');
                        return;
                    }

                    const direction = this.getAttribute('data-direction');
                    const thumbWidth = 60;
                    const gap = 8;
                    const scrollAmount = thumbWidth + gap;
                    const moveAmount = direction === 'prev' ? scrollAmount : -scrollAmount;

                    console.log('🎯 Scrolling gallery:', direction, 'by', moveAmount);

                    wrapper.scrollBy({
                        left: moveAmount,
                        behavior: 'smooth'
                    });
                }, true); // Use capture phase
            });
        }

        // Attach listeners initially and on hover
        attachGalleryListeners();

        // Re-attach when hovering over product cards (for dynamically shown overlays)
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                setTimeout(attachGalleryListeners, 100);
            });
        });

        // Handle sizes navigation - FORCE IT TO WORK
        // Use both event delegation AND direct listeners
        document.addEventListener('click', function(e) {
            // Try multiple ways to find the button
            let sizesNav = e.target.closest('.overlay-sizes-nav');

            // If clicking on SVG or path, go up to button (check both upper and lowercase)
            const tagName = e.target.tagName.toLowerCase();
            if (!sizesNav && (tagName === 'svg' || tagName === 'path' || tagName === 'polyline')) {
                sizesNav = e.target.closest('button.overlay-sizes-nav');
                if (!sizesNav) {
                    sizesNav = e.target.parentElement;
                    while (sizesNav && !sizesNav.classList.contains('overlay-sizes-nav')) {
                        sizesNav = sizesNav.parentElement;
                    }
                }
            }

            // If still not found, check if target IS the button
            if (!sizesNav && e.target.classList && e.target.classList.contains('overlay-sizes-nav')) {
                sizesNav = e.target;
            }

            console.log('🔥 Sizes nav button found:', sizesNav);

            if (sizesNav) {
                console.log('✅ SIZES BUTTON CLICKED!');
                e.stopPropagation();
                e.preventDefault();

                const wrapper = sizesNav.closest('.overlay-sizes-wrapper');
                const section = wrapper ? wrapper.querySelector('.overlay-sizes-section') : null;

                if (!section) {
                    console.error('❌ Sizes section not found!');
                    return;
                }

                const direction = sizesNav.getAttribute('data-direction');
                const sizeWidth = 28;
                const gap = 8;
                const scrollAmount = sizeWidth + gap;

                console.log('📊 Sizes Info:');
                console.log('   Direction:', direction);
                console.log('   Current scroll:', section.scrollLeft);
                console.log('   Scroll amount:', scrollAmount);
                console.log('   Total width:', section.scrollWidth);
                console.log('   Visible width:', section.clientWidth);

                // REVERSED: prev scrolls RIGHT (positive), next scrolls LEFT (negative)
                const moveAmount = direction === 'prev' ? scrollAmount : -scrollAmount;
                console.log('   Moving by:', moveAmount);

                section.scrollBy({
                    left: moveAmount,
                    behavior: 'smooth'
                });

                setTimeout(() => {
                    console.log('   After scroll:', section.scrollLeft);
                }, 300);
            }
        });

        // DIRECT EVENT LISTENERS for overlay sizes buttons
        function attachSizesListeners() {
            const sizesButtons = document.querySelectorAll('.overlay-sizes-nav');
            console.log('📌 Found sizes buttons:', sizesButtons.length);

            sizesButtons.forEach((btn, index) => {
                console.log(`📌 Attaching listener to sizes button ${index}:`, btn);

                btn.addEventListener('click', function(e) {
                    console.log('🎯 DIRECT SIZES BUTTON CLICKED!', this);
                    e.stopPropagation();
                    e.preventDefault();

                    const wrapper = this.closest('.overlay-sizes-wrapper');
                    const section = wrapper ? wrapper.querySelector('.overlay-sizes-section') : null;

                    if (!section) {
                        console.error('❌ Sizes section not found!');
                        return;
                    }

                    const direction = this.getAttribute('data-direction');
                    const sizeWidth = 28;
                    const gap = 8;
                    const scrollAmount = sizeWidth + gap;
                    const moveAmount = direction === 'prev' ? scrollAmount : -scrollAmount;

                    console.log('🎯 Scrolling sizes:', direction, 'by', moveAmount);

                    section.scrollBy({
                        left: moveAmount,
                        behavior: 'smooth'
                    });
                }, true); // Use capture phase
            });
        }

        // Attach listeners initially and on hover
        attachSizesListeners();

        // Re-attach when hovering over product cards (for dynamically shown overlays)
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                setTimeout(attachSizesListeners, 100);
            });
        });

        // Handle main slider navigation
        const sliderContainer = document.querySelector('.perfect-gift-section .products-container');
        console.log('🎯 Slider Container:', sliderContainer);

        if (sliderContainer) {
            const prevSliderBtn = document.querySelector('.perfect-gift-section .slider-btn.prev');
            const nextSliderBtn = document.querySelector('.perfect-gift-section .slider-btn.next');

            console.log('🎯 Prev Button:', prevSliderBtn);
            console.log('🎯 Next Button:', nextSliderBtn);

            if (prevSliderBtn) {
                prevSliderBtn.addEventListener('click', function(e) {
                    console.log('⬅️ PREV BUTTON CLICKED!');
                    e.preventDefault();
                    e.stopPropagation();

                    const cardWidth = sliderContainer.querySelector('.product-card')?.offsetWidth || 250;
                    const gap = 20;
                    const scrollAmount = cardWidth + gap;

                    console.log('Card Width:', cardWidth);
                    console.log('Scroll Amount:', scrollAmount);
                    console.log('Current Scroll:', sliderContainer.scrollLeft);

                    // REVERSED: prev scrolls RIGHT (positive)
                    sliderContainer.scrollBy({
                        left: scrollAmount,
                        behavior: 'smooth'
                    });

                    setTimeout(() => {
                        console.log('After Scroll:', sliderContainer.scrollLeft);
                    }, 300);
                });
            }

            if (nextSliderBtn) {
                nextSliderBtn.addEventListener('click', function(e) {
                    console.log('➡️ NEXT BUTTON CLICKED!');
                    e.preventDefault();
                    e.stopPropagation();

                    const cardWidth = sliderContainer.querySelector('.product-card')?.offsetWidth || 250;
                    const gap = 20;
                    const scrollAmount = cardWidth + gap;

                    console.log('Card Width:', cardWidth);
                    console.log('Scroll Amount:', scrollAmount);
                    console.log('Current Scroll:', sliderContainer.scrollLeft);

                    // REVERSED: next scrolls LEFT (negative)
                    sliderContainer.scrollBy({
                        left: -scrollAmount,
                        behavior: 'smooth'
                    });

                    setTimeout(() => {
                        console.log('After Scroll:', sliderContainer.scrollLeft);
                    }, 300);
                });
            }
        } else {
            // Silently skip if slider not found (not needed on all pages)
            console.log('ℹ️ Perfect gift slider container not found (expected on non-home pages)');
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFeaturedSection);
    } else {
        initFeaturedSection();
    }

    // Expose initialization function globally for lazy loading
    window.initializePerfectGiftSlider = initFeaturedSection;
})();
