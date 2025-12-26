@extends('admin.layouts.app')

@section('title', 'إدارة قسم الهدية المثالية')

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

    /* ═══════════════════════════════════════════════════════════ */
    /* 🔥 NEW INSTANT DELETE BUTTON - تصميم جديد كلياً */
    /* ═══════════════════════════════════════════════════════════ */
    .instant-delete-btn {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: white;
        border: 2px solid #b91c1c;
        padding: 10px 18px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        flex-shrink: 0;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
        position: relative;
        overflow: hidden;
    }

    .instant-delete-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .instant-delete-btn:hover::before {
        left: 100%;
    }

    .instant-delete-btn:hover {
        background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%);
        border-color: #991b1b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.5);
    }

    .instant-delete-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);
    }

    .instant-delete-btn i {
        margin-left: 6px;
        font-size: 13px;
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* OLD BUTTON - تم إزالته ولن يُستخدم بعد الآن */
    /* ═══════════════════════════════════════════════════════════ */
    .remove-product-btn {
        display: none !important; /* مخفي تماماً */
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
                        <i class="fas fa-gift"></i>
                        <span>إدارة قسم الهدية المثالية</span>
                    </h3>
                </div>

                <div class="admin-section-body">
                    <form action="{{ route('admin.perfect-gift-section.update') }}" method="POST" id="section-form">
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
                                           value="{{ $section->title['ar'] ?? 'الهدية المثالية' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">العنوان (إنجليزي)</label>
                                    <input type="text" name="title_en" class="form-control"
                                           value="{{ $section->title['en'] ?? 'Perfect Gift' }}" required>
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
                                            <!-- 🔥 NEW DELETE BUTTON - معمارية جديدة تماماً -->
                                            <button type="button"
                                                    class="instant-delete-btn"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->getName() }}"
                                                    onclick="instantDeleteProduct({{ $product->id }}, '{{ addslashes($product->getName()) }}')">
                                                <i class="fas fa-trash-alt me-1"></i> حذف فوري
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
console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
console.log('🌟 PERFECT GIFT SECTION PAGE LOADED');
console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

