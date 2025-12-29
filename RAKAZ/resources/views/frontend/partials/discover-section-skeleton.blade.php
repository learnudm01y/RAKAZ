{{-- Discover Section Skeleton --}}
<section class="discover-section skeleton-loading" style="transition: opacity 0.3s ease;">
    <div class="skeleton skeleton-title" style="width: 200px; height: 32px; margin-bottom: 30px;"></div>

    <div class="discover-grid">
        @for($i = 0; $i < 3; $i++)
        <div class="discover-card">
            <div class="skeleton skeleton-image" style="width: 100%; height: 300px; border-radius: 8px; margin-bottom: 15px;"></div>
            <div class="skeleton skeleton-text" style="width: 70%; height: 20px;"></div>
        </div>
        @endfor
    </div>

    <div class="discover-row" style="margin-top: 20px;">
        @for($i = 0; $i < 2; $i++)
        <div class="discover-card-wide">
            <div class="skeleton skeleton-image" style="width: 100%; height: 250px; border-radius: 8px; margin-bottom: 15px;"></div>
            <div class="skeleton skeleton-text" style="width: 60%; height: 20px;"></div>
        </div>
        @endfor
    </div>
</section>
