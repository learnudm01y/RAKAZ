@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة المنتجات' : 'Products Management')

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
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ app()->getLocale() == 'ar' ? 'البحث' : 'Search' }}</label>
                        <div class="input-with-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="input-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="search" class="form-control" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث باسم المنتج، SKU، أو العلامة التجارية...' : 'Search by name, SKU, or brand...' }}" value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
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

                    <div class="col-md-2 mb-3">
                        <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</label>
                        <select name="status" class="form-control">
                            <option value="">{{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'نشط' : 'Active' }}</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'غير نشط' : 'Inactive' }}</option>
                            <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'مميز' : 'Featured' }}</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'مخزون منخفض' : 'Low Stock' }}</option>
                        </select>
                    </div>

                    <div class="col-md-1 mb-3">
                        <label class="form-label">{{ app()->getLocale() == 'ar' ? 'عدد' : 'Per Page' }}</label>
                        <select name="per_page" class="form-control">
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3 d-flex align-items-end gap-2">
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
                                <th width="60">{{ app()->getLocale() == 'ar' ? 'الصورة' : 'Image' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'المنتج' : 'Product' }}</th>
                                <th width="100">{{ app()->getLocale() == 'ar' ? 'SKU' : 'SKU' }}</th>
                                <th width="120">{{ app()->getLocale() == 'ar' ? 'التصنيف' : 'Category' }}</th>
                                <th width="100">{{ app()->getLocale() == 'ar' ? 'السعر' : 'Price' }}</th>
                                <th width="80">{{ app()->getLocale() == 'ar' ? 'المخزون' : 'Stock' }}</th>
                                <th width="100">{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
                                <th width="150">{{ app()->getLocale() == 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="product-image">
                                            @if($product->main_image)
                                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName() }}">
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
                                            <div class="product-name">{{ $product->getName('ar') }}</div>
                                            <div class="product-name-en">{{ $product->getName('en') }}</div>
                                            @if($product->brand)
                                                <div class="product-brand">{{ $product->brand }}</div>
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
                            {{ $products->links() }}
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
}

.products-table thead th {
    background: #f9fafb;
    color: #374151;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 2px solid #e5e7eb;
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
    padding: 16px 20px;
    background: #fafbfc;
}

.pagination-info {
    color: #6b7280;
    font-size: 14px;
}

.pagination-links nav {
    display: flex;
    gap: 4px;
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
