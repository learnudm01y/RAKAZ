@extends('layouts.app')

@push('styles')
<style>
    .privacy-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 40px;
        background: #fff;
    }

    .privacy-header {
        text-align: center;
        margin-bottom: 50px;
        padding-bottom: 30px;
        border-bottom: 2px solid #e5e5e5;
    }

    .privacy-header h1 {
        font-size: 42px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 15px;
    }

    .privacy-header .update-date {
        font-size: 14px;
        color: #666;
    }

    .privacy-content {
        line-height: 1.8;
    }

    .privacy-section {
        margin-bottom: 40px;
    }

    .privacy-section h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        padding-top: 10px;
    }

    .privacy-section h3 {
        font-size: 22px;
        font-weight: 600;
        color: #333;
        margin: 25px 0 15px 0;
    }

    .privacy-section p {
        font-size: 16px;
        color: #444;
        margin-bottom: 15px;
        line-height: 1.9;
    }

    .privacy-section ul {
        margin: 15px 0;
        padding-right: 30px;
    }

    .privacy-section ul li {
        font-size: 16px;
        color: #444;
        margin-bottom: 12px;
        line-height: 1.8;
    }

    .privacy-section strong {
        color: #1a1a1a;
        font-weight: 600;
    }

    .privacy-content h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 40px 0 20px 0;
        padding-top: 10px;
    }

    .privacy-content h3 {
        font-size: 22px;
        font-weight: 600;
        color: #333;
        margin: 25px 0 15px 0;
    }

    .privacy-content ul {
        margin: 15px 0;
        padding-right: 30px;
    }

    .privacy-content ul li {
        font-size: 16px;
        color: #444;
        margin-bottom: 12px;
        line-height: 1.8;
    }

    .privacy-content p {
        font-size: 16px;
        color: #444;
        margin-bottom: 15px;
        line-height: 1.9;
    }

    .privacy-content strong {
        color: #1a1a1a;
        font-weight: 600;
    }

    .contact-info {
        background: #f9f9f9;
        padding: 30px;
        margin-top: 40px;
        border-radius: 8px;
    }

    .contact-info h3 {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
    }

    .contact-info p {
        font-size: 16px;
        color: #444;
        margin-bottom: 10px;
    }

    .contact-info a {
        color: #1a1a1a;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .privacy-page {
            padding: 40px 20px;
        }

        .privacy-header h1 {
            font-size: 32px;
        }

        .privacy-section h2,
        .privacy-content h2 {
            font-size: 24px;
        }

        .privacy-section h3,
        .privacy-content h3 {
            font-size: 20px;
        }

        .privacy-section p,
        .privacy-section ul li,
        .privacy-content p,
        .privacy-content ul li {
            font-size: 15px;
        }
    }
</style>
@endpush

@section('content')
<!-- Privacy Policy Content -->
<main class="privacy-page">
    <div class="privacy-header">
        @if(app()->getLocale() == 'ar')
            <h1>{{ $page->hero_title_ar ?? 'سياسة الخصوصية والكوكيز' }}</h1>
            <p class="update-date">{{ $page->hero_subtitle_ar ?? 'آخر تحديث: 3 ديسمبر 2025' }}</p>
        @else
            <h1>{{ $page->hero_title_en ?? 'Privacy Policy and Cookies' }}</h1>
            <p class="update-date">{{ $page->hero_subtitle_en ?? 'Last updated: December 3, 2025' }}</p>
        @endif
    </div>

    <div class="privacy-content">
        @if(app()->getLocale() == 'ar')
            @if(isset($page) && $page->content_ar)
                {!! $page->content_ar !!}
            @else
                <div class="privacy-section">
                    <h2>مقدمة</h2>
                    <p>نحن في <strong>ركاز</strong> نلتزم بحماية خصوصيتك واحترام بياناتك الشخصية.</p>
                </div>
            @endif
        @else
            @if(isset($page) && $page->content_en)
                {!! $page->content_en !!}
            @else
                <div class="privacy-section">
                    <h2>Introduction</h2>
                    <p>At <strong>Rakaz</strong>, we are committed to protecting your privacy and respecting your personal data.</p>
                </div>
            @endif
        @endif
    </div>
</main>
@endsection
