@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة التصنيفات' : 'Manage Categories')

@section('page-title')
    <span class="ar-text">إدارة التصنيفات</span>
    <span class="en-text">Manage Categories</span>
@endsection

@push('styles')
<style>
    /* Pagination Styles */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: white;
        border-top: 2px solid #f0f0f0;
        margin-top: 20px;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
    }

    html[lang="ar"] .en-text,
    html[data-locale="ar"] .en-text,
    [dir="rtl"] .en-text {
        display: none !important;
    }

    html[lang="en"] .ar-text,
    html[data-locale="en"] .ar-text,
    [dir="ltr"] .ar-text {
        display: none !important;
    }

    .pagination-links {
        display: flex;
        align-items: center;
    }

    .pagination-links nav > div:first-child {
        display: none !important;
    }

    .pagination-links nav {
        display: block !important;
    }

    .pagination-links .d-none.flex-sm-fill div:first-child,
    .pagination-links .small.text-muted,
    .pagination-links p.small {
        display: none !important;
    }

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
        text-decoration: none;
        transition: all 0.2s ease;
        min-width: 40px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pagination .page-link:hover {
        background: #1a1a1a;
        border-color: #1a1a1a;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .pagination .page-item.active .page-link {
        background: #1a1a1a;
        border-color: #1a1a1a;
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(26, 26, 26, 0.2);
    }

    .pagination .page-item.disabled .page-link {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination .page-item.disabled .page-link:hover {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        transform: none;
        box-shadow: none;
    }

    .categories-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
    }

    .categories-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .add-category-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .add-category-btn:hover {
        background: #2c5aa0;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(49, 130, 206, 0.3);
    }

    .category-tree {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-item {
        margin-bottom: 0.5rem;
    }

    .category-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: var(--hover-bg);
        border-radius: 8px;
        border-left: 4px solid var(--primary-color);
        transition: all 0.2s;
    }

    [dir="rtl"] .category-row {
        border-left: none;
        border-right: 4px solid var(--primary-color);
    }

    .category-row:hover {
        background: #e0f2fe;
        transform: translateX(-4px);
    }

    [dir="rtl"] .category-row:hover {
        transform: translateX(4px);
    }

    .category-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .category-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        background: var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .category-details {
        flex: 1;
    }

    .category-name {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--text-color);
        margin-bottom: 0.25rem;
    }

    .category-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.875rem;
        color: #666;
    }

    .category-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .category-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-edit {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-edit:hover {
        background: #bfdbfe;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    .subcategories {
        list-style: none;
        padding: 0;
        margin-top: 0.5rem;
        margin-left: 2rem;
    }

    [dir="rtl"] .subcategories {
        margin-left: 0;
        margin-right: 2rem;
    }

    .subcategory-row {
        border-left-color: #60a5fa;
    }

    [dir="rtl"] .subcategory-row {
        border-right-color: #60a5fa;
    }

    .sub-subcategory-row {
        border-left-color: #93c5fd;
    }

    [dir="rtl"] .sub-subcategory-row {
        border-right-color: #93c5fd;
    }

    .level-indicator {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        background: white;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .level-0 { color: #1e40af; }
    .level-1 { color: #2563eb; }
    .level-2 { color: #3b82f6; }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #666;
    }

    .empty-state svg {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        color: #cbd5e0;
    }

    /* Pagination */
    .categories-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
        flex-wrap: wrap;
    }

    .categories-pagination .pagination-info {
        color: #6b7280;
        font-size: 0.9rem;
    }

    .categories-pagination .pagination {
        margin: 0;
    }

    [dir="rtl"] .categories-pagination {
        flex-direction: row-reverse;
    }

    /* Drag & Drop */
    .drop-zone {
        position: relative;
    }

    .draggable-item {
        cursor: grab;
    }

    .draggable-item:active {
        cursor: grabbing;
    }

    .drop-zone.drop-hover {
        outline: 2px dashed rgba(59, 130, 246, 0.7);
        outline-offset: 4px;
        background: #e0f2fe;
    }

    .drop-target.drop-before {
        box-shadow: inset 0 3px 0 rgba(59, 130, 246, 0.8);
    }

    .drop-target.drop-after {
        box-shadow: inset 0 -3px 0 rgba(59, 130, 246, 0.8);
    }

    .drag-hint {
        margin-bottom: 1rem;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: #f8fafc;
        color: #374151;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="categories-container">
    <div class="categories-header">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                <span class="ar-text">التصنيفات</span>
                <span class="en-text">Categories</span>
            </h2>
            <p style="color: #666; font-size: 0.875rem;">
                <span class="ar-text">إدارة التصنيفات الرئيسية والفرعية</span>
                <span class="en-text">Manage main and sub categories</span>
            </p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="add-category-btn">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="ar-text">إضافة تصنيف</span>
            <span class="en-text">Add Category</span>
        </a>
    </div>

    <div class="drag-hint">
        <span class="ar-text">يمكنك نقل التصنيف الفرعي بسحبه وإفلاته فوق تصنيف رئيسي آخر.</span>
        <span class="en-text">You can move a subcategory by dragging it onto another main category.</span>
    </div>

    <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-3" style="display:flex; gap: 12px; align-items: end; flex-wrap: wrap;">
        <div style="min-width: 180px;">
            <label class="form-label" style="margin-bottom: 0.35rem;">
                <span class="ar-text">عدد التصنيفات الرئيسية بالصفحة</span>
                <span class="en-text">Main categories per page</span>
            </label>
            <select name="per_page" class="form-control" style="height: 38px;">
                <option value="10" {{ request('per_page', 25) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page', 25) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page', 25) == 100 ? 'selected' : '' }}>100</option>
                <option value="1000" {{ request('per_page', 25) == 1000 ? 'selected' : '' }}>1000</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="height: 38px;">
            <span class="ar-text">تطبيق</span>
            <span class="en-text">Apply</span>
        </button>
    </form>

    @if($categories->count() > 0)
        <ul class="category-tree">
            @foreach($categories as $category)
                @include('admin.categories.partials.category-item', ['category' => $category, 'level' => 0])
            @endforeach
        </ul>

        <div class="categories-pagination">
            <div class="pagination-info">
                <span class="ar-text">عرض {{ $categories->firstItem() }} إلى {{ $categories->lastItem() }} من إجمالي {{ $categories->total() }} تصنيف رئيسي</span>
                <span class="en-text">Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} main categories</span>
            </div>
            <div class="pagination-links">
                {{ $categories->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @else
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">
                <span class="ar-text">لا توجد تصنيفات</span>
                <span class="en-text">No Categories</span>
            </h3>
            <p>
                <span class="ar-text">ابدأ بإضافة تصنيف جديد</span>
                <span class="en-text">Start by adding a new category</span>
            </p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
const categoriesMoveUrl = "{{ route('admin.categories.move') }}";

let draggedCategoryId = null;
let isDraggingCategory = false;
let lastPointerY = 0;

let autoScrollTimer = null;
let autoScrollDir = 0;

function clearDropHighlights() {
    document.querySelectorAll('.drop-target.drop-hover, .drop-target.drop-before, .drop-target.drop-after')
        .forEach((el) => el.classList.remove('drop-hover', 'drop-before', 'drop-after'));
}

function startAutoScroll() {
    if (autoScrollTimer) return;
    autoScrollTimer = window.setInterval(() => {
        if (!isDraggingCategory || autoScrollDir === 0) return;
        const speed = 18; // px per tick
        window.scrollBy(0, autoScrollDir * speed);
    }, 16);
}

function stopAutoScroll() {
    if (autoScrollTimer) {
        window.clearInterval(autoScrollTimer);
        autoScrollTimer = null;
    }
    autoScrollDir = 0;
}

function updateAutoScrollFromPointer(clientY) {
    lastPointerY = clientY;
    const edge = 90; // px
    const viewportH = window.innerHeight;

    if (clientY < edge) {
        autoScrollDir = -1;
    } else if (clientY > viewportH - edge) {
        autoScrollDir = 1;
    } else {
        autoScrollDir = 0;
    }
}

async function sendDragDrop(categoryId, targetId, position) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        Swal.fire({
            icon: 'error',
            title: document.documentElement.getAttribute('lang') === 'ar' ? 'خطأ' : 'Error',
            text: document.documentElement.getAttribute('lang') === 'ar' ? 'CSRF token غير موجود.' : 'Missing CSRF token.',
        });
        return;
    }

    const res = await fetch(categoriesMoveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ category_id: categoryId, target_id: targetId, position }),
    });

    if (!res.ok) {
        let msg = 'Request failed';
        try {
            const data = await res.json();
            msg = data?.message || msg;
        } catch (_) {}
        throw new Error(msg);
    }
}

