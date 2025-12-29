// Product Details JavaScript

document.addEventListener('DOMContentLoaded', function() {
    const isArabic = document.documentElement.getAttribute('dir') === 'rtl' || (document.documentElement.getAttribute('lang') || '').toLowerCase().startsWith('ar');
    const t = (ar, en) => (isArabic ? ar : en);

    // ========================================
    // Thumbnail Gallery
    // ========================================
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.getElementById('mainProductImage');

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));

            // Add active class to clicked thumbnail
            this.classList.add('active');

            // Update main image
            mainImage.src = this.src;
        });
    });

    // ========================================
    // Image Navigation Arrows
    // ========================================
    const prevImageBtn = document.querySelector('.prev-image');
    const nextImageBtn = document.querySelector('.next-image');
    let currentImageIndex = 0;
    const images = Array.from(thumbnails).map(thumb => thumb.src);

    if (prevImageBtn && nextImageBtn) {
        prevImageBtn.addEventListener('click', function() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            updateMainImage(currentImageIndex);
        });

        nextImageBtn.addEventListener('click', function() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            updateMainImage(currentImageIndex);
        });
    }

    function updateMainImage(index) {
        mainImage.src = images[index];
        thumbnails.forEach((thumb, i) => {
            thumb.classList.toggle('active', i === index);
        });
    }

    // ========================================
    // Thumbnail Navigation
    // ========================================
    const thumbnailsWrapper = document.querySelector('.thumbnails-wrapper');
    const prevThumb = document.querySelector('.prev-thumb');
    const nextThumb = document.querySelector('.next-thumb');

    if (prevThumb && nextThumb && thumbnailsWrapper) {
        prevThumb.addEventListener('click', function() {
            // Check if viewport is mobile/tablet (horizontal scroll)
            if (window.innerWidth <= 1024) {
                thumbnailsWrapper.scrollBy({
                    left: -200,
                    behavior: 'smooth'
                });
            } else {
                // Desktop (vertical scroll)
                thumbnailsWrapper.scrollBy({
                    top: -150,
                    behavior: 'smooth'
                });
            }
        });

        nextThumb.addEventListener('click', function() {
            // Check if viewport is mobile/tablet (horizontal scroll)
            if (window.innerWidth <= 1024) {
                thumbnailsWrapper.scrollBy({
                    left: 200,
                    behavior: 'smooth'
                });
            } else {
                // Desktop (vertical scroll)
                thumbnailsWrapper.scrollBy({
                    top: 150,
                    behavior: 'smooth'
                });
            }
        });
    }

    // ========================================
    // Color Selection
    // ========================================
    const colorOptions = document.querySelectorAll('.color-option');
    const selectedColorText = document.querySelector('.selected-option');

    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            colorOptions.forEach(opt => opt.classList.remove('active'));

            // Add active class to clicked option
            this.classList.add('active');

            // Update selected color text
            const colorName = this.getAttribute('data-color');
            if (selectedColorText) {
                selectedColorText.textContent = colorName;
            }
        });
    });

    // ========================================
    // Wishlist Functionality
    // ========================================
    const wishlistBtns = document.querySelectorAll('.wishlist-btn-large, .wishlist-btn-small, .btn-add-to-wishlist');

    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.toggle('active');

            if (this.classList.contains('active')) {
                Swal.fire({
                    icon: 'success',
                    title: t('تمت الإضافة!', 'Added!'),
                    text: t('تم إضافة المنتج إلى قائمة المفضلة', 'Added to wishlist'),
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });

    // ========================================
    // Add to Bag
    // ========================================
    // NOTE: Add to cart functionality is handled in product-details.blade.php
    // to avoid duplicate event listeners and SweetAlert notifications.
    // DO NOT add event listener here to prevent duplicate notifications!

    // Get size select element for other features (not for add to cart)
    const sizeSelect = document.getElementById('sizeSelect');

    // ========================================
    // Product Tabs
    // ========================================
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all buttons and panels
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(p => p.classList.remove('active'));

            // Add active class to clicked button and corresponding panel
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // ========================================
    // Carousel Navigation
    // ========================================
    const carousels = document.querySelectorAll('.products-carousel');

    carousels.forEach(carousel => {
        const slider = carousel.querySelector('.products-slider');
        const prevBtn = carousel.querySelector('.carousel-nav.prev');
        const nextBtn = carousel.querySelector('.carousel-nav.next');

        if (prevBtn && nextBtn && slider) {
            // Get card width for smooth scrolling
            const getScrollAmount = () => {
                const card = slider.querySelector('.product-card-small');
                if (card) {
                    return card.offsetWidth + 20; // card width + gap
                }
                return 300; // fallback
            };

            prevBtn.addEventListener('click', function() {
                const scrollAmount = getScrollAmount();
                slider.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });

            nextBtn.addEventListener('click', function() {
                const scrollAmount = getScrollAmount();
                slider.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });

            // Update button states based on scroll position
            const updateButtons = () => {
                const scrollLeft = slider.scrollLeft;
                const scrollWidth = slider.scrollWidth;
                const clientWidth = slider.clientWidth;

                prevBtn.style.opacity = scrollLeft <= 0 ? '0.5' : '1';
                prevBtn.style.pointerEvents = scrollLeft <= 0 ? 'none' : 'auto';

                nextBtn.style.opacity = scrollLeft + clientWidth >= scrollWidth - 1 ? '0.5' : '1';
                nextBtn.style.pointerEvents = scrollLeft + clientWidth >= scrollWidth - 1 ? 'none' : 'auto';
            };

            slider.addEventListener('scroll', updateButtons);
            updateButtons(); // Initial state
        }
    });

    // ========================================
    // Size Select Enhancement
    // ========================================
    if (sizeSelect) {
        sizeSelect.addEventListener('change', function() {
            if (this.value) {
                this.style.borderColor = '#4CAF50';
                setTimeout(() => {
                    this.style.borderColor = '#ddd';
                }, 1000);
            }
        });
    }

    // ========================================
    // Scroll to Top on Load
    // ========================================
    window.scrollTo(0, 0);

    // ========================================
    // Image Zoom on Hover (Desktop)
    // ========================================
    if (window.innerWidth > 768) {
        const mainImageWrapper = document.querySelector('.main-image-wrapper');

        if (mainImageWrapper && mainImage) {
            mainImageWrapper.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;

                mainImage.style.transformOrigin = `${x}% ${y}%`;
                mainImage.style.transform = 'scale(1.5)';
            });

            mainImageWrapper.addEventListener('mouseleave', function() {
                mainImage.style.transform = 'scale(1)';
            });
        }
    }

    // ========================================
    // Product Card Click (Related Products)
    // ========================================
    const productCards = document.querySelectorAll('.product-card-small');

    productCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't navigate if clicking on wishlist button
            if (e.target.closest('.wishlist-btn-small')) {
                return;
            }

            // Navigate to product details (you can update this URL)
            // window.location.href = 'product-details.html';
        });
    });

    // ========================================
    // Lazy Loading for Images
    // ========================================
    const lazyImages = document.querySelectorAll('img[data-src]');

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
        });
    }
});
