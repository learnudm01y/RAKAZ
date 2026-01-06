@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="/assets/css/about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
@php
    $lang = session('locale', 'ar'); // Default to Arabic
@endphp

    <!-- About Page Content -->
    <main class="about-page">
        <!-- Hero Section -->
        <section class="about-hero">
            <div class="hero-content">
                <h1>{{ $lang == 'ar' ? ($page->hero_title_ar ?? $page->title_ar) : ($page->hero_title_en ?? $page->title_en) }}</h1>
                <p class="hero-subtitle">{{ $lang == 'ar' ? ($page->hero_subtitle_ar ?? 'ركاز - وجهتك الأولى للأزياء الإماراتية الفاخرة') : ($page->hero_subtitle_en ?? 'Rakaz - Your premier destination for luxury Emirati fashion') }}</p>
            </div>
        </section>

        <!-- Story Section -->
        <section class="about-story">
            <div class="story-container">
                <div class="story-content">
                    @if($lang == 'ar')
                        <h2>{{ $page->story_title_ar ?? 'قصتنا' }}</h2>
                        <div class="page-content">
                            {!! $page->story_content_ar ?? $page->content_ar !!}
                        </div>
                    @else
                        <h2>{{ $page->story_title_en ?? 'Our Story' }}</h2>
                        <div class="page-content">
                            {!! $page->story_content_en ?? $page->content_en !!}
                        </div>
                    @endif
                </div>
                <div class="story-image">
                    <img src="{{ $page->story_image ?? '/assets/images/New folder/Emirati_Gold_Edition_White.jpg' }}" alt="{{ $lang == 'ar' ? $page->title_ar : $page->title_en }}">
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="about-values">
            <h2>{{ $lang == 'ar' ? ($page->values_title_ar ?? 'قيمنا') : ($page->values_title_en ?? 'Our Values') }}</h2>
            <div class="values-grid">
                @php
                    // Default Font Awesome icons
                    $default_icons = [
                        'fa-solid fa-star',
                        'fa-solid fa-lightbulb',
                        'fa-solid fa-handshake',
                        'fa-solid fa-trophy'
                    ];

                    // Default values
                    $defaults_ar = ['الجودة', 'الابتكار', 'الثقة', 'التميز'];
                    $defaults_en = ['Quality', 'Innovation', 'Trust', 'Excellence'];
                    $defaults_desc_ar = [
                        'نلتزم بتقديم أعلى مستويات الجودة',
                        'نسعى دائما للتطور والابتكار',
                        'نبني علاقات طويلة الأمد مع عملائنا',
                        'نسعى دائما لتحقيق التميز'
                    ];
                    $defaults_desc_en = [
                        'We are committed to delivering the highest quality standards',
                        'We always strive for development and innovation',
                        'We build long-term relationships with our clients',
                        'We always strive to achieve excellence'
                    ];
                @endphp

                @for($i = 1; $i <= 4; $i++)
                    @php
                        $title_ar = $page->{"value{$i}_title_ar"};
                        $title_en = $page->{"value{$i}_title_en"};
                        $desc_ar = $page->{"value{$i}_description_ar"};
                        $desc_en = $page->{"value{$i}_description_en"};
                        $icon = $page->{"value{$i}_icon"} ?? $default_icons[$i-1];
                    @endphp

                    <div class="value-card">
                        <div class="value-icon">
                            <i class="{{ $icon }}"></i>
                        </div>
                        <h3>{{ $lang == 'ar' ? ($title_ar ?: $defaults_ar[$i-1]) : ($title_en ?: $defaults_en[$i-1]) }}</h3>
                        <p>{{ $lang == 'ar' ? ($desc_ar ?: $defaults_desc_ar[$i-1]) : ($desc_en ?: $defaults_desc_en[$i-1]) }}</p>
                    </div>
                @endfor
            </div>
        </section>

        <!-- Stats Section -->
        <section class="about-stats">
            <div class="stats-container">
                @php
                    $statsData = [];
                    foreach($stats as $stat) {
                        $key = $stat->key;
                        $statsData[$key] = $lang == 'ar' ? $stat->value_ar : $stat->value_en;
                    }
                @endphp

                <div class="stat-item">
                    <div class="stat-number">{{ $statsData['branches_count'] ?? '10' }}+</div>
                    <div class="stat-label">{{ $statsData['branches_label'] ?? ($lang == 'ar' ? 'فرع' : 'Branches') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($statsData['customers_count'] ?? 50000) }}+</div>
                    <div class="stat-label">{{ $statsData['customers_label'] ?? ($lang == 'ar' ? 'عميل سعيد' : 'Happy Clients') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $statsData['products_count'] ?? '500' }}+</div>
                    <div class="stat-label">{{ $statsData['products_label'] ?? ($lang == 'ar' ? 'منتج' : 'Products') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $statsData['years_experience'] ?? '15' }}+</div>
                    <div class="stat-label">{{ $statsData['years_label'] ?? ($lang == 'ar' ? 'سنة خبرة' : 'Years Experience') }}</div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="about-team">
            <h2>{{ $lang == 'ar' ? 'خدماتنا' : 'Our Services' }}</h2>
            <div class="services-grid">
                @php
                    $servicesData = [];
                    foreach($services as $service) {
                        $key = $service->key;
                        $servicesData[$key] = $lang == 'ar' ? $service->value_ar : $service->value_en;
                    }
                @endphp

                <div class="service-card">
                    <div class="service-icon">
                        <i class="{{ $servicesData['service_1_icon'] ?? 'fa-solid fa-pen-ruler' }}" style="font-size: 2rem;"></i>
                    </div>
                    <h3>{{ $servicesData['service_1_title'] ?? ($lang == 'ar' ? 'تفصيل حسب الطلب' : 'Custom Orders') }}</h3>
                    <p>{{ $servicesData['service_1_desc'] ?? ($lang == 'ar' ? 'احصل على كندورة مصممة خصيصاً لك بالقياسات والتفاصيل التي تفضلها' : 'Get a kandora designed specifically for you with your preferred measurements and details') }}</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="{{ $servicesData['service_2_icon'] ?? 'fa-solid fa-truck-fast' }}" style="font-size: 2rem;"></i>
                    </div>
                    <h3>{{ $servicesData['service_2_title'] ?? ($lang == 'ar' ? 'التوصيل السريع' : 'Fast Delivery') }}</h3>
                    <p>{{ $servicesData['service_2_desc'] ?? ($lang == 'ar' ? 'توصيل خلال ساعتين في دبي وتوصيل مجاني لجميع أنحاء الإمارات' : 'Delivery within two hours in Dubai and free delivery to all UAE') }}</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="{{ $servicesData['service_3_icon'] ?? 'fa-solid fa-headset' }}" style="font-size: 2rem;"></i>
                    </div>
                    <h3>{{ $servicesData['service_3_title'] ?? ($lang == 'ar' ? 'خدمة ما بعد البيع' : 'After Sales Service') }}</h3>
                    <p>{{ $servicesData['service_3_desc'] ?? ($lang == 'ar' ? 'نقدم خدمات التعديل والصيانة لضمان رضاك التام' : 'We provide modification and maintenance services to ensure your complete satisfaction') }}</p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="about-cta">
            <div class="cta-content">
                <h2>{{ app()->getLocale() == 'ar' ? 'ابدأ تجربة التسوق الآن' : 'Start Your Shopping Experience Now' }}</h2>
                <p>{{ app()->getLocale() == 'ar' ? 'اكتشف مجموعتنا الواسعة من الكنادر والأزياء الإماراتية الفاخرة' : 'Discover our wide collection of Emirati kanduras and luxury fashion' }}</p>
                <div class="cta-buttons">
                    <a href="{{ route('shop') }}" class="btn-primary">{{ app()->getLocale() == 'ar' ? 'تصفح المنتجات' : 'Browse Products' }}</a>
                    <a href="{{ route('contact') }}" class="btn-secondary">{{ app()->getLocale() == 'ar' ? 'اتصل بنا' : 'Contact Us' }}</a>
                </div>
            </div>
        </section>
    </main>

@endsection
