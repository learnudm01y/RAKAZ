@extends('admin.layouts.app')

@section('title', 'إدارة الصفحة الرئيسية')

@section('page-title')
    <span class="ar-text">إدارة الصفحة الرئيسية</span>
    <span class="en-text">Manage Home Page</span>
@endsection

@push('styles')
<style>
    /* Preview Panel Styles */
    .preview-panel {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: white;
        z-index: 9999;
        display: none;
        flex-direction: column;
    }

    .preview-panel.active {
        display: flex;
    }

    .preview-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--hover-bg);
        flex-wrap: wrap;
        gap: 1rem;
        flex-shrink: 0;
    }

    .preview-close-btn {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .preview-close-btn:hover {
        background: #c82333;
    }

    .preview-controls {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .device-mode-buttons {
        display: flex;
        gap: 0.25rem;
        background: white;
        padding: 0.25rem;
        border-radius: 6px;
        border: 1px solid var(--border-color);
    }

    .device-btn {
        padding: 0.5rem 0.75rem;
        border: none;
        background: transparent;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8rem;
        color: #666;
    }

    .device-btn:hover {
        background: var(--hover-bg);
    }

    .device-btn.active {
        background: var(--primary-color);
        color: white;
    }

    .preview-frame-container {
        width: 100%;
        height: calc(100vh - 60px);
        display: flex;
        align-items: flex-start;
        justify-content: center;
        background: #f5f5f5;
        overflow: auto;
        padding: 20px 0;
        flex: 1;
    }

    .preview-frame {
        width: 100%;
        height: 100%;
        border: none;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        min-height: 100%;
    }

    .preview-frame.mobile {
        max-width: 375px;
        min-height: 2000px;
        height: auto;
        border-radius: 12px;
    }

    .preview-frame.tablet {
        max-width: 768px;
        min-height: 2000px;
        height: auto;
        border-radius: 12px;
    }

    .preview-frame.desktop {
        width: 100%;
        height: 100%;
        border-radius: 0;
        min-height: 100%;
    }

    .home-page-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-intro {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .page-intro h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .page-intro p {
        opacity: 0.9;
        font-size: 0.875rem;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border-left: 4px solid #48bb78;
    }

    .alert-danger {
        background: #fed7d7;
        color: #742a2a;
        border-left: 4px solid #fc8181;
    }

    .language-card {
        background: white;
        padding: 1.25rem;
        border-radius: 10px;
        border: 2px solid var(--border-color);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .language-label {
        font-weight: 600;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .language-label svg {
        width: 20px;
        height: 20px;
        color: var(--primary-color);
    }

    .language-selector {
        padding: 0.625rem 1.25rem;
        border: 2px solid #3182ce;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        min-width: 150px;
        color: #1e40af;
    }

    .language-selector:hover {
        border-color: #2563eb;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.15);
    }

    .language-selector:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
    }

    .tabs-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .tabs-nav {
        display: flex;
        background: #f8fafc;
        border-bottom: 2px solid var(--border-color);
        overflow-x: auto;
        padding: 0;
    }

    .tabs-nav::-webkit-scrollbar {
        height: 4px;
    }

    .tabs-nav::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .tabs-nav::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .tab-button {
        flex-shrink: 0;
        padding: 1rem 1.5rem;
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
        position: relative;
        white-space: nowrap;
    }

    .tab-button:hover {
        background: #edf2f7;
        color: #334155;
    }

    .tab-button.active {
        color: var(--primary-color);
        background: white;
        border-bottom-color: var(--primary-color);
    }

    .tab-button i {
        margin-right: 0.375rem;
        font-size: 1rem;
    }

    html[dir="rtl"] .tab-button i {
        margin-right: 0;
        margin-left: 0.375rem;
    }

    .tab-content {
        display: none;
        padding: 2rem;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .section-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .section-description {
        color: #64748b;
        font-size: 0.875rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #334155;
        font-size: 0.875rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
    }

    .form-control:disabled {
        background: #f1f5f9;
        cursor: not-allowed;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 2px solid var(--border-color);
        margin-bottom: 1.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .checkbox-wrapper:hover {
        background: #edf2f7;
        border-color: var(--primary-color);
    }

    .checkbox-wrapper input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: var(--primary-color);
    }

    .checkbox-wrapper label {
        cursor: pointer;
        margin: 0;
        font-weight: 500;
        color: #1e293b;
        flex: 1;
    }

    .row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .image-preview-container {
        position: relative;
        margin-top: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border: 2px dashed var(--border-color);
        border-radius: 8px;
    }

    .image-preview-container img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
        display: block;
    }

    .image-remove-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #dc3545;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
        transition: all 0.3s ease;
        z-index: 10;
    }

    .image-remove-btn:hover {
        background: #c82333;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.6);
    }

    .image-remove-btn svg {
        width: 16px;
        height: 16px;
        stroke-width: 2.5;
    }

    .file-input-custom {
        position: relative;
        display: block;
    }

    .file-input-custom input[type="file"] {
        padding: 0.75rem 1rem;
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
    }

    .file-input-custom input[type="file"]:hover {
        border-color: var(--primary-color);
        background: #f8fafc;
    }

    .helper-text {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .helper-text svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }

    .item-card {
        background: #f8fafc;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.2s;
    }

    .item-card:hover {
        border-color: #cbd5e0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .item-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .item-card-title {
        font-weight: 600;
        color: #1e293b;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .item-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        justify-content: center;
    }

    .btn i {
        font-size: 1rem;
    }

    .btn svg {
        width: 16px;
        height: 16px;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
        box-shadow: 0 1px 3px rgba(49, 130, 206, 0.3);
        margin-bottom: 10px;
    }

    .btn-primary:hover {
        background: #2c5aa0;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(49, 130, 206, 0.4);
    }

    .btn-success {
        background: var(--accent-color);
        color: white;
        box-shadow: 0 1px 3px rgba(72, 187, 120, 0.3);
    }

    .btn-success:hover {
        background: #38a169;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(72, 187, 120, 0.4);
    }

    .btn-danger {
        background: var(--danger-color);
        color: white;
    }

    .btn-danger:hover {
        background: #c53030;
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #334155;
    }

    .btn-secondary:hover {
        background: #cbd5e0;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8125rem;
    }

    .save-bar {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 1.25rem 2rem;
        border-top: 2px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 100;
        margin: 0 -2rem -2rem -2rem;
    }

    .save-bar-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.875rem;
        padding-right: 32px;
    }

    .save-bar-actions {
        display: flex;
        gap: 1rem;
        padding-left: 34px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #94a3b8;
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Gifts Grid Layout - Matching Frontend Design */
    #gifts-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        margin-bottom: 1.5rem;
    }

    #gifts-container .item-card {
        margin-bottom: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    #gifts-container .image-preview-container {
        aspect-ratio: 3 / 4.7;
        width: 100%;
        height: auto;
        max-height: none;
    }

    #gifts-container .image-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    /* Discover Grid Layout - Matching Frontend Design */
    #discover-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        margin-bottom: 1.5rem;
    }

    #discover-container .item-card {
        margin-bottom: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    #discover-container .image-preview-container {
        aspect-ratio: 3 / 4.7;
        width: 100%;
        height: auto;
        max-height: none;
    }

    #discover-container .image-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    @media (max-width: 768px) {
        .tabs-nav {
            flex-wrap: nowrap;
        }

        .row {
            grid-template-columns: 1fr;
        }

        .save-bar {
            flex-direction: column;
            padding: 1rem;
        }

        .save-bar-actions {
            width: 100%;
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        /* Mobile: 3 cards per row - matching frontend */
        #gifts-container,
        #discover-container {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            padding: 0 15px;
        }

        #gifts-container .image-preview-container,
        #discover-container .image-preview-container {
            aspect-ratio: 3 / 5.64;
            max-height: none;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        /* Tablet: 3 cards per row - matching frontend */
        #gifts-container,
        #discover-container {
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            padding: 0 25px;
        }

        #gifts-container .image-preview-container,
        #discover-container .image-preview-container {
            aspect-ratio: 3 / 4.7;
            max-height: none;
        }
    }
    .content {
    padding: 14px 3px !important;
    }
</style>
@endpush