$(document).ready(function() {
    console.log('✅ jQuery ready - DOM loaded');
    console.log('📊 Initial product count:', $('#selected-products .product-card').length);
    console.log('🔍 Remove buttons found:', $('.remove-product-btn').length);

    // List all remove buttons
    $('.remove-product-btn').each(function(index) {
        console.log(`  - Button ${index + 1}:`, this, 'Classes:', this.className);
    });

    let searchTimeout;
    const searchInput = $('#product-search-input');
    const searchResults = $('#search-results');

    // Log form submission
    $('#section-form').on('submit', function(e) {
        console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        console.log('📝 FORM SUBMISSION STARTED');
        const productIds = [];
        $('#selected-products .product-card').each(function() {
            productIds.push($(this).data('product-id'));
        });
        console.log('Products being submitted:', productIds);
        console.log('Total products:', productIds.length);
        console.log('Form data:', $(this).serialize());
        console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        console.log('Form data:', $(this).serialize());
    });

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
                url: '{{ route('admin.perfect-gift-section.products') }}',
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

    // ═══════════════════════════════════════════════════════════
    // 🔥 تم إزالة الـ JavaScript القديم بالكامل
    // الزر الجديد يستخدم onclick="instantDeleteProduct()"
    // ═══════════════════════════════════════════════════════════

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
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'هذا المنتج موجود بالفعل!',
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#1a1a1a'
        });
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
            <button type="button"
                    class="instant-delete-btn"
                    data-product-id="${id}"
                    data-product-name="${name}"
                    onclick="instantDeleteProduct(${id}, '${name.replace(/'/g, "\\'")}')">
                <i class="fas fa-trash-alt me-1"></i> حذف فوري
            </button>
        </div>
    `;

    $('#selected-products').append(productHtml);
    updateProductCount();
}

// ═══════════════════════════════════════════════════════════
// 🔥 NEW INSTANT DELETE FUNCTION - معمارية جديدة تماماً
// يحذف المنتج مباشرة من قاعدة البيانات عبر AJAX
// ═══════════════════════════════════════════════════════════
function instantDeleteProduct(productId, productName) {
    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    console.log('🔥 PERFECT GIFT - INSTANT DELETE TRIGGERED');
    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    console.log('📦 Product ID:', productId);
    console.log('📝 Product Name:', productName);
    console.log('🌐 CSRF Token:', $('meta[name="csrf-token"]').attr('content'));

    // Show confirmation dialog
    Swal.fire({
        icon: 'warning',
        title: '🗑️ حذف فوري من قاعدة البيانات',
        html: `
            <p><strong>هل أنت متأكد من حذف هذا المنتج؟</strong></p>
            <p class="text-muted">${productName}</p>
            <div class="alert alert-danger mt-3">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>تنبيه:</strong> سيتم الحذف فوراً من قاعدة البيانات!
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash-alt"></i> نعم، احذف الآن!',
        cancelButtonText: '<i class="fas fa-times"></i> إلغاء',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            console.log('🚀 User confirmed - Starting AJAX DELETE request...');

            return $.ajax({
                url: `/admin/perfect-gift-section/product/${productId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    console.log('📡 AJAX Request starting...');
                    console.log('URL:', `/admin/perfect-gift-section/product/${productId}`);
                    console.log('Method: DELETE');
                },
                success: function(response) {
                    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                    console.log('✅ AJAX SUCCESS RESPONSE');
                    console.log('Response:', response);
                    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                    return response;
                },
                error: function(xhr, status, error) {
                    console.error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                    console.error('❌ AJAX ERROR');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response:', xhr.responseText);
                    console.error('Status Code:', xhr.status);
                    console.error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

                    throw new Error(xhr.responseJSON?.message || 'فشل الاتصال بالخادم');
                }
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            const response = result.value;

            console.log('🎉 Delete confirmed and successful');
            console.log('Server response:', response);

            if (response.success) {
                // Remove from DOM
                console.log('🗑️ Removing product card from DOM...');
                const $productCard = $(`.product-card[data-product-id="${productId}"]`);

                $productCard.fadeOut(400, function() {
                    $(this).remove();
                    console.log('✅ Product card removed from DOM');

                    const remainingCount = $('#selected-products .product-card').length;
                    console.log('📊 Remaining products:', remainingCount);

                    updateProductCount();

                    // Show empty state if needed
                    if (remainingCount === 0) {
                        $('#selected-products').html(`
                            <div class="empty-products">
                                <i class="fas fa-box-open"></i>
                                <p>لا توجد منتجات مضافة</p>
                                <small>استخدم البحث أعلاه لإضافة منتجات جديدة</small>
                            </div>
                        `);
                    }
                });

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: '✅ تم الحذف من قاعدة البيانات!',
                    html: `
                        <p>${response.message}</p>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-database"></i>
                                العدد قبل الحذف: ${response.before_count}
                                → بعد الحذف: ${response.after_count}
                            </small>
                        </div>
                    `,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });

                console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                console.log('✅ PERFECT GIFT - INSTANT DELETE COMPLETED');
                console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'فشل الحذف!',
                    text: response.message || 'حدث خطأ غير متوقع',
                    confirmButtonColor: '#dc3545'
                });
            }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            console.log('❌ User cancelled the deletion');
        }
    }).catch((error) => {
        console.error('❌ Error in delete process:', error);

        Swal.fire({
            icon: 'error',
            title: 'خطأ!',
            text: error.message || 'حدث خطأ أثناء الحذف',
            footer: 'تحقق من Console للمزيد من التفاصيل',
            confirmButtonColor: '#dc3545'
        });
    });
}

// ═══════════════════════════════════════════════════════════
// تم حذف removeProduct() القديم - لم يعد مستخدماً
// ═══════════════════════════════════════════════════════════

function updateProductCount() {
    const count = $('#selected-products .product-card').length;
    console.log('Updating product count to:', count);
    $('#product-count').text(count);
}
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'نجح!',
        text: '{{ session('success') }}',
        confirmButtonText: 'حسناً',
        confirmButtonColor: '#10b981',
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif
@endpush
