@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'تعديل المنتج' : 'Edit Product')

@section('page-title')
    <span class="ar-text">تعديل المنتج</span>
    <span class="en-text">Edit Product</span>
@endsection

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
            <!-- Right Column - Basic Information (70%) -->
            <div class="col-lg-8">
                <!-- Names and Descriptions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ app()->getLocale() == 'ar' ? 'المعلومات الأساسية' : 'Basic Information' }}</h3>
                    </div>
                    <div class="card-body">
                        <!-- Hidden slug fields - auto-generated from product names -->
                        <input type="hidden" name="slug_ar" value="{{ old('slug_ar', $product->getSlug('ar')) }}">
                        <input type="hidden" name="slug_en" value="{{ old('slug_en', $product->getSlug('en')) }}">

                        <div class="mb-3">
                            <label class="form-label required">{{ app()->getLocale() == 'ar' ? 'اسم المنتج (عربي)' : 'Product Name (Arabic)' }}</label>
                            <input type="text" name="name_ar" dir="rtl" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $product->getName('ar')) }}" required>
                            @error('name_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">{{ app()->getLocale() == 'ar' ? 'اسم المنتج (إنجليزي)' : 'Product Name (English)' }}</label>
                            <input type="text" name="name_en" dir="ltr" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $product->getName('en')) }}" required>
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الوصف (عربي)' : 'Description (Arabic)' }}</label>
                            <div id="description_ar_editor" class="quill-editor-rtl" style="height: 250px; background: #fff;" dir="rtl"></div>
                            <textarea name="description_ar" id="description_ar" class="d-none">{{ old('description_ar', $product->description['ar'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الوصف (إنجليزي)' : 'Description (English)' }}</label>
                            <div id="description_en_editor" class="quill-editor-ltr" style="height: 250px; background: #fff;" dir="ltr"></div>
                            <textarea name="description_en" id="description_en" class="d-none">{{ old('description_en', $product->description['en'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ app()->getLocale() == 'ar' ? 'المقاسات والحجم (عربي)' : 'Sizing Info (Arabic)' }}</label>
                            <div id="sizing_info_ar_editor" class="quill-editor-rtl" style="height: 200px; background: #fff;" dir="rtl"></div>
                            <textarea name="sizing_info_ar" id="sizing_info_ar" class="d-none">{{ old('sizing_info_ar', $product->sizing_info['ar'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ app()->getLocale() == 'ar' ? 'المقاسات والحجم (إنجليزي)' : 'Sizing Info (English)' }}</label>
                            <div id="sizing_info_en_editor" class="quill-editor-ltr" style="height: 200px; background: #fff;" dir="ltr"></div>
                            <textarea name="sizing_info_en" id="sizing_info_en" class="d-none">{{ old('sizing_info_en', $product->sizing_info['en'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ app()->getLocale() == 'ar' ? 'تفاصيل التصميم (عربي)' : 'Design Details (Arabic)' }}</label>
                            <div id="design_details_ar_editor" class="quill-editor-rtl" style="height: 200px; background: #fff;" dir="rtl"></div>
                            <textarea name="design_details_ar" id="design_details_ar" class="d-none">{{ old('design_details_ar', $product->design_details['ar'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">{{ app()->getLocale() == 'ar' ? 'تفاصيل التصميم (إنجليزي)' : 'Design Details (English)' }}</label>
                            <div id="design_details_en_editor" class="quill-editor-ltr" style="height: 200px; background: #fff;" dir="ltr"></div>
                            <textarea name="design_details_en" id="design_details_en" class="d-none">{{ old('design_details_en', $product->design_details['en'] ?? '') }}</textarea>
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
                <div class="card mb-4 inventory-card">
                    <div class="card-header">
                        <h3 class="card-title">{{ app()->getLocale() == 'ar' ? 'إدارة المخزون' : 'Inventory Management' }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'رمز المنتج (SKU)' : 'SKU' }}</label>
                                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" placeholder="{{ $product->sku }}">
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

                            <div class="col-md-4 mb-3" id="stock_quantity_field">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'كمية المخزون' : 'Stock Quantity' }}</label>
                                <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0">
                            </div>

                            <div class="col-md-4 mb-3" id="low_stock_field">
                                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'حد المخزون المنخفض' : 'Low Stock Threshold' }}</label>
                                <input type="number" name="low_stock_threshold" class="form-control" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Column - Images & Settings (30%) -->
            <div class="col-lg-4">
                <!-- Product Images -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ app()->getLocale() == 'ar' ? 'صور المنتج' : 'Product Images' }}</h3>
                        <small class="text-muted d-block mt-1">{{ app()->getLocale() == 'ar' ? 'نسبة العرض: 3:4 (مثل 300×400 بكسل)' : 'Aspect Ratio: 3:4 (e.g., 300×400 px)' }}</small>
                    </div>
                    <div class="card-body">
                        <!-- Main Image (First - Default Display) -->
                        <div class="mb-4">
                            <label class="form-label required">
                                <span class="badge bg-primary me-2">1</span>
                                {{ app()->getLocale() == 'ar' ? 'الصورة الرئيسية (الافتراضية)' : 'Main Image (Default)' }}
                            </label>
                            <div class="image-upload-wrapper">
                                <div class="product-image-preview" id="main-image-preview">
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="Main Image">
                                    @else
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="mb-0">{{ app()->getLocale() == 'ar' ? 'الصورة الأولى' : 'First Image' }}</p>
                                        <small class="text-muted">{{ app()->getLocale() == 'ar' ? 'تظهر بشكل افتراضي' : 'Shows by default' }}</small>
                                    @endif
                                </div>
                                <input type="file" name="main_image" id="main_image" class="d-none" accept="image/*">
                            </div>
                            @error('main_image')
                                <div class="text-danger mt-2" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hover Image (Second - Shows on Hover) -->
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="badge bg-secondary me-2">2</span>
                                {{ app()->getLocale() == 'ar' ? 'صورة التمرير (تظهر عند Hover)' : 'Hover Image (Shows on Hover)' }}
                            </label>
                            <div class="image-upload-wrapper">
                                <div class="product-image-preview" id="hover-image-preview">
                                    @if($product->hover_image)
                                        <img src="{{ asset('storage/' . $product->hover_image) }}" alt="Hover Image">
                                    @else
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                                        </svg>
                                        <p class="mb-0">{{ app()->getLocale() == 'ar' ? 'الصورة الثانية' : 'Second Image' }}</p>
                                        <small class="text-muted">{{ app()->getLocale() == 'ar' ? 'تظهر عند التمرير بالماوس' : 'Shows on mouse hover' }}</small>
                                    @endif
                                </div>
                                <input type="file" name="hover_image" id="hover_image" class="d-none" accept="image/*">
                            </div>
                            <small class="form-text text-muted">{{ app()->getLocale() == 'ar' ? 'اختياري - إذا لم تُضف، ستظهر الصورة الرئيسية' : 'Optional - Main image shows if not added' }}</small>
                        </div>

                        <hr class="my-4">

                        <!-- Additional Gallery Images -->
                        <div>
                            <label class="form-label">
                                {{ app()->getLocale() == 'ar' ? 'صور إضافية (معرض الصور)' : 'Additional Images (Gallery)' }}
                            </label>
                            <div class="gallery-upload-wrapper">
                                <button type="button" class="btn btn-outline-primary btn-sm w-100" id="add-gallery-images">
                                    <i class="fas fa-plus"></i>
                                    {{ app()->getLocale() == 'ar' ? 'إضافة صور للمعرض' : 'Add Gallery Images' }}
                                </button>
                                <input type="file" name="gallery_images[]" id="gallery_images" class="d-none" accept="image/*" multiple>
                                <input type="hidden" name="removed_images" id="removed_images" value="">
                            </div>
                            <div id="gallery-preview" class="gallery-preview-grid mt-3">
                                @if($product->gallery_images && count($product->gallery_images) > 0)
                                    @foreach($product->gallery_images as $index => $image)
                                        <div class="gallery-image-item" data-existing="{{ $image }}">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image {{ $index + 1 }}">
                                            <button type="button" class="remove-image" onclick="removeExistingGalleryImage('{{ $image }}', this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <small class="form-text text-muted">{{ app()->getLocale() == 'ar' ? 'هذه الصور تظهر في صفحة تفاصيل المنتج فقط' : 'These images appear in product details page only' }}</small>
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
                            <select name="brand_id" class="form-select">
                                <option value="">{{ app()->getLocale() == 'ar' ? 'اختر العلامة التجارية' : 'Select Brand' }}</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->getName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
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
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                {{ app()->getLocale() == 'ar' ? 'نشط' : 'Active' }}
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                {{ app()->getLocale() == 'ar' ? 'منتج مميز' : 'Featured Product' }}
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_new" id="is_new" {{ old('is_new') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_new">
                                {{ app()->getLocale() == 'ar' ? 'منتج جديد' : 'New Product' }}
                            </label>
                        </div>

                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="is_on_sale" id="is_on_sale" {{ old('is_on_sale') ? 'checked' : '' }}>
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
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                        <small class="form-text text-muted">{{ app()->getLocale() == 'ar' ? 'الأقل يظهر أولاً' : 'Lower numbers appear first' }}</small>
                    </div>
                </div>

                <!-- Clothing Sizes -->
                <div class="card mb-4">
                    <div class="card-header" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#clothingSizesCollapse" aria-expanded="false">
                        <h3 class="card-title">
                            <i class="fas fa-chevron-down me-2"></i>
                            {{ app()->getLocale() == 'ar' ? 'قياسات الملابس' : 'Clothing Sizes' }}
                        </h3>
                    </div>
                    <div class="collapse" id="clothingSizesCollapse">
                    <div class="card-body">
                        <div class="row">
                            @foreach($sizes as $size)
                            <div class="col-md-6 col-sm-6 col-12 mb-3">
                                <div class="variant-checkbox-group">
                                    <div class="form-check">
                                        <input class="form-check-input size-checkbox" type="checkbox" name="sizes[]" value="{{ $size->id }}" id="size_{{ $size->id }}" {{ in_array($size->id, old('sizes', $product->productSizes->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="size_{{ $size->id }}">
                                            {{ $size->name }} ({{ $size->translated_name }})
                                        </label>
                                    </div>
                                    <div class="stock-input-wrapper mt-2" id="size_stock_{{ $size->id }}" style="display: {{ in_array($size->id, old('sizes', $product->productSizes->pluck('id')->toArray())) ? 'block' : 'none' }};">
                                        <input type="number" name="size_stock[{{ $size->id }}]" class="form-control form-control-sm" placeholder="{{ app()->getLocale() == 'ar' ? 'الكمية' : 'Stock' }}" min="0" value="{{ old('size_stock.'.$size->id, $product->productSizes->where('id', $size->id)->first()->pivot->stock_quantity ?? 0) }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Shoe Sizes -->
                <div class="card mb-4">
                    <div class="card-header" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#shoeSizesCollapse" aria-expanded="true" aria-controls="shoeSizesCollapse">
                        <h3 class="card-title">
                            <i class="fas fa-chevron-down me-2"></i>
                            {{ app()->getLocale() == 'ar' ? 'قياسات الأحذية' : 'Shoe Sizes' }}
                        </h3>
                    </div>
                    <div class="collapse show" id="shoeSizesCollapse">
                    <div class="card-body">
                        <div class="row">
                            @foreach($shoeSizes as $shoeSize)
                            <div class="col-md-4 col-sm-4 col-6 mb-3">
                                <div class="variant-checkbox-group">
                                    <div class="form-check">
                                        <input class="form-check-input shoe-size-checkbox" type="checkbox" name="shoe_sizes[]" value="{{ $shoeSize->id }}" id="shoe_size_{{ $shoeSize->id }}" {{ in_array($shoeSize->id, old('shoe_sizes', $product->productShoeSizes->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="shoe_size_{{ $shoeSize->id }}">
                                            {{ $shoeSize->size }}
                                        </label>
                                    </div>
                                    <div class="stock-input-wrapper mt-2" id="shoe_size_stock_{{ $shoeSize->id }}" style="display: {{ in_array($shoeSize->id, old('shoe_sizes', $product->productShoeSizes->pluck('id')->toArray())) ? 'block' : 'none' }};">
                                        <input type="number" name="shoe_size_stock[{{ $shoeSize->id }}]" class="form-control form-control-sm" placeholder="{{ app()->getLocale() == 'ar' ? 'الكمية' : 'Stock' }}" min="0" value="{{ old('shoe_size_stock.'.$shoeSize->id, $product->productShoeSizes->where('id', $shoeSize->id)->first()->pivot->stock_quantity ?? 0) }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Colors -->
                <div class="card mb-4">
                    <div class="card-header" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#colorsCollapse" aria-expanded="true" aria-controls="colorsCollapse">
                        <h3 class="card-title">
                            <i class="fas fa-chevron-down me-2"></i>
                            {{ app()->getLocale() == 'ar' ? 'الألوان المتاحة' : 'Available Colors' }}
                        </h3>
                    </div>
                    <div class="collapse show" id="colorsCollapse">
                    <div class="card-body">
                        <div class="row">
                            @foreach($colors as $color)
                            <div class="col-md-6 col-sm-6 col-12 mb-3">
                                <div class="variant-checkbox-group">
                                    <div class="form-check">
                                        <input class="form-check-input color-checkbox" type="checkbox" name="colors[]" value="{{ $color->id }}" id="color_{{ $color->id }}" {{ in_array($color->id, old('colors', $product->productColors->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label d-flex align-items-center" for="color_{{ $color->id }}">
                                            <span class="color-circle-preview me-2" style="background-color: {{ $color->hex_code }}; @if($color->hex_code == '#FFFFFF') border: 1px solid #ddd; @endif"></span>
                                            {{ $color->translated_name }}
                                        </label>
                                    </div>
                                    <div class="stock-input-wrapper mt-2" id="color_stock_{{ $color->id }}" style="display: {{ in_array($color->id, old('colors', $product->productColors->pluck('id')->toArray())) ? 'block' : 'none' }};">
                                        <input type="number" name="color_stock[{{ $color->id }}]" class="form-control form-control-sm" placeholder="{{ app()->getLocale() == 'ar' ? 'الكمية' : 'Stock' }}" min="0" value="{{ old('color_stock.'.$color->id, $product->productColors->where('id', $color->id)->first()->pivot->stock_quantity ?? 0) }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Color Images Card -->
                <div class="card mb-4" id="colorImagesSection" style="display: none;">
                    <div class="card-header collapsible-header bg-light" data-bs-toggle="collapse" data-bs-target="#colorImagesCollapse" aria-expanded="true" style="cursor: pointer;">
                        <h3 class="card-title">
                            <i class="fas fa-chevron-down me-2"></i>
                            {{ app()->getLocale() == 'ar' ? 'صور الألوان' : 'Color Images' }}
                        </h3>
                    </div>
                    <div class="collapse show" id="colorImagesCollapse">
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                {{ app()->getLocale() == 'ar' ? 'قم بإضافة صورة خاصة لكل لون محدد' : 'Add a specific image for each selected color' }}
                            </p>
                            <div id="colorImagesContainer" class="color-images-grid">
                                <!-- Color image cards will be dynamically inserted here -->
                            </div>
                            <div id="noColorsSelectedMsg" class="text-muted text-center py-4" style="display: none;">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ app()->getLocale() == 'ar' ? 'يرجى اختيار ألوان أولاً من قسم الألوان المتاحة' : 'Please select colors first from the Available Colors section' }}
                            </div>
                        </div>
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
    max-width: 100%;
}

.product-form .row {
    margin-left: -12px;
    margin-right: -12px;
    display: flex !important;
    flex-wrap: wrap !important;
}

.product-form .row > [class*='col-'] {
    padding-left: 12px;
    padding-right: 12px;
}

/* Force Column Display - CRITICAL */
.product-form .col-lg-8 {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    width: 66.666667% !important;
    flex: 0 0 66.666667% !important;
    max-width: 66.666667% !important;
}

.product-form .col-lg-4 {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    width: 33.333333% !important;
    flex: 0 0 33.333333% !important;
    max-width: 33.333333% !important;
}

@media (max-width: 991.98px) {
    .product-form .col-lg-8,
    .product-form .col-lg-4 {
        width: 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
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

/* Product Image Preview - 3:4 Aspect Ratio (Like Product Cards) */
.product-image-preview {
    width: 100%;
    aspect-ratio: 3 / 4;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
    transition: all 0.3s;
    overflow: hidden;
    position: relative;
}

.product-image-preview:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.product-image-preview svg {
    width: 48px;
    height: 48px;
    color: #9ca3af;
    margin-bottom: 8px;
}

.product-image-preview p {
    color: #6b7280;
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}

.product-image-preview small {
    color: #9ca3af;
    font-size: 12px;
}

.product-image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
}

/* Badge Styling */
.badge.bg-primary {
    background-color: #3b82f6 !important;
}

.badge.bg-secondary {
    background-color: #6b7280 !important;
}

/* Old style for backward compatibility */
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

/* Gallery Preview Grid */
.gallery-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
    gap: 8px;
    margin-top: 12px;
}

.gallery-image-item {
    position: relative;
    width: 100%;
    aspect-ratio: 3 / 4;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #1f2937;
    background: #f3f4f6;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.gallery-image-item:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    transform: translateY(-2px);
}

.gallery-image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.gallery-image-item .remove-image {
    position: absolute;
    top: 4px;
    right: 4px;
    background: #ef4444;
    color: white;
    border: 1px solid white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 11px;
    transition: all 0.2s;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
    z-index: 10;
}

.gallery-image-item .remove-image:hover {
    background: #dc2626;
    transform: scale(1.1);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
}

.gallery-image-item .remove-image i {
    pointer-events: none;
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

/* Collapsible Card Headers */
.card-header[data-bs-toggle="collapse"] {
    user-select: none;
    transition: background-color 0.2s;
}

.card-header[data-bs-toggle="collapse"]:hover {
    background-color: #f9fafb;
}

.card-header[data-bs-toggle="collapse"] .fa-chevron-down {
    transition: transform 0.3s ease;
    display: inline-block;
}

.card-header[data-bs-toggle="collapse"].collapsed .fa-chevron-down {
    transform: rotate(0deg);
}

.card-header[data-bs-toggle="collapse"]:not(.collapsed) .fa-chevron-down {
    transform: rotate(180deg);
}

/* Collapse functionality */
.collapse {
    display: none;
    transition: all 0.3s ease;
}

.collapse.show {
    display: block;
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

/* Force LTR for English Quill Editors - CRITICAL FIX */
.quill-editor-ltr,
.quill-editor-ltr .ql-editor,
.quill-editor-ltr .ql-container,
.quill-editor-ltr .ql-editor.ql-blank::before {
    direction: ltr !important;
    text-align: left !important;
}

.quill-editor-ltr .ql-editor {
    unicode-bidi: embed !important;
}

.quill-editor-ltr .ql-editor p,
.quill-editor-ltr .ql-editor h1,
.quill-editor-ltr .ql-editor h2,
.quill-editor-ltr .ql-editor h3,
.quill-editor-ltr .ql-editor ul,
.quill-editor-ltr .ql-editor ol,
.quill-editor-ltr .ql-editor li,
.quill-editor-ltr .ql-editor div,
.quill-editor-ltr .ql-editor span,
.quill-editor-ltr .ql-editor strong,
.quill-editor-ltr .ql-editor em {
    direction: ltr !important;
    text-align: left !important;
}

/* Force RTL for Arabic Quill Editors */
.quill-editor-rtl,
.quill-editor-rtl .ql-editor,
.quill-editor-rtl .ql-container,
.quill-editor-rtl .ql-editor.ql-blank::before {
    direction: rtl !important;
    text-align: right !important;
}

.quill-editor-rtl .ql-editor {
    unicode-bidi: embed !important;
}

.quill-editor-rtl .ql-editor p,
.quill-editor-rtl .ql-editor h1,
.quill-editor-rtl .ql-editor h2,
.quill-editor-rtl .ql-editor h3,
.quill-editor-rtl .ql-editor ul,
.quill-editor-rtl .ql-editor ol,
.quill-editor-rtl .ql-editor li,
.quill-editor-rtl .ql-editor div,
.quill-editor-rtl .ql-editor span,
.quill-editor-rtl .ql-editor strong,
.quill-editor-rtl .ql-editor em {
    direction: rtl !important;
    text-align: right !important;
}

/* Fix Quill toolbar alignment */
.quill-editor-ltr .ql-toolbar {
    text-align: left !important;
}

.quill-editor-rtl .ql-toolbar {
    text-align: right !important;
}

/* Fix list items in RTL context - CRITICAL */
[dir="rtl"] .ql-editor li:not(.ql-direction-rtl)::before {
    margin-left: -0.5em !important;
    margin-right: 0.5em !important;
    text-align: right !important;
    display: inline-block !important;
    white-space: nowrap !important;
    width: 1.2em !important;
}

/* Adaptive Protection - Prevent Editor Destruction */
.quill-editor-ltr,
.quill-editor-rtl {
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
}

.quill-editor-ltr .ql-container,
.quill-editor-rtl .ql-container {
    max-width: 100% !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
}

.quill-editor-ltr .ql-editor,
.quill-editor-rtl .ql-editor {
    max-width: 100% !important;
    overflow-x: auto !important;
    overflow-wrap: break-word !important;
    word-wrap: break-word !important;
    word-break: break-word !important;
    box-sizing: border-box !important;
}

/* Remove external styles and prevent inheritance */
.quill-editor-ltr .ql-editor *,
.quill-editor-rtl .ql-editor * {
    max-width: 100% !important;
    box-sizing: border-box !important;
}

/* Force container boundaries */
.quill-editor-ltr .ql-editor p,
.quill-editor-ltr .ql-editor div,
.quill-editor-ltr .ql-editor span {
    max-width: 100% !important;
    overflow-wrap: break-word !important;
    word-wrap: break-word !important;
}

.quill-editor-rtl .ql-editor p,
.quill-editor-rtl .ql-editor div,
.quill-editor-rtl .ql-editor span {
    max-width: 100% !important;
    overflow-wrap: break-word !important;
    word-wrap: break-word !important;
}

/* Reset external margins and paddings */
.quill-editor-ltr .ql-editor *,
.quill-editor-rtl .ql-editor * {
    margin-left: 0 !important;
    margin-right: 0 !important;
}

.quill-editor-ltr .ql-editor ul,
.quill-editor-ltr .ql-editor ol {
    margin-left: 1.5em !important;
    margin-right: 0 !important;
}

.quill-editor-rtl .ql-editor ul,
.quill-editor-rtl .ql-editor ol {
    margin-right: 1.5em !important;
    margin-left: 0 !important;
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

/* Force Column Display */
.product-form .col-lg-6 {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

@media (min-width: 992px) {
    .product-form .col-lg-6 {
        flex: 0 0 auto;
        width: 50%;
    }
}

@media (max-width: 991px) {
    .product-form .col-lg-6 {
        width: 100%;
    }
}

/* Debug - make sure columns are visible */
.product-form .row > div {
    border: 1px solid transparent;
}
</style>

<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

@push('scripts')
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<!-- Pica.js for High Quality Image Processing -->
<script src="https://cdn.jsdelivr.net/npm/pica@9.0.1/dist/pica.min.js"></script>

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
    const quillOptionsRTL = {
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

    const quillOptionsLTR = {
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

    // Description Arabic (RTL)
    const descArEditor = new Quill('#description_ar_editor', quillOptionsRTL);
    descArEditor.root.setAttribute('dir', 'rtl');

    // Adaptive paste handler - Clean external styles
    descArEditor.clipboard.addMatcher(Node.ELEMENT_NODE, function(node, delta) {
        const ops = [];
        delta.ops.forEach(op => {
            if (op.insert && typeof op.insert === 'string') {
                ops.push({
                    insert: op.insert,
                    attributes: op.attributes || {}
                });
            }
        });
        return new Quill.imports.delta(ops);
    });

    descArEditor.on('text-change', function() {
        $('#description_ar').val(descArEditor.root.innerHTML);
    });
    if ($('#description_ar').val()) {
        descArEditor.root.innerHTML = $('#description_ar').val();
    }

    // Description English (LTR)
    const descEnEditor = new Quill('#description_en_editor', quillOptionsLTR);
    descEnEditor.root.setAttribute('dir', 'ltr');
    descEnEditor.root.style.textAlign = 'left';

    // Adaptive paste handler - Clean external styles
    descEnEditor.clipboard.addMatcher(Node.ELEMENT_NODE, function(node, delta) {
        const ops = [];
        delta.ops.forEach(op => {
            if (op.insert && typeof op.insert === 'string') {
                ops.push({
                    insert: op.insert,
                    attributes: op.attributes || {}
                });
            }
        });
        return new Quill.imports.delta(ops);
    });

    descEnEditor.on('text-change', function() {
        $('#description_en').val(descEnEditor.root.innerHTML);
    });
    if ($('#description_en').val()) {
        descEnEditor.root.innerHTML = $('#description_en').val();
    }

    // Sizing Info Arabic (RTL)
    const sizingArEditor = new Quill('#sizing_info_ar_editor', quillOptionsRTL);
    sizingArEditor.root.setAttribute('dir', 'rtl');

    sizingArEditor.clipboard.addMatcher(Node.ELEMENT_NODE, function(node, delta) {
        const ops = [];
        delta.ops.forEach(op => {
            if (op.insert && typeof op.insert === 'string') {
                ops.push({
                    insert: op.insert,
                    attributes: op.attributes || {}
                });
            }
        });
        return new Quill.imports.delta(ops);
    });

    sizingArEditor.on('text-change', function() {
        $('#sizing_info_ar').val(sizingArEditor.root.innerHTML);
    });
    if ($('#sizing_info_ar').val()) {
        sizingArEditor.root.innerHTML = $('#sizing_info_ar').val();
    }

    // Sizing Info English (LTR)
    const sizingEnEditor = new Quill('#sizing_info_en_editor', quillOptionsLTR);
    sizingEnEditor.root.setAttribute('dir', 'ltr');
    sizingEnEditor.root.style.textAlign = 'left';

    sizingEnEditor.clipboard.addMatcher(Node.ELEMENT_NODE, function(node, delta) {
        const ops = [];
        delta.ops.forEach(op => {
            if (op.insert && typeof op.insert === 'string') {
                ops.push({
                    insert: op.insert,
                    attributes: op.attributes || {}
                });
            }
        });
        return new Quill.imports.delta(ops);
    });

    sizingEnEditor.on('text-change', function() {
        $('#sizing_info_en').val(sizingEnEditor.root.innerHTML);
    });
    if ($('#sizing_info_en').val()) {
        sizingEnEditor.root.innerHTML = $('#sizing_info_en').val();
    }

    // Design Details Arabic (RTL)
    const designArEditor = new Quill('#design_details_ar_editor', quillOptionsRTL);
    designArEditor.root.setAttribute('dir', 'rtl');

    designArEditor.clipboard.addMatcher(Node.ELEMENT_NODE, function(node, delta) {
        const ops = [];
        delta.ops.forEach(op => {
            if (op.insert && typeof op.insert === 'string') {
                ops.push({
                    insert: op.insert,
                    attributes: op.attributes || {}
                });
            }
        });
        return new Quill.imports.delta(ops);
    });

    designArEditor.on('text-change', function() {
        $('#design_details_ar').val(designArEditor.root.innerHTML);
    });
    if ($('#design_details_ar').val()) {
        designArEditor.root.innerHTML = $('#design_details_ar').val();
    }

    // Design Details English (LTR)
    const designEnEditor = new Quill('#design_details_en_editor', quillOptionsLTR);
    designEnEditor.root.setAttribute('dir', 'ltr');
    designEnEditor.root.style.textAlign = 'left';

    designEnEditor.clipboard.addMatcher(Node.ELEMENT_NODE, function(node, delta) {
        const ops = [];
        delta.ops.forEach(op => {
            if (op.insert && typeof op.insert === 'string') {
                ops.push({
                    insert: op.insert,
                    attributes: op.attributes || {}
                });
            }
        });
        return new Quill.imports.delta(ops);
    });

    designEnEditor.on('text-change', function() {
        $('#design_details_en').val(designEnEditor.root.innerHTML);
    });
    if ($('#design_details_en').val()) {
        designEnEditor.root.innerHTML = $('#design_details_en').val();
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
    // Pica instance for high quality image processing
    const picaInstance = typeof pica !== 'undefined' ? pica() : null;

    // Function to enhance image quality with Pica
    function enhanceImageWithPica(imgElement, maxWidth = 800) {
        if (!picaInstance || !imgElement.complete || imgElement.naturalWidth === 0) {
            return Promise.resolve();
        }

        return new Promise((resolve, reject) => {
            try {
                const img = imgElement;
                const aspectRatio = img.naturalWidth / img.naturalHeight;

                // Calculate dimensions maintaining aspect ratio
                let targetWidth = Math.min(img.naturalWidth, maxWidth);
                let targetHeight = Math.round(targetWidth / aspectRatio);

                // Don't upscale
                if (targetWidth >= img.naturalWidth) {
                    resolve();
                    return;
                }

                const canvas = document.createElement('canvas');
                canvas.width = targetWidth;
                canvas.height = targetHeight;

                picaInstance.resize(img, canvas, {
                    quality: 3,
                    alpha: true,
                    unsharpAmount: 160,
                    unsharpRadius: 0.6,
                    unsharpThreshold: 1
                }).then(result => {
                    return picaInstance.toBlob(result, 'image/jpeg', 0.92);
                }).then(blob => {
                    const url = URL.createObjectURL(blob);
                    img.src = url;
                    img.dataset.picaProcessed = 'true';
                    resolve();
                }).catch(err => {
                    console.warn('Pica processing failed:', err);
                    resolve();
                });
            } catch (err) {
                console.warn('Pica enhancement error:', err);
                resolve();
            }
        });
    }

    // Enhance existing main image on page load
    const existingMainImg = $('#main-image-preview img')[0];
    if (existingMainImg && existingMainImg.complete && existingMainImg.naturalWidth > 0) {
        enhanceImageWithPica(existingMainImg, 600);
    } else if (existingMainImg) {
        existingMainImg.onload = function() {
            enhanceImageWithPica(this, 600);
        };
    }

    // Main Image Preview
    $('#main-image-preview').on('click', function() {
        $('#main_image').click();
    });

    $('#main_image').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgHtml = '<img src="' + e.target.result + '" alt="Main Image">';
                $('#main-image-preview').html(imgHtml);

                // Enhance with Pica after image loads
                const img = $('#main-image-preview img')[0];
                if (img) {
                    img.onload = function() {
                        enhanceImageWithPica(this, 600);
                    };
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Enhance existing hover image on page load
    const existingHoverImg = $('#hover-image-preview img')[0];
    if (existingHoverImg && existingHoverImg.complete && existingHoverImg.naturalWidth > 0) {
        enhanceImageWithPica(existingHoverImg, 600);
    } else if (existingHoverImg) {
        existingHoverImg.onload = function() {
            enhanceImageWithPica(this, 600);
        };
    }

    // Hover Image Preview
    $('#hover-image-preview').on('click', function() {
        $('#hover_image').click();
    });

    $('#hover_image').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgHtml = '<img src="' + e.target.result + '" alt="Hover Image">';
                $('#hover-image-preview').html(imgHtml);

                // Enhance with Pica after image loads
                const img = $('#hover-image-preview img')[0];
                if (img) {
                    img.onload = function() {
                        enhanceImageWithPica(this, 600);
                    };
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Enhance existing gallery images on page load
    $('#gallery-preview .gallery-image-item img').each(function() {
        const img = this;
        if (img.complete && img.naturalWidth > 0) {
            enhanceImageWithPica(img, 400);
        } else {
            img.onload = function() {
                enhanceImageWithPica(this, 400);
            };
        }
    });

    // Gallery images
    let galleryFiles = [];

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

                    // Enhance gallery image with Pica
                    const galleryImg = $(`#gallery-preview .gallery-image-item[data-id="${imageId}"] img`)[0];
                    if (galleryImg) {
                        galleryImg.onload = function() {
                            enhanceImageWithPica(this, 400);
                        };
                    }

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

    // Variant checkboxes handlers
    $('.size-checkbox').on('change', function() {
        const sizeId = $(this).val();
        const stockWrapper = $('#size_stock_' + sizeId);
        if ($(this).is(':checked')) {
            stockWrapper.show();
        } else {
            stockWrapper.hide();
            stockWrapper.find('input').val(0);
        }
    });

    $('.shoe-size-checkbox').on('change', function() {
        const shoeSizeId = $(this).val();
        const stockWrapper = $('#shoe_size_stock_' + shoeSizeId);
        if ($(this).is(':checked')) {
            stockWrapper.show();
        } else {
            stockWrapper.hide();
            stockWrapper.find('input').val(0);
        }
    });

    $('.color-checkbox').on('change', function() {
        const colorId = $(this).val();
        const stockWrapper = $('#color_stock_' + colorId);
        if ($(this).is(':checked')) {
            stockWrapper.show();
        } else {
            stockWrapper.hide();
            stockWrapper.find('input').val(0);
        }

        // Update color images section
        updateColorImagesSection();
    });

    // Initialize color images on page load
    updateColorImagesSection();
});

// Color images data
var colorImagesData = {};

// Existing color images from database
var existingColorImages = {
    @foreach($product->colorImages as $colorImage)
    {{ $colorImage->color_id }}: {
        id: {{ $colorImage->id }},
        image: "{{ $colorImage->image_url }}",
        is_primary: {{ $colorImage->is_primary ? 'true' : 'false' }}
    },
    @endforeach
};

// Colors data from PHP
var colorsData = {
    @foreach($colors as $color)
    {{ $color->id }}: {
        id: {{ $color->id }},
        name: "{{ $color->translated_name }}",
        hex: "{{ $color->hex_code }}"
    },
    @endforeach
};

// Update color images section based on selected colors
function updateColorImagesSection() {
    var selectedColors = [];
    $('.color-checkbox:checked').each(function() {
        selectedColors.push(parseInt($(this).val()));
    });

    var container = $('#colorImagesContainer');
    var section = $('#colorImagesSection');
    var noColorsMsg = $('#noColorsSelectedMsg');

    if (selectedColors.length === 0) {
        section.hide();
        container.empty();
        return;
    }

    section.show();
    noColorsMsg.hide();

    // Remove cards for unselected colors
    container.find('.color-image-card').each(function() {
        var cardColorId = parseInt($(this).data('color-id'));
        if (!selectedColors.includes(cardColorId)) {
            $(this).slideUp(200, function() {
                $(this).remove();
            });
            delete colorImagesData[cardColorId];
        }
    });

    // Add cards for newly selected colors
    selectedColors.forEach(function(colorId) {
        if (container.find('[data-color-id="' + colorId + '"]').length === 0) {
            var color = colorsData[colorId];
            if (color) {
                var existingImage = existingColorImages[colorId] || null;
                var cardHtml = createColorImageCard(color, existingImage);
                container.append(cardHtml);
            }
        }
    });
}

// Create color image card HTML
function createColorImageCard(color, existingImage) {
    var arText = '{{ app()->getLocale() }}' === 'ar';
    var uploadText = arText ? 'اختر صورة' : 'Choose Image';
    var changeText = arText ? 'تغيير الصورة' : 'Change Image';
    var removeText = arText ? 'حذف' : 'Remove';
    var primaryText = arText ? 'صورة رئيسية لهذا اللون' : 'Primary image for this color';
    var currentImageText = arText ? 'الصورة الحالية' : 'Current Image';

    var hasExisting = existingImage && existingImage.image;
    var cardClass = hasExisting ? 'has-image' : '';

    var existingImageHtml = '';
    if (hasExisting) {
        existingImageHtml = `
            <div class="existing-color-image">
                <img src="${existingImage.image}" alt="${color.name}">
                <span class="existing-color-image-info">${currentImageText}</span>
            </div>
        `;
    }

    var previewContent = hasExisting
        ? `<img src="${existingImage.image}" alt="${color.name}">`
        : `<i class="fas fa-image placeholder-icon"></i>`;

    var isPrimaryChecked = hasExisting && existingImage.is_primary ? 'checked' : '';

    return `
        <div class="color-image-card ${cardClass}" data-color-id="${color.id}">
            <div class="color-image-header">
                <div class="color-image-color-preview" style="background-color: ${color.hex};"></div>
                <span class="color-image-name">${color.name}</span>
            </div>
            <div class="color-image-upload-area">
                <div class="color-image-preview-container" id="color_image_preview_${color.id}">
                    ${previewContent}
                </div>
                <div class="color-image-upload-controls">
                    <div class="color-image-input-wrapper">
                        <label class="color-image-upload-btn">
                            <i class="fas fa-upload"></i>
                            ${hasExisting ? changeText : uploadText}
                            <input type="file" name="color_images[${color.id}]" accept="image/*" onchange="handleColorImageSelect(this, ${color.id})">
                        </label>
                    </div>
                    <button type="button" class="color-image-remove-btn" onclick="removeColorImage(${color.id})">
                        <i class="fas fa-trash-alt"></i> ${removeText}
                    </button>
                    <div class="color-image-filename" id="color_image_filename_${color.id}"></div>
                    <label class="color-image-primary-label">
                        <input type="checkbox" name="color_image_primary[${color.id}]" value="1" ${isPrimaryChecked}>
                        ${primaryText}
                    </label>
                </div>
            </div>
        </div>
    `;
}

// Handle color image selection
function handleColorImageSelect(input, colorId) {
    var file = input.files[0];
    if (!file) return;

    var card = $('[data-color-id="' + colorId + '"]');
    var previewContainer = $('#color_image_preview_' + colorId);
    var filenameDisplay = $('#color_image_filename_' + colorId);

    // Validate file type
    if (!file.type.startsWith('image/')) {
        alert('{{ app()->getLocale() == "ar" ? "يرجى اختيار ملف صورة" : "Please select an image file" }}');
        input.value = '';
        return;
    }

    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('{{ app()->getLocale() == "ar" ? "حجم الملف كبير جداً (الحد الأقصى 5 ميجابايت)" : "File size too large (max 5MB)" }}');
        input.value = '';
        return;
    }

    // Store file reference
    colorImagesData[colorId] = file;

    // Show preview
    var reader = new FileReader();
    reader.onload = function(e) {
        previewContainer.html('<img src="' + e.target.result + '" alt="Preview">');
        card.addClass('has-image');
        filenameDisplay.text(file.name);
    };
    reader.readAsDataURL(file);
}

// Remove color image
function removeColorImage(colorId) {
    var card = $('[data-color-id="' + colorId + '"]');
    var previewContainer = $('#color_image_preview_' + colorId);
    var filenameDisplay = $('#color_image_filename_' + colorId);
    var input = card.find('input[type="file"]');

    // Clear file input
    input.val('');

    // Remove from data
    delete colorImagesData[colorId];

    // Mark for deletion if has existing image
    if (existingColorImages[colorId]) {
        // Add hidden input to mark for deletion
        if (!$('#remove_color_image_' + colorId).length) {
            card.append('<input type="hidden" id="remove_color_image_' + colorId + '" name="remove_color_images[]" value="' + colorId + '">');
        }
        delete existingColorImages[colorId];
    }

    // Reset preview
    previewContainer.html('<i class="fas fa-image placeholder-icon"></i>');
    card.removeClass('has-image');
    filenameDisplay.text('');
}

// Remove gallery image function for new images
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

// Remove existing gallery image function
function removeExistingGalleryImage(imagePath, button) {
    // Add to removed images list
    const removedInput = document.getElementById('removed_images');
    const currentRemoved = removedInput.value ? removedInput.value.split(',') : [];
    currentRemoved.push(imagePath);
    removedInput.value = currentRemoved.join(',');

    // Remove the image element
    $(button).closest('.gallery-image-item').remove();
}
</script>

<style>
/* Variant Styles */
.variant-checkbox-group {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px;
    background: #f9fafb;
    transition: all 0.3s;
}

.variant-checkbox-group:hover {
    background: #fff;
    border-color: #3b82f6;
}

.variant-checkbox-group .form-check-label {
    font-weight: 500;
    color: #1f2937;
    cursor: pointer;
}

.variant-checkbox-group .form-check {
    align-items: flex-start;
}

.variant-checkbox-group .form-check-input {
    flex: 0 0 auto;
    margin-top: 0.2rem;
}

.variant-checkbox-group .form-check-label {
    flex: 1;
    min-width: 0;
    white-space: normal !important;
    word-break: break-word;
}

.color-circle-preview {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 1px solid #ddd;
}

.stock-input-wrapper {
    margin-top: 8px;
}

.stock-input-wrapper input {
    width: 100%;
    font-size: 13px;
}

/* Color Images Styles */
.color-images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.color-image-card {
    border: 2px dashed #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    background: #f9fafb;
    transition: all 0.3s ease;
}

.color-image-card:hover {
    border-color: #3b82f6;
    background: #fff;
}

.color-image-card.has-image {
    border-style: solid;
    border-color: #10b981;
    background: #f0fdf4;
}

.color-image-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e7eb;
}

.color-image-color-preview {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.color-image-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 14px;
}

.color-image-upload-area {
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.color-image-preview-container {
    width: 100px;
    height: 100px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    flex-shrink: 0;
}

.color-image-preview-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.color-image-preview-container .placeholder-icon {
    font-size: 32px;
    color: #d1d5db;
}

.color-image-upload-controls {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.color-image-upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: #3b82f6;
    color: white;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
}

.color-image-upload-btn:hover {
    background: #2563eb;
}

.color-image-upload-btn input[type="file"] {
    display: none;
}

.color-image-remove-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #fee2e2;
    color: #dc2626;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.color-image-remove-btn:hover {
    background: #fecaca;
}

.color-image-filename {
    font-size: 11px;
    color: #6b7280;
    word-break: break-all;
}

.color-image-primary-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #4b5563;
    cursor: pointer;
}

.color-image-primary-label input {
    margin: 0;
}

.existing-color-image {
    margin-bottom: 8px;
    padding: 8px;
    background: #e0f2fe;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.existing-color-image img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.existing-color-image-info {
    flex: 1;
    font-size: 12px;
    color: #0369a1;
}
</style>
@endpush
@endsection
