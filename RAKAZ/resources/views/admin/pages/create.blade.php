@extends('admin.layouts.app')

@section('title', 'إضافة صفحة جديدة')

@section('content')
<div class="content-header">
    <div class="content-header-left">
        <h1 class="page-title">
            <span class="ar-text">إضافة صفحة جديدة</span>
            <span class="en-text">Add New Page</span>
        </h1>
    </div>
    <div class="content-header-right">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="ar-text">رجوع</span>
            <span class="en-text">Back</span>
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.pages.store') }}" class="page-form">
    @csrf

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
                    <textarea
                        id="content_ar"
                        name="content_ar"
                        rows="12"
                        class="form-control editor @error('content_ar') is-invalid @enderror"
                        required
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
                    <textarea
                        id="content_en"
                        name="content_en"
                        rows="12"
                        class="form-control editor @error('content_en') is-invalid @enderror"
                        required
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
            <div class="form-row">
                <div class="form-group">
                    <label for="slug" class="required">
                        <span class="ar-text">الرابط (Slug)</span>
                        <span class="en-text">URL Slug</span>
                    </label>
                    <input
                        type="text"
                        id="slug"
                        name="slug"
                        value="{{ old('slug') }}"
                        class="form-control @error('slug') is-invalid @enderror"
                        placeholder="about-us"
                        required
                    >
                    <small class="form-text">
                        <span class="ar-text">مثال: about-us أو privacy-policy</span>
                        <span class="en-text">Example: about-us or privacy-policy</span>
                    </small>
                    @error('slug')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="required">
                        <span class="ar-text">الحالة</span>
                        <span class="en-text">Status</span>
                    </label>
                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                            <span class="ar-text">نشط</span>
                            <span class="en-text">Active</span>
                        </option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            <span class="ar-text">غير نشط</span>
                            <span class="en-text">Inactive</span>
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
