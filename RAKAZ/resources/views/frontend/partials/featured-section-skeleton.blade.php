<!-- Featured Section Skeleton Loader -->
<section class="must-have-section skeleton-loading" id="featured-skeleton">
    <div class="section-header">
        <div class="skeleton skeleton-title"></div>
        <div class="skeleton skeleton-link"></div>
    </div>
    <div class="products-slider">
        <div class="skeleton skeleton-slider-btn"></div>
        <div class="products-container">
            @for($i = 0; $i < 5; $i++)
                <div class="skeleton-product-card">
                    <div class="skeleton skeleton-image"></div>
                    <div class="skeleton skeleton-text"></div>
                    <div class="skeleton skeleton-text-small"></div>
                    <div class="skeleton skeleton-price"></div>
                </div>
            @endfor
        </div>
        <div class="skeleton skeleton-slider-btn"></div>
    </div>
</section>
