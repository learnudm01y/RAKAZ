@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة البراندات' : 'Brands Management')

@section('page-title')
    <span class="ar-text">إدارة البراندات</span>
    <span class="en-text">Brands Management</span>
@endsection

@push('styles')
<style>
    .brands-page {
        padding: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .brands-table {
        width: 100%;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .brands-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .brands-table th {
        background: #1a1a1a;
        color: white;
        padding: 16px 20px;
        text-align: right;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 3px solid #000;
        white-space: nowrap;
    }

    .brands-table th svg {
        opacity: 0.9;
    }

    [dir="ltr"] .brands-table th {
        text-align: left;
    }

    .brands-table td {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .brands-table tbody tr {
        transition: all 0.2s ease;
    }

    .brands-table tbody tr:hover {
        background: #fafafa;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .brand-logo {
        width: 60px;
        height: 60px;
        object-fit: contain;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
        padding: 5px;
        background: white;
    }

    .brand-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .brand-name {
        font-weight: 600;
        font-size: 16px;
        color: #1a1a1a;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.active {
        background: #10b981;
        color: white;
    }

    .status-badge.inactive {
        background: #6c757d;
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    [dir="ltr"] .action-buttons {
        justify-content: flex-start;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary {
        background: #1a1a1a;
        color: white;
        border: 2px solid #1a1a1a;
    }

    .btn-primary:hover {
        background: white;
        color: #1a1a1a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 8px;
    }

    .empty-state svg {
        width: 80px;
        height: 80px;
        color: #ccc;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 20px;
        color: #666;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #999;
        margin-bottom: 20px;
    }

    /* Pagination Styles */
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

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 8px;
    }

    .empty-state svg {
        width: 80px;
        height: 80px;
        color: #ccc;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 20px;
        color: #666;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #999;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="brands-page">
    @if(session('success'))
        <div class="alert alert-success" style="background: #10b981; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <h1 class="page-title">
            <span class="ar-text">إدارة البراندات</span>
            <span class="en-text">Brands Management</span>
        </h1>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="ar-text">إضافة براند جديد</span>
            <span class="en-text">Add New Brand</span>
        </a>
    </div>

    @if($brands->count() > 0)
        <div class="brands-table">
            <table>
                <thead>
                    <tr>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle; margin-inline-end: 6px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="ar-text">الشعار</span><span class="en-text">Logo</span>
                        </th>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle; margin-inline-end: 6px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="ar-text">الاسم</span><span class="en-text">Name</span>
                        </th>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle; margin-inline-end: 6px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="ar-text">عدد المنتجات</span><span class="en-text">Products</span>
                        </th>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle; margin-inline-end: 6px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="ar-text">الحالة</span><span class="en-text">Status</span>
                        </th>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle; margin-inline-end: 6px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                            <span class="ar-text">الترتيب</span><span class="en-text">Order</span>
                        </th>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle; margin-inline-end: 6px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                            </svg>
                            <span class="ar-text">الإجراءات</span><span class="en-text">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brands as $brand)
                        <tr>
                            <td>
                                <div class="brand-logo" style="display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                                    <svg width="30" height="30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                            </td>
                            <td>
                                <div class="brand-info">
                                    <span class="brand-name">{{ $brand->getName() }}</span>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $brand->products()->count() }}</strong>
                            </td>
                            <td>
                                <span class="status-badge {{ $brand->is_active ? 'active' : 'inactive' }}">
                                    @if($brand->is_active)
                                        <span class="ar-text">نشط</span>
                                        <span class="en-text">Active</span>
                                    @else
                                        <span class="ar-text">غير نشط</span>
                                        <span class="en-text">Inactive</span>
                                    @endif
                                </span>
                            </td>
                            <td>{{ $brand->sort_order }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-warning">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span class="ar-text">تعديل</span>
                                        <span class="en-text">Edit</span>
                                    </a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد من حذف هذا البراند؟' : 'Are you sure you want to delete this brand?' }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span class="ar-text">حذف</span>
                                            <span class="en-text">Delete</span>
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
        @if($brands->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    <span class="ar-text">
                        عرض {{ $brands->firstItem() }} إلى {{ $brands->lastItem() }} من إجمالي {{ $brands->total() }} براند
                    </span>
                    <span class="en-text">
                        Showing {{ $brands->firstItem() }} to {{ $brands->lastItem() }} of {{ $brands->total() }} brands
                    </span>
                </div>
                <div class="pagination-links">
                    {{ $brands->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    @else
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            <h3>
                <span class="ar-text">لا توجد براندات</span>
                <span class="en-text">No Brands Found</span>
            </h3>
            <p>
                <span class="ar-text">ابدأ بإضافة براند جديد</span>
                <span class="en-text">Start by adding a new brand</span>
            </p>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="ar-text">إضافة براند جديد</span>
                <span class="en-text">Add New Brand</span>
            </a>
        </div>
    @endif
</div>
@endsection
