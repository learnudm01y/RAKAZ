@extends('admin.layouts.app')

@section('title', __('admin.about.title'))

@section('page-title')
    {{ __('admin.about.title') }}
@endsection

@push('styles')
<style>
    .editor-container {
        display: block;
        width: 100%;
        height: calc(100vh - 180px);
        position: relative;
    }

    .editor-panel {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow-y: auto;
        padding: 2rem;
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Preview Panel - Fullscreen Overlay */
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

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .form-section-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .lang-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        background: var(--hover-bg);
        padding: 0.5rem;
        border-radius: 8px;
    }

    .lang-tab {
        flex: 1;
        padding: 0.5rem 1rem;
        border: none;
        background: transparent;
        cursor: pointer;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .lang-tab.active {
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        color: var(--primary-color);
    }

    .lang-content {
        display: none;
    }

    .lang-content.active {
        display: block;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.25rem;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
    }

    .image-upload {
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .image-upload:hover {
        border-color: var(--primary-color);
        background: var(--hover-bg);
    }

    .image-preview {
        margin-top: 1rem;
        max-width: 100%;
        border-radius: 8px;
    }

    .save-btn {
        width: 100%;
        padding: 0.875rem;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
        font-size: 1rem;
    }

    .save-btn:hover {
        background: #2c5aa0;
    }

    .save-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: none;
    }

    .alert.show {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border: 1px solid #9ae6b4;
    }

    .alert-error {
        background: #fed7d7;
        color: #742a2a;
        border: 1px solid #fc8181;
    }

    /* Image Upload Styles */
    .image-upload-wrapper {
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        background: var(--hover-bg);
        transition: all 0.3s;
    }

    .image-upload-wrapper:hover {
        border-color: var(--primary-color);
        background: rgba(52, 120, 246, 0.05);
    }

    .image-preview {
        max-width: 200px;
        max-height: 200px;
        margin: 1rem auto;
        border-radius: 8px;
        overflow: hidden;
        display: none;
    }

    .image-preview.show {
        display: block;
    }

    .image-preview img {
        width: 100%;
        height: auto;
        display: block;
    }

    .upload-btn {
        background: var(--primary-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .upload-btn:hover {
        background: #2c5aa0;
    }

    .file-input {
        display: none;
    }

    /* Icon Picker Styles */
    .icon-input-group {
        position: relative;
    }

    .icon-display {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
        color: var(--primary-color);
        pointer-events: none;
    }

    .icon-input {
        padding-right: 45px !important;
        cursor: pointer;
    }

    .icon-picker-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }

    .icon-picker-modal.active {
        display: flex;
    }

    .icon-picker-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 800px;
        max-height: 80vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    .icon-picker-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .icon-picker-header h3 {
        margin: 0;
        font-size: 1.25rem;
    }

    .icon-picker-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #999;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-picker-close:hover {
        color: #333;
    }

    .icon-picker-search {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .icon-picker-search input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.95rem;
    }

    .icon-picker-body {
        padding: 1.5rem;
        overflow-y: auto;
        flex: 1;
    }

    .icon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
        gap: 0.75rem;
    }

    .icon-item {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1.5rem;
        background: white;
    }

    .icon-item:hover {
        border-color: var(--primary-color);
        background: var(--hover-bg);
        transform: scale(1.05);
    }

    .icon-item.selected {
        border-color: var(--primary-color);
        background: var(--primary-color);
        color: white;
    }

    .icon-picker-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .icon-picker-footer .btn {
        padding: 0.625rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .icon-picker-footer .btn-secondary {
        background: #e2e8f0;
        color: #4a5568;
    }

    .icon-picker-footer .btn-secondary:hover {
        background: #cbd5e0;
    }

    .icon-picker-footer .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .icon-picker-footer .btn-primary:hover {
        background: #2c5aa0;
    }

    @media (max-width: 768px) {
        .preview-toggle-btn {
            padding: 0.75rem 0.5rem;
        }

        .preview-toggle-btn svg {
            width: 20px;
            height: 20px;
        }

        .editor-panel {
            padding: 1rem;
        }
    }
</style>

<!-- Quill Editor - Rich Text Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
<style>
    /* Quill Editor Styles */
    .quill-editor {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
    }

    .quill-editor .ql-toolbar {
        background: #f8f9fa;
        border: none;
        border-bottom: 1px solid var(--border-color);
        padding: 8px;
        direction: ltr;
        text-align: left;
    }

    .quill-editor .ql-container {
        font-size: 16px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        border: none;
    }

    .quill-editor .ql-editor {
        min-height: 300px;
        padding: 20px;
        line-height: 1.8;
        direction: rtl;
        text-align: right;
    }

    .quill-editor.ltr-editor .ql-editor {
        direction: ltr;
        text-align: left;
    }

    .quill-editor .ql-editor.ql-blank::before {
        right: 20px;
        left: auto;
        text-align: right;
        font-style: normal;
        color: #adb5bd;
    }

    .quill-editor.ltr-editor .ql-editor.ql-blank::before {
        right: auto;
        left: 20px;
        text-align: left;
    }

    /* Fix toolbar buttons alignment */
    .ql-toolbar button {
        width: 28px !important;
        height: 28px !important;
        padding: 3px !important;
        margin: 0 2px !important;
    }

    .ql-toolbar .ql-formats {
        margin-right: 8px !important;
    }

    /* Fix dropdown styles */
    .ql-toolbar .ql-picker {
        color: #495057;
        display: inline-block;
        font-size: 14px;
        font-weight: 500;
        height: 28px;
        position: relative;
        vertical-align: middle;
    }

    .ql-toolbar .ql-picker-label {
        cursor: pointer;
        display: inline-block;
        height: 100%;
        padding-left: 8px;
        padding-right: 2px;
        position: relative;
        width: 100%;
    }

    /* Arabic text in editor */
    .quill-editor .ql-editor p,
    .quill-editor .ql-editor h1,
    .quill-editor .ql-editor h2,
    .quill-editor .ql-editor h3 {
        margin: 0 0 15px 0;
    }

    .quill-editor .ql-editor h1 {
        font-size: 2em;
        font-weight: bold;
    }

    .quill-editor .ql-editor h2 {
        font-size: 1.5em;
        font-weight: bold;
    }

    .quill-editor .ql-editor h3 {
        font-size: 1.17em;
        font-weight: bold;
    }

    /* Fix list alignment for RTL */
    .quill-editor .ql-editor ul,
    .quill-editor .ql-editor ol {
        padding-right: 1.5em;
        padding-left: 0;
    }

    .quill-editor.ltr-editor .ql-editor ul,
    .quill-editor.ltr-editor .ql-editor ol {
        padding-left: 1.5em;
        padding-right: 0;
    }

    /* Fix list item bullets/numbers alignment for RTL */
    .ql-editor li:not(.ql-direction-rtl)::before {
        margin-left: 0;
        margin-right: 0.3em;
        text-align: right;
    }

    /* Override Quill's default styles */
    .ql-snow .ql-tooltip {
        left: 0 !important;
        z-index: 1000;
    }

    .ql-snow.ql-toolbar button:hover,
    .ql-snow .ql-toolbar button:hover,
    .ql-snow.ql-toolbar button:focus,
    .ql-snow .ql-toolbar button:focus,
    .ql-snow.ql-toolbar button.ql-active,
    .ql-snow .ql-toolbar button.ql-active {
        color: var(--primary-color);
    }

    .ql-snow.ql-toolbar button:hover .ql-stroke,
    .ql-snow .ql-toolbar button:hover .ql-stroke,
    .ql-snow.ql-toolbar button:focus .ql-stroke,
    .ql-snow .ql-toolbar button:focus .ql-stroke,
    .ql-snow.ql-toolbar button.ql-active .ql-stroke,
    .ql-snow .ql-toolbar button.ql-active .ql-stroke {
        stroke: var(--primary-color);
    }

    .ql-snow.ql-toolbar button:hover .ql-fill,
    .ql-snow .ql-toolbar button:hover .ql-fill,
    .ql-snow.ql-toolbar button:focus .ql-fill,
    .ql-snow .ql-toolbar button:focus .ql-fill,
    .ql-snow.ql-toolbar button.ql-active .ql-fill,
    .ql-snow .ql-toolbar button.ql-active .ql-fill {
        fill: var(--primary-color);
</style>

<div id="alertBox" class="alert">
    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
    </svg>
    <span id="alertMessage"></span>
</div>

<div class="editor-container">
    <!-- Editor Panel -->
    <div class="editor-panel">
        <form id="aboutForm">
            @csrf

            <!-- Language Tabs -->
            <div class="lang-tabs">
                <button type="button" class="lang-tab active" onclick="switchLang('ar')">
                    🇸🇦 {{ __('admin.about.arabic_content') }}
                </button>
                <button type="button" class="lang-tab" onclick="switchLang('en')">
                    🇬🇧 {{ __('admin.about.english_content') }}
                </button>
            </div>

            <!-- Arabic Content -->
            <div class="lang-content active" id="ar-content">
                <!-- Hero Section AR -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-bullseye"></i> {{ __('admin.about.hero_section') }}
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>{{ __('admin.about.hero_title') }}</label>
                            <input type="text" name="hero_title_ar" class="form-control" value="{{ $page->hero_title_ar ?? 'من نحن' }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('admin.about.hero_subtitle') }}</label>
                            <textarea name="hero_subtitle_ar" class="form-control" rows="2">{{ $page->hero_subtitle_ar ?? 'ركاز - وجهتك الأولى للأزياء الإماراتية الفاخرة' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Story Section AR -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-book-open"></i> {{ __('admin.about.story_section') }}
                    </div>

                    <div class="form-group">
                        <label>{{ __('admin.about.story_title') }}</label>
                        <input type="text" name="story_title_ar" class="form-control" value="{{ $page->story_title_ar ?? 'قصتنا' }}">
                    </div>

                    <div class="form-group">
                        <label>{{ __('admin.about.story_content') }}</label>
                        <div id="editor_story_ar" class="quill-editor"></div>
                        <textarea name="story_content_ar" style="display:none;">{{ $page->story_content_ar ?? $page->content_ar }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">صورة القصة</span>
                            <span class="en-text">Story Image</span>
                        </label>
                        <div class="image-upload-wrapper">
                            <div class="image-preview {{ $page->story_image ? 'show' : '' }}" id="story_image_preview">
                                <img src="{{ $page->story_image ?? '' }}" alt="Story Image">
                            </div>
                            <input type="file" id="story_image_input" name="story_image_file" class="file-input" accept="image/*" onchange="handleImageUpload(this, 'story_image_preview')">
                            <input type="hidden" name="story_image" id="story_image_path" value="{{ $page->story_image ?? '' }}">
                            <button type="button" class="upload-btn" onclick="document.getElementById('story_image_input').click()">
                                <i class="fa-solid fa-upload"></i>
                                <span class="ar-text">اختر صورة</span>
                                <span class="en-text">Choose Image</span>
                            </button>
                            <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #666;">
                                <span class="ar-text">JPG, PNG, GIF, WEBP (حد أقصى 5MB)</span>
                                <span class="en-text">JPG, PNG, GIF, WEBP (Max 5MB)</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Values Section AR -->
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="ar-text"><i class="fa-solid fa-star"></i> قسم القيم</span>
                        <span class="en-text"><i class="fa-solid fa-star"></i> Values Section</span>
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">عنوان القسم</span>
                            <span class="en-text">Section Title</span>
                        </label>
                        <input type="text" name="values_title_ar" class="form-control" value="{{ $page->values_title_ar ?? 'قيمنا' }}">
                    </div>

                    <div class="form-row">
                        @for($i = 1; $i <= 2; $i++)
                        <div style="background: var(--hover-bg); padding: 1rem; border-radius: 8px;">
                            <h4 style="margin-bottom: 1rem; font-size: 0.95rem;">القيمة {{ $i }}</h4>

                            <div class="form-group">
                                <label>العنوان</label>
                                <input type="text" name="value{{$i}}_title_ar" class="form-control" value="{{ $page->{"value{$i}_title_ar"} ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label>
                                    <span class="ar-text">الوصف</span>
                                    <span class="en-text">Description</span>
                                </label>
                                <textarea name="value{{$i}}_description_ar" class="form-control" rows="2">{{ $page->{"value{$i}_description_ar"} ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>
                                    <span class="ar-text">الأيقونة</span>
                                    <span class="en-text">Icon</span>
                                </label>
                                <div class="icon-input-group">
                                    <input type="text" name="value{{$i}}_icon" class="form-control icon-input"
                                           value="{{ $page->{"value{$i}_icon"} ?? 'fa-solid fa-star' }}"
                                           placeholder="fa-solid fa-star"
                                           readonly
                                           onclick="openIconPicker(this)">
                                    <i class="icon-display {{ $page->{"value{$i}_icon"} ?? 'fa-solid fa-star' }}"></i>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="form-row">
                        @for($i = 3; $i <= 4; $i++)
                        <div style="background: var(--hover-bg); padding: 1rem; border-radius: 8px;">
                            <h4 style="margin-bottom: 1rem; font-size: 0.95rem;">القيمة {{ $i }}</h4>

                            <div class="form-group">
                                <label>العنوان</label>
                                <input type="text" name="value{{$i}}_title_ar" class="form-control" value="{{ $page->{"value{$i}_title_ar"} ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label>
                                    <span class="ar-text">الوصف</span>
                                    <span class="en-text">Description</span>
                                </label>
                                <textarea name="value{{$i}}_description_ar" class="form-control" rows="2">{{ $page->{"value{$i}_description_ar"} ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>
                                    <span class="ar-text">الأيقونة</span>
                                    <span class="en-text">Icon</span>
                                </label>
                                <div class="icon-input-group">
                                    <input type="text" name="value{{$i}}_icon" class="form-control icon-input"
                                           value="{{ $page->{"value{$i}_icon"} ?? 'fa-solid fa-star' }}"
                                           placeholder="fa-solid fa-star"
                                           readonly
                                           onclick="openIconPicker(this)">
                                    <i class="icon-display {{ $page->{"value{$i}_icon"} ?? 'fa-solid fa-star' }}"></i>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                <!-- Statistics Section AR -->
                @if(isset($grouped['stats']))
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="ar-text"><i class="fa-solid fa-chart-bar"></i> قسم الإحصائيات</span>
                        <span class="en-text"><i class="fa-solid fa-chart-bar"></i> Statistics Section</span>
                    </div>

                    @php
                        // Get current language
                        $currentLang = app()->getLocale();

                        // Translation helper function
                        $translateFieldName = function($key) use ($currentLang) {
                            $translations = [
                                'ar' => [
                                    'branches_count' => 'عدد الفروع',
                                    'branches_label' => 'تسمية الفروع',
                                    'products_count' => 'عدد المنتجات',
                                    'products_label' => 'تسمية المنتجات',
                                    'customers_count' => 'عدد العملاء',
                                    'customers_label' => 'تسمية العملاء',
                                    'years_experience' => 'سنوات الخبرة',
                                    'years_label' => 'تسمية سنوات الخبرة',
                                    'service_1_icon' => 'أيقونة الخدمة الأولى',
                                    'service_1_title' => 'عنوان الخدمة الأولى',
                                    'service_1_desc' => 'وصف الخدمة الأولى',
                                    'service_2_icon' => 'أيقونة الخدمة الثانية',
                                    'service_2_title' => 'عنوان الخدمة الثانية',
                                    'service_2_desc' => 'وصف الخدمة الثانية',
                                    'service_3_icon' => 'أيقونة الخدمة الثالثة',
                                    'service_3_title' => 'عنوان الخدمة الثالثة',
                                    'service_3_desc' => 'وصف الخدمة الثالثة',
                                ],
                                'en' => [
                                    'branches_count' => 'Number of Branches',
                                    'branches_label' => 'Branches Label',
                                    'products_count' => 'Number of Products',
                                    'products_label' => 'Products Label',
                                    'customers_count' => 'Number of Customers',
                                    'customers_label' => 'Customers Label',
                                    'years_experience' => 'Years of Experience',
                                    'years_label' => 'Experience Label',
                                    'service_1_icon' => 'First Service Icon',
                                    'service_1_title' => 'First Service Title',
                                    'service_1_desc' => 'First Service Description',
                                    'service_2_icon' => 'Second Service Icon',
                                    'service_2_title' => 'Second Service Title',
                                    'service_2_desc' => 'Second Service Description',
                                    'service_3_icon' => 'Third Service Icon',
                                    'service_3_title' => 'Third Service Title',
                                    'service_3_desc' => 'Third Service Description',
                                ]
                            ];
                            return $translations[$currentLang][$key] ?? $key;
                        };

                        // Group stats by pairs (count + label)
                        $statPairs = [];
                        foreach($grouped['stats'] as $index => $setting) {
                            $baseKey = str_replace(['_count', '_label'], '', $setting->key);
                            if (!isset($statPairs[$baseKey])) {
                                $statPairs[$baseKey] = [];
                            }
                            $statPairs[$baseKey][] = ['setting' => $setting, 'index' => $index];
                        }
                    @endphp                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                        @foreach($statPairs as $baseKey => $pair)
                        <div style="background: var(--hover-bg); padding: 1rem; border-radius: 8px;">
                            <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; color: #1a1a1a;">{{ ucfirst(str_replace('_', ' ', $baseKey)) }}</h4>

                            @foreach($pair as $item)
                            <div class="form-group" style="margin-bottom: 0.75rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.85rem; color: #666;">
                                    {{ $translateFieldName($item['setting']->key) }}
                                </label>
                                <input type="hidden" name="settings[{{ $item['index'] }}][id]" value="{{ $item['setting']->id }}">
                                <input
                                    type="{{ $item['setting']->type === 'number' ? 'number' : 'text' }}"
                                    name="settings[{{ $item['index'] }}][value_ar]"
                                    class="form-control"
                                    value="{{ $item['setting']->value_ar }}"
                                    dir="rtl"
                                    style="margin: 0;">
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Services Section AR -->
                @if(isset($grouped['services']))
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="ar-text"><i class="fa-solid fa-screwdriver-wrench"></i> قسم الخدمات</span>
                        <span class="en-text"><i class="fa-solid fa-screwdriver-wrench"></i> Services Section</span>
                    </div>

                    @php
                        $serviceGroups = $grouped['services']->chunk(3);
                        $statsCount = isset($grouped['stats']) ? $grouped['stats']->count() : 0;
                    @endphp

                    @foreach($serviceGroups as $groupIndex => $serviceGroup)
                    <div style="background: var(--hover-bg); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <h4 style="margin-bottom: 1rem; font-size: 0.95rem;">
                            <span class="ar-text">الخدمة {{ $groupIndex + 1 }}</span>
                            <span class="en-text">Service {{ $groupIndex + 1 }}</span>
                        </h4>

                        @foreach($serviceGroup as $setting)
                            @php
                                $globalIndex = $statsCount + $loop->parent->index * 3 + $loop->index;
                            @endphp
                            <div class="form-group">
                                <label style="font-size: 0.85rem; color: #666;">{{ $translateFieldName($setting->key) }}</label>
                                <input type="hidden" name="settings[{{ $globalIndex }}][id]" value="{{ $setting->id }}">
                                <input type="hidden" name="settings[{{ $globalIndex }}][value_en]" value="{{ $setting->value_en }}">

                                @if($setting->type === 'textarea')
                                    <textarea
                                        name="settings[{{ $globalIndex }}][value_ar]"
                                        class="form-control"
                                        rows="2"
                                        dir="rtl">{{ $setting->value_ar }}</textarea>
                                @else
                                    <input
                                        type="text"
                                        name="settings[{{ $globalIndex }}][value_ar]"
                                        class="form-control"
                                        value="{{ $setting->value_ar }}"
                                        dir="rtl"
                                        @if(str_contains($setting->key, 'icon'))
                                            readonly
                                            onclick="openIconPicker(this)"
                                            style="cursor: pointer; background: white;"
                                        @endif>
                                    @if(str_contains($setting->key, 'icon'))
                                        <div style="margin-top: 0.5rem;">
                                            <i class="{{ $setting->value_ar }}" style="font-size: 24px; color: var(--primary-color);"></i>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- English Content -->
            <div class="lang-content" id="en-content">
                <!-- Hero Section EN -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-bullseye"></i> <span class="ar-text">قسم البانر الرئيسي</span><span class="en-text">Hero Section</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><span class="ar-text">عنوان البانر</span><span class="en-text">Hero Title</span></label>
                            <input type="text" name="hero_title_en" class="form-control" value="{{ $page->hero_title_en ?? 'About Us' }}">
                        </div>

                        <div class="form-group">
                            <label><span class="ar-text">نص البانر الفرعي</span><span class="en-text">Hero Subtitle</span></label>
                            <textarea name="hero_subtitle_en" class="form-control" rows="2">{{ $page->hero_subtitle_en ?? 'Rakaz - Your premier destination for luxury Emirati fashion' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Story Section EN -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-book-open"></i> <span class="ar-text">قسم القصة</span><span class="en-text">Story Section</span>
                    </div>

                    <div class="form-group">
                        <label><span class="ar-text">عنوان القصة</span><span class="en-text">Story Title</span></label>
                        <input type="text" name="story_title_en" class="form-control" value="{{ $page->story_title_en ?? 'Our Story' }}">
                    </div>

                    <div class="form-group">
                        <label><span class="ar-text">محتوى القصة</span><span class="en-text">Story Content</span></label>
                        <div id="editor_story_en" class="quill-editor"></div>
                        <textarea name="story_content_en" style="display:none;">{{ $page->story_content_en ?? $page->content_en }}</textarea>
                    </div>
                </div>

                <!-- Values Section EN -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-star"></i> <span class="ar-text">قسم القيم</span><span class="en-text">Values Section</span>
                    </div>

                    <div class="form-group">
                        <label><span class="ar-text">عنوان قسم القيم</span><span class="en-text">Section Title</span></label>
                        <input type="text" name="values_title_en" class="form-control" value="{{ $page->values_title_en ?? 'Our Values' }}">
                    </div>

                    <div class="form-row">
                        @for($i = 1; $i <= 2; $i++)
                        <div style="background: var(--hover-bg); padding: 1rem; border-radius: 8px;">
                            <h4 style="margin-bottom: 1rem; font-size: 0.95rem;"><span class="ar-text">القيمة {{ $i }}</span><span class="en-text">Value {{ $i }}</span></h4>

                            <div class="form-group">
                                <label><span class="ar-text">العنوان</span><span class="en-text">Title</span></label>
                                <input type="text" name="value{{$i}}_title_en" class="form-control" value="{{ $page->{"value{$i}_title_en"} ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label><span class="ar-text">الوصف</span><span class="en-text">Description</span></label>
                                <textarea name="value{{$i}}_description_en" class="form-control" rows="2">{{ $page->{"value{$i}_description_en"} ?? '' }}</textarea>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="form-row">
                        @for($i = 3; $i <= 4; $i++)
                        <div style="background: var(--hover-bg); padding: 1rem; border-radius: 8px;">
                            <h4 style="margin-bottom: 1rem; font-size: 0.95rem;"><span class="ar-text">القيمة {{ $i }}</span><span class="en-text">Value {{ $i }}</span></h4>

                            <div class="form-group">
                                <label><span class="ar-text">العنوان</span><span class="en-text">Title</span></label>
                                <input type="text" name="value{{$i}}_title_en" class="form-control" value="{{ $page->{"value{$i}_title_en"} ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label><span class="ar-text">الوصف</span><span class="en-text">Description</span></label>
                                <textarea name="value{{$i}}_description_en" class="form-control" rows="2">{{ $page->{"value{$i}_description_en"} ?? '' }}</textarea>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                <!-- Statistics Section EN -->
                @if(isset($grouped['stats']))
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-chart-bar"></i> <span class="ar-text">قسم الإحصائيات</span><span class="en-text">Statistics Section</span>
                    </div>

                    @php
                        // Group stats by pairs (count + label)
                        $statPairs = [];
                        foreach($grouped['stats'] as $index => $setting) {
                            $baseKey = str_replace(['_count', '_label'], '', $setting->key);
                            if (!isset($statPairs[$baseKey])) {
                                $statPairs[$baseKey] = [];
                            }
                            $statPairs[$baseKey][] = ['setting' => $setting, 'index' => $index];
                        }
                    @endphp

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                        @foreach($statPairs as $baseKey => $pair)
                        <div style="background: var(--hover-bg); padding: 1rem; border-radius: 8px;">
                            <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; color: #1a1a1a;">{{ ucfirst(str_replace('_', ' ', $baseKey)) }}</h4>

                            @foreach($pair as $item)
                            <div class="form-group" style="margin-bottom: 0.75rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.85rem; color: #666;">
                                    {{ $translateFieldName($item['setting']->key) }}
                                </label>
                                <input type="hidden" name="settings[{{ $item['index'] }}][id]" value="{{ $item['setting']->id }}">
                                <input type="hidden" name="settings[{{ $item['index'] }}][value_ar]" value="{{ $item['setting']->value_ar }}">
                                <input
                                    type="{{ $item['setting']->type === 'number' ? 'number' : 'text' }}"
                                    name="settings[{{ $item['index'] }}][value_en]"
                                    class="form-control"
                                    value="{{ $item['setting']->value_en }}"
                                    dir="ltr"
                                    style="margin: 0;">
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Services Section EN -->
                @if(isset($grouped['services']))
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-screwdriver-wrench"></i> <span class="ar-text">قسم الخدمات</span><span class="en-text">Services Section</span>
                    </div>

                    @php
                        $serviceGroups = $grouped['services']->chunk(3);
                        $statsCount = isset($grouped['stats']) ? $grouped['stats']->count() : 0;
                    @endphp

                    @foreach($serviceGroups as $groupIndex => $serviceGroup)
                    <div style="background: var(--hover-bg); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <h4 style="margin-bottom: 1rem; font-size: 0.95rem;">Service {{ $groupIndex + 1 }}</h4>

                        @foreach($serviceGroup as $setting)
                            @php
                                $globalIndex = $statsCount + $loop->parent->index * 3 + $loop->index;
                            @endphp
                            <div class="form-group">
                                <label style="font-size: 0.85rem; color: #666;">{{ $translateFieldName($setting->key) }}</label>
                                <input type="hidden" name="settings[{{ $globalIndex }}][id]" value="{{ $setting->id }}">
                                <input type="hidden" name="settings[{{ $globalIndex }}][value_ar]" value="{{ $setting->value_ar }}">

                                @if($setting->type === 'textarea')
                                    <textarea
                                        name="settings[{{ $globalIndex }}][value_en]"
                                        class="form-control"
                                        rows="2"
                                        dir="ltr">{{ $setting->value_en }}</textarea>
                                @else
                                    <input
                                        type="text"
                                        name="settings[{{ $globalIndex }}][value_en]"
                                        class="form-control"
                                        value="{{ $setting->value_en }}"
                                        dir="ltr"
                                        @if(str_contains($setting->key, 'icon'))
                                            readonly
                                            onclick="openIconPicker(this)"
                                            style="cursor: pointer; background: white;"
                                        @endif>
                                    @if(str_contains($setting->key, 'icon'))
                                        <div style="margin-top: 0.5rem;">
                                            <i class="{{ $setting->value_en }}" style="font-size: 24px; color: var(--primary-color);"></i>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Save Button -->
            <button type="submit" class="save-btn" id="saveBtn">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="ar-text">حفظ جميع التغييرات</span>
                <span class="en-text">Save All Changes</span>
            </button>
        </form>
    </div>
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
            <a href="{{ route('about') }}" target="_blank" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
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
        <iframe id="previewFrame" class="preview-frame desktop" src="{{ route('about') }}"></iframe>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="icon-picker-modal" id="iconPickerModal">
    <div class="icon-picker-content">
        <div class="icon-picker-header">
            <h3>
                <span class="ar-text">اختر أيقونة</span>
                <span class="en-text">Choose an Icon</span>
            </h3>
            <button class="icon-picker-close" onclick="closeIconPicker()">&times;</button>
        </div>
        <div class="icon-picker-search">
            <input type="text" id="iconSearch" placeholder="ابحث عن أيقونة... / Search icons..." onkeyup="filterIcons()">
        </div>
        <div class="icon-picker-body">
            <div class="icon-grid" id="iconGrid"></div>
        </div>
        <div class="icon-picker-footer">
            <button class="btn btn-secondary" onclick="closeIconPicker()">
                <span class="ar-text">إلغاء</span>
                <span class="en-text">Cancel</span>
            </button>
            <button class="btn btn-primary" onclick="selectIcon()">
                <span class="ar-text">اختيار</span>
                <span class="en-text">Select</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Font Awesome Icons List
    const fontAwesomeIcons = [
        'fa-star', 'fa-heart', 'fa-user', 'fa-home', 'fa-search', 'fa-envelope', 'fa-phone', 'fa-map-marker-alt',
        'fa-shopping-cart', 'fa-shopping-bag', 'fa-gift', 'fa-tag', 'fa-tags', 'fa-credit-card', 'fa-money-bill',
        'fa-trophy', 'fa-award', 'fa-medal', 'fa-crown', 'fa-gem', 'fa-diamond', 'fa-ring',
        'fa-lightbulb', 'fa-fire', 'fa-bolt', 'fa-sun', 'fa-moon', 'fa-star-half-alt', 'fa-sparkles',
        'fa-check', 'fa-check-circle', 'fa-check-double', 'fa-times', 'fa-times-circle', 'fa-exclamation',
        'fa-handshake', 'fa-hands-helping', 'fa-hand-holding-heart', 'fa-thumbs-up', 'fa-thumbs-down',
        'fa-chart-line', 'fa-chart-bar', 'fa-chart-pie', 'fa-chart-area', 'fa-analytics',
        'fa-rocket', 'fa-plane', 'fa-car', 'fa-truck', 'fa-ship', 'fa-train',
        'fa-clock', 'fa-calendar', 'fa-calendar-check', 'fa-hourglass', 'fa-stopwatch',
        'fa-cog', 'fa-cogs', 'fa-wrench', 'fa-tools', 'fa-hammer', 'fa-screwdriver',
        'fa-book', 'fa-book-open', 'fa-graduation-cap', 'fa-university', 'fa-school', 'fa-chalkboard',
        'fa-briefcase', 'fa-suitcase', 'fa-folder', 'fa-file', 'fa-file-alt', 'fa-clipboard',
        'fa-shield', 'fa-shield-alt', 'fa-lock', 'fa-unlock', 'fa-key', 'fa-fingerprint',
        'fa-globe', 'fa-map', 'fa-compass', 'fa-location-arrow', 'fa-route', 'fa-directions',
        'fa-users', 'fa-user-friends', 'fa-user-tie', 'fa-user-check', 'fa-user-plus', 'fa-user-circle',
        'fa-smile', 'fa-laugh', 'fa-grin', 'fa-meh', 'fa-frown', 'fa-sad-tear',
        'fa-wifi', 'fa-signal', 'fa-battery-full', 'fa-plug', 'fa-mobile', 'fa-laptop',
        'fa-camera', 'fa-image', 'fa-images', 'fa-video', 'fa-film', 'fa-photo-video',
        'fa-music', 'fa-headphones', 'fa-microphone', 'fa-volume-up', 'fa-volume-down', 'fa-volume-mute',
        'fa-coffee', 'fa-utensils', 'fa-glass-martini', 'fa-wine-glass', 'fa-beer', 'fa-pizza-slice',
        'fa-leaf', 'fa-tree', 'fa-seedling', 'fa-flower', 'fa-spa', 'fa-feather',
        'fa-cloud', 'fa-cloud-sun', 'fa-cloud-rain', 'fa-snowflake', 'fa-rainbow', 'fa-wind',
        'fa-dumbbell', 'fa-running', 'fa-walking', 'fa-bicycle', 'fa-swimmer', 'fa-football-ball',
        'fa-palette', 'fa-paint-brush', 'fa-pen', 'fa-pencil-alt', 'fa-marker', 'fa-highlighter',
        'fa-box', 'fa-boxes', 'fa-cube', 'fa-cubes', 'fa-archive', 'fa-warehouse'
    ];

    let currentInput = null;
    let selectedIcon = null;

    // Quill Editors
    let quillEditors = {};

    // Initialize Quill Editors
    document.addEventListener('DOMContentLoaded', function() {
        // Toolbar configuration
        const toolbarOptions = [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['link'],
            ['clean']
        ];

        // Arabic Story Editor
        const editorArElement = document.querySelector('#editor_story_ar');
        quillEditors.story_ar = new Quill('#editor_story_ar', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'اكتب محتوى القصة هنا...'
        });

        // Set initial content for Arabic
        const storyArContent = document.querySelector('textarea[name="story_content_ar"]').value;
        if (storyArContent) {
            quillEditors.story_ar.root.innerHTML = storyArContent;
        }

        // English Story Editor
        const editorEnElement = document.querySelector('#editor_story_en');
        editorEnElement.parentElement.classList.add('ltr-editor');

        quillEditors.story_en = new Quill('#editor_story_en', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Write your story content here...'
        });

        // Set initial content for English
        const storyEnContent = document.querySelector('textarea[name="story_content_en"]').value;
        if (storyEnContent) {
            quillEditors.story_en.root.innerHTML = storyEnContent;
        }
    });

    // Image Upload Handler
    function handleImageUpload(input, previewId) {
        const preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                showAlert('حجم الصورة كبير جداً! الحد الأقصى 5MB / Image too large! Max 5MB', 'error');
                input.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showAlert('نوع الملف غير مدعوم! / Invalid file type!', 'error');
                input.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.add('show');
            };

            reader.readAsDataURL(file);
        }
    }

    // Language Switch
    function switchLang(lang) {
        document.querySelectorAll('.lang-tab').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');

        document.querySelectorAll('.lang-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById(lang + '-content').classList.add('active');
    }

    // Show Alert
    function showAlert(message, type = 'success') {
        const alertBox = document.getElementById('alertBox');
        const alertMessage = document.getElementById('alertMessage');

        alertBox.className = 'alert alert-' + type + ' show';
        alertMessage.textContent = message;

        setTimeout(() => {
            alertBox.classList.remove('show');
        }, 5000);
    }

    // Refresh Preview
    function refreshPreview() {
        const frame = document.getElementById('previewFrame');
        frame.src = frame.src;
    }

    // Toggle Preview Panel
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

    // Device Mode Switcher
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

    // Form Submit
    document.getElementById('aboutForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Sync Quill editors content to textareas
        document.querySelector('textarea[name="story_content_ar"]').value = quillEditors.story_ar.root.innerHTML;
        document.querySelector('textarea[name="story_content_en"]').value = quillEditors.story_en.root.innerHTML;

        const saveBtn = document.getElementById('saveBtn');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="4" stroke-dasharray="31.4" stroke-dashoffset="10" style="animation: spin 1s linear infinite;"></circle></svg><span>جاري الحفظ...</span>';

        const formData = new FormData(this);

        try {
            const response = await fetch('{{ route("admin.about.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showAlert('تم الحفظ بنجاح! / Saved successfully!', 'success');

                // Update image preview if new image was uploaded
                if (data.image_path) {
                    const preview = document.getElementById('story_image_preview');
                    const img = preview.querySelector('img');
                    img.src = data.image_path + '?t=' + new Date().getTime(); // Cache busting
                    preview.classList.add('show');
                    document.getElementById('story_image_path').value = data.image_path;
                }

                setTimeout(() => {
                    refreshPreview();
                }, 1000);
            } else {
                const errorMsg = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
                showAlert('خطأ: ' + errorMsg + ' / Error: ' + errorMsg, 'error');
            }
        } catch (error) {
            showAlert('حدث خطأ في الاتصال / Connection error', 'error');
            console.error('Error:', error);
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span class="ar-text">حفظ جميع التغييرات</span><span class="en-text">Save All Changes</span>';
        }
    });

    // Spinning animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);

    // Icon Picker Functions
    function openIconPicker(input) {
        currentInput = input;
        selectedIcon = input.value || 'fa-solid fa-star';

        const modal = document.getElementById('iconPickerModal');
        const grid = document.getElementById('iconGrid');

        // Generate icon grid
        grid.innerHTML = '';
        fontAwesomeIcons.forEach(icon => {
            const iconClass = 'fa-solid ' + icon;
            const div = document.createElement('div');
            div.className = 'icon-item';
            if (iconClass === selectedIcon) {
                div.classList.add('selected');
            }
            div.innerHTML = `<i class="${iconClass}"></i>`;
            div.onclick = function() {
                document.querySelectorAll('.icon-item').forEach(item => item.classList.remove('selected'));
                this.classList.add('selected');
                selectedIcon = iconClass;
            };
            grid.appendChild(div);
        });

        modal.classList.add('active');
    }

    function closeIconPicker() {
        document.getElementById('iconPickerModal').classList.remove('active');
        document.getElementById('iconSearch').value = '';
        filterIcons();
    }

    function selectIcon() {
        if (selectedIcon && currentInput) {
            currentInput.value = selectedIcon;
            const iconDisplay = currentInput.parentElement.querySelector('.icon-display');
            if (iconDisplay) {
                iconDisplay.className = 'icon-display ' + selectedIcon;
            }
        }
        closeIconPicker();
    }

    function filterIcons() {
        const searchValue = document.getElementById('iconSearch').value.toLowerCase();
        const items = document.querySelectorAll('.icon-item');

        items.forEach(item => {
            const icon = item.querySelector('i').className.toLowerCase();
            if (icon.includes(searchValue)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Close modal on outside click
    document.getElementById('iconPickerModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeIconPicker();
        }
    });
</script>

<!-- Quill Editor Script -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@endpush
