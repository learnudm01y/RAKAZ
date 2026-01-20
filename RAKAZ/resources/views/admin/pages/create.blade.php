@extends('admin.layouts.app')

@section('title', 'إضافة صفحة جديدة')

@push('styles')
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .page-form {
        max-width: 100%;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .card {
        background: var(--card-bg);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1a4d4d 100%);
        color: white;
        padding: 1rem 1.5rem;
    }

    .card-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .card-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-color);
    }

    .form-group label.required::after {
        content: '*';
        color: var(--danger-color);
        margin-right: 4px;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--card-bg);
        color: var(--text-color);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(201, 169, 110, 0.1);
    }

    .form-control.is-invalid {
        border-color: var(--danger-color);
    }

    .error-message {
        color: var(--danger-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-text {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
        margin-top: 1.5rem;
    }

    /* Quill Editor Styles */
    .quill-editor {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: visible;
        display: block !important;
    }

    .quill-editor .ql-toolbar {
        background: #f8f9fa;
        border: none;
        border-bottom: 1px solid var(--border-color);
        padding: 12px;
        direction: ltr;
        text-align: left;
        border-radius: 8px 8px 0 0;
        display: block !important;
    }

    .quill-editor .ql-toolbar .ql-formats {
        margin-right: 10px;
        display: inline-block !important;
    }

    .quill-editor .ql-container {
        font-size: 16px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        border: none;
        min-height: 300px;
        height: auto;
        display: block !important;
    }

    .quill-editor .ql-editor {
        min-height: 300px;
        height: auto;
        padding: 20px;
        line-height: 1.8;
        direction: rtl;
        text-align: right;
        display: block !important;
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

    /* Ensure Quill pickers SVG icons align correctly in RTL pages */
    [dir="rtl"] .ql-snow .ql-picker:not(.ql-color-picker):not(.ql-icon-picker) svg {
        right: 20px !important;
        left: auto !important;
    }

    /* Image Upload Styles */
    .image-upload-wrapper {
        border: 2px dashed var(--border-color);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: var(--card-bg);
    }

    .image-upload-wrapper:hover {
        border-color: var(--primary-color);
        background: rgba(201, 169, 110, 0.05);
    }

    .image-upload-wrapper.has-image {
        padding: 1rem;
    }

    .image-preview {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        margin: 0 auto 1rem;
        display: none;
    }

    .image-preview.show {
        display: block;
    }

    .upload-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 1rem;
        color: var(--text-muted);
    }

    .upload-text {
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .upload-hint {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .image-input {
        display: none;
    }

    .remove-image-btn {
        background: var(--danger-color);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .remove-image-btn:hover {
        background: #c82333;
    }

    /* Fix custom select dropdown overflow */
    .form-group {
        position: relative;
    }

    .card-body {
        overflow: visible;
    }

    .card {
        overflow: visible;
    }

    .custom-select-wrapper {
        position: relative;
    }

    .custom-select-options {
        position: absolute;
        z-index: 9999 !important;
    }

    @media (max-width: 1024px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="content-header-left" style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="ar-text">رجوع</span>
            <span class="en-text">Back</span>
        </a>
        <h1 class="page-title" style="margin: 0;">
            <span class="ar-text">إضافة صفحة جديدة</span>
            <span class="en-text">Add New Page</span>
        </h1>
    </div>
</div>

<form method="POST" action="{{ route('admin.pages.store') }}" class="page-form" enctype="multipart/form-data" id="pageForm">
    @csrf

    <!-- Image Upload Section -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3>
                <span class="ar-text">🖼️ صورة الصفحة</span>
                <span class="en-text">🖼️ Page Image</span>
            </h3>
        </div>
        <div class="card-body">
            <div class="image-upload-wrapper" id="imageUploadWrapper" onclick="document.getElementById('imageInput').click()">
                <img src="" alt="Preview" class="image-preview" id="imagePreview">
                <div class="upload-placeholder" id="uploadPlaceholder">
                    <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="upload-text">
                        <span class="ar-text">اضغط لرفع صورة أو اسحب وأفلت</span>
                        <span class="en-text">Click to upload or drag and drop</span>
                    </p>
                    <p class="upload-hint">
                        <span class="ar-text">PNG, JPG, GIF, WEBP حتى 5 ميجابايت - الحجم المثالي: 1200x600</span>
                        <span class="en-text">PNG, JPG, GIF, WEBP up to 5MB - Optimal size: 1200x600</span>
                    </p>
                </div>
                <input type="file" name="image" id="imageInput" class="image-input" accept="image/*" onchange="previewImage(this)">
            </div>
            <button type="button" class="remove-image-btn" id="removeImageBtn" style="display: none;" onclick="removeImage(event)">
                <span class="ar-text">إزالة الصورة</span>
                <span class="en-text">Remove Image</span>
            </button>
            @error('image')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-grid">
        <!-- Arabic Section -->
        <div class="card">
            <div class="card-header">
                <h3>🇸🇦 المحتوى العربي / Arabic Content</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="title_ar" class="required">
                        <span class="ar-text">العنوان بالعربي</span>
                        <span class="en-text">Arabic Title</span>
                    </label>
                    <input
                        type="text"
                        id="title_ar"
                        name="title_ar"
                        value="{{ old('title_ar') }}"
                        class="form-control @error('title_ar') is-invalid @enderror"
                        required
                    >
                    @error('title_ar')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content_ar" class="required">
                        <span class="ar-text">المحتوى بالعربي</span>
                        <span class="en-text">Arabic Content</span>
                    </label>
                    <div class="quill-editor">
                        <div id="editor_content_ar" style="min-height: 300px;"></div>
                    </div>
                    <textarea
                        id="content_ar"
                        name="content_ar"
                        style="display: none;"
                    >{{ old('content_ar') }}</textarea>
                    @error('content_ar')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="meta_description_ar">
                        <span class="ar-text">وصف SEO بالعربي</span>
                        <span class="en-text">Arabic SEO Description</span>
                    </label>
                    <textarea
                        id="meta_description_ar"
                        name="meta_description_ar"
                        rows="3"
                        class="form-control @error('meta_description_ar') is-invalid @enderror"
                        maxlength="255"
                    >{{ old('meta_description_ar') }}</textarea>
                    @error('meta_description_ar')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="meta_keywords_ar">
                        <span class="ar-text">الكلمات المفتاحية بالعربي</span>
                        <span class="en-text">Arabic Keywords</span>
                    </label>
                    <input
                        type="text"
                        id="meta_keywords_ar"
                        name="meta_keywords_ar"
                        value="{{ old('meta_keywords_ar') }}"
                        class="form-control @error('meta_keywords_ar') is-invalid @enderror"
                        placeholder="كلمة1, كلمة2, كلمة3"
                    >
                    @error('meta_keywords_ar')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- English Section -->
        <div class="card">
            <div class="card-header">
                <h3>🇬🇧 المحتوى الإنجليزي / English Content</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="title_en" class="required">
                        <span class="ar-text">العنوان بالإنجليزي</span>
                        <span class="en-text">English Title</span>
                    </label>
                    <input
                        type="text"
                        id="title_en"
                        name="title_en"
                        value="{{ old('title_en') }}"
                        class="form-control @error('title_en') is-invalid @enderror"
                        required
                    >
                    @error('title_en')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content_en" class="required">
                        <span class="ar-text">المحتوى بالإنجليزي</span>
                        <span class="en-text">English Content</span>
                    </label>
                    <div class="quill-editor ltr-editor">
                        <div id="editor_content_en" style="min-height: 300px;"></div>
                    </div>
                    <textarea
                        id="content_en"
                        name="content_en"
                        style="display: none;"
                    >{{ old('content_en') }}</textarea>
                    @error('content_en')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="meta_description_en">
                        <span class="ar-text">وصف SEO بالإنجليزي</span>
                        <span class="en-text">English SEO Description</span>
                    </label>
                    <textarea
                        id="meta_description_en"
                        name="meta_description_en"
                        rows="3"
                        class="form-control @error('meta_description_en') is-invalid @enderror"
                        maxlength="255"
                    >{{ old('meta_description_en') }}</textarea>
                    @error('meta_description_en')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="meta_keywords_en">
                        <span class="ar-text">الكلمات المفتاحية بالإنجليزي</span>
                        <span class="en-text">English Keywords</span>
                    </label>
                    <input
                        type="text"
                        id="meta_keywords_en"
                        name="meta_keywords_en"
                        value="{{ old('meta_keywords_en') }}"
                        class="form-control @error('meta_keywords_en') is-invalid @enderror"
                        placeholder="keyword1, keyword2, keyword3"
                    >
                    @error('meta_keywords_en')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Section -->
    <div class="card">
        <div class="card-header">
            <h3>
                <span class="ar-text">⚙️ إعدادات الصفحة</span>
                <span class="en-text">⚙️ Page Settings</span>
            </h3>
        </div>
        <div class="card-body">
            <div class="form-row" style="grid-template-columns: repeat(2, 1fr);">
                <input type="hidden" id="slug" name="slug" value="{{ old('slug') }}">
                <div class="form-group">
                    <label for="status" class="required">
                        <span class="ar-text">الحالة</span>
                        <span class="en-text">Status</span>
                    </label>
                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                            نشط / Active
                        </option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            غير نشط / Inactive
                        </option>
                    </select>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="order">
                        <span class="ar-text">الترتيب</span>
                        <span class="en-text">Order</span>
                    </label>
                    <input
                        type="number"
                        id="order"
                        name="order"
                        value="{{ old('order', 0) }}"
                        class="form-control @error('order') is-invalid @enderror"
                        min="0"
                    >
                    @error('order')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-lg">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="ar-text">حفظ الصفحة</span>
            <span class="en-text">Save Page</span>
        </button>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary btn-lg">
            <span class="ar-text">إلغاء</span>
            <span class="en-text">Cancel</span>
        </a>
    </div>
</form>
@endsection

@push('scripts')
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    let quillEditors = {};

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

        // Arabic Content Editor
        quillEditors.content_ar = new Quill('#editor_content_ar', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'اكتب المحتوى هنا...'
        });

        const contentArValue = document.querySelector('textarea[name="content_ar"]').value;
        if (contentArValue) {
            quillEditors.content_ar.root.innerHTML = contentArValue;
        }

        // English Content Editor
        quillEditors.content_en = new Quill('#editor_content_en', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Write content here...'
        });

        const contentEnValue = document.querySelector('textarea[name="content_en"]').value;
        if (contentEnValue) {
            quillEditors.content_en.root.innerHTML = contentEnValue;
        }

        // Sync editors content before form submit
        document.getElementById('pageForm').addEventListener('submit', function(e) {
            const contentAr = quillEditors.content_ar.root.innerHTML;
            const contentEn = quillEditors.content_en.root.innerHTML;

            // Check if content is empty (only contains empty tags)
            const isArEmpty = contentAr === '<p><br></p>' || contentAr.trim() === '';
            const isEnEmpty = contentEn === '<p><br></p>' || contentEn.trim() === '';

            if (isArEmpty || isEnEmpty) {
                e.preventDefault();
                if (isArEmpty) {
                    alert('يرجى إدخال المحتوى بالعربي / Please enter Arabic content');
                    quillEditors.content_ar.focus();
                } else {
                    alert('يرجى إدخال المحتوى بالإنجليزي / Please enter English content');
                    quillEditors.content_en.focus();
                }
                return false;
            }

            document.querySelector('textarea[name="content_ar"]').value = contentAr;
            document.querySelector('textarea[name="content_en"]').value = contentEn;

            // Auto-generate slug from English title if empty
            const slugField = document.getElementById('slug');
            const titleEn = document.getElementById('title_en').value;
            if (!slugField.value && titleEn) {
                slugField.value = generateSlug(titleEn);
            }
        });

        // Auto-generate slug from English title
        document.getElementById('title_en').addEventListener('input', function() {
            const slugField = document.getElementById('slug');
            slugField.value = generateSlug(this.value);
        });
    });

    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        const wrapper = document.getElementById('imageUploadWrapper');
        const removeBtn = document.getElementById('removeImageBtn');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.add('show');
                placeholder.style.display = 'none';
                wrapper.classList.add('has-image');
                removeBtn.style.display = 'inline-block';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage(event) {
        event.stopPropagation();

        const input = document.getElementById('imageInput');
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        const wrapper = document.getElementById('imageUploadWrapper');
        const removeBtn = document.getElementById('removeImageBtn');

        input.value = '';
        preview.src = '';
        preview.classList.remove('show');
        placeholder.style.display = 'block';
        wrapper.classList.remove('has-image');
        removeBtn.style.display = 'none';
    }
</script>
@endpush
