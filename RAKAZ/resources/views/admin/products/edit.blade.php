@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'تعديل المنتج' : 'Edit Product')

@section('content')
<div class="product-form-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 28px; height: 28px; display: inline-block; vertical-align: middle; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 10px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                {{ app()->getLocale() == 'ar' ? 'تعديل المنتج' : 'Edit Product' }}
            </h1>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ app()->getLocale() == 'ar' ? 'رجوع' : 'Back' }}
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>خطأ!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>يرجى تصحيح الأخطاء التالية:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="product-form" id="product-form">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main Content Column -->
            <div class="col-md-8">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ app()->getLocale() == 'ar' ? 'المعلومات الأساسية' : 'Basic Information' }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">{{ app()->getLocale() == 'ar' ? 'اسم المنتج (عربي)' : 'Product Name (Arabic)' }}</label>
                                <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $product->getName('ar')) }}" required>
                                @error('name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required">{{ app()->getLocale() == 'ar' ? 'اسم المنتج (إنجليزي)' : 'Product Name (English)' }}</label>
                                <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $product->getName('en')) }}" required>
                                @error('name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Hidden slug fields - auto-generated from product names -->
                            <input type="hidden" name="slug_ar" value="{{ old('slug_ar', $product->getSlug('ar')) }}">
                            <input type="hidden" name="slug_en" value="{{ old('slug_en', $product->getSlug('en')) }}">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'وصف مختصر (عربي)' : 'Short Description (Arabic)' }}</label>
                                <div id="short_description_ar_editor" style="height: 150px; background: #fff;"></div>
                                <textarea name="short_description_ar" id="short_description_ar" class="d-none">{{ old('short_description_ar', $product->short_description['ar'] ?? '') }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'وصف مختصر (إنجليزي)' : 'Short Description (English)' }}</label>
                                <div id="short_description_en_editor" style="height: 150px; background: #fff;"></div>
                                <textarea name="short_description_en" id="short_description_en" class="d-none">{{ old('short_description_en', $product->short_description['en'] ?? '') }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الوصف الكامل (عربي)' : 'Full Description (Arabic)' }}</label>
                                <div id="description_ar_editor" style="height: 250px; background: #fff;"></div>
                                <textarea name="description_ar" id="description_ar" class="d-none">{{ old('description_ar', $product->description['ar'] ?? '') }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الوصف الكامل (إنجليزي)' : 'Full Description (English)' }}</label>
                                <div id="description_en_editor" style="height: 250px; background: #fff;"></div>
                                <textarea name="description_en" id="description_en" class="d-none">{{ old('description_en', $product->description['en'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ app()->getLocale() == 'ar' ? 'التسعير' : 'Pricing' }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">{{ app()->getLocale() == 'ar' ? 'السعر الأساسي' : 'Regular Price' }}</label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                                    <span class="input-group-text">{{ app()->getLocale() == 'ar' ? 'ر.س' : 'SAR' }}</span>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'سعر التخفيض' : 'Sale Price' }}</label>
                                <div class="input-group">
                                    <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0">
                                    <span class="input-group-text">{{ app()->getLocale() == 'ar' ? 'ر.س' : 'SAR' }}</span>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'سعر التكلفة' : 'Cost Price' }}</label>
                                <div class="input-group">
                                    <input type="number" name="cost" class="form-control" value="{{ old('cost', $product->cost) }}" step="0.01" min="0">
                                    <span class="input-group-text">{{ app()->getLocale() == 'ar' ? 'ر.س' : 'SAR' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ app()->getLocale() == 'ar' ? 'إدارة المخزون' : 'Inventory Management' }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'رمز المنتج (SKU)' : 'Product SKU' }}</label>
                                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" placeholder="PRD-{{ strtoupper(Str::random(8)) }}">
                                <small class="form-text text-muted">{{ app()->getLocale() == 'ar' ? 'سيتم إنشاؤه تلقائياً إذا ترك فارغاً' : 'Will be auto-generated if left empty' }}</small>
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'حالة المخزون' : 'Stock Status' }}</label>
                                <select name="stock_status" class="form-control" required>
                                    <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'متوفر' : 'In Stock' }}</option>
                                    <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'غير متوفر' : 'Out of Stock' }}</option>
                                    <option value="on_backorder" {{ old('stock_status', $product->stock_status) == 'on_backorder' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'طلب مسبق' : 'On Backorder' }}</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="manage_stock" id="manage_stock" {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="manage_stock">
                                        {{ app()->getLocale() == 'ar' ? 'إدارة المخزون' : 'Manage Stock' }}
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3" id="stock_quantity_field">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'كمية المخزون' : 'Stock Quantity' }}</label>
                                <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0">
                            </div>

                            <div class="col-md-6 mb-3" id="low_stock_field">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'حد المخزون المنخفض' : 'Low Stock Threshold' }}</label>
                                <input type="number" name="low_stock_threshold" class="form-control" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="col-md-4">
                <!-- Product Images -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ app()->getLocale() == 'ar' ? 'صور المنتج' : 'Product Images' }}</h3>
                    </div>
                    <div class="card-body">
                        <!-- Main Image -->
                        <div class="mb-3">
                            <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الصورة الرئيسية' : 'Main Image' }}</label>
                            <div class="image-upload-wrapper">
                                <div class="image-preview" id="image-preview">
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName() }}">
                                    @else
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p>{{ app()->getLocale() == 'ar' ? 'اضغط لاختيار صورة' : 'Click to select image' }}</p>
                                    @endif
                                </div>
                                <input type="file" name="main_image" id="main_image" class="d-none" accept="image/*">
                            </div>
                            @error('main_image')
                                <div class="text-danger mt-2" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gallery Images -->
                        <div>
                            <label class="form-label">{{ app()->getLocale() == 'ar' ? 'معرض الصور' : 'Image Gallery' }}</label>
                            <div class="gallery-upload-wrapper">
                                <button type="button" class="btn btn-outline-primary btn-sm w-100" id="add-gallery-images">
                                    <i class="fas fa-plus"></i>
                                    {{ app()->getLocale() == 'ar' ? 'إضافة صور' : 'Add Images' }}
                                </button>
                                <input type="file" name="gallery_images[]" id="gallery_images" class="d-none" accept="image/*" multiple>
                            </div>
                            <div id="gallery-preview" class="gallery-preview-grid mt-3">
                                @if($product->gallery_images && is_array($product->gallery_images))
                                    @foreach($product->gallery_images as $index => $image)
                                        <div class="gallery-image-item" data-existing="{{ $image }}">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image">
                                            <button type="button" class="remove-image" onclick="removeExistingImage(this, '{{ $image }}')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <input type="hidden" name="removed_images" id="removed_images" value="">
                            @error('gallery_images')
                                <div class="text-danger mt-2" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ app()->getLocale() == 'ar' ? 'التصنيف' : 'Category' }}</h3>
                    </div>
                    <div class="card-body">
                        <select name="category_id" class="form-control">
                            <option value="">{{ app()->getLocale() == 'ar' ? 'بدون تصنيف' : 'No Category' }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->getName(app()->getLocale()) }}
                                </option>
                                @if($category->children->count() > 0)
                                    @foreach($category->children as $child)
                                        <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                            — {{ $child->getName(app()->getLocale()) }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Brand & Manufacturer -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ app()->getLocale() == 'ar' ? 'العلامة التجارية' : 'Brand & Manufacturer' }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ app()->getLocale() == 'ar' ? 'العلامة التجارية' : 'Brand' }}</label>
                            <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الشركة المصنعة' : 'Manufacturer' }}</label>
                            <input type="text" name="manufacturer" class="form-control" value="{{ old('manufacturer', $product->manufacturer) }}">
                        </div>
                    </div>
                </div>

                <!-- Product Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ app()->getLocale() == 'ar' ? 'حالة المنتج' : 'Product Status' }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                {{ app()->getLocale() == 'ar' ? 'نشط' : 'Active' }}
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                {{ app()->getLocale() == 'ar' ? 'منتج مميز' : 'Featured Product' }}
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_new" id="is_new" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_new">
                                {{ app()->getLocale() == 'ar' ? 'منتج جديد' : 'New Product' }}
                            </label>
                        </div>

                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="is_on_sale" id="is_on_sale" {{ old('is_on_sale', $product->is_on_sale) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_on_sale">
                                {{ app()->getLocale() == 'ar' ? 'في التخفيضات' : 'On Sale' }}
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Sort Order -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ app()->getLocale() == 'ar' ? 'ترتيب العرض' : 'Display Order' }}</h3>
                    </div>
                    <div class="card-body">
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $product->sort_order) }}" min="0">
                        <small class="form-text text-muted">{{ app()->getLocale() == 'ar' ? 'الأقل يظهر أولاً' : 'Lower numbers appear first' }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ app()->getLocale() == 'ar' ? 'حفظ المنتج' : 'Save Product' }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-lg">
                {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
            </a>
        </div>
    </form>
</div>

<style>
/* Product Form Styles */
.product-form-container {
    padding: 20px;
}

.card-title {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.form-label.required::after {
    content: ' *';
    color: #dc2626;
}

/* Image Upload */
.image-upload-wrapper {
    cursor: pointer;
}

.image-preview {
    width: 100%;
    height: 250px;
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
    transition: all 0.3s;
    overflow: hidden;
}

.image-preview:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.image-preview svg {
    width: 48px;
    height: 48px;
    color: #9ca3af;
    margin-bottom: 12px;
}

.image-preview p {
    color: #6b7280;
    margin: 0;
    font-size: 14px;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    padding: 24px;
    background: #f9fafb;
    border-radius: 8px;
    margin-top: 24px;
}

.form-actions .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* Quill Editor Styling */
.ql-toolbar {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px 8px 0 0;
}

.ql-container {
    border: 1px solid #e5e7eb;
    border-top: none;
    border-radius: 0 0 8px 8px;
    font-family: 'Tajawal', sans-serif;
}

.ql-editor {
    min-height: 100px;
    font-size: 14px;
}

.ql-editor.ql-blank::before {
    color: #9ca3af;
    font-style: normal;
}

/* Hide textarea elements used for Quill data storage */
textarea.d-none {
    display: none !important;
}

/* Gallery Preview Grid */
.gallery-preview-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.gallery-image-item {
    position: relative;
    width: 100%;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e5e7eb;
}

.gallery-image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-image-item .remove-image {
    position: absolute;
    top: 4px;
    right: 4px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.gallery-image-item .remove-image:hover {
    background: #dc2626;
    transform: scale(1.1);
}

/* Responsive */
@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }

    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

@push('scripts')
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
$(document).ready(function() {
    const isArabic = '{{ app()->getLocale() }}' === 'ar';

    // Show error message with SweetAlert
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: isArabic ? 'خطأ!' : 'Error!',
            text: '{{ session('error') }}',
            confirmButtonText: isArabic ? 'حسناً' : 'OK',
            confirmButtonColor: '#dc3545'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: isArabic ? 'يرجى تصحيح الأخطاء' : 'Please correct the errors',
            html: '<ul style="text-align: ' + (isArabic ? 'right' : 'left') + '; list-style: none; padding: 0;">@foreach($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>',
            confirmButtonText: isArabic ? 'حسناً' : 'OK',
            confirmButtonColor: '#dc3545'
        });
    @endif

    // Form validation before submit
    $('#product-form').on('submit', function(e) {
        let errors = [];

        // Check required fields
        if (!$('input[name="name_ar"]').val()) {
            errors.push(isArabic ? 'اسم المنتج بالعربي مطلوب' : 'Product name in Arabic is required');
        }
        if (!$('input[name="name_en"]').val()) {
            errors.push(isArabic ? 'اسم المنتج بالإنجليزي مطلوب' : 'Product name in English is required');
        }
        if (!$('input[name="price"]').val()) {
            errors.push(isArabic ? 'سعر المنتج مطلوب' : 'Product price is required');
        }

        if (errors.length > 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: isArabic ? 'يرجى ملء الحقول المطلوبة' : 'Please fill required fields',
                html: '<ul style="text-align: ' + (isArabic ? 'right' : 'left') + '; list-style: none; padding: 0;">' +
                      errors.map(error => '<li>• ' + error + '</li>').join('') +
                      '</ul>',
                confirmButtonText: isArabic ? 'حسناً' : 'OK',
                confirmButtonColor: '#dc3545'
            });
            return false;
        }
    });

    // Initialize Quill Editors
    const quillOptions = {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                ['link'],
                ['clean']
            ]
        }
    };

    // Short Description Arabic
    const shortDescArEditor = new Quill('#short_description_ar_editor', quillOptions);
    shortDescArEditor.on('text-change', function() {
        $('#short_description_ar').val(shortDescArEditor.root.innerHTML);
    });
    if ($('#short_description_ar').val()) {
        shortDescArEditor.root.innerHTML = $('#short_description_ar').val();
    }

    // Short Description English
    const shortDescEnEditor = new Quill('#short_description_en_editor', quillOptions);
    shortDescEnEditor.on('text-change', function() {
        $('#short_description_en').val(shortDescEnEditor.root.innerHTML);
    });
    if ($('#short_description_en').val()) {
        shortDescEnEditor.root.innerHTML = $('#short_description_en').val();
    }

    // Full Description Arabic
    const descArEditor = new Quill('#description_ar_editor', quillOptions);
    descArEditor.on('text-change', function() {
        $('#description_ar').val(descArEditor.root.innerHTML);
    });
    if ($('#description_ar').val()) {
        descArEditor.root.innerHTML = $('#description_ar').val();
    }

    // Full Description English
    const descEnEditor = new Quill('#description_en_editor', quillOptions);
    descEnEditor.on('text-change', function() {
        $('#description_en').val(descEnEditor.root.innerHTML);
    });
    if ($('#description_en').val()) {
        descEnEditor.root.innerHTML = $('#description_en').val();
    }

    // Auto-generate slug from name (Arabic-friendly)
    $('input[name="name_ar"]').on('input', function() {
        const name = $(this).val();
        // Keep Arabic characters, replace spaces with hyphens, remove special characters
        const slug = name.toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^\u0600-\u06FF\u0660-\u0669a-z0-9\-]/g, '');
        $('input[name="slug_ar"]').val(slug);
    });

    $('input[name="name_en"]').on('input', function() {
        const slug = $(this).val().toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^a-z0-9\-]/g, '');
        $('input[name="slug_en"]').val(slug);
    });

    // Main image preview
    $('#image-preview').on('click', function() {
        $('#main_image').click();
    });

    $('#main_image').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').html('<img src="' + e.target.result + '" alt="Preview">');
            };
            reader.readAsDataURL(file);
        }
    });

    // Gallery images
    let galleryFiles = [];
    let removedImages = [];

    $('#add-gallery-images').on('click', function() {
        $('#gallery_images').click();
    });

    $('#gallery_images').on('change', function(e) {
        const files = Array.from(e.target.files);
        let loadedCount = 0;
        const totalFiles = files.filter(f => f.type.startsWith('image/')).length;

        if (totalFiles === 0) return;

        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const imageId = Date.now() + index;
                galleryFiles.push({ id: imageId, file: file });

                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageHtml = `
                        <div class="gallery-image-item" data-id="${imageId}">
                            <img src="${e.target.result}" alt="Gallery Image">
                            <button type="button" class="remove-image" onclick="removeGalleryImage(${imageId})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    $('#gallery-preview').append(imageHtml);

                    loadedCount++;
                    if (loadedCount === totalFiles) {
                        // Update input only after all files are loaded
                        updateGalleryInput();
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Update gallery input with all files
    function updateGalleryInput() {
        const dt = new DataTransfer();
        galleryFiles.forEach(item => {
            dt.items.add(item.file);
        });
        document.getElementById('gallery_images').files = dt.files;
        console.log('Gallery files count:', galleryFiles.length);
        console.log('Input files count:', document.getElementById('gallery_images').files.length);
    }

    // Toggle stock fields
    $('#manage_stock').on('change', function() {
        if ($(this).is(':checked')) {
            $('#stock_quantity_field, #low_stock_field').show();
        } else {
            $('#stock_quantity_field, #low_stock_field').hide();
        }
    }).trigger('change');
});

// Remove new gallery image
function removeGalleryImage(imageId) {
    $(`[data-id="${imageId}"]`).remove();
    galleryFiles = galleryFiles.filter(f => f.id !== imageId);

    // Update the input files
    const dt = new DataTransfer();
    galleryFiles.forEach(item => {
        dt.items.add(item.file);
    });
    document.getElementById('gallery_images').files = dt.files;
}

// Remove existing gallery image
function removeExistingImage(button, imagePath) {
    $(button).parent().remove();
    let removedImages = $('#removed_images').val().split(',').filter(i => i);
    removedImages.push(imagePath);
    $('#removed_images').val(removedImages.join(','));
}
</script>
@endpush
@endsection