@section('content')
<div class="home-page-container">
    {{-- <div class="page-intro">
        <h2>
            <span class="ar-text">مرحباً في إدارة الصفحة الرئيسية 🏠</span>
            <span class="en-text">Welcome to Home Page Management 🏠</span>
        </h2>
        <p>
            <span class="ar-text">قم بتخصيص جميع عناصر الصفحة الرئيسية بسهولة - الصور، النصوص، البانرات وغيرها</span>
            <span class="en-text">Easily customize all home page elements - images, texts, banners and more</span>
        </p>
    </div> --}}



    <!-- Language Selector -->
    {{-- CRITICAL NOTICE: This selector is for CONTENT LANGUAGE ONLY, NOT dashboard interface language --}}
    {{-- Dashboard language is controlled by the toggle button in the top header (session-based) --}}
    {{-- Changing content language here will NOT and MUST NOT change dashboard interface language --}}
    <div class="language-card" style="margin-bottom: 24px; border: 3px dashed #dc3545; position: relative; background: #fff5f5;">
        <div style="position: absolute; top: -12px; left: 20px; background: white; padding: 0 10px; font-size: 11px; font-weight: 700; color: #dc3545; text-transform: uppercase; letter-spacing: 0.5px;">
            <span class="ar-text">⚠️ محدد لغة المحتوى فقط (ليس لوحة التحكم)</span>
            <span class="en-text">⚠️ Content Language Only (NOT Dashboard)</span>
        </div>
        <div class="language-label">
            <i class="fas fa-language" style="font-size: 20px; color: #dc3545;"></i>
            <span class="ar-text">اختر لغة المحتوى للتعديل (لوحة التحكم تبقى بنفس اللغة)</span>
            <span class="en-text">Select Content Language to Edit (Content Only)</span>
        </div>
        <p style="font-size: 13px; color: #666; margin: 8px 0 12px; line-height: 1.5;">
            <span class="ar-text">💡 هذا المحدد للمحتوى فقط! لتغيير لغة عرض لوحة التحكم استخدم زر اللغة في القائمة العلوية</span>
            <span class="en-text">💡 This selector is for content only! To change dashboard interface language, use the language button in the top menu</span>
        </p>
        <form action="{{ route('admin.home.edit') }}" method="GET" style="margin: 0;">
            <select name="content_lang" onchange="this.form.submit()" class="language-selector">
                <option value="ar" {{ $contentLang === 'ar' ? 'selected' : '' }}>🇸🇦 محتوى عربي (Arabic Content)</option>
                <option value="en" {{ $contentLang === 'en' ? 'selected' : '' }}>🇬🇧 محتوى إنجليزي (English Content)</option>
            </select>
        </form>
    </div>

    <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data" id="homePageForm">
        @csrf
        {{-- ARCHITECTURE FIX: Using content_lang prevents ANY conflict with dashboard locale --}}
        {{-- content_lang = content version only, locale = dashboard only, ZERO overlap --}}
        <input type="hidden" name="content_lang" value="{{ $contentLang }}">

        <div class="tabs-wrapper">
            <nav class="tabs-nav">
                <button type="button" class="tab-button active" onclick="switchTab(event, 'hero')">
                    <i class="fas fa-images"></i>
                    <span class="ar-text">Hero Slider</span>
                    <span class="en-text">Hero Slider</span>
                </button>
                <button type="button" class="tab-button" onclick="switchTab(event, 'cyber')">
                    <i class="fas fa-fire"></i>
                    <span class="ar-text">Cyber Sale</span>
                    <span class="en-text">Cyber Sale</span>
                </button>
                <button type="button" class="tab-button" onclick="switchTab(event, 'gifts')">
                    <i class="fas fa-gift"></i>
                    <span class="ar-text">الهدايا</span>
                    <span class="en-text">Gifts</span>
                </button>
                <button type="button" class="tab-button" onclick="switchTab(event, 'dg')">
                    <i class="fas fa-image"></i>
                    <span class="ar-text">DG Banner</span>
                    <span class="en-text">DG Banner</span>
                </button>
                <button type="button" class="tab-button" onclick="switchTab(event, 'gucci')">
                    <i class="fas fa-crown"></i>
                    <span class="ar-text">Gucci Spotlight</span>
                    <span class="en-text">Gucci Spotlight</span>
                </button>
                <button type="button" class="tab-button" onclick="switchTab(event, 'featured')">
                    <i class="fas fa-star"></i>
                    <span class="ar-text">البانر المميز</span>
                    <span class="en-text">Featured</span>
                </button>
                <button type="button" class="tab-button" onclick="switchTab(event, 'spotlight')">
                    <i class="fas fa-lightbulb"></i>
                    <span class="ar-text">Spotlight</span>
                    <span class="en-text">Spotlight</span>
                </button>
                <button type="button" class="tab-button" onclick="switchTab(event, 'discover')">
                    <i class="fas fa-search"></i>
                    <span class="ar-text">الاكتشاف</span>
                    <span class="en-text">Discover</span>
                </button>
                <button type="button" class="tab-button" onclick="switchTab(event, 'promo')">
                    <i class="fas fa-mobile-alt"></i>
                    <span class="ar-text">العضوية</span>
                    <span class="en-text">Membership</span>
                </button>
            </nav>

            <!-- Hero Slider Tab -->
            <div id="hero-tab" class="tab-content active">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="ar-text">صور البانر الرئيسي</span>
                        <span class="en-text">Hero Banner Images</span>
                    </h2>
                    <p class="section-description">
                        <span class="ar-text">الصور المتحركة في أعلى الصفحة الرئيسية - يمكنك إضافة عدة صور</span>
                        <span class="en-text">Rotating images at the top of home page - you can add multiple slides</span>
                    </p>
                </div>

                <div id="hero-slides-container">
                    @foreach($homePage->hero_slides ?? [] as $index => $slide)
                    <div class="item-card hero-slide-item" data-index="{{ $index }}">
                        <div class="item-card-header">
                            <div class="item-card-title">
                                <span class="item-number">{{ $index + 1 }}</span>
                                <span class="ar-text">صورة رقم {{ $index + 1 }}</span>
                                <span class="en-text">Slide #{{ $index + 1 }}</span>
                            </div>
                            <button type="button" onclick="removeItem(this, 'hero')" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i>
                                <span class="ar-text">حذف</span>
                                <span class="en-text">Delete</span>
                            </button>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">الصورة الحالية</span>
                                <span class="en-text">Current Image</span>
                            </label>
                            <div class="image-preview-container" id="hero-preview-{{ $index }}" style="display: block;">
                                <img src="{{ $slide['image'] }}" alt="Hero {{ $index + 1 }}">
                                <button type="button" class="image-remove-btn" onclick="removeImage(document.getElementById('hero-preview-{{ $index }}'), document.querySelector('input[name=\'hero_slide_image[{{ $index }}]\']'))">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span class="ar-text">حذف</span>
                                    <span class="en-text">Delete</span>
                                </button>
                            </div>
                            <input type="hidden" name="hero_slides[{{ $index }}][image]" value="{{ $slide['image'] }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">💻 تغيير الصورة (للحاسوب)</span>
                                <span class="en-text">💻 Change Image (Desktop)</span>
                            </label>
                            <div class="file-input-custom">
                                <input type="file" name="hero_slide_image[{{ $index }}]" class="form-control" accept="image/*" data-preview="hero-preview-{{ $index }}" onchange="previewImage(this)">
                            </div>
                            <p class="helper-text">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="ar-text">الحجم المثالي: 1920x800 بكسل | JPG, PNG</span>
                                <span class="en-text">Optimal size: 1920x800px | JPG, PNG</span>
                            </p>
                        </div>

                        @php
                            $tabletSlide = $homePage->hero_slides_tablet[$index] ?? null;
                            $mobileSlide = $homePage->hero_slides_mobile[$index] ?? null;
                        @endphp

                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">📱 صورة التابلت (اختياري)</span>
                                <span class="en-text">📱 Tablet Image (Optional)</span>
                            </label>
                            @if($tabletSlide && isset($tabletSlide['image']))
                            <div class="image-preview-container" id="hero-tablet-preview-{{ $index }}" style="display: block;">
                                <img src="{{ $tabletSlide['image'] }}" alt="Hero Tablet {{ $index + 1 }}">
                                <button type="button" class="image-remove-btn" onclick="removeImage(document.getElementById('hero-tablet-preview-{{ $index }}'), document.querySelector('input[name=\'hero_slide_tablet_image[{{ $index }}]\']'))">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span class="ar-text">حذف</span>
                                    <span class="en-text">Delete</span>
                                </button>
                            </div>
                            <input type="hidden" name="hero_slides_tablet[{{ $index }}][image]" value="{{ $tabletSlide['image'] }}">
                            @endif
                            <div class="file-input-custom">
                                <input type="file" name="hero_slide_tablet_image[{{ $index }}]" class="form-control" accept="image/*" data-preview="hero-tablet-preview-{{ $index }}" onchange="previewImage(this)">
                            </div>
                            <p class="helper-text">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="ar-text">الحجم المثالي: 1024x600 بكسل | سيتم عرضها على التابلت</span>
                                <span class="en-text">Optimal size: 1024x600px | Will be shown on tablets</span>
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">📱 صورة الموبايل (اختياري)</span>
                                <span class="en-text">📱 Mobile Image (Optional)</span>
                            </label>
                            @if($mobileSlide && isset($mobileSlide['image']))
                            <div class="image-preview-container" id="hero-mobile-preview-{{ $index }}" style="display: block;">
                                <img src="{{ $mobileSlide['image'] }}" alt="Hero Mobile {{ $index + 1 }}">
                                <button type="button" class="image-remove-btn" onclick="removeImage(document.getElementById('hero-mobile-preview-{{ $index }}'), document.querySelector('input[name=\'hero_slide_mobile_image[{{ $index }}]\']'))">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span class="ar-text">حذف</span>
                                    <span class="en-text">Delete</span>
                                </button>
                            </div>
                            <input type="hidden" name="hero_slides_mobile[{{ $index }}][image]" value="{{ $mobileSlide['image'] }}">
                            @endif
                            <div class="file-input-custom">
                                <input type="file" name="hero_slide_mobile_image[{{ $index }}]" class="form-control" accept="image/*" data-preview="hero-mobile-preview-{{ $index }}" onchange="previewImage(this)">
                            </div>
                            <p class="helper-text">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="ar-text">الحجم المثالي: 768x600 بكسل | سيتم عرضها على الهواتف</span>
                                <span class="en-text">Optimal size: 768x600px | Will be shown on mobile phones</span>
                            </p>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="form-label">
                                    <span class="ar-text">الرابط (URL)</span>
                                    <span class="en-text">Link (URL)</span>
                                </label>
                                <input type="text" name="hero_slides[{{ $index }}][link]" value="{{ $slide['link'] ?? '#' }}" class="form-control" placeholder="https://example.com">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <span class="ar-text">النص البديل</span>
                                    <span class="en-text">Alt Text</span>
                                </label>
                                <input type="text" name="hero_slides[{{ $index }}][alt]" value="{{ $slide['alt'] ?? '' }}" class="form-control" placeholder="Hero Banner">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if(count($homePage->hero_slides ?? []) == 0)
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p>
                        <span class="ar-text">لا توجد صور بانر حالياً</span>
                        <span class="en-text">No banner slides yet</span>
                    </p>
                </div>
                @endif

                <button type="button" onclick="addHeroSlide()" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span class="ar-text">إضافة صورة جديدة</span>
                    <span class="en-text">Add New Slide</span>
                </button>
            </div>

            <!-- Cyber Sale Tab -->
            <div id="cyber-tab" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="ar-text">قسم Cyber Sale</span>
                        <span class="en-text">Cyber Sale Section</span>
                    </h2>
                    <p class="section-description">
                        <span class="ar-text">بانر العروض الخاصة والتخفيضات</span>
                        <span class="en-text">Special offers and discounts banner</span>
                    </p>
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" name="cyber_sale_active" value="1" id="cyber_sale_active" {{ $homePage->cyber_sale_active ? 'checked' : '' }}>
                    <label for="cyber_sale_active">
                        <span class="ar-text">✅ تفعيل قسم Cyber Sale</span>
                        <span class="en-text">✅ Enable Cyber Sale Section</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">💻 صورة البانر (للحاسوب)</span>
                        <span class="en-text">💻 Banner Image (Desktop)</span>
                    </label>
                    <div class="image-preview-container" id="cyber-preview" @if($homePage->cyber_sale_image) style="display: block;" @else style="display: none;" @endif>
                        <img src="{{ $homePage->cyber_sale_image ?? '' }}" alt="Cyber Sale">
                    </div>
                    <input type="hidden" name="cyber_sale_image_current" value="{{ $homePage->cyber_sale_image ?? '' }}">
                    <div class="file-input-custom">
                        <input type="file" name="cyber_sale_image" class="form-control image-upload" accept="image/*" data-preview="cyber-preview" onchange="previewImage(this)">
                    </div>
                    <p class="helper-text">
                        <i class="fas fa-info-circle"></i>
                        <span class="ar-text">الحجم المثالي: 1920x600 بكسل</span>
                        <span class="en-text">Optimal size: 1920x600px</span>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">📱 صورة التابلت (اختياري)</span>
                        <span class="en-text">📱 Tablet Image (Optional)</span>
                    </label>
                    <div class="image-preview-container" id="cyber-tablet-preview" @if($homePage->cyber_sale_image_tablet) style="display: block;" @else style="display: none;" @endif>
                        <img src="{{ $homePage->cyber_sale_image_tablet ?? '' }}" alt="Cyber Sale Tablet">
                    </div>
                    <input type="hidden" name="cyber_sale_image_tablet_current" value="{{ $homePage->cyber_sale_image_tablet ?? '' }}">
                    <div class="file-input-custom">
                        <input type="file" name="cyber_sale_image_tablet" class="form-control image-upload" accept="image/*" data-preview="cyber-tablet-preview" onchange="previewImage(this)">
                    </div>
                    <p class="helper-text">
                        <i class="fas fa-info-circle"></i>
                        <span class="ar-text">الحجم المثالي: 1024x400 بكسل | سيتم عرضها على التابلت</span>
                        <span class="en-text">Optimal size: 1024x400px | Will be shown on tablets</span>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">📱 صورة الموبايل (اختياري)</span>
                        <span class="en-text">📱 Mobile Image (Optional)</span>
                    </label>
                    <div class="image-preview-container" id="cyber-mobile-preview" @if($homePage->cyber_sale_image_mobile) style="display: block;" @else style="display: none;" @endif>
                        <img src="{{ $homePage->cyber_sale_image_mobile ?? '' }}" alt="Cyber Sale Mobile">
                    </div>
                    <input type="hidden" name="cyber_sale_image_mobile_current" value="{{ $homePage->cyber_sale_image_mobile ?? '' }}">
                    <div class="file-input-custom">
                        <input type="file" name="cyber_sale_image_mobile" class="form-control image-upload" accept="image/*" data-preview="cyber-mobile-preview" onchange="previewImage(this)">
                    </div>
                    <p class="helper-text">
                        <i class="fas fa-info-circle"></i>
                        <span class="ar-text">الحجم المثالي: 768x400 بكسل | سيتم عرضها على الهواتف</span>
                        <span class="en-text">Optimal size: 768x400px | Will be shown on mobile phones</span>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">رابط البانر</span>
                        <span class="en-text">Banner Link</span>
                    </label>
                    <input type="text" name="cyber_sale_link" value="{{ $homePage->cyber_sale_link ?? '#' }}" class="form-control" placeholder="https://...">
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">العنوان (عربي)</span>
                            <span class="en-text">Title (Arabic)</span>
                        </label>
                        <input type="text" name="cyber_sale_title_ar" value="{{ $homePage->cyber_sale_title['ar'] ?? '' }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">العنوان (إنجليزي)</span>
                            <span class="en-text">Title (English)</span>
                        </label>
                        <input type="text" name="cyber_sale_title_en" value="{{ $homePage->cyber_sale_title['en'] ?? '' }}" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">نص الزر (عربي)</span>
                            <span class="en-text">Button Text (Arabic)</span>
                        </label>
                        <input type="text" name="cyber_sale_button_text_ar" value="{{ $homePage->cyber_sale_button_text['ar'] ?? '' }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">نص الزر (إنجليزي)</span>
                            <span class="en-text">Button Text (English)</span>
                        </label>
                        <input type="text" name="cyber_sale_button_text_en" value="{{ $homePage->cyber_sale_button_text['en'] ?? '' }}" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Gifts Tab -->
            <div id="gifts-tab" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="ar-text">قسم الهدايا</span>
                        <span class="en-text">Gifts Section</span>
                    </h2>
                    <p class="section-description">
                        <span class="ar-text">عرض بطاقات الهدايا والمنتجات المميزة</span>
                        <span class="en-text">Display gift cards and featured products</span>
                    </p>
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" name="gifts_section_active" value="1" id="gifts_section_active" {{ $homePage->gifts_section_active ? 'checked' : '' }}>
                    <label for="gifts_section_active">
                        <span class="ar-text">✅ تفعيل قسم الهدايا</span>
                        <span class="en-text">✅ Enable Gifts Section</span>
                    </label>
                </div>

                <div class="item-card" style="margin-bottom: 1.5rem;">
                    <h3 class="item-card-title" style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color);">
                        <i class="fas fa-heading" style="color: var(--primary-color);"></i>
                        <span class="ar-text">عنوان قسم الهدايا</span>
                        <span class="en-text">Gifts Section Title</span>
                    </h3>

                    <div class="row">
                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">العنوان (عربي)</span>
                                <span class="en-text">Title (Arabic)</span>
                            </label>
                            <input type="text" name="gifts_section_title_ar" value="{{ $giftsTitle->title_ar ?? '' }}" class="form-control" placeholder="هدايا مثالية للرجل الإماراتي">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">العنوان (إنجليزي)</span>
                                <span class="en-text">Title (English)</span>
                            </label>
                            <input type="text" name="gifts_section_title_en" value="{{ $giftsTitle->title_en ?? '' }}" class="form-control" placeholder="Perfect Gifts for Emirati Men">
                        </div>
                    </div>
                </div>

                <div id="gifts-container">
                    @foreach($homePage->gifts_items ?? [] as $index => $gift)
                    <div class="item-card gift-item" data-index="{{ $index }}">
                        <div class="item-card-header">
                            <div class="item-card-title">
                                <span class="item-number">{{ $index + 1 }}</span>
                                <span class="ar-text">هدية رقم {{ $index + 1 }}</span>
                                <span class="en-text">Gift #{{ $index + 1 }}</span>
                            </div>
                            <button type="button" onclick="removeItem(this, 'gift')" class="btn btn-danger btn-sm">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span class="ar-text">حذف</span>
                                <span class="en-text">Delete</span>
                            </button>
                        </div>

                        <!-- Gift Image -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">الصورة الحالية</span>
                                <span class="en-text">Current Image</span>
                            </label>
                            @php
                                $currentImage = is_array($gift['image'] ?? null) ? ($gift['image']['ar'] ?? $gift['image']['en'] ?? '') : ($gift['image'] ?? '');
                            @endphp
                            <div class="image-preview-container" id="gift-preview-{{ $index }}" @if($currentImage) style="display: block;" @else style="display: none;" @endif>
                                <img src="{{ $currentImage }}" alt="Gift {{ $index + 1 }}">
                            </div>
                            <input type="hidden" name="gifts_items[{{ $index }}][image][ar]" value="{{ $currentImage }}">
                            <input type="hidden" name="gifts_items[{{ $index }}][image][en]" value="{{ $currentImage }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">{{ $currentImage ? 'تغيير الصورة' : 'رفع الصورة' }}</span>
                                <span class="en-text">{{ $currentImage ? 'Change Image' : 'Upload Image' }}</span>
                            </label>
                            <div class="file-input-custom">
                                <input type="file" name="gift_image[{{ $index }}]" class="form-control image-upload" accept="image/*" data-preview="gift-preview-{{ $index }}" onchange="previewImage(this)">
                            </div>
                            <p class="helper-text">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="ar-text">الحجم المثالي: 400x600 بكسل</span>
                                <span class="en-text">Optimal size: 400x600px</span>
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">الرابط</span>
                                <span class="en-text">Link</span>
                            </label>
                            <input type="text" name="gifts_items[{{ $index }}][link]" value="{{ $gift['link'] ?? '#' }}" class="form-control" placeholder="https://...">
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="form-label">
                                    <span class="ar-text">العنوان (عربي)</span>
                                    <span class="en-text">Title (Arabic)</span>
                                </label>
                                <input type="text" name="gifts_items[{{ $index }}][title][ar]" value="{{ $gift['title']['ar'] ?? '' }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <span class="ar-text">العنوان (إنجليزي)</span>
                                    <span class="en-text">Title (English)</span>
                                </label>
                                <input type="text" name="gifts_items[{{ $index }}][title][en]" value="{{ $gift['title']['en'] ?? '' }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if(count($homePage->gifts_items ?? []) == 0)
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                    <p>
                        <span class="ar-text">لا توجد هدايا حالياً</span>
                        <span class="en-text">No gifts yet</span>
                    </p>
                </div>
                @endif

                <button type="button" onclick="addGift()" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="ar-text">إضافة هدية</span>
                    <span class="en-text">Add Gift</span>
                </button>
            </div>

            <!-- DG Banner Tab -->
            <div id="dg-tab" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="ar-text">بانر Dolce & Gabbana</span>
                        <span class="en-text">Dolce & Gabbana Banner</span>
                    </h2>
                    <p class="section-description">
                        <span class="ar-text">بانر كبير للعلامة التجارية Dolce & Gabbana Casa</span>
                        <span class="en-text">Large banner for Dolce & Gabbana Casa brand</span>
                    </p>
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" name="dg_banner_active" id="dg_banner_active" value="1" {{ $homePage->dg_banner_active ? 'checked' : '' }}>
                    <label for="dg_banner_active">
                        <span class="ar-text">تفعيل بانر DG</span>
                        <span class="en-text">Activate DG Banner</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">رابط البانر</span>
                        <span class="en-text">Banner Link</span>
                    </label>
                    <input type="text" name="dg_banner_link" value="{{ $homePage->dg_banner_link ?? '#' }}" class="form-control" placeholder="https://...">
                    <p class="helper-text">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ar-text">الرابط الذي سيتم التوجه إليه عند الضغط على البانر</span>
                        <span class="en-text">The link to navigate to when clicking the banner</span>
                    </p>
                </div>

                <div class="row">
                    <!-- Arabic Image -->
                    @if($contentLang === 'ar')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">💻 صورة البانر (عربي - للحاسوب)</span>
                            <span class="en-text">💻 Banner Image (Arabic - Desktop)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="dg_banner_image_ar" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="dg-preview-ar">
                            <input type="hidden" name="dg_banner_image_ar_current" value="{{ is_array($homePage->dg_banner_image ?? null) ? ($homePage->dg_banner_image['ar'] ?? '') : ($homePage->dg_banner_image ?? '') }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 1920x600 بكسل</span>
                            <span class="en-text">Recommended size: 1920x600 pixels</span>
                        </p>
                        @if(is_array($homePage->dg_banner_image ?? null) && !empty($homePage->dg_banner_image['ar']))
                        <div class="image-preview-container" id="dg-preview-ar" style="display: block;">
                            <img src="{{ $homePage->dg_banner_image['ar'] }}" alt="DG Banner AR">
                        </div>
                        @else
                        <div class="image-preview-container" id="dg-preview-ar" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">📱 صورة التابلت (عربي - اختياري)</span>
                            <span class="en-text">📱 Tablet Image (Arabic - Optional)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="dg_banner_image_tablet_ar" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="dg-tablet-preview-ar">
                            <input type="hidden" name="dg_banner_image_tablet_ar_current" value="{{ is_array($homePage->dg_banner_image_tablet ?? null) ? ($homePage->dg_banner_image_tablet['ar'] ?? '') : '' }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 1024x400 بكسل | سيتم عرضها على التابلت</span>
                            <span class="en-text">Recommended size: 1024x400 pixels | Will be shown on tablets</span>
                        </p>
                        @if(is_array($homePage->dg_banner_image_tablet ?? null) && !empty($homePage->dg_banner_image_tablet['ar']))
                        <div class="image-preview-container" id="dg-tablet-preview-ar" style="display: block;">
                            <img src="{{ $homePage->dg_banner_image_tablet['ar'] }}" alt="DG Banner Tablet AR">
                        </div>
                        @else
                        <div class="image-preview-container" id="dg-tablet-preview-ar" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">📱 صورة الموبايل (عربي - اختياري)</span>
                            <span class="en-text">📱 Mobile Image (Arabic - Optional)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="dg_banner_image_mobile_ar" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="dg-mobile-preview-ar">
                            <input type="hidden" name="dg_banner_image_mobile_ar_current" value="{{ is_array($homePage->dg_banner_image_mobile ?? null) ? ($homePage->dg_banner_image_mobile['ar'] ?? '') : '' }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 768x400 بكسل | سيتم عرضها على الهواتف</span>
                            <span class="en-text">Recommended size: 768x400 pixels | Will be shown on mobile phones</span>
                        </p>
                        @if(is_array($homePage->dg_banner_image_mobile ?? null) && !empty($homePage->dg_banner_image_mobile['ar']))
                        <div class="image-preview-container" id="dg-mobile-preview-ar" style="display: block;">
                            <img src="{{ $homePage->dg_banner_image_mobile['ar'] }}" alt="DG Banner Mobile AR">
                        </div>
                        @else
                        <div class="image-preview-container" id="dg-mobile-preview-ar" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- English Image -->
                    @if($contentLang === 'en')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">💻 صورة البانر (إنجليزي - للحاسوب)</span>
                            <span class="en-text">💻 Banner Image (English - Desktop)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="dg_banner_image_en" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="dg-preview-en">
                            <input type="hidden" name="dg_banner_image_en_current" value="{{ is_array($homePage->dg_banner_image ?? null) ? ($homePage->dg_banner_image['en'] ?? '') : ($homePage->dg_banner_image ?? '') }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 1920x600 بكسل</span>
                            <span class="en-text">Recommended size: 1920x600 pixels</span>
                        </p>
                        @if(is_array($homePage->dg_banner_image ?? null) && !empty($homePage->dg_banner_image['en']))
                        <div class="image-preview-container" id="dg-preview-en" style="display: block;">
                            <img src="{{ $homePage->dg_banner_image['en'] }}" alt="DG Banner EN">
                        </div>
                        @else
                        <div class="image-preview-container" id="dg-preview-en" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">📱 صورة التابلت (إنجليزي - اختياري)</span>
                            <span class="en-text">📱 Tablet Image (English - Optional)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="dg_banner_image_tablet_en" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="dg-tablet-preview-en">
                            <input type="hidden" name="dg_banner_image_tablet_en_current" value="{{ is_array($homePage->dg_banner_image_tablet ?? null) ? ($homePage->dg_banner_image_tablet['en'] ?? '') : '' }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 1024x400 بكسل | سيتم عرضها على التابلت</span>
                            <span class="en-text">Recommended size: 1024x400 pixels | Will be shown on tablets</span>
                        </p>
                        @if(is_array($homePage->dg_banner_image_tablet ?? null) && !empty($homePage->dg_banner_image_tablet['en']))
                        <div class="image-preview-container" id="dg-tablet-preview-en" style="display: block;">
                            <img src="{{ $homePage->dg_banner_image_tablet['en'] }}" alt="DG Banner Tablet EN">
                        </div>
                        @else
                        <div class="image-preview-container" id="dg-tablet-preview-en" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">📱 صورة الموبايل (إنجليزي - اختياري)</span>
                            <span class="en-text">📱 Mobile Image (English - Optional)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="dg_banner_image_mobile_en" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="dg-mobile-preview-en">
                            <input type="hidden" name="dg_banner_image_mobile_en_current" value="{{ is_array($homePage->dg_banner_image_mobile ?? null) ? ($homePage->dg_banner_image_mobile['en'] ?? '') : '' }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 768x400 بكسل | سيتم عرضها على الهواتف</span>
                            <span class="en-text">Recommended size: 768x400 pixels | Will be shown on mobile phones</span>
                        </p>
                        @if(is_array($homePage->dg_banner_image_mobile ?? null) && !empty($homePage->dg_banner_image_mobile['en']))
                        <div class="image-preview-container" id="dg-mobile-preview-en" style="display: block;">
                            <img src="{{ $homePage->dg_banner_image_mobile['en'] }}" alt="DG Banner Mobile EN">
                        </div>
                        @else
                        <div class="image-preview-container" id="dg-mobile-preview-en" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Gucci Spotlight Tab -->
            <div id="gucci-tab" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="ar-text">بانر Gucci Spotlight</span>
                        <span class="en-text">Gucci Spotlight Banner</span>
                    </h2>
                    <p class="section-description">
                        <span class="ar-text">بانر تسليط الضوء على المنتجات المميزة</span>
                        <span class="en-text">Spotlight banner for featured products</span>
                    </p>
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" name="gucci_spotlight_active" id="gucci_spotlight_active" value="1" {{ $homePage->gucci_spotlight_active ? 'checked' : '' }}>
                    <label for="gucci_spotlight_active">
                        <span class="ar-text">تفعيل بانر Gucci Spotlight</span>
                        <span class="en-text">Activate Gucci Spotlight Banner</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">رابط البانر</span>
                        <span class="en-text">Banner Link</span>
                    </label>
                    <input type="text" name="gucci_spotlight_link" value="{{ $homePage->gucci_spotlight_link ?? '#' }}" class="form-control" placeholder="https://...">
                    <p class="helper-text">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ar-text">الرابط الذي سيتم التوجه إليه عند الضغط على البانر</span>
                        <span class="en-text">The link to navigate to when clicking the banner</span>
                    </p>
                </div>

                <div class="row">
                    <!-- Arabic Image -->
                    @if($contentLang === 'ar')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">💻 صورة البانر (عربي - للحاسوب)</span>
                            <span class="en-text">💻 Banner Image (Arabic - Desktop)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="gucci_spotlight_image_ar" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="gucci-preview-ar">
                            <input type="hidden" name="gucci_spotlight_image_ar_current" value="{{ is_array($homePage->gucci_spotlight_image ?? null) ? ($homePage->gucci_spotlight_image['ar'] ?? '') : ($homePage->gucci_spotlight_image ?? '') }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 1920x600 بكسل</span>
                            <span class="en-text">Recommended size: 1920x600 pixels</span>
                        </p>
                        @if(is_array($homePage->gucci_spotlight_image ?? null) && !empty($homePage->gucci_spotlight_image['ar']))
                        <div class="image-preview-container" id="gucci-preview-ar" style="display: block;">
                            <img src="{{ $homePage->gucci_spotlight_image['ar'] }}" alt="Gucci Spotlight AR">
                        </div>
                        @else
                        <div class="image-preview-container" id="gucci-preview-ar" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">📱 صورة التابلت (عربي - اختياري)</span>
                            <span class="en-text">📱 Tablet Image (Arabic - Optional)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="gucci_spotlight_image_tablet_ar" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="gucci-tablet-preview-ar">
                            <input type="hidden" name="gucci_spotlight_image_tablet_ar_current" value="{{ is_array($homePage->gucci_spotlight_image_tablet ?? null) ? ($homePage->gucci_spotlight_image_tablet['ar'] ?? '') : '' }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 1024x400 بكسل | سيتم عرضها على التابلت</span>
                            <span class="en-text">Recommended size: 1024x400 pixels | Will be shown on tablets</span>
                        </p>
                        @if(is_array($homePage->gucci_spotlight_image_tablet ?? null) && !empty($homePage->gucci_spotlight_image_tablet['ar']))
                        <div class="image-preview-container" id="gucci-tablet-preview-ar" style="display: block;">
                            <img src="{{ $homePage->gucci_spotlight_image_tablet['ar'] }}" alt="Gucci Spotlight Tablet AR">
                        </div>
                        @else
                        <div class="image-preview-container" id="gucci-tablet-preview-ar" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">📱 صورة الموبايل (عربي - اختياري)</span>
                            <span class="en-text">📱 Mobile Image (Arabic - Optional)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="gucci_spotlight_image_mobile_ar" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="gucci-mobile-preview-ar">
                            <input type="hidden" name="gucci_spotlight_image_mobile_ar_current" value="{{ is_array($homePage->gucci_spotlight_image_mobile ?? null) ? ($homePage->gucci_spotlight_image_mobile['ar'] ?? '') : '' }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 768x400 بكسل | سيتم عرضها على الهواتف</span>
                            <span class="en-text">Recommended size: 768x400 pixels | Will be shown on mobile phones</span>
                        </p>
                        @if(is_array($homePage->gucci_spotlight_image_mobile ?? null) && !empty($homePage->gucci_spotlight_image_mobile['ar']))
                        <div class="image-preview-container" id="gucci-mobile-preview-ar" style="display: block;">
                            <img src="{{ $homePage->gucci_spotlight_image_mobile['ar'] }}" alt="Gucci Spotlight Mobile AR">
                        </div>
                        @else
                        <div class="image-preview-container" id="gucci-mobile-preview-ar" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- English Image -->
                    @if($contentLang === 'en')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">💻 صورة البانر (إنجليزي - للحاسوب)</span>
                            <span class="en-text">💻 Banner Image (English - Desktop)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="gucci_spotlight_image_en" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="gucci-preview-en">
                            <input type="hidden" name="gucci_spotlight_image_en_current" value="{{ is_array($homePage->gucci_spotlight_image ?? null) ? ($homePage->gucci_spotlight_image['en'] ?? '') : ($homePage->gucci_spotlight_image ?? '') }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 1920x600 بكسل</span>
                            <span class="en-text">Recommended size: 1920x600 pixels</span>
                        </p>
                        @if(is_array($homePage->gucci_spotlight_image ?? null) && !empty($homePage->gucci_spotlight_image['en']))
                        <div class="image-preview-container" id="gucci-preview-en" style="display: block;">
                            <img src="{{ $homePage->gucci_spotlight_image['en'] }}" alt="Gucci Spotlight EN">
                        </div>
                        @else
                        <div class="image-preview-container" id="gucci-preview-en" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">📱 صورة التابلت (إنجليزي - اختياري)</span>
                            <span class="en-text">📱 Tablet Image (English - Optional)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="gucci_spotlight_image_tablet_en" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="gucci-tablet-preview-en">
                            <input type="hidden" name="gucci_spotlight_image_tablet_en_current" value="{{ is_array($homePage->gucci_spotlight_image_tablet ?? null) ? ($homePage->gucci_spotlight_image_tablet['en'] ?? '') : '' }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 1024x400 بكسل | سيتم عرضها على التابلت</span>
                            <span class="en-text">Recommended size: 1024x400 pixels | Will be shown on tablets</span>
                        </p>
                        @if(is_array($homePage->gucci_spotlight_image_tablet ?? null) && !empty($homePage->gucci_spotlight_image_tablet['en']))
                        <div class="image-preview-container" id="gucci-tablet-preview-en" style="display: block;">
                            <img src="{{ $homePage->gucci_spotlight_image_tablet['en'] }}" alt="Gucci Spotlight Tablet EN">
                        </div>
                        @else
                        <div class="image-preview-container" id="gucci-tablet-preview-en" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">📱 صورة الموبايل (إنجليزي - اختياري)</span>
                            <span class="en-text">📱 Mobile Image (English - Optional)</span>
                        </label>
                        <div class="file-input-custom">
                            <input type="file" name="gucci_spotlight_image_mobile_en" accept="image/*" class="form-control" onchange="previewImage(this)" data-preview="gucci-mobile-preview-en">
                            <input type="hidden" name="gucci_spotlight_image_mobile_en_current" value="{{ is_array($homePage->gucci_spotlight_image_mobile ?? null) ? ($homePage->gucci_spotlight_image_mobile['en'] ?? '') : '' }}">
                        </div>
                        <p class="helper-text">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ar-text">الحجم الموصى به: 768x400 بكسل | سيتم عرضها على الهواتف</span>
                            <span class="en-text">Recommended size: 768x400 pixels | Will be shown on mobile phones</span>
                        </p>
                        @if(is_array($homePage->gucci_spotlight_image_mobile ?? null) && !empty($homePage->gucci_spotlight_image_mobile['en']))
                        <div class="image-preview-container" id="gucci-mobile-preview-en" style="display: block;">
                            <img src="{{ $homePage->gucci_spotlight_image_mobile['en'] }}" alt="Gucci Spotlight Mobile EN">
                        </div>
                        @else
                        <div class="image-preview-container" id="gucci-mobile-preview-en" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Featured Banner Tab -->
            <div id="featured-tab" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="ar-text">البانر المميز</span>
                        <span class="en-text">Featured Banner</span>
                    </h2>
                    <p class="section-description">
                        <span class="ar-text">بانر عرض كبير في منتصف الصفحة</span>
                        <span class="en-text">Large promotional banner in the middle of the page</span>
                    </p>
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" name="featured_banner_active" value="1" id="featured_banner_active" {{ $homePage->featured_banner_active ? 'checked' : '' }}>
                    <label for="featured_banner_active">
                        <span class="ar-text">✅ تفعيل البانر المميز</span>
                        <span class="en-text">✅ Enable Featured Banner</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">💻 صورة البانر (للحاسوب)</span>
                        <span class="en-text">💻 Banner Image (Desktop)</span>
                    </label>
                    <div class="image-preview-container" id="featured-preview" @if($homePage->featured_banner_image) style="display: block;" @else style="display: none;" @endif>
                        <img src="{{ $homePage->featured_banner_image ?? '' }}" alt="Featured Banner">
                    </div>
                    <input type="hidden" name="featured_banner_image_current" value="{{ $homePage->featured_banner_image ?? '' }}">
                    <div class="file-input-custom">
                        <input type="file" name="featured_banner_image" class="form-control image-upload" accept="image/*" data-preview="featured-preview" onchange="previewImage(this)">
                    </div>
                    <p class="helper-text">
                        <i class="fas fa-info-circle"></i>
                        <span class="ar-text">الحجم المثالي: 1920x600 بكسل</span>
                        <span class="en-text">Optimal size: 1920x600px</span>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">📱 صورة التابلت (اختياري)</span>
                        <span class="en-text">📱 Tablet Image (Optional)</span>
                    </label>
                    <div class="image-preview-container" id="featured-tablet-preview" @if($homePage->featured_banner_image_tablet) style="display: block;" @else style="display: none;" @endif>
                        <img src="{{ $homePage->featured_banner_image_tablet ?? '' }}" alt="Featured Banner Tablet">
                    </div>
                    <input type="hidden" name="featured_banner_image_tablet_current" value="{{ $homePage->featured_banner_image_tablet ?? '' }}">
                    <div class="file-input-custom">
                        <input type="file" name="featured_banner_image_tablet" class="form-control image-upload" accept="image/*" data-preview="featured-tablet-preview" onchange="previewImage(this)">
                    </div>
                    <p class="helper-text">
                        <i class="fas fa-info-circle"></i>
                        <span class="ar-text">الحجم المثالي: 1024x400 بكسل | سيتم عرضها على التابلت</span>
                        <span class="en-text">Optimal size: 1024x400px | Will be shown on tablets</span>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">📱 صورة الموبايل (اختياري)</span>
                        <span class="en-text">📱 Mobile Image (Optional)</span>
                    </label>
                    <div class="image-preview-container" id="featured-mobile-preview" @if($homePage->featured_banner_image_mobile) style="display: block;" @else style="display: none;" @endif>
                        <img src="{{ $homePage->featured_banner_image_mobile ?? '' }}" alt="Featured Banner Mobile">
                    </div>
                    <input type="hidden" name="featured_banner_image_mobile_current" value="{{ $homePage->featured_banner_image_mobile ?? '' }}">
                    <div class="file-input-custom">
                        <input type="file" name="featured_banner_image_mobile" class="form-control image-upload" accept="image/*" data-preview="featured-mobile-preview" onchange="previewImage(this)">
                    </div>
                    <p class="helper-text">
                        <i class="fas fa-info-circle"></i>
                        <span class="ar-text">الحجم المثالي: 768x400 بكسل | سيتم عرضها على الهواتف</span>
                        <span class="en-text">Optimal size: 768x400px | Will be shown on mobile phones</span>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">رابط البانر</span>
                        <span class="en-text">Banner Link</span>
                    </label>
                    <input type="text" name="featured_banner_link" value="{{ $homePage->featured_banner_link ?? '#' }}" class="form-control" placeholder="https://...">
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">العنوان (عربي)</span>
                            <span class="en-text">Title (Arabic)</span>
                        </label>
                        <input type="text" name="featured_banner_title_ar" value="{{ $homePage->featured_banner_title['ar'] ?? '' }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">العنوان (إنجليزي)</span>
                            <span class="en-text">Title (English)</span>
                        </label>
                        <input type="text" name="featured_banner_title_en" value="{{ $homePage->featured_banner_title['en'] ?? '' }}" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Spotlight Banner Tab -->
            <div id="spotlight-tab" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="ar-text">بانر Spotlight</span>
                        <span class="en-text">Spotlight Banner</span>
                    </h2>
                    <p class="section-description">
                        <span class="ar-text">بانر الإضاءة الخاص بالمنتجات المميزة</span>
                        <span class="en-text">Spotlight banner for featured products</span>
                    </p>
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" name="spotlight_banner_active" value="1" id="spotlight_banner_active" {{ $homePage->spotlight_banner_active ? 'checked' : '' }}>
                    <label for="spotlight_banner_active">
                        <span class="ar-text">✅ تفعيل بانر Spotlight</span>
                        <span class="en-text">✅ Enable Spotlight Banner</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">صورة البانر</span>
                        <span class="en-text">Banner Image</span>
                    </label>
                    <div class="image-preview-container" id="spotlight-preview" @if($homePage->spotlight_banner_image) style="display: block;" @else style="display: none;" @endif>
                        <img src="{{ $homePage->spotlight_banner_image ?? '' }}" alt="Spotlight Banner">
                    </div>
                    <input type="hidden" name="spotlight_banner_image_current" value="{{ $homePage->spotlight_banner_image ?? '' }}">
                    <div class="file-input-custom">
                        <input type="file" name="spotlight_banner_image" class="form-control image-upload" accept="image/*" data-preview="spotlight-preview" onchange="previewImage(this)">
                    </div>
                    <p class="helper-text">
                        <i class="fas fa-info-circle"></i>
                        <span class="ar-text">الحجم المثالي: 1920x600 بكسل</span>
                        <span class="en-text">Optimal size: 1920x600px</span>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">رابط البانر</span>
                        <span class="en-text">Banner Link</span>
                    </label>
                    <input type="text" name="spotlight_banner_link" value="{{ $homePage->spotlight_banner_link ?? '#' }}" class="form-control" placeholder="https://...">
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">العنوان (عربي)</span>
                            <span class="en-text">Title (Arabic)</span>
                        </label>
                        <input type="text" name="spotlight_banner_title_ar" value="{{ $homePage->spotlight_banner_title['ar'] ?? '' }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">العنوان (إنجليزي)</span>
                            <span class="en-text">Title (English)</span>
                        </label>
                        <input type="text" name="spotlight_banner_title_en" value="{{ $homePage->spotlight_banner_title['en'] ?? '' }}" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Discover Tab -->
            <div id="discover-tab" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="ar-text">قسم الاكتشاف</span>
                        <span class="en-text">Discover Section</span>
                    </h2>
                    <p class="section-description">
                        <span class="ar-text">اكتشف المزيد من المنتجات والعروض</span>
                        <span class="en-text">Discover more products and offers</span>
                    </p>
                </div>

                <div style="background: #e3f2fd; border: 2px solid #2196f3; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: start; gap: 0.75rem;">
                        <svg fill="none" stroke="#2196f3" viewBox="0 0 24 24" style="width: 24px; height: 24px; flex-shrink: 0; margin-top: 2px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <strong style="color: #1976d2; display: block; margin-bottom: 0.5rem;">
                                <span class="ar-text">ملاحظة هامة عن ترتيب العناصر:</span>
                                <span class="en-text">Important Note About Item Order:</span>
                            </strong>
                            <p style="margin: 0; color: #0d47a1; line-height: 1.6;">
                                <span class="ar-text">
                                    • أول <strong>3 عناصر</strong> ستظهر في صف واحد (grid)<br>
                                    • آخر <strong>عنصرين</strong> (رقم 4 و 5) ستظهر في صف منفصل بتصميم عريض (wide cards)<br>
                                    • يُفضل رفع 5 عناصر بالمجموع للحصول على أفضل تصميم في الواجهة
                                </span>
                                <span class="en-text">
                                    • First <strong>3 items</strong> will appear in one row (grid)<br>
                                    • Last <strong>2 items</strong> (#4 and #5) will appear in a separate row as wide cards<br>
                                    • It's recommended to add 5 items total for the best frontend layout
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" name="discover_section_active" value="1" id="discover_section_active" {{ $homePage->discover_section_active ? 'checked' : '' }}>
                    <label for="discover_section_active">
                        <span class="ar-text">✅ تفعيل قسم الاكتشاف</span>
                        <span class="en-text">✅ Enable Discover Section</span>
                    </label>
                </div>

                <div id="discover-container">
                    @foreach($discoverItems ?? [] as $index => $item)
                    @php
                        $isWideCard = $index >= 3; // Items 4 and 5 (index 3, 4) are wide cards
                        $cardTypeLabel = $index < 3 ?
                            ($contentLang == 'ar' ? '(كرت صغير - Grid)' : '(Small Card - Grid)') :
                            ($contentLang == 'ar' ? '(كرت عريض - Wide)' : '(Wide Card)');
                        $cardTypeColor = $index < 3 ? '#4caf50' : '#ff9800';
                    @endphp
                    <div class="item-card discover-item" data-index="{{ $index }}" style="border-left: 4px solid {{ $cardTypeColor }};">
                        <div class="item-card-header">
                            <div class="item-card-title">
                                <span class="item-number" style="background: {{ $cardTypeColor }};">{{ $index + 1 }}</span>
                                <span class="ar-text">عنصر رقم {{ $index + 1 }} {{ $cardTypeLabel }}</span>
                                <span class="en-text">Item #{{ $index + 1 }} {{ $cardTypeLabel }}</span>
                            </div>
                            <button type="button" onclick="removeDiscoverItem({{ $item->id }})" class="btn btn-danger btn-sm">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span class="ar-text">حذف</span>
                                <span class="en-text">Delete</span>
                            </button>
                        </div>

                        <input type="hidden" name="discover_items[{{ $index }}][id]" value="{{ $item->id }}">

                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">الصورة الحالية</span>
                                <span class="en-text">Current Image</span>
                            </label>
                            <div class="image-preview-container" id="discover-preview-{{ $index }}" @if($item->image) style="display: block;" @else style="display: none;" @endif>
                                <img src="{{ $item->image }}" alt="Discover {{ $index + 1 }}">
                            </div>
                            <input type="hidden" name="discover_items[{{ $index }}][image]" value="{{ $item->image }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">{{ $item->image ? 'تغيير الصورة' : 'رفع الصورة' }}</span>
                                <span class="en-text">{{ $item->image ? 'Change Image' : 'Upload Image' }}</span>
                            </label>
                            <div class="file-input-custom">
                                <input type="file" name="discover_image[{{ $index }}]" class="form-control image-upload" accept="image/*" data-preview="discover-preview-{{ $index }}" onchange="previewImage(this)">
                            </div>
                            <p class="helper-text">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="ar-text">الحجم المثالي: 600x400 بكسل</span>
                                <span class="en-text">Optimal size: 600x400px</span>
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <span class="ar-text">الرابط</span>
                                <span class="en-text">Link</span>
                            </label>
                            <input type="text" name="discover_items[{{ $index }}][link]" value="{{ $item->link }}" class="form-control" placeholder="https://...">
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="form-label">
                                    <span class="ar-text">العنوان (عربي)</span>
                                    <span class="en-text">Title (Arabic)</span>
                                </label>
                                <input type="text" name="discover_items[{{ $index }}][title][ar]" value="{{ $item->title['ar'] ?? '' }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <span class="ar-text">العنوان (إنجليزي)</span>
                                    <span class="en-text">Title (English)</span>
                                </label>
                                <input type="text" name="discover_items[{{ $index }}][title][en]" value="{{ $item->title['en'] ?? '' }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if(count($discoverItems ?? []) == 0)
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p>
                        <span class="ar-text">لا توجد عناصر اكتشاف حالياً</span>
                        <span class="en-text">No discover items yet</span>
                    </p>
                </div>
                @endif

                <button type="button" onclick="addDiscover()" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="ar-text">إضافة عنصر اكتشاف</span>
                    <span class="en-text">Add Discover Item</span>
                </button>
            </div>

            <!-- Promo Tab (Membership & App) -->
            <div id="promo-tab" class="tab-content">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="ar-text">العضوية والتطبيق</span>
                        <span class="en-text">Membership & App</span>
                    </h2>
                    <p class="section-description">
                        <span class="ar-text">قسم التطبيق والعضويات الخاصة</span>
                        <span class="en-text">App promotion and membership section</span>
                    </p>
                </div>

                <!-- Membership -->
                <div class="item-card">
                    <h3 class="item-card-title" style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color);">
                        <i class="fas fa-crown" style="color: var(--warning-color);"></i>
                        <span class="ar-text">قسم العضوية</span>
                        <span class="en-text">Membership Section</span>
                    </h3>

                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="membership_section_active" value="1" id="membership_section_active" {{ $homePage->membership_section_active ? 'checked' : '' }}>
                        <label for="membership_section_active">
                            <span class="ar-text">✅ تفعيل قسم العضوية</span>
                            <span class="en-text">✅ Enable Membership Section</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">صورة القسم</span>
                            <span class="en-text">Section Image</span>
                        </label>
                        <div class="image-preview-container" id="membership-preview" @if($homePage->membership_image) style="display: block;" @else style="display: none;" @endif>
                            <img src="{{ $homePage->membership_image ?? '' }}" alt="Membership">
                        </div>
                        <input type="hidden" name="membership_image_current" value="{{ $homePage->membership_image ?? '' }}">
                        <div class="file-input-custom">
                            <input type="file" name="membership_image" class="form-control image-upload" accept="image/*" data-preview="membership-preview" onchange="previewImage(this)">
                        </div>
                        <p class="helper-text">
                            <i class="fas fa-info-circle"></i>
                            <span class="ar-text">الحجم المثالي: 800x600 بكسل</span>
                            <span class="en-text">Optimal size: 800x600px</span>
                        </p>
                    </div>

                    @if($contentLang === 'ar')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">العنوان (عربي)</span>
                            <span class="en-text">Title (Arabic)</span>
                        </label>
                        <input type="text" name="membership_title_ar" value="{{ $homePage->membership_title['ar'] ?? '' }}" class="form-control">
                    </div>
                    @endif

                    @if($contentLang === 'en')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">العنوان (إنجليزي)</span>
                            <span class="en-text">Title (English)</span>
                        </label>
                        <input type="text" name="membership_title_en" value="{{ $homePage->membership_title['en'] ?? '' }}" class="form-control">
                    </div>
                    @endif

                    @if($contentLang === 'ar')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">الوصف (عربي)</span>
                            <span class="en-text">Description (Arabic)</span>
                        </label>
                        <textarea name="membership_desc_ar" class="form-control">{{ $homePage->membership_desc['ar'] ?? '' }}</textarea>
                    </div>
                    @endif

                    @if($contentLang === 'en')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">الوصف (إنجليزي)</span>
                            <span class="en-text">Description (English)</span>
                        </label>
                        <textarea name="membership_desc_en" class="form-control">{{ $homePage->membership_desc['en'] ?? '' }}</textarea>
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">رابط العضوية</span>
                            <span class="en-text">Membership Link</span>
                        </label>
                        <input type="text" name="membership_link" value="{{ $homePage->membership_link ?? '#' }}" class="form-control" placeholder="https://...">
                    </div>
                </div>

                <!-- App Section -->
                <div class="item-card">
                    <h3 class="item-card-title" style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color);">
                        <i class="fas fa-mobile-alt" style="color: var(--primary-color);"></i>
                        <span class="ar-text">قسم التطبيق</span>
                        <span class="en-text">App Section</span>
                    </h3>

                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="app_section_active" value="1" id="app_section_active" {{ $homePage->app_section_active ? 'checked' : '' }}>
                        <label for="app_section_active">
                            <span class="ar-text">✅ تفعيل قسم التطبيق</span>
                            <span class="en-text">✅ Enable App Section</span>
                        </label>
                    </div>

                    @if($contentLang === 'ar')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">العنوان (عربي)</span>
                            <span class="en-text">Title (Arabic)</span>
                        </label>
                        <input type="text" name="app_download_title_ar" value="{{ $homePage->app_download_title['ar'] ?? '' }}" class="form-control">
                    </div>
                    @endif

                    @if($contentLang === 'en')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">العنوان (إنجليزي)</span>
                            <span class="en-text">Title (English)</span>
                        </label>
                        <input type="text" name="app_download_title_en" value="{{ $homePage->app_download_title['en'] ?? '' }}" class="form-control">
                    </div>
                    @endif

                    @if($contentLang === 'ar')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">الوصف (عربي)</span>
                            <span class="en-text">Description (Arabic)</span>
                        </label>
                        <textarea name="app_download_subtitle_ar" class="form-control">{{ $homePage->app_download_subtitle['ar'] ?? '' }}</textarea>
                    </div>
                    @endif

                    @if($contentLang === 'en')
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">الوصف (إنجليزي)</span>
                            <span class="en-text">Description (English)</span>
                        </label>
                        <textarea name="app_download_subtitle_en" class="form-control">{{ $homePage->app_download_subtitle['en'] ?? '' }}</textarea>
                    </div>
                    @endif

                    <!-- Google Play Section -->
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">🤖 صورة شارة Google Play</span>
                            <span class="en-text">🤖 Google Play Badge Image</span>
                        </label>
                        @php
                            $googlePlayImage = '';
                            if ($homePage->app_image) {
                                if (is_array($homePage->app_image)) {
                                    $googlePlayImage = $homePage->app_image['android'] ?? '';
                                } else {
                                    $googlePlayImage = $homePage->app_image;
                                }
                            }
                        @endphp
                        <div class="image-preview-container" id="google-play-preview" @if($googlePlayImage) style="display: block;" @else style="display: none;" @endif>
                            <img src="{{ $googlePlayImage }}" alt="Google Play">
                        </div>
                        <input type="hidden" name="google_play_image_current" value="{{ $googlePlayImage }}">
                        <div class="file-input-custom">
                            <input type="file" name="google_play_image" class="form-control image-upload" accept="image/*" data-preview="google-play-preview" onchange="previewImage(this)">
                        </div>
                        <p class="helper-text">
                            <i class="fas fa-info-circle"></i>
                            <span class="ar-text">ارفع شارة Google Play المخصصة (اختياري)</span>
                            <span class="en-text">Upload custom Google Play badge (optional)</span>
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">رابط Google Play</span>
                            <span class="en-text">Google Play Link</span>
                        </label>
                        <input type="text" name="google_play_link" value="{{ $homePage->google_play_link ?? '#' }}" class="form-control" placeholder="https://play.google.com...">
                    </div>

                    <!-- App Store Section -->
                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">🍎 صورة شارة App Store</span>
                            <span class="en-text">🍎 App Store Badge Image</span>
                        </label>
                        @php
                            $appStoreImage = '';
                            if ($homePage->app_image) {
                                if (is_array($homePage->app_image)) {
                                    $appStoreImage = $homePage->app_image['ios'] ?? '';
                                } else {
                                    $appStoreImage = $homePage->app_image;
                                }
                            }
                        @endphp
                        <div class="image-preview-container" id="app-store-preview" @if($appStoreImage) style="display: block;" @else style="display: none;" @endif>
                            <img src="{{ $appStoreImage }}" alt="App Store">
                        </div>
                        <input type="hidden" name="app_store_image_current" value="{{ $appStoreImage }}">
                        <div class="file-input-custom">
                            <input type="file" name="app_store_image" class="form-control image-upload" accept="image/*" data-preview="app-store-preview" onchange="previewImage(this)">
                        </div>
                        <p class="helper-text">
                            <i class="fas fa-info-circle"></i>
                            <span class="ar-text">ارفع شارة App Store المخصصة (اختياري)</span>
                            <span class="en-text">Upload custom App Store badge (optional)</span>
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="ar-text">رابط App Store</span>
                            <span class="en-text">App Store Link</span>
                        </label>
                        <input type="text" name="app_store_link" value="{{ $homePage->app_store_link ?? '#' }}" class="form-control" placeholder="https://apps.apple.com...">
                    </div>
                </div>
            </div>

            <!-- Save Bar -->
            <div class="save-bar">
                <div class="save-bar-info">
                    <i class="fas fa-info-circle" style="font-size: 20px;"></i>
                    <span class="ar-text">تأكد من حفظ التغييرات بعد التعديل</span>
                    <span class="en-text">Make sure to save changes after editing</span>
                </div>
                <div class="save-bar-actions">
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        <span class="ar-text">إلغاء</span>
                        <span class="en-text">Cancel</span>
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        <span class="ar-text">حفظ التغييرات</span>
                        <span class="en-text">Save Changes</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Preview Toggle Button -->
