@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'تعديل التصنيف' : 'Edit Category')

@section('page-title')
    <span class="ar-text">تعديل التصنيف: {{ $category->getName() }}</span>
    <span class="en-text">Edit Category: {{ $category->getName() }}</span>
@endsection

@push('styles')
<style>
    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .form-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .image-upload-area {
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--hover-bg);
    }

    .image-upload-area:hover {
        border-color: var(--primary-color);
        background: #f0f9ff;
    }

    .current-image {
        max-width: 200px;
        max-height: 200px;
        margin: 1rem auto;
        border-radius: 8px;
        display: block;
    }

    .image-preview {
        max-width: 200px;
        max-height: 200px;
        margin: 1rem auto;
        border-radius: 8px;
        display: none;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        background: var(--hover-bg);
        border-radius: 8px;
    }

    .checkbox-wrapper input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .checkbox-wrapper label {
        cursor: pointer;
        margin: 0;
        font-weight: 500;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }

    .btn {
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background: #2c5aa0;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(49, 130, 206, 0.3);
    }

    .btn-secondary {
        background: var(--border-color);
        color: var(--text-color);
    }

    .btn-secondary:hover {
        background: #cbd5e0;
    }

    .helper-text {
        font-size: 0.875rem;
        color: #666;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .row {
            grid-template-columns: 1fr;
        }
    }

    .breadcrumb-info {
        padding: 1rem;
        background: #f0f9ff;
        border: 1px solid #3b82f6;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="form-header">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">
            <span class="ar-text">تعديل التصنيف</span>
            <span class="en-text">Edit Category</span>
        </h2>
        <p style="color: #666; font-size: 0.875rem;">{{ $category->getName() }}</p>
    </div>

    @if($category->parent)
        <div class="breadcrumb-info">
            <strong>
                <span class="ar-text">📍 المسار:</span>
                <span class="en-text">📍 Path:</span>
            </strong>
            {{ $category->getBreadcrumb()->implode(' → ') }}
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Category Names -->
        <div class="row">
            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">اسم التصنيف (عربي) *</span>
                    <span class="en-text">Category Name (Arabic) *</span>
                </label>
                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $category->name['ar'] ?? '') }}" required>
                @error('name_ar')
                    <p class="helper-text" style="color: var(--danger-color);">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">اسم التصنيف (إنجليزي) *</span>
                    <span class="en-text">Category Name (English) *</span>
                </label>
                <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $category->name['en'] ?? '') }}" required>
                @error('name_en')
                    <p class="helper-text" style="color: var(--danger-color);">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Slugs -->
        <div class="row">
            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الرابط (عربي)</span>
                    <span class="en-text">Slug (Arabic)</span>
                </label>
                <input type="text" name="slug_ar" class="form-control" value="{{ old('slug_ar', $category->slug['ar'] ?? '') }}">
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الرابط (إنجليزي)</span>
                    <span class="en-text">Slug (English)</span>
                </label>
                <input type="text" name="slug_en" class="form-control" value="{{ old('slug_en', $category->slug['en'] ?? '') }}">
            </div>
        </div>

        <!-- Descriptions -->
        <div class="row">
            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الوصف (عربي)</span>
                    <span class="en-text">Description (Arabic)</span>
                </label>
                <textarea name="description_ar" class="form-control">{{ old('description_ar', $category->description['ar'] ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الوصف (إنجليزي)</span>
                    <span class="en-text">Description (English)</span>
                </label>
                <textarea name="description_en" class="form-control">{{ old('description_en', $category->description['en'] ?? '') }}</textarea>
            </div>
        </div>

        <!-- Parent Category -->
        <div class="form-group">
            <label class="form-label">
                <span class="ar-text">التصنيف الأب (اختياري)</span>
                <span class="en-text">Parent Category (Optional)</span>
            </label>
            <select name="parent_id" class="form-control">
                <option value="">
                    <span class="ar-text">-- تصنيف رئيسي --</span>
                    <span class="en-text">-- Main Category --</span>
                </option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                        {{ $parent->getName() }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Image Upload -->
        <div class="form-group">
            <label class="form-label">
                <span class="ar-text">صورة التصنيف</span>
                <span class="en-text">Category Image</span>
            </label>

            @if($category->image)
                <img src="{{ $category->image }}" class="current-image" alt="{{ $category->getName() }}" id="currentImage">
                <p style="text-align: center; color: #666; margin-top: 0.5rem;">
                    <span class="ar-text">الصورة الحالية</span>
                    <span class="en-text">Current Image</span>
                </p>
            @endif

            <div class="image-upload-area" onclick="document.getElementById('imageInput').click()">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto; color: #cbd5e0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p style="margin-top: 1rem; color: #666;">
                    <span class="ar-text">انقر لرفع صورة جديدة</span>
                    <span class="en-text">Click to upload new image</span>
                </p>
            </div>
            <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;" onchange="previewImage(this)">
            <img id="imagePreview" class="image-preview" alt="Preview">
        </div>

        <!-- Sort Order -->
        <div class="form-group">
            <label class="form-label">
                <span class="ar-text">ترتيب العرض</span>
                <span class="en-text">Sort Order</span>
            </label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order) }}" min="0">
        </div>

        <!-- Active Status -->
        <div class="form-group">
            <div class="checkbox-wrapper">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <label for="is_active">
                    <span class="ar-text">✅ تفعيل التصنيف</span>
                    <span class="en-text">✅ Activate Category</span>
                </label>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="ar-text">إلغاء</span>
                <span class="en-text">Cancel</span>
            </a>
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="ar-text">حفظ التعديلات</span>
                <span class="en-text">Save Changes</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const currentImage = document.getElementById('currentImage');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (currentImage) {
                currentImage.style.display = 'none';
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
