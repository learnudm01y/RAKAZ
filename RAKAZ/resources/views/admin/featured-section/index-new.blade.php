@extends('admin.layouts.app')

@section('title', 'إدارة المنتجات المميزة')

@push('styles')
<style>
    /* ===== Site Colors Theme (Black & White) ===== */
    :root {
        --primary-black: #1a1a1a;
        --secondary-black: #2d2d2d;
        --border-gray: #e5e5e5;
        --bg-light: #f9f9f9;
        --text-dark: #1a1a1a;
        --text-muted: #666666;
        --success-green: #10b981;
        --danger-red: #ef4444;
    }

    /* Main Card Container */
    .admin-section-card {
        background: white;
        border-radius: 0;
        border: 1px solid var(--border-gray);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .admin-section-header {
        background: var(--primary-black);
        color: white;
        padding: 24px 30px;
        border-bottom: none;
    }

    .admin-section-header h3 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .admin-section-body {
        padding: 30px;
    }

    /* Form Sections */
    .form-group-section {
        background: var(--bg-light);
        padding: 24px;
        border: 1px solid var(--border-gray);
        margin-bottom: 24px;
    }

    .section-label {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--primary-black);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control {
        border: 1px solid var(--border-gray);
        border-radius: 0;
        padding: 10px 16px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
    }

    .form-control:focus {
        border-color: var(--primary-black);
        box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
        outline: none;
    }

    .form-check-input:checked {
        background-color: var(--primary-black);
        border-color: var(--primary-black);
    }

    /* Product Search Box */
    .product-search-box {
        background: white;
        padding: 20px;
        border: 2px dashed var(--border-gray);
        margin-bottom: 24px;
    }

    .search-title {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
    }

    #product-search-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--border-gray);
        font-size: 14px;
        transition: all 0.2s;
    }

    #product-search-input:focus {
        border-color: var(--primary-black);
        outline: none;
    }

    /* Search Results Dropdown */
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 2px solid var(--primary-black);
        max-height: 400px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .search-results.show {
        display: block;
    }

    .search-result-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid var(--border-gray);
        transition: background 0.2s;
    }

    .search-result-item:hover {
        background: var(--bg-light);
    }

    .search-result-item img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        margin-left: 12px;
        border: 1px solid var(--border-gray);
    }

    .search-result-info {
        flex: 1;
    }

    .search-result-name {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 14px;
    }

    .search-result-id {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .search-empty {
        padding: 20px;
        text-align: center;
        color: var(--text-muted);
    }

    .search-loading {
        padding: 20px;
        text-align: center;
        color: var(--text-muted);
    }

    /* Selected Products List */
    .products-container {
        background: white;
        padding: 20px;
        border: 1px solid var(--border-gray);
        min-height: 100px;
    }

    .product-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        border: 2px solid var(--border-gray);
        margin-bottom: 12px;
        background: white;
        transition: all 0.3s;
        cursor: move;
    }

    .product-card:hover {
        border-color: var(--primary-black);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .product-card.sortable-ghost {
        opacity: 0.4;
        background: var(--bg-light);
    }

    .product-card.sortable-chosen {
        border-color: var(--primary-black);
        background: var(--bg-light);
    }

    .drag-icon {
        color: var(--text-muted);
        cursor: grab;
        font-size: 18px;
        flex-shrink: 0;
    }

    .drag-icon:active {
        cursor: grabbing;
    }

    .product-card img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border: 1px solid var(--border-gray);
        flex-shrink: 0;
    }

    .product-details {
        flex: 1;
        min-width: 0;
    }

    .product-title {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 15px;
        margin-bottom: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-meta {
        font-size: 12px;
        color: var(--text-muted);
        background: var(--bg-light);
        padding: 2px 8px;
        border-radius: 2px;
        display: inline-block;
    }

    .remove-product-btn {
        background: var(--danger-red);
        color: white;
        border: none;
        padding: 8px 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 13px;
        flex-shrink: 0;
    }

    .remove-product-btn:hover {
        background: #dc2626;
        transform: scale(1.05);
    }

    .empty-products {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty-products i {
        font-size: 56px;
        margin-bottom: 16px;
        opacity: 0.3;
        display: block;
    }

    .empty-products p {
        margin: 8px 0 0;
        font-size: 16px;
    }

    .empty-products small {
        font-size: 13px;
    }

    /* Save Button */
    .save-button {
        background: var(--primary-black);
        color: white;
        border: none;
        padding: 14px 40px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 15px;
    }

    .save-button:hover {
        background: var(--secondary-black);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Alert Messages */
    .alert {
        border-radius: 0;
        border: none;
        font-weight: 600;
    }

    .alert-success {
        background: var(--success-green);
        color: white;
    }

    /* Product Count Badge */
    .count-badge {
        background: var(--primary-black);
        color: white;
        padding: 4px 12px;
        border-radius: 2px;
        font-size: 13px;
        font-weight: 700;
        margin-right: 8px;
    }

    /* Help Text */
    .help-text {
        color: var(--text-muted);
        font-size: 13px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="admin-section-card">
                <div class="admin-section-header">
                    <h3>
                        <i class="fas fa-star"></i>
                        <span>إدارة المنتجات المميزة</span>
                    </h3>
                </div>

                <div class="admin-section-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.featured-section.update') }}" method="POST" id="section-form">
                        @csrf

                        <!-- Section Information -->
                        <div class="form-group-section">
                            <div class="section-label">
                                <i class="fas fa-info-circle"></i>
                                معلومات القسم
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">العنوان (عربي)</label>
                                    <input type="text" name="title_ar" class="form-control"
                                           value="{{ $section->title['ar'] ?? 'المنتجات المميزة' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">العنوان (إنجليزي)</label>
                                    <input type="text" name="title_en" class="form-control"
                                           value="{{ $section->title['en'] ?? 'Featured Products' }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">رابط "تسوق الكل"</label>
                                    <input type="text" name="link_url" class="form-control"
                                           value="{{ $section->link_url ?? '/shop' }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">نص الرابط (عربي)</label>
                                    <input type="text" name="link_text_ar" class="form-control"
                                           value="{{ $section->link_text['ar'] ?? 'تسوق الكل' }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">نص الرابط (إنجليزي)</label>
                                    <input type="text" name="link_text_en" class="form-control"
                                           value="{{ $section->link_text['en'] ?? 'Shop All' }}" required>
                                </div>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active"
                                       id="is_active" {{ $section->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">تفعيل القسم في الصفحة الرئيسية</label>
                            </div>
                        </div>

                        <!-- Product Search -->
                        <div class="product-search-box">
                            <div class="search-title">
                                <i class="fas fa-search"></i>
                                البحث عن المنتجات وإضافتها
                            </div>
                            <div style="position: relative;">
                                <input type="text"
                                       id="product-search-input"
                                       placeholder="ابحث باسم المنتج أو رقمه..."
                                       autocomplete="off">
                                <div class="search-results" id="search-results"></div>
                            </div>
                            <div class="help-text">
                                <i class="fas fa-info-circle"></i>
                                اكتب اسم المنتج أو رقمه ثم اختره من القائمة لإضافته
                            </div>
                        </div>

                        <!-- Selected Products -->
                        <div class="form-group-section">
                            <div class="section-label">
                                <i class="fas fa-box"></i>
                                المنتجات المختارة
                                <span class="count-badge" id="product-count">{{ $section->products->count() ?? 0 }}</span>
                            </div>

                            <div id="selected-products" class="products-container">
                                @if($section && $section->products && $section->products->count() > 0)
                                    @foreach($section->products as $product)
                                        <div class="product-card" data-product-id="{{ $product->id }}">
                                            <span class="drag-icon">
                                                <i class="fas fa-grip-vertical"></i>
                                            </span>
                                            <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                            <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : '/assets/images/placeholder.jpg' }}" alt="{{ $product->getName() }}">
                                            <div class="product-details">
                                                <div class="product-title">{{ $product->getName() }}</div>
                                                <span class="product-meta">ID: {{ $product->id }}</span>
                                            </div>
                                            <button type="button" class="remove-product-btn" onclick="removeProduct(this)">
                                                <i class="fas fa-times me-1"></i> إزالة
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-products">
                                        <i class="fas fa-box-open"></i>
                                        <p>لا توجد منتجات مضافة</p>
                                        <small>استخدم البحث أعلاه لإضافة منتجات جديدة</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="save-button">
                                <i class="fas fa-save me-2"></i>
                                حفظ جميع التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    let searchTimeout;
    const searchInput = $('#product-search-input');
    const searchResults = $('#search-results');

    // Product Search
    searchInput.on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val().trim();

        if (query.length < 1) {
            searchResults.removeClass('show').empty();
            return;
        }

        searchResults.html('<div class="search-loading"><i class="fas fa-spinner fa-spin"></i> جاري البحث...</div>').addClass('show');

        searchTimeout = setTimeout(function() {
            $.ajax({
                url: '{{ route('admin.featured-section.products') }}',
                data: { q: query },
                success: function(data) {
                    if (data.length === 0) {
                        searchResults.html('<div class="search-empty">لا توجد نتائج</div>');
                        return;
                    }

                    let html = '';
                    data.forEach(function(product) {
                        html += `
                            <div class="search-result-item" data-id="${product.id}" data-name="${product.text}" data-image="${product.image || ''}">
                                ${product.image ? `<img src="${product.image}" alt="${product.text}">` : '<div style="width: 50px; height: 50px; background: #f0f0f0; margin-left: 12px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-image text-muted"></i></div>'}
                                <div class="search-result-info">
                                    <div class="search-result-name">${product.text}</div>
                                    <div class="search-result-id">ID: ${product.id}</div>
                                </div>
                            </div>
                        `;
                    });
                    searchResults.html(html);
                }
            });
        }, 300);
    });

    // Click on search result
    $(document).on('click', '.search-result-item', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const image = $(this).data('image');

        addProduct(id, name, image);
        searchInput.val('');
        searchResults.removeClass('show').empty();
    });

    // Close search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#product-search-input, #search-results').length) {
            searchResults.removeClass('show');
        }
    });

    // Initialize Sortable
    const el = document.getElementById('selected-products');
    if (el) {
        Sortable.create(el, {
            animation: 150,
            handle: '.drag-icon',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                updateProductCount();
            }
        });
    }

    updateProductCount();
});