<button class="preview-toggle-btn" onclick="togglePreview()" title="إظهار المعاينة / Show Preview">
    <svg fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
    </svg>
</button>

<!-- Preview Panel -->
<div class="preview-panel" id="previewPanel">
    <div class="preview-header">
        <strong>
            <span class="ar-text">معاينة مباشرة</span>
            <span class="en-text">Live Preview</span>
        </strong>
        <div class="preview-controls">
            <div class="device-mode-buttons">
                <button class="device-btn" onclick="setDeviceMode('mobile')" data-mode="mobile" title="Mobile View">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 2H7c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H7V4h10v16z"/>
                    </svg>
                    <span class="ar-text">موبايل</span>
                    <span class="en-text">Mobile</span>
                </button>
                <button class="device-btn" onclick="setDeviceMode('tablet')" data-mode="tablet" title="Tablet View">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 4H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H3V6h18v12z"/>
                    </svg>
                    <span class="ar-text">تابلت</span>
                    <span class="en-text">Tablet</span>
                </button>
                <button class="device-btn active" onclick="setDeviceMode('desktop')" data-mode="desktop" title="Desktop View">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 2H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h7l-2 3v1h8v-1l-2-3h7c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H3V4h18v12z"/>
                    </svg>
                    <span class="ar-text">حاسوب</span>
                    <span class="en-text">Desktop</span>
                </button>
            </div>
            <button onclick="refreshPreview()" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
            <a href="{{ url('/') }}" target="_blank" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
            </a>
            <button onclick="togglePreview()" class="preview-close-btn">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
                <span class="ar-text">إغلاق</span>
                <span class="en-text">Close</span>
            </button>
        </div>
    </div>
    <div class="preview-frame-container">
        <iframe id="previewFrame" class="preview-frame desktop" src="{{ url('/') }}"></iframe>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ============================================================================
