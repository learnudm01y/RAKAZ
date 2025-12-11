@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إضافة تصنيف جديد' : 'Add New Category')

@section('page-title')
    <span class="ar-text">إضافة تصنيف جديد</span>
    <span class="en-text">Add New Category</span>
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

    .parent-category-info {
        padding: 1rem;
        background: #f0f9ff;
        border: 1px solid #3b82f6;
        border-radius: 8px;
        margin-top: 0.5rem;
        display: none;
    }

    .parent-category-info.show {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="form-header">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">
            <span class="ar-text">إضافة تصنيف جديد</span>
            <span class="en-text">Add New Category</span>
        </h2>
        <p style="color: #666; font-size: 0.875rem;">
            <span class="ar-text">أضف تصنيفاً رئيسياً أو فرعياً بلغتين</span>
            <span class="en-text">Add a main or sub category in both languages</span>
        </p>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Category Names -->
        <div class="row">
            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">اسم التصنيف (عربي) *</span>
                    <span class="en-text">Category Name (Arabic) *</span>
                </label>
                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar') }}" required>
                @error('name_ar')
                    <p class="helper-text" style="color: var(--danger-color);">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">اسم التصنيف (إنجليزي) *</span>
                    <span class="en-text">Category Name (English) *</span>
                </label>
                <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}" required>
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
                <input type="text" name="slug_ar" class="form-control" value="{{ old('slug_ar') }}" placeholder="auto-generated">
                <p class="helper-text">
                    <i class="fas fa-info-circle"></i>
                    <span class="ar-text">اتركه فارغاً للإنشاء تلقائياً</span>
                    <span class="en-text">Leave empty for auto-generation</span>
                </p>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الرابط (إنجليزي)</span>
                    <span class="en-text">Slug (English)</span>
                </label>
                <input type="text" name="slug_en" class="form-control" value="{{ old('slug_en') }}" placeholder="auto-generated">
                <p class="helper-text">
                    <i class="fas fa-info-circle"></i>
                    <span class="ar-text">اتركه فارغاً للإنشاء تلقائياً</span>
                    <span class="en-text">Leave empty for auto-generation</span>
                </p>
            </div>
        </div>

        <!-- Descriptions -->
        <div class="row">
            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الوصف (عربي)</span>
                    <span class="en-text">Description (Arabic)</span>
                </label>
                <textarea name="description_ar" class="form-control">{{ old('description_ar') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الوصف (إنجليزي)</span>
                    <span class="en-text">Description (English)</span>
                </label>
                <textarea name="description_en" class="form-control">{{ old('description_en') }}</textarea>
            </div>
        </div>

        <!-- Parent Category -->
        <div class="form-group">
            <label class="form-label">
                <span class="ar-text">التصنيف الأب (اختياري)</span>
                <span class="en-text">Parent Category (Optional)</span>
            </label>
            <select name="parent_id" class="form-control" id="parentCategorySelect">
                <option value="">
                    <span class="ar-text">-- تصنيف رئيسي --</span>
                    <span class="en-text">-- Main Category --</span>
                </option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->getName() }}
                    </option>
                @endforeach
            </select>
            <p class="helper-text">
                <i class="fas fa-info-circle"></i>
                <span class="ar-text">اختر التصنيف الأب لجعل هذا تصنيفاً فرعياً</span>
                <span class="en-text">Select parent to make this a subcategory</span>
            </p>
            <div class="parent-category-info" id="parentInfo">
                <strong>
                    <span class="ar-text">📂 سيكون هذا تصنيفاً فرعياً</span>
                    <span class="en-text">📂 This will be a subcategory</span>
                </strong>
            </div>
        </div>

        <!-- Image Upload -->
        <div class="form-group">
            <label class="form-label">
                <span class="ar-text">صورة التصنيف</span>
                <span class="en-text">Category Image</span>
            </label>
            <div class="image-upload-area" onclick="document.getElementById('imageInput').click()">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto; color: #cbd5e0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p style="margin-top: 1rem; color: #666;">
                    <span class="ar-text">انقر لرفع صورة</span>
                    <span class="en-text">Click to upload image</span>
                </p>
                <p style="font-size: 0.875rem; color: #999;">
                    <span class="ar-text">PNG, JPG, GIF حتى 2MB</span>
                    <span class="en-text">PNG, JPG, GIF up to 2MB</span>
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
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
            <p class="helper-text">
                <i class="fas fa-info-circle"></i>
                <span class="ar-text">الأرقام الأصغر تظهر أولاً</span>
                <span class="en-text">Lower numbers appear first</span>
            </p>
        </div>

        <!-- Active Status -->
        <div class="form-group">
            <div class="checkbox-wrapper">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
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
                <span class="ar-text">حفظ التصنيف</span>
                <span class="en-text">Save Category</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }

        reader.readAsDataURL(input.files[0]);
    }
}

// Show/hide parent category info
document.getElementById('parentCategorySelect').addEventListener('change', function() {
    const parentInfo = document.getElementById('parentInfo');
    if (this.value) {
        parentInfo.classList.add('show');
    } else {
        parentInfo.classList.remove('show');
    }
});

// Check on page load
if (document.getElementById('parentCategorySelect').value) {
    document.getElementById('parentInfo').classList.add('show');
}
</script>
@endpush
