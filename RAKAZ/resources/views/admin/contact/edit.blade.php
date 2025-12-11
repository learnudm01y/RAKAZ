@extends('admin.layouts.app')

@section('title', 'تعديل صفحة تواصل معنا')

@section('page-title')
    <span class="ar-text">تعديل صفحة تواصل معنا</span>
    <span class="en-text">Edit Contact Page</span>
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
        height: calc(100% - 60px);
        display: flex;
        align-items: flex-start;
        justify-content: center;
        background: #f5f5f5;
        overflow: auto;
        padding: 20px 0;
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

    @media (max-width: 1200px) {
        .editor-container {
            grid-template-columns: 1fr;
            height: auto;
        }

        .preview-panel {
            height: 600px;
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
        <form id="contactForm">
            @csrf

            <!-- Language Tabs -->
            <div class="lang-tabs">
                <button type="button" class="lang-tab active" onclick="switchLang('ar')">
                    🇸🇦 عربي
                </button>
                <button type="button" class="lang-tab" onclick="switchLang('en')">
                    🇬🇧 English
                </button>
            </div>

            <!-- Arabic Content -->
            <div class="lang-content active" id="ar-content">
                <!-- Hero Section AR -->
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="ar-text"><i class="fa-solid fa-bullseye"></i> قسم البانر الرئيسي</span>
                        <span class="en-text"><i class="fa-solid fa-bullseye"></i> Hero Section</span>
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">عنوان البانر</span>
                            <span class="en-text">Hero Title</span>
                        </label>
                        <input type="text" name="hero_title_ar" class="form-control" value="{{ $page->hero_title_ar ?? 'تواصل معنا' }}">
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">نص البانر الفرعي</span>
                            <span class="en-text">Hero Subtitle</span>
                        </label>
                        <textarea name="hero_subtitle_ar" class="form-control" rows="2">{{ $page->hero_subtitle_ar ?? 'نحن هنا للإجابة على جميع استفساراتك' }}</textarea>
                    </div>
                </div>

                <!-- Contact Information Section AR -->
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="ar-text"><i class="fa-solid fa-phone"></i> معلومات الاتصال</span>
                        <span class="en-text"><i class="fa-solid fa-phone"></i> Contact Information</span>
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">رقم الهاتف</span>
                            <span class="en-text">Phone</span>
                        </label>
                        <input type="text" name="phone" class="form-control" value="{{ $page->phone ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">البريد الإلكتروني</span>
                            <span class="en-text">Email</span>
                        </label>
                        <input type="email" name="email" class="form-control" value="{{ $page->email ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">العنوان (عربي)</span>
                            <span class="en-text">Address (Arabic)</span>
                        </label>
                        <textarea name="address_ar" class="form-control" rows="2">{{ $page->address_ar ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">رابط الخريطة</span>
                            <span class="en-text">Map URL</span>
                        </label>
                        <input type="url" name="map_url" class="form-control" value="{{ $page->map_url ?? '' }}" placeholder="https://maps.google.com/...">
                    </div>
                </div>

                <!-- Working Hours Section AR -->
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="ar-text">🕒 ساعات العمل</span>
                        <span class="en-text">🕒 Working Hours</span>
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">ساعات العمل (عربي)</span>
                            <span class="en-text">Working Hours (Arabic)</span>
                        </label>
                        <div id="editor_working_hours_ar" class="quill-editor"></div>
                        <textarea name="working_hours_ar" style="display:none;">{{ $page->working_hours_ar ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Social Media Section AR -->
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="ar-text">🌐 وسائل التواصل الاجتماعي</span>
                        <span class="en-text">🌐 Social Media</span>
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">فيسبوك</span>
                            <span class="en-text">Facebook</span>
                        </label>
                        <input type="url" name="facebook_url" class="form-control" value="{{ $page->facebook_url ?? '' }}" placeholder="https://facebook.com/...">
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">تويتر</span>
                            <span class="en-text">Twitter</span>
                        </label>
                        <input type="url" name="twitter_url" class="form-control" value="{{ $page->twitter_url ?? '' }}" placeholder="https://twitter.com/...">
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">إنستغرام</span>
                            <span class="en-text">Instagram</span>
                        </label>
                        <input type="url" name="instagram_url" class="form-control" value="{{ $page->instagram_url ?? '' }}" placeholder="https://instagram.com/...">
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">لينكد إن</span>
                            <span class="en-text">LinkedIn</span>
                        </label>
                        <input type="url" name="linkedin_url" class="form-control" value="{{ $page->linkedin_url ?? '' }}" placeholder="https://linkedin.com/...">
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">يوتيوب</span>
                            <span class="en-text">YouTube</span>
                        </label>
                        <input type="url" name="youtube_url" class="form-control" value="{{ $page->youtube_url ?? '' }}" placeholder="https://youtube.com/...">
                    </div>
                </div>

                <!-- Additional Information Section AR -->
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="ar-text"><i class="fa-solid fa-circle-info"></i> معلومات إضافية</span>
                        <span class="en-text"><i class="fa-solid fa-circle-info"></i> Additional Information</span>
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">معلومات إضافية (عربي)</span>
                            <span class="en-text">Additional Information (Arabic)</span>
                        </label>
                        <div id="editor_additional_info_ar" class="quill-editor"></div>
                        <textarea name="additional_info_ar" style="display:none;">{{ $page->additional_info_ar ?? '' }}</textarea>
                    </div>
                </div>

                <!-- SEO Section AR -->
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="ar-text"><i class="fa-solid fa-magnifying-glass"></i> تحسين محركات البحث</span>
                        <span class="en-text"><i class="fa-solid fa-magnifying-glass"></i> SEO Settings</span>
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">وصف الميتا (عربي)</span>
                            <span class="en-text">Meta Description (Arabic)</span>
                        </label>
                        <textarea name="meta_description_ar" class="form-control" rows="2">{{ $page->meta_description_ar ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>
                            <span class="ar-text">كلمات البحث (عربي)</span>
                            <span class="en-text">Meta Keywords (Arabic)</span>
                        </label>
                        <input type="text" name="meta_keywords_ar" class="form-control" value="{{ $page->meta_keywords_ar ?? '' }}" placeholder="كلمة1, كلمة2, كلمة3">
                    </div>
                </div>
            </div>

            <!-- English Content -->
            <div class="lang-content" id="en-content">
                <!-- Hero Section EN -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-bullseye"></i> <span class="ar-text">قسم البانر</span><span class="en-text">Hero Section</span>
                    </div>

                    <div class="form-group">
                        <label><span class="ar-text">عنوان الصفحة</span><span class="en-text">Hero Title</span></label>
                        <input type="text" name="hero_title_en" class="form-control" value="{{ $page->hero_title_en ?? 'Contact Us' }}">
                    </div>

                    <div class="form-group">
                        <label><span class="ar-text">النص الفرعي</span><span class="en-text">Hero Subtitle</span></label>
                        <textarea name="hero_subtitle_en" class="form-control" rows="2">{{ $page->hero_subtitle_en ?? 'We are here to answer all your inquiries' }}</textarea>
                    </div>
                </div>

                <!-- Contact Information Section EN -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-phone"></i> <span class="ar-text">معلومات التواصل</span><span class="en-text">Contact Information</span>
                    </div>

                    <div class="form-group">
                        <label><span class="ar-text">العنوان</span><span class="en-text">Address (English)</span></label>
                        <textarea name="address_en" class="form-control" rows="2">{{ $page->address_en ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Working Hours Section EN -->
                <div class="form-section">
                    <div class="form-section-title">
                        🕒 <span class="ar-text">أوقات العمل</span><span class="en-text">Working Hours</span>
                    </div>

                    <div class="form-group">
                        <label><span class="ar-text">أوقات العمل</span><span class="en-text">Working Hours (English)</span></label>
                        <div id="editor_working_hours_en" class="quill-editor ltr-editor"></div>
                        <textarea name="working_hours_en" style="display:none;">{{ $page->working_hours_en ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Additional Information Section EN -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-circle-info"></i> <span class="ar-text">معلومات إضافية</span><span class="en-text">Additional Information</span>
                    </div>

                    <div class="form-group">
                        <label><span class="ar-text">معلومات إضافية</span><span class="en-text">Additional Information (English)</span></label>
                        <div id="editor_additional_info_en" class="quill-editor ltr-editor"></div>
                        <textarea name="additional_info_en" style="display:none;">{{ $page->additional_info_en ?? '' }}</textarea>
                    </div>
                </div>

                <!-- SEO Section EN -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-magnifying-glass"></i> <span class="ar-text">إعدادات SEO</span><span class="en-text">SEO Settings</span>
                    </div>

                    <div class="form-group">
                        <label><span class="ar-text">وصف الصفحة</span><span class="en-text">Meta Description (English)</span></label>
                        <textarea name="meta_description_en" class="form-control" rows="2">{{ $page->meta_description_en ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label><span class="ar-text">الكلمات المفتاحية</span><span class="en-text">Meta Keywords (English)</span></label>
                        <input type="text" name="meta_keywords_en" class="form-control" value="{{ $page->meta_keywords_en ?? '' }}" placeholder="keyword1, keyword2, keyword3">
                    </div>
                </div>
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
            <a href="{{ route('contact') }}" target="_blank" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
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
        <iframe id="previewFrame" class="preview-frame desktop" src="{{ route('contact') }}"></iframe>
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

        // Arabic Working Hours Editor
        quillEditors.working_hours_ar = new Quill('#editor_working_hours_ar', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'اكتب ساعات العمل هنا...'
        });

        const workingHoursArContent = document.querySelector('textarea[name="working_hours_ar"]').value;
        if (workingHoursArContent) {
            quillEditors.working_hours_ar.root.innerHTML = workingHoursArContent;
        }

        // English Working Hours Editor
        quillEditors.working_hours_en = new Quill('#editor_working_hours_en', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Write working hours here...'
        });

        const workingHoursEnContent = document.querySelector('textarea[name="working_hours_en"]').value;
        if (workingHoursEnContent) {
            quillEditors.working_hours_en.root.innerHTML = workingHoursEnContent;
        }

        // Arabic Additional Info Editor
        quillEditors.additional_info_ar = new Quill('#editor_additional_info_ar', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'اكتب معلومات إضافية هنا...'
        });

        const additionalInfoArContent = document.querySelector('textarea[name="additional_info_ar"]').value;
        if (additionalInfoArContent) {
            quillEditors.additional_info_ar.root.innerHTML = additionalInfoArContent;
        }

        // English Additional Info Editor
        quillEditors.additional_info_en = new Quill('#editor_additional_info_en', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Write additional information here...'
        });

        const additionalInfoEnContent = document.querySelector('textarea[name="additional_info_en"]').value;
        if (additionalInfoEnContent) {
            quillEditors.additional_info_en.root.innerHTML = additionalInfoEnContent;
        }
    });

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

    // Close preview with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const panel = document.getElementById('previewPanel');
            if (panel.classList.contains('active')) {
                togglePreview();
            }
        }
    });

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

    // Form Submit
    document.getElementById('contactForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Sync Quill editors content to textareas
        document.querySelector('textarea[name="working_hours_ar"]').value = quillEditors.working_hours_ar.root.innerHTML;
        document.querySelector('textarea[name="working_hours_en"]').value = quillEditors.working_hours_en.root.innerHTML;
        document.querySelector('textarea[name="additional_info_ar"]').value = quillEditors.additional_info_ar.root.innerHTML;
        document.querySelector('textarea[name="additional_info_en"]').value = quillEditors.additional_info_en.root.innerHTML;

        const saveBtn = document.getElementById('saveBtn');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="4" stroke-dasharray="31.4" stroke-dashoffset="10" style="animation: spin 1s linear infinite;"></circle></svg><span>جاري الحفظ...</span>';

        const formData = new FormData(this);

        try {
            const response = await fetch('{{ route("admin.contact.update") }}', {
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