// CRITICAL SEPARATION: Content Locale vs Dashboard Locale
// ============================================================================
// This page uses 'locale' URL parameter for CONTENT version selection ONLY
// It does NOT and MUST NOT affect the dashboard interface language
//
// Dashboard Language: Controlled by session, changed via top menu toggle
// Content Language: Controlled by URL parameter '?locale=en/ar'
//
// When you edit English content while dashboard is in Arabic:
// - Dashboard stays in Arabic (from session)
// - You're editing English version of content
// - After save, dashboard remains Arabic, you continue editing English content
// ============================================================================

// ARCHITECTURE FIX: content_lang completely separate from locale
const contentLang = new URLSearchParams(window.location.search).get('content_lang') || 'ar';
const contentLangInput = document.querySelector('input[name="content_lang"]');
console.log('=== Home Edit Page - FIXED ARCHITECTURE ===');
console.log('Content Language (content_lang):', contentLang, '← Edits THIS content version');
console.log('Dashboard Language (locale):', '{{ app()->getLocale() }}', '← Interface language');
console.log('Form content_lang value:', contentLangInput ? contentLangInput.value : 'NOT FOUND');
console.log('✅ ZERO CONFLICT - Different parameter names!');
console.log('✅ Dashboard language CANNOT be affected by content editing!');

