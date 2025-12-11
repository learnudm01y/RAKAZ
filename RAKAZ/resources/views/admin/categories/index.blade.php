@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة التصنيفات' : 'Manage Categories')

@section('page-title')
    <span class="ar-text">إدارة التصنيفات</span>
    <span class="en-text">Manage Categories</span>
@endsection

@push('styles')
<style>
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

    @if($categories->count() > 0)
        <ul class="category-tree">
            @foreach($categories as $category)
                @include('admin.categories.partials.category-item', ['category' => $category, 'level' => 0])
            @endforeach
        </ul>
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