function addProduct(id, name, image) {
    // Check if already exists
    if ($('#selected-products').find('[data-product-id="' + id + '"]').length > 0) {
        alert('هذا المنتج موجود بالفعل!');
        return;
    }

    // Remove empty state
    $('#selected-products .empty-products').remove();

    const imageHtml = image ?
        `<img src="${image}" alt="${name}">` :
        '<img src="/assets/images/placeholder.jpg" alt="No Image">';

    const productHtml = `
        <div class="product-card" data-product-id="${id}">
            <span class="drag-icon">
                <i class="fas fa-grip-vertical"></i>
            </span>
            <input type="hidden" name="product_ids[]" value="${id}">
            ${imageHtml}
            <div class="product-details">
                <div class="product-title">${name}</div>
                <span class="product-meta">ID: ${id}</span>
            </div>
            <button type="button" class="remove-product-btn" onclick="removeProduct(this)">
                <i class="fas fa-times me-1"></i> إزالة
            </button>
        </div>
    `;

    $('#selected-products').append(productHtml);
    updateProductCount();
}

function removeProduct(btn) {
    $(btn).closest('.product-card').fadeOut(300, function() {
        $(this).remove();
        updateProductCount();

        // Add empty state if no products
        if ($('#selected-products .product-card').length === 0) {
            $('#selected-products').html(`
                <div class="empty-products">
                    <i class="fas fa-box-open"></i>
                    <p>لا توجد منتجات مضافة</p>
                    <small>استخدم البحث أعلاه لإضافة منتجات جديدة</small>
                </div>
            `);
        }
    });
}

function updateProductCount() {
    const count = $('#selected-products .product-card').length;
    $('#product-count').text(count);
}
</script>
@endpush