let heroSlideCount = {{ count($homePage->hero_slides ?? []) }};
let giftCount = {{ count($homePage->gifts_items ?? []) }};
let discoverCount = {{ count($homePage->discover_items ?? []) }};

// Image Preview Function
function previewImage(input) {
    const previewId = input.getAttribute('data-preview');
    const previewContainer = document.getElementById(previewId);

    // Check if preview container exists
    if (!previewContainer) {
        console.error('Preview container not found:', previewId);
        return;
    }

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const img = previewContainer.querySelector('img');
            if (!img) {
                console.error('Image element not found in preview container');
                return;
            }

            img.src = e.target.result;
            previewContainer.style.display = 'block';

            // Add remove button if not exists
            if (!previewContainer.querySelector('.image-remove-btn')) {
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'image-remove-btn';
                removeBtn.onclick = function() { removeImage(previewContainer, input); };
                removeBtn.innerHTML = `
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span class="ar-text">حذف</span>
                    <span class="en-text">Delete</span>
                `;
                previewContainer.appendChild(removeBtn);
            }

            // Add animation
            previewContainer.style.opacity = '0';
            setTimeout(() => {
                previewContainer.style.transition = 'opacity 0.3s';
                previewContainer.style.opacity = '1';
            }, 10);
        };

        reader.readAsDataURL(input.files[0]);
    } else if (previewContainer) {
        previewContainer.style.display = 'none';
    }
}

