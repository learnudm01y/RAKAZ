{{-- Hero Banner Skeleton --}}
<section class="hero-banner skeleton-loading" style="transition: opacity 0.3s ease;">
    <div class="hero-slider">
        <div class="hero-slide active">
            <div class="skeleton skeleton-image" style="width: 100%; height: 600px; border-radius: 0;"></div>
        </div>
    </div>
    <div class="hero-dots">
        @for($i = 0; $i < 3; $i++)
        <span class="skeleton" style="width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin: 0 5px;"></span>
        @endfor
    </div>
</section>

<style>
    @media (max-width: 768px) {
        .hero-banner.skeleton-loading .skeleton-image {
            height: 400px;
        }
    }
    @media (max-width: 480px) {
        .hero-banner.skeleton-loading .skeleton-image {
            height: 300px;
        }
    }
</style>
