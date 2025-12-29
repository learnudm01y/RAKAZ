@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة المنتجات' : 'Products Management')

@section('page-title')
    <span class="ar-text">إدارة المنتجات</span>
    <span class="en-text">Products Management</span>
@endsection

@section('content')
<div class="products-management">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 28px; height: 28px; display: inline-block; vertical-align: middle; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 10px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                {{ app()->getLocale() == 'ar' ? 'إدارة المنتجات' : 'Products Management' }}
            </h1>
            <p class="page-subtitle">{{ app()->getLocale() == 'ar' ? 'إضافة، تعديل وإدارة المنتجات' : 'Add, edit and manage products' }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ app()->getLocale() == 'ar' ? 'إضافة منتج جديد' : 'Add New Product' }}
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="products-search-form">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">{{ app()->getLocale() == 'ar' ? 'البحث' : 'Search' }}</label>
                        <div class="input-with-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="input-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="search" class="form-control" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث باسم المنتج، SKU، أو العلامة التجارية...' : 'Search by name, SKU, or brand...' }}" value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">{{ app()->getLocale() == 'ar' ? 'التصنيف' : 'Category' }}</label>
                        <select name="category_id" class="form-control">
                            <option value="">{{ app()->getLocale() == 'ar' ? 'كل التصنيفات' : 'All Categories' }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->getName(app()->getLocale()) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">{{ app()->getLocale() == 'ar' ? 'العلامة التجارية' : 'Brand' }}</label>
                        <select name="brand_id" class="form-control">
                            <option value="">{{ app()->getLocale() == 'ar' ? 'كل العلامات التجارية' : 'All Brands' }}</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->getName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</label>
                        <select name="status" class="form-control">
                            <option value="">{{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'نشط' : 'Active' }}</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'غير نشط' : 'Inactive' }}</option>
                            <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'مميز' : 'Featured' }}</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'مخزون منخفض' : 'Low Stock' }}</option>
                        </select>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label">{{ app()->getLocale() == 'ar' ? 'عدد' : 'Per Page' }}</label>
                        <select name="per_page" class="form-control">
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-1">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            {{ app()->getLocale() == 'ar' ? 'بحث' : 'Search' }}
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($products->isEmpty())
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3>{{ app()->getLocale() == 'ar' ? 'لا توجد منتجات' : 'No Products Found' }}</h3>
                    <p>{{ app()->getLocale() == 'ar' ? 'ابدأ بإضافة منتج جديد لمتجرك' : 'Start by adding a new product to your store' }}</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-3">
                        {{ app()->getLocale() == 'ar' ? 'إضافة منتج جديد' : 'Add New Product' }}
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table products-table">
                        <thead>
                            <tr>
                                <th width="60">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ app()->getLocale() == 'ar' ? 'الصورة' : 'Image' }}
                                </th>
                                <th>
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    {{ app()->getLocale() == 'ar' ? 'المنتج' : 'Product' }}
                                </th>
                                <th width="100">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    SKU
                                </th>
                                <th width="120">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ app()->getLocale() == 'ar' ? 'التصنيف' : 'Category' }}
                                </th>
                                <th width="150">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                    {{ app()->getLocale() == 'ar' ? 'المتغيرات' : 'Variants' }}
                                </th>
                                <th width="100">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ app()->getLocale() == 'ar' ? 'السعر' : 'Price' }}
                                </th>
                                <th width="80">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    {{ app()->getLocale() == 'ar' ? 'المخزون' : 'Stock' }}
                                </th>
                                <th width="100">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}
                                </th>
                                <th width="150">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                    {{ app()->getLocale() == 'ar' ? 'الإجراءات' : 'Actions' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="product-image">
                                            @if($product->main_image)
                                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName(app()->getLocale()) }}">
                                            @else
                                                <div class="no-image">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-info">
                                            <div class="product-name">{{ $product->getName(app()->getLocale()) }}</div>
                                            @if(app()->getLocale() == 'ar' && $product->getName('en'))
                                                <div class="product-name-en">{{ $product->getName('en') }}</div>
                                            @elseif(app()->getLocale() == 'en' && $product->getName('ar'))
                                                <div class="product-name-en">{{ $product->getName('ar') }}</div>
                                            @endif
                                            @if($product->brand)
                                                <div class="product-brand">{{ $product->brand->getName() }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="sku-badge">{{ $product->sku }}</span>
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <span class="category-badge">{{ $product->category->getName(app()->getLocale()) }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="variants-info">
                                            @if($product->productSizes->count() > 0)
                                                <div class="variant-group mb-1">
                                                    <small class="text-muted">{{ app()->getLocale() == 'ar' ? 'قياسات:' : 'Sizes:' }}</small>
                                                    <div class="variant-badges">
                                                        @foreach($product->productSizes->take(3) as $size)
                                                            <span class="badge badge-secondary badge-sm">{{ $size->name }}</span>
                                                        @endforeach
                                                        @if($product->productSizes->count() > 3)
                                                            <span class="badge badge-light badge-sm">+{{ $product->productSizes->count() - 3 }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            @if($product->productShoeSizes->count() > 0)
                                                <div class="variant-group mb-1">
                                                    <small class="text-muted">{{ app()->getLocale() == 'ar' ? 'أحذية:' : 'Shoes:' }}</small>
                                                    <div class="variant-badges">
                                                        @foreach($product->productShoeSizes->take(3) as $shoeSize)
                                                            <span class="badge badge-secondary badge-sm">{{ $shoeSize->size }}</span>
                                                        @endforeach
                                                        @if($product->productShoeSizes->count() > 3)
                                                            <span class="badge badge-light badge-sm">+{{ $product->productShoeSizes->count() - 3 }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            @if($product->productColors->count() > 0)
                                                <div class="variant-group">
                                                    <small class="text-muted">{{ app()->getLocale() == 'ar' ? 'ألوان:' : 'Colors:' }}</small>
                                                    <div class="variant-badges">
                                                        @foreach($product->productColors->take(3) as $color)
                                                            <span class="color-dot" style="background-color: {{ $color->hex_code }}; @if($color->hex_code == '#FFFFFF') border: 1px solid #ddd; @endif" title="{{ $color->translated_name }}"></span>
                                                        @endforeach
                                                        @if($product->productColors->count() > 3)
                                                            <span class="badge badge-light badge-sm">+{{ $product->productColors->count() - 3 }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            @if($product->productSizes->count() == 0 && $product->productShoeSizes->count() == 0 && $product->productColors->count() == 0)
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-price">
                                            @if($product->hasDiscount())
                                                <div class="sale-price">{{ number_format($product->sale_price, 2) }} {{ app()->getLocale() == 'ar' ? 'ر.س' : 'SAR' }}</div>
                                                <div class="original-price">{{ number_format($product->price, 2) }}</div>
                                            @else
                                                <div class="regular-price">{{ number_format($product->price, 2) }} {{ app()->getLocale() == 'ar' ? 'ر.س' : 'SAR' }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->manage_stock)
                                            <div class="stock-info {{ $product->isLowStock() ? 'low-stock' : '' }}">
                                                <span class="stock-qty">{{ $product->stock_quantity }}</span>
                                                @if($product->isLowStock())
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px; color: #f59e0b;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge badge-info">{{ app()->getLocale() == 'ar' ? 'غير محدود' : 'Unlimited' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="status-badges">
                                            @if($product->is_active)
                                                <span class="badge badge-success">{{ app()->getLocale() == 'ar' ? 'نشط' : 'Active' }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ app()->getLocale() == 'ar' ? 'غير نشط' : 'Inactive' }}</span>
                                            @endif

                                            @if($product->is_featured)
                                                <span class="badge badge-warning">{{ app()->getLocale() == 'ar' ? 'مميز' : 'Featured' }}</span>
                                            @endif

                                            @if($product->is_new)
                                                <span class="badge badge-info">{{ app()->getLocale() == 'ar' ? 'جديد' : 'New' }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="{{ app()->getLocale() == 'ar' ? 'تعديل' : 'Edit' }}">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>

                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-btn" title="{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            {{ app()->getLocale() == 'ar' ? 'عرض' : 'Showing' }}
                            <strong>{{ $products->firstItem() }}</strong>
                            {{ app()->getLocale() == 'ar' ? 'إلى' : 'to' }}
                            <strong>{{ $products->lastItem() }}</strong>
                            {{ app()->getLocale() == 'ar' ? 'من' : 'of' }}
                            <strong>{{ $products->total() }}</strong>
                            {{ app()->getLocale() == 'ar' ? 'منتج' : 'products' }}
                        </div>
                        <div class="pagination-links">
                            {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Products Management Styles */
.products-management {
    padding: 20px;
}

/* Filters: keep all controls the same height */
.products-management .products-search-form {
    --products-filter-control-height: 36px;
}

.products-management .products-search-form .form-control,
.products-management .products-search-form .custom-select-trigger,
.products-management .products-search-form button.btn,
.products-management .products-search-form a.btn {
    height: var(--products-filter-control-height);
    min-height: var(--products-filter-control-height);
}

.products-management .products-search-form .input-with-icon input.form-control {
    padding-top: 6px;
    padding-bottom: 6px;
}

.products-management .products-search-form .custom-select-trigger {
    line-height: 1.2;
}

/* Filters: allow dropdown to render outside the card */
.products-management .card.mb-4,
.products-management .card.mb-4 .card-body {
    overflow: visible !important;
}

/* Filters: smaller custom-select fields */
.products-management .products-search-form .custom-select-wrapper {
    min-width: 0 !important;
}

.products-management .products-search-form .custom-select-trigger {
    min-height: var(--products-filter-control-height);
    min-width: 0 !important;
    padding: 4px 10px;
    font-size: 13px;
    border-width: 1px;
}

.products-management .products-search-form .custom-select-trigger .arrow {
    border-left-width: 5px;
    border-right-width: 5px;
    border-top-width: 5px;
    margin-left: 8px;
}

.products-management .products-search-form .custom-option {
    padding: 8px 10px;
    font-size: 13px;
    min-height: 34px;
}

/* Ensure dropdown stacks above the card/table */
.products-management .products-search-form .custom-select-trigger.active {
    position: relative;
    z-index: 2001;
}

.products-management .products-search-form .custom-select-options.active {
    z-index: 2000;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e5e7eb;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 8px 0;
}

.page-subtitle {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

.products-search-form .input-with-icon {
    position: relative;
}

.products-search-form .input-icon {
    position: absolute;
    {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    color: #9ca3af;
    pointer-events: none;
}

.products-search-form .input-with-icon input {
    padding-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 40px;
}

/* Products Table */
.products-table {
    margin: 0;
    border-collapse: collapse;
}

.products-table thead {
    position: relative;
    border: none;
    outline: none;
}

.products-table thead::before,
.products-table thead::after,
.products-table thead th::before,
.products-table thead th::after {
    display: none !important;
    content: none !important;
}

.products-table thead th {
    background: #1a1a1a;
    background-image: none !important;
    color: white;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 16px 20px;
    border-bottom: 3px solid #000;
    border-top: none;
    border-left: none;
    border-right: none;
    white-space: nowrap;
    outline: none !important;
    box-shadow: none !important;
    position: relative;
    z-index: 1;
}

.products-table thead th svg {
    opacity: 0.9;
    display: inline-block;
    vertical-align: middle;
    margin-inline-end: 6px;
}

.products-table tbody td {
    padding: 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f3f4f6;
}

.products-table tbody tr:hover {
    background: #fafbfc;
}

/* Product Image */
.product-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    color: #d1d5db;
}

.no-image svg {
    width: 24px;
    height: 24px;
}

/* Product Info */
.product-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.product-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 14px;
}

.product-name-en {
    color: #6b7280;
    font-size: 13px;
}

.product-brand {
    color: #9ca3af;
    font-size: 12px;
}

/* SKU Badge */
.sku-badge {
    display: inline-block;
    padding: 4px 10px;
    background: #f3f4f6;
    color: #6b7280;
    font-size: 12px;
    font-family: 'Courier New', monospace;
    font-weight: 600;
    border-radius: 4px;
}

/* Category Badge */
.category-badge {
    display: inline-block;
    padding: 4px 10px;
    background: #dbeafe;
    color: #1e40af;
    font-size: 12px;
    font-weight: 500;
    border-radius: 4px;
}

/* Product Price */
.product-price {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.sale-price {
    color: #dc2626;
    font-weight: 700;
    font-size: 14px;
}

.original-price {
    color: #9ca3af;
    font-size: 12px;
    text-decoration: line-through;
}

.regular-price {
    color: #1f2937;
    font-weight: 600;
    font-size: 14px;
}

/* Stock Info */
.stock-info {
    display: flex;
    align-items: center;
    gap: 6px;
}

.stock-qty {
    font-weight: 600;
    color: #059669;
}

.stock-info.low-stock .stock-qty {
    color: #f59e0b;
}

/* Status Badges */
.status-badges {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 6px;
}

.action-buttons .btn {
    padding: 6px 10px;
}

.action-buttons svg {
    width: 16px;
    height: 16px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state svg {
    width: 80px;
    height: 80px;
    color: #d1d5db;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 20px;
    color: #374151;
    margin-bottom: 8px;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 20px;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: white;
    border-top: 2px solid #f0f0f0;
    margin-top: 0;
}

.pagination-info {
    color: #6b7280;
    font-size: 14px;
    font-weight: 500;
}

/* Force hide English text in Arabic mode */
html[lang="ar"] .en-text,
html[data-locale="ar"] .en-text,
[dir="rtl"] .en-text {
    display: none !important;
}

/* Force hide Arabic text in English mode */
html[lang="en"] .ar-text,
html[data-locale="en"] .ar-text,
[dir="ltr"] .ar-text {
    display: none !important;
}

.pagination-links {
    display: flex;
    align-items: center;
}

/* Hide default Bootstrap pagination text */
.pagination-links nav > div:first-child {
    display: none !important;
}

.pagination-links nav {
    display: block !important;
}

/* Hide the English stats in Bootstrap pagination */
.pagination-links .d-none.flex-sm-fill div:first-child,
.pagination-links .small.text-muted,
.pagination-links p.small {
    display: none !important;
}

/* Ensure pagination nav uses flex for proper alignment */
.pagination-links nav.d-flex {
    display: flex !important;
    justify-content: flex-end !important;
}

.pagination-links nav.d-flex > div:last-child {
    display: flex !important;
}

.pagination {
    display: flex;
    gap: 6px;
    margin: 0;
    list-style: none;
    padding: 0;
}

[dir="rtl"] .pagination {
    flex-direction: row-reverse;
}

.pagination .page-item {
    margin: 0;
}

.pagination .page-link {
    padding: 8px 14px;
    border: 2px solid #e5e7eb;
    background: white;
    color: #374151;
    border-radius: 6px;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
}

.pagination .page-link:hover {
    background: #1a1a1a;
    color: white;
    border-color: #1a1a1a;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.pagination .page-item.active .page-link {
    background: #1a1a1a;
    color: white;
    border-color: #1a1a1a;
    font-weight: 600;
}

.pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f9fafb;
    color: #9ca3af;
}

.pagination .page-item.disabled .page-link:hover {
    background: #f9fafb;
    color: #9ca3af;
    border-color: #e5e7eb;
    transform: none;
    box-shadow: none;
}

/* Variants Styles */
.variants-info {
    font-size: 12px;
}

.variant-group {
    margin-bottom: 4px;
}

.variant-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-top: 2px;
}

.badge-sm {
    font-size: 10px;
    padding: 2px 6px;
}

.color-dot {
    display: inline-block;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 1px solid #e5e7eb;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 16px;
    }

    .page-actions {
        width: 100%;
    }

    .page-actions .btn {
        width: 100%;
    }
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    const isArabic = '{{ app()->getLocale() }}' === 'ar';

    // Delete confirmation
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('.delete-form');

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: isArabic ? 'هل أنت متأكد؟' : 'Are you sure?',
                text: isArabic ? 'لن تتمكن من التراجع عن هذا الإجراء!' : 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: isArabic ? 'نعم، احذف!' : 'Yes, delete it!',
                cancelButtonText: isArabic ? 'إلغاء' : 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            if (confirm(isArabic ? 'هل أنت متأكد من حذف هذا المنتج؟' : 'Are you sure you want to delete this product?')) {
                form.submit();
            }
        }
    });
});
</script>
@endpush
@endsection
