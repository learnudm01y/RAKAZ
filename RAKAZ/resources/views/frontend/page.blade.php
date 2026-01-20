@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="/assets/css/about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Dynamic Page Styles - Based on About Us Design */
        .dynamic-page {
            background: #fff;
        }

        /* Hero Section */
        .page-hero {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: #fff;
            padding: 100px 20px;
            text-align: center;
        }

        .page-hero h1 {
            font-size: 48px;
            font-weight: 700;
            margin: 0 0 20px 0;
            font-family: 'Playfair Display', serif;
        }

        /* Content Section - Same as Story Section */
        .page-content-section {
            padding: 80px 0;
        }

        .page-content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: start;
        }

        .page-content-container.no-image {
            grid-template-columns: 1fr;
            max-width: 900px;
        }

        .page-text-content h2 {
            font-size: 36px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0 0 25px 0;
        }

        .page-text-content p {
            font-size: 16px;
            line-height: 1.8;
            color: #666;
            margin: 0 0 20px 0;
        }

        /* Dynamic Page Content Styles */
        .page-text-content h3 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin: 30px 0 15px 0;
        }

        .page-text-content ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .page-text-content ul li {
            font-size: 16px;
            line-height: 1.8;
            color: #666;
            margin: 0 0 10px 0;
            padding-right: 25px;
            position: relative;
        }

        [dir="rtl"] .page-text-content ul li::before,
        .page-text-content ul li::before {
            content: "✓";
            position: absolute;
            right: 0;
            color: #c9a96e;
            font-weight: bold;
        }

        [dir="ltr"] .page-text-content ul li {
            padding-right: 0;
            padding-left: 25px;
        }

        [dir="ltr"] .page-text-content ul li::before {
            right: auto;
            left: 0;
        }

        .page-text-content strong {
            color: #1a1a1a;
            font-weight: 600;
        }

        .page-text-content ol {
            padding-right: 25px;
            padding-left: 0;
            margin: 20px 0;
        }

        [dir="ltr"] .page-text-content ol {
            padding-right: 0;
            padding-left: 25px;
        }

        .page-text-content ol li {
            font-size: 16px;
            line-height: 1.8;
            color: #666;
            margin: 0 0 10px 0;
        }

        .page-image-section {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .page-image-section img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* CTA Section */
        .page-cta {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: #fff;
            padding: 80px 30px;
            text-align: center;
        }

        .page-cta h2 {
            font-size: 36px;
            font-weight: 600;
            margin: 0 0 15px 0;
        }

        .page-cta p {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0 0 30px 0;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-buttons .btn-primary {
            background: #c9a96e;
            color: #fff;
            padding: 15px 40px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .cta-buttons .btn-primary:hover {
            background: #b8986a;
        }

        .cta-buttons .btn-secondary {
            background: transparent;
            color: #fff;
            padding: 15px 40px;
            border: 2px solid #fff;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .cta-buttons .btn-secondary:hover {
            background: #fff;
            color: #1a1a1a;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .page-content-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .page-hero h1 {
                font-size: 36px;
            }

            .page-text-content h2 {
                font-size: 28px;
            }
        }

        @media (max-width: 576px) {
            .page-hero {
                padding: 60px 20px;
            }

            .page-hero h1 {
                font-size: 28px;
            }

            .page-content-section {
                padding: 40px 0;
            }

            .page-content-container {
                padding: 0 15px;
            }

            .page-cta {
                padding: 50px 15px;
            }

            .page-cta h2 {
                font-size: 24px;
            }
        }
    </style>
@endpush

@section('content')
@php
    $lang = session('locale', 'ar');
    $title = $lang == 'ar' ? $page->title_ar : $page->title_en;
    $content = $lang == 'ar' ? $page->content_ar : $page->content_en;
@endphp

<main class="dynamic-page">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-content">
            <h1>{{ $title }}</h1>
        </div>
    </section>

    <!-- Content Section -->
    <section class="page-content-section">
        <div class="page-content-container {{ !$page->image ? 'no-image' : '' }}">
            <div class="page-text-content">
                {!! $content !!}
            </div>
            @if($page->image)
            <div class="page-image-section">
                <img src="{{ asset($page->image) }}" alt="{{ $title }}">
            </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="page-cta">
        <div class="cta-content">
            <h2>{{ $lang == 'ar' ? 'ابدأ تجربة التسوق الآن' : 'Start Your Shopping Experience Now' }}</h2>
            <p>{{ $lang == 'ar' ? 'اكتشف مجموعتنا الواسعة من المنتجات الفاخرة' : 'Discover our wide collection of luxury products' }}</p>
            <div class="cta-buttons">
                <a href="{{ route('shop') }}" class="btn-primary">{{ $lang == 'ar' ? 'تصفح المنتجات' : 'Browse Products' }}</a>
                <a href="{{ route('contact') }}" class="btn-secondary">{{ $lang == 'ar' ? 'اتصل بنا' : 'Contact Us' }}</a>
            </div>
        </div>
    </section>
</main>
@endsection
