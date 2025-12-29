{{-- Hero Banner Content --}}
@if($homePage && $homePage->hero_slides && count($homePage->hero_slides) > 0)
<section class="hero-banner">
    <div class="hero-slider">
        @foreach($homePage->hero_slides as $index => $slide)
        @php
            $desktopImage = $slide['image'];
            $tabletImage = isset($homePage->hero_slides_tablet[$index]['image']) && !empty($homePage->hero_slides_tablet[$index]['image'])
                ? $homePage->hero_slides_tablet[$index]['image']
                : $desktopImage;
            $mobileImage = isset($homePage->hero_slides_mobile[$index]['image']) && !empty($homePage->hero_slides_mobile[$index]['image'])
                ? $homePage->hero_slides_mobile[$index]['image']
                : $desktopImage;
        @endphp
        <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" data-slide-index="{{ $index }}">
            <a href="{{ $slide['link'] ?? '#' }}" class="hero-slide-link" rel="noopener">
                @if($index === 0)
                    {{-- First slide: Load immediately --}}
                    <picture>
                        <source media="(max-width: 767px)" srcset="{{ $mobileImage }}">
                        <source media="(max-width: 1024px)" srcset="{{ $tabletImage }}">
                        <img src="{{ $desktopImage }}" alt="{{ $slide['alt'] ?? 'Hero Banner ' . ($index + 1) }}" class="hero-banner-image">
                    </picture>
                @else
                    {{-- Other slides: Lazy load via JS --}}
                    <picture class="lazy-picture"
                             data-desktop="{{ $desktopImage }}"
                             data-tablet="{{ $tabletImage }}"
                             data-mobile="{{ $mobileImage }}"
                             data-alt="{{ $slide['alt'] ?? 'Hero Banner ' . ($index + 1) }}">
                        <div class="hero-banner-placeholder" style="background: #f0f0f0; width: 100%; height: 600px;"></div>
                    </picture>
                @endif
            </a>
        </div>
        @endforeach
    </div>
    <div class="hero-dots">
        @foreach($homePage->hero_slides as $index => $slide)
        <span class="hero-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></span>
        @endforeach
    </div>
    <div class="hero-content">
        <div class="hero-text">
            <!-- <a href="#" class="hero-btn">تسوق التشكيلة</a> -->
        </div>
    </div>
</section>
@endif
