{{-- Discover Section Content --}}
@if($discoverItems && $discoverItems->count() > 0)
    @php
        $currentLocale = app()->getLocale();
        $discoverTitle = $currentLocale === 'ar' ? 'اكتشف المزيد' : 'Discover More';

        // Split items for grid layout: first 3 items in grid, remaining in row
        $gridItems = $discoverItems->take(3);
        $rowItems = $discoverItems->slice(3);
    @endphp
    <section class="discover-section">
        <h2 class="section-title">{{ $discoverTitle }}</h2>

        @if($gridItems->count() > 0)
        <div class="discover-grid">
            @foreach($gridItems as $item)
            <div class="discover-card">
                <a href="{{ $item->link }}">
                    <img src="{{ $item->image }}" alt="{{ $item->getTitle($currentLocale) }}" class="discover-image" loading="lazy">
                    <h3 class="discover-title">{{ $item->getTitle($currentLocale) }}</h3>
                </a>
            </div>
            @endforeach
        </div>
        @endif

        @if($rowItems->count() > 0)
        <div class="discover-row">
            @foreach($rowItems as $index => $item)
            <div class="discover-card-wide {{ $index === 0 ? 'fragrance' : 'watches' }}">
                <a href="{{ $item->link }}">
                    <img src="{{ $item->image }}" alt="{{ $item->getTitle($currentLocale) }}" class="discover-wide-image" loading="lazy">
                    <h3 class="discover-title-wide">{{ $item->getTitle($currentLocale) }}</h3>
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </section>
@endif