// Remove Image Function - Server-side deletion
function removeImage(previewContainer, fileInput, imageType = null, imageIndex = null, deviceType = 'desktop') {
    const isArabic = document.documentElement.lang === 'ar';

    Swal.fire({
        title: isArabic ? 'حذف الصورة؟' : 'Delete Image?',
        text: isArabic ? 'هل أنت متأكد من حذف هذه الصورة من السيرفر؟' : 'Are you sure you want to delete this image from the server?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: isArabic ? 'نعم، احذف' : 'Yes, delete',
        cancelButtonText: isArabic ? 'إلغاء' : 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: isArabic ? 'جاري الحذف...' : 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Get image metadata from container ID or data attributes
            if (!imageType) {
                const containerId = previewContainer.id;
                imageType = extractImageType(containerId);
                imageIndex = extractImageIndex(containerId);
                deviceType = extractDeviceType(containerId);
            }

            const contentLang = new URLSearchParams(window.location.search).get('content_lang') || 'ar';

            // Send delete request to server
            fetch('{{ route('admin.home.deleteImage') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    content_lang: contentLang,
                    image_type: imageType,
                    image_index: imageIndex,
                    device_type: deviceType
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear file input
                    if (fileInput) {
                        fileInput.value = '';
                    }

                    // Clear hidden input if exists
                    const hiddenInput = previewContainer.parentElement.querySelector('input[type="hidden"]');
                    if (hiddenInput) {
                        hiddenInput.remove();
                    }

                    // Hide preview
                    previewContainer.style.display = 'none';

                    // Reset image src
                    const img = previewContainer.querySelector('img');
                    if (img) {
                        img.src = '';
                    }

                    Swal.fire({
                        title: isArabic ? 'تم الحذف!' : 'Deleted!',
                        text: isArabic ? 'تم حذف الصورة بنجاح من السيرفر' : 'Image deleted successfully from server',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        title: isArabic ? 'خطأ!' : 'Error!',
                        text: data.message || (isArabic ? 'فشل حذف الصورة' : 'Failed to delete image'),
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                Swal.fire({
                    title: isArabic ? 'خطأ!' : 'Error!',
                    text: isArabic ? 'حدث خطأ أثناء الحذف' : 'An error occurred while deleting',
                    icon: 'error'
                });
            });
        }
    });
}

