{{-- Vertical Images Section --}}
@php
    $currentLocale = app()->getLocale();
    $giftsItems = $homePage->gifts_items ?? [];
    $isActive = $homePage->gifts_section_active ?? false;
    $hasItems = is_array($giftsItems) && count($giftsItems) > 0;

    // Get section title - $giftsTitle is already a string from SectionTitle::getByKey()
    $sectionTitle = '';
    if (isset($giftsTitle) && !empty($giftsTitle)) {
        // $giftsTitle is already the translated title string
        $sectionTitle = $giftsTitle;
    }
    if (empty($sectionTitle)) {
        $sectionTitle = $currentLocale === 'ar' ? 'الصور الطولية' : 'Vertical Images';
    }
@endphp

@if($homePage && $isActive && $hasItems)
<section class="vertical-images-section">
    <h2 class="section-title">{{ $sectionTitle }}</h2>

    <div class="vertical-images-grid">
        @foreach($giftsItems as $gift)
        @php
            $image = '';
            if (isset($gift['image'])) {
                if (is_array($gift['image'])) {
                    $image = $gift['image'][$currentLocale] ?? $gift['image']['ar'] ?? $gift['image']['en'] ?? '';
                } else {
                    $image = $gift['image'];
                }
            }

            $title = '';
            if (isset($gift['title'])) {
                if (is_array($gift['title'])) {
                    $title = $gift['title'][$currentLocale] ?? $gift['title']['ar'] ?? $gift['title']['en'] ?? '';
                } else {
                    $title = $gift['title'];
                }
            }

            $link = $gift['link'] ?? '#';
        @endphp

        <div class="vertical-image-card">
            <a href="{{ $link }}">
                @if($image)
                <img src="{{ $image }}" alt="{{ $title }}" class="vertical-image" loading="lazy">
                @endif
                @if($title)
                <h3 class="vertical-image-title">{{ $title }}</h3>
                @endif
            </a>
        </div>
        @endforeach
    </div>
</section>
@endif