function setupCategoryDragAndDrop() {
    // Drag start/end on rows (direct listeners)
    document.querySelectorAll('.category-row[draggable="true"]').forEach((el) => {
        el.addEventListener('dragstart', (e) => {
            draggedCategoryId = el.dataset.categoryId;
            isDraggingCategory = true;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', draggedCategoryId);
            startAutoScroll();
        });

        el.addEventListener('dragend', () => {
            draggedCategoryId = null;
            isDraggingCategory = false;
            clearDropHighlights();
            stopAutoScroll();
        });
    });

    // Event delegation in capture phase so drop works over the full row (even over icons/buttons/text)
    document.addEventListener('dragover', (e) => {
        if (!isDraggingCategory) return;

        updateAutoScrollFromPointer(e.clientY);

        const row = e.target?.closest?.('.category-row.drop-target');
        if (!row) return;

        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';

        // Visual hint before/after for reordering
        const rect = row.getBoundingClientRect();
        const before = e.clientY < rect.top + rect.height / 2;

        clearDropHighlights();
        row.classList.add('drop-hover');
        row.classList.add(before ? 'drop-before' : 'drop-after');
    }, true);

    document.addEventListener('dragleave', (e) => {
        // When leaving the document or moving between children, we don't want to flicker;
        // highlights are re-applied on dragover.
        if (!isDraggingCategory) return;
        if (e.relatedTarget == null) {
            clearDropHighlights();
        }
    }, true);

    document.addEventListener('drop', async (e) => {
        if (!isDraggingCategory) return;

        const row = e.target?.closest?.('.category-row.drop-target');
        if (!row) return;

        e.preventDefault();

        const categoryId = e.dataTransfer.getData('text/plain') || draggedCategoryId;
        const targetId = row.dataset.categoryId;
        if (!categoryId || !targetId) return;

        const rect = row.getBoundingClientRect();
        const position = e.clientY < rect.top + rect.height / 2 ? 'before' : 'after';

        try {
            await sendDragDrop(categoryId, targetId, position);

            Swal.fire({
                icon: 'success',
                title: document.documentElement.getAttribute('lang') === 'ar' ? 'تم' : 'Done',
                text: document.documentElement.getAttribute('lang') === 'ar'
                    ? 'تم تحديث ترتيب/مكان التصنيف بنجاح.'
                    : 'Category position/order updated successfully.',
                timer: 900,
                showConfirmButton: false,
            }).then(() => window.location.reload());
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: document.documentElement.getAttribute('lang') === 'ar' ? 'فشل' : 'Failed',
                text: err?.message || (document.documentElement.getAttribute('lang') === 'ar'
                    ? 'تعذر تحديث التصنيف.'
                    : 'Could not update category.'),
            });
        } finally {
            clearDropHighlights();
            stopAutoScroll();
        }
    }, true);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupCategoryDragAndDrop);
} else {
    setupCategoryDragAndDrop();
}

function deleteCategory(id, name) {
    const isArabic = document.documentElement.getAttribute('lang') === 'ar';

    Swal.fire({
        title: isArabic ? 'هل أنت متأكد؟' : 'Are you sure?',
        html: isArabic
            ? `سيتم حذف التصنيف "<strong>${name}</strong>" وجميع التصنيفات الفرعية`
            : `Category "<strong>${name}</strong>" and all subcategories will be deleted`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53e3e',
        cancelButtonColor: '#6c757d',
        confirmButtonText: isArabic ? 'نعم، احذف' : 'Yes, delete',
        cancelButtonText: isArabic ? 'إلغاء' : 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/categories/${id}`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

@if(session('success'))
Swal.fire({
    icon: 'success',
    title: '<span class="ar-text">نجح!</span><span class="en-text">Success!</span>',
    text: '{{ session("success") }}',
    confirmButtonText: '<span class="ar-text">حسناً</span><span class="en-text">OK</span>',
    confirmButtonColor: '#48bb78',
    timer: 3000,
    timerProgressBar: true
});
@endif
</script>
@endpush