// Helper functions to extract image metadata from container ID
function extractImageType(containerId) {
    if (containerId.includes('hero')) return 'hero';
    if (containerId.includes('cyber')) return 'cyber_sale';
    if (containerId.includes('dg')) return 'dg_banner';
    if (containerId.includes('gucci')) return 'gucci_spotlight';
    if (containerId.includes('featured')) return 'featured_banner';
    if (containerId.includes('gift')) return 'gift';
    return null;
}

function extractImageIndex(containerId) {
    const match = containerId.match(/-(\d+)$/);
    return match ? parseInt(match[1]) : null;
}

function extractDeviceType(containerId) {
    if (containerId.includes('tablet')) return 'tablet';
    if (containerId.includes('mobile')) return 'mobile';
    return 'desktop';
}

function switchTab(event, tabName) {
    // Remove active class from all tabs and buttons
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });

    // Add active class to selected tab and button
    document.getElementById(tabName + '-tab').classList.add('active');
    event.currentTarget.classList.add('active');

    // Scroll to top of content
    document.querySelector('.tabs-wrapper').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function addHeroSlide() {
    const container = document.getElementById('hero-slides-container');
    const index = heroSlideCount++;

    // Remove empty state if exists
    const emptyState = container.parentElement.querySelector('.empty-state');
    if (emptyState) emptyState.remove();

    const html = `
        <div class="item-card hero-slide-item" data-index="${index}">
            <div class="item-card-header">
                <div class="item-card-title">
                    <span class="item-number">${index + 1}</span>
                    <span class="ar-text">صورة رقم ${index + 1}</span>
                    <span class="en-text">Slide #${index + 1}</span>
                </div>
                <button type="button" onclick="removeItem(this, 'hero')" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt"></i>
                    <span class="ar-text">حذف</span>
                    <span class="en-text">Delete</span>
                </button>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">رفع الصورة</span>
                    <span class="en-text">Upload Image</span>
                </label>
                <div class="file-input-custom">
                    <input type="file" name="hero_slide_image[${index}]" class="form-control image-upload" accept="image/*" data-preview="hero-new-preview-${index}" onchange="previewImage(this)" required>
                </div>
                <div id="hero-new-preview-${index}" class="image-preview-container" style="display: none; margin-top: 1rem;">
                    <img src="" alt="Preview" style="max-width: 100%; border-radius: 8px;">
                </div>
                <p class="helper-text">
                    <i class="fas fa-info-circle"></i>
                    <span class="ar-text">الحجم المثالي: 1920x800 بكسل</span>
                    <span class="en-text">Optimal size: 1920x800px</span>
                </p>
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">الرابط</span>
                        <span class="en-text">Link</span>
                    </label>
                    <input type="text" name="hero_slides[${index}][link]" value="#" class="form-control" placeholder="https://...">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">النص البديل</span>
                        <span class="en-text">Alt Text</span>
                    </label>
                    <input type="text" name="hero_slides[${index}][alt]" value="Hero Banner ${index + 1}" class="form-control">
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

function addGift() {
    const container = document.getElementById('gifts-container');
    const index = giftCount++;

    // Remove empty state if exists
    const emptyState = container.parentElement.querySelector('.empty-state');
    if (emptyState) emptyState.remove();

    const html = `
        <div class="item-card gift-item" data-index="${index}">
            <div class="item-card-header">
                <div class="item-card-title">
                    <span class="item-number">${index + 1}</span>
                    <span class="ar-text">هدية رقم ${index + 1}</span>
                    <span class="en-text">Gift #${index + 1}</span>
                </div>
                <button type="button" onclick="removeItem(this, 'gift')" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt"></i>
                    <span class="ar-text">حذف</span>
                    <span class="en-text">Delete</span>
                </button>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">رفع الصورة</span>
                    <span class="en-text">Upload Image</span>
                </label>
                <div class="file-input-custom">
                    <input type="file" name="gift_image[${index}]" class="form-control image-upload" accept="image/*" data-preview="gift-new-preview-${index}" onchange="previewImage(this)" required>
                </div>
                <div id="gift-new-preview-${index}" class="image-preview-container" style="display: none; margin-top: 1rem;">
                    <img src="" alt="Preview" style="max-width: 100%; border-radius: 8px;">
                </div>
                <p class="helper-text">
                    <i class="fas fa-info-circle"></i>
                    <span class="ar-text">الحجم المثالي: 400x400 بكسل</span>
                    <span class="en-text">Optimal size: 400x400px</span>
                </p>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الرابط</span>
                    <span class="en-text">Link</span>
                </label>
                <input type="text" name="gifts_items[${index}][link]" value="#" class="form-control" placeholder="https://...">
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">العنوان (عربي)</span>
                        <span class="en-text">Title (Arabic)</span>
                    </label>
                    <input type="text" name="gifts_items[${index}][title][ar]" value="" class="form-control" placeholder="بطاقة هدية">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">العنوان (إنجليزي)</span>
                        <span class="en-text">Title (English)</span>
                    </label>
                    <input type="text" name="gifts_items[${index}][title][en]" value="" class="form-control" placeholder="Gift Card">
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

// Remove Discover Item from Database
function removeDiscoverItem(itemId) {
    const locale = document.querySelector('input[name="locale"]').value;
    const confirmTitle = locale === 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?';
    const confirmText = locale === 'ar' ? 'سيتم حذف هذا العنصر نهائياً!' : 'This item will be permanently deleted!';
    const confirmButton = locale === 'ar' ? 'نعم، احذف!' : 'Yes, delete it!';
    const cancelButton = locale === 'ar' ? 'إلغاء' : 'Cancel';

    Swal.fire({
        title: confirmTitle,
        text: confirmText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmButton,
        cancelButtonText: cancelButton
    }).then((result) => {
        if (result.isConfirmed) {
            // Send AJAX request to delete
            fetch(`/admin/discover-items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const deletedTitle = locale === 'ar' ? 'تم الحذف!' : 'Deleted!';
                    const deletedText = locale === 'ar' ? 'تم حذف العنصر بنجاح' : 'Item deleted successfully';

                    Swal.fire(deletedTitle, deletedText, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    const errorTitle = locale === 'ar' ? 'خطأ!' : 'Error!';
                    const errorText = locale === 'ar' ? 'حدث خطأ أثناء الحذف' : 'Error occurred while deleting';
                    Swal.fire(errorTitle, errorText, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorTitle = locale === 'ar' ? 'خطأ!' : 'Error!';
                const errorText = locale === 'ar' ? 'حدث خطأ في الاتصال' : 'Connection error occurred';
                Swal.fire(errorTitle, errorText, 'error');
            });
        }
    });
}

function addDiscover() {
    const container = document.getElementById('discover-container');
    const index = discoverCount++;

    // Remove empty state if exists
    const emptyState = container.parentElement.querySelector('.empty-state');
    if (emptyState) emptyState.remove();

    const html = `
        <div class="item-card discover-item" data-index="${index}">
            <div class="item-card-header">
                <div class="item-card-title">
                    <span class="item-number">${index + 1}</span>
                    <span class="ar-text">عنصر رقم ${index + 1}</span>
                    <span class="en-text">Item #${index + 1}</span>
                </div>
                <button type="button" onclick="removeItem(this, 'discover')" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt"></i>
                    <span class="ar-text">حذف</span>
                    <span class="en-text">Delete</span>
                </button>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">رفع الصورة</span>
                    <span class="en-text">Upload Image</span>
                </label>
                <div class="file-input-custom">
                    <input type="file" name="discover_image[${index}]" class="form-control image-upload" accept="image/*" data-preview="discover-new-preview-${index}" onchange="previewImage(this)" required>
                </div>
                <div id="discover-new-preview-${index}" class="image-preview-container" style="display: none; margin-top: 1rem;">
                    <img src="" alt="Preview" style="max-width: 100%; border-radius: 8px;">
                </div>
                <p class="helper-text">
                    <i class="fas fa-info-circle"></i>
                    <span class="ar-text">الحجم المثالي: 600x400 بكسل</span>
                    <span class="en-text">Optimal size: 600x400px</span>
                </p>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الرابط</span>
                    <span class="en-text">Link</span>
                </label>
                <input type="text" name="discover_items[${index}][link]" value="#" class="form-control" placeholder="https://...">
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">العنوان (عربي)</span>
                        <span class="en-text">Title (Arabic)</span>
                    </label>
                    <input type="text" name="discover_items[${index}][title][ar]" value="" class="form-control" placeholder="اكتشف المزيد">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="ar-text">العنوان (إنجليزي)</span>
                        <span class="en-text">Title (English)</span>
                    </label>
                    <input type="text" name="discover_items[${index}][title][en]" value="" class="form-control" placeholder="Discover More">
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

function removeItem(button, type) {
    const locale = document.querySelector('input[name="locale"]').value;
    const confirmTitle = locale === 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?';
    const confirmText = locale === 'ar' ? 'لن تتمكن من التراجع عن هذا!' : 'You won\'t be able to revert this!';
    const confirmButton = locale === 'ar' ? 'نعم، احذف!' : 'Yes, delete it!';
    const cancelButton = locale === 'ar' ? 'إلغاء' : 'Cancel';
    const deletedTitle = locale === 'ar' ? 'تم الحذف!' : 'Deleted!';
    const deletedText = locale === 'ar' ? 'تم حذف العنصر بنجاح' : 'Item has been deleted successfully';

    Swal.fire({
        title: confirmTitle,
        text: confirmText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmButton,
        cancelButtonText: cancelButton
    }).then((result) => {
        if (result.isConfirmed) {
            const itemCard = button.closest('.item-card');
            const container = itemCard.parentElement;

            itemCard.remove();

            // Renumber items
            const items = container.querySelectorAll('.item-card');
            items.forEach((item, idx) => {
                const numberSpan = item.querySelector('.item-number');
                if (numberSpan) numberSpan.textContent = idx + 1;

                // Update AR/EN text numbers
                const arText = item.querySelector('.item-card-title .ar-text');
                const enText = item.querySelector('.item-card-title .en-text');

                if (type === 'hero') {
                    if (arText) arText.textContent = `صورة رقم ${idx + 1}`;
                    if (enText) enText.textContent = `Slide #${idx + 1}`;
                } else if (type === 'gift') {
                    if (arText) arText.textContent = `هدية رقم ${idx + 1}`;
                    if (enText) enText.textContent = `Gift #${idx + 1}`;
                } else if (type === 'discover') {
                    if (arText) arText.textContent = `عنصر رقم ${idx + 1}`;
                    if (enText) enText.textContent = `Item #${idx + 1}`;
                }
            });

            // Show empty state if no items left
            if (items.length === 0) {
                let emptyMessage = '';
                let emptyIcon = '';

                if (type === 'hero') {
                    emptyMessage = locale === 'ar' ? 'لا توجد صور بانر حالياً' : 'No banner slides yet';
                    emptyIcon = 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z';
                } else if (type === 'gift') {
                    emptyMessage = locale === 'ar' ? 'لا توجد هدايا حالياً' : 'No gifts yet';
                    emptyIcon = 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7';
                } else if (type === 'discover') {
                    emptyMessage = locale === 'ar' ? 'لا توجد عناصر اكتشاف حالياً' : 'No discover items yet';
                    emptyIcon = 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z';
                }

                const emptyStateHTML = `
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${emptyIcon}"></path>
                        </svg>
                        <p>${emptyMessage}</p>
                    </div>
                `;

                container.insertAdjacentHTML('afterend', emptyStateHTML);
            }

            Swal.fire({
                title: deletedTitle,
                text: deletedText,
                icon: 'success',
                confirmButtonColor: '#48bb78',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

// Form submission confirmation
document.getElementById('homePageForm').addEventListener('submit', function(e) {
    const contentLangInput = document.querySelector('input[name="content_lang"]');
    const contentLang = contentLangInput ? contentLangInput.value : 'ar';

    // ARCHITECTURE FIX: Ensure content_lang is always sent
    if (!contentLangInput || !contentLangInput.value) {
        console.error('content_lang input missing! Adding it now...');
        const newContentLangInput = document.createElement('input');
        newContentLangInput.type = 'hidden';
        newContentLangInput.name = 'content_lang';
        newContentLangInput.value = new URLSearchParams(window.location.search).get('content_lang') || 'ar';
        this.appendChild(newContentLangInput);
    }

    console.log('Form submitting with content_lang:', contentLang);

    const submitBtn = this.querySelector('button[type="submit"]');

    submitBtn.disabled = true;
    submitBtn.innerHTML = locale === 'ar'
        ? '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...'
        : '<i class="fas fa-spinner fa-spin"></i> Saving...';

    // Disable beforeunload warning during form submission
    formChanged = false;
});

// Auto-save indicator (optional feature)
let formChanged = false;
document.getElementById('homePageForm').addEventListener('input', function() {
    formChanged = true;
    const saveBarInfo = document.querySelector('.save-bar-info');
    if (saveBarInfo && !saveBarInfo.classList.contains('has-changes')) {
        saveBarInfo.classList.add('has-changes');
        saveBarInfo.style.color = '#f59e0b';
        const locale = document.querySelector('input[name="locale"]').value;
        const warningIcon = saveBarInfo.querySelector('svg, i');
        if (warningIcon) {
            warningIcon.style.color = '#f59e0b';
        }
        const text = saveBarInfo.querySelector('span');
        if (text) {
            text.textContent = locale === 'ar'
                ? 'لديك تغييرات غير محفوظة!'
                : 'You have unsaved changes!';
        }
    }
});

// Auto-save indication removed - No beforeunload warning
let confirmLeave = false; // Disabled
// beforeunload event removed - users can leave freely

// Cancel button - no confirmation needed
document.querySelector('.btn-secondary[href*="admin.pages.index"]')?.addEventListener('click', function(e) {
    // Direct navigation - no warning
});

// Preview Functions
function refreshPreview() {
    const frame = document.getElementById('previewFrame');
    frame.src = frame.src;
}

function togglePreview() {
    const panel = document.getElementById('previewPanel');
    panel.classList.toggle('active');

    // Prevent body scroll when preview is open
    if (panel.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

function setDeviceMode(mode) {
    const frame = document.getElementById('previewFrame');
    const buttons = document.querySelectorAll('.device-btn');

    // Remove active class from all buttons
    buttons.forEach(btn => btn.classList.remove('active'));

    // Add active class to clicked button
    const activeBtn = document.querySelector(`.device-btn[data-mode="${mode}"]`);
    if (activeBtn) {
        activeBtn.classList.add('active');
    }

    // Remove all device classes
    frame.classList.remove('mobile', 'tablet', 'desktop');

    // Add new device class
    frame.classList.add(mode);
}

// Close preview with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const panel = document.getElementById('previewPanel');
        if (panel.classList.contains('active')) {
            togglePreview();
        }
    }
});

// SweetAlert for success/error messages
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: '<span class="ar-text">نجح!</span><span class="en-text">Success!</span>',
    html: '{{ session("success") }}',
    confirmButtonText: '<span class="ar-text">حسناً</span><span class="en-text">OK</span>',
    confirmButtonColor: '#48bb78',
    timer: 3000,
    timerProgressBar: true
});
@endif

@if($errors->any())
Swal.fire({
    icon: 'error',
    title: '<span class="ar-text">خطأ!</span><span class="en-text">Error!</span>',
    html: '<ul style="text-align: right; list-style: none; padding: 0;">@foreach($errors->all() as $error)<li style="margin: 0.5rem 0;">• {{ $error }}</li>@endforeach</ul>',
    confirmButtonText: '<span class="ar-text">حسناً</span><span class="en-text">OK</span>',
    confirmButtonColor: '#f56565'
});
@endif
</script>
@endpush
