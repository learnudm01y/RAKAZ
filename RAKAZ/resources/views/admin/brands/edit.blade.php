@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'تعديل البراند' : 'Edit Brand')

@section('page-title')
    <span class="ar-text">تعديل البراند</span>
    <span class="en-text">Edit Brand</span>
@endsection

@push('styles')
<style>
    .brand-form {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 30px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #1a1a1a;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-check input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .image-upload {
        border: 2px dashed #ddd;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .image-upload:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }

    .image-upload svg {
        width: 48px;
        height: 48px;
        color: #999;
        margin-bottom: 10px;
    }

    .current-logo {
        margin-bottom: 15px;
        text-align: center;
    }

    .current-logo img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
        background: white;
    }

    .image-preview {
        margin-top: 15px;
        display: none;
        text-align: center;
    }

    .image-preview img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 30px;
    }

    [dir="ltr"] .form-actions {
        justify-content: flex-start;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 13px;
        margin-top: 5px;
    }
</style>
@endpush

@section('content')
<div class="brand-form">
    <div class="card">
        <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">اسم البراند (عربي)</span>
                    <span class="en-text">Brand Name (Arabic)</span>
                    <span style="color: red;">*</span>
                </label>
                <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $brand->name_ar) }}" required>
                @error('name_ar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">اسم البراند (إنجليزي)</span>
                    <span class="en-text">Brand Name (English)</span>
                    <span style="color: red;">*</span>
                </label>
                <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $brand->name_en) }}" required>
                @error('name_en')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الترتيب</span>
                    <span class="en-text">Sort Order</span>
                </label>
                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $brand->sort_order) }}">
                @error('sort_order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-label" style="margin: 0;">
                        <span class="ar-text">تفعيل البراند</span>
                        <span class="en-text">Activate Brand</span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="ar-text">تحديث البراند</span>
                    <span class="en-text">Update Brand</span>
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="ar-text">إلغاء</span>
                    <span class="en-text">Cancel</span>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('logoPreview');
    const img = document.getElementById('logoImg');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
