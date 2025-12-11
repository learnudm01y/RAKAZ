@extends('admin.layouts.app')

@section('title', 'إدارة الصفحات')

@section('page-title')
    <span class="ar-text">إدارة الصفحات</span>
    <span class="en-text">Manage Pages</span>
@endsection

@push('styles')
<style>
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-subtitle {
        color: #718096;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background: #2c5aa0;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border: 1px solid #9ae6b4;
    }

    .card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .search-form {
        width: 100%;
    }

    .search-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input-group svg {
        position: absolute;
        left: 1rem;
        color: #a0aec0;
    }

    [dir="rtl"] .search-input-group svg {
        left: auto;
        right: 1rem;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 3rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.875rem;
    }

    [dir="rtl"] .search-input {
        padding: 0.75rem 3rem 0.75rem 1rem;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: var(--hover-bg);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--secondary-color);
    }

    [dir="rtl"] th {
        text-align: right;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background: var(--hover-bg);
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-active {
        background: #c6f6d5;
        color: #22543d;
    }

    .badge-inactive {
        background: #fed7d7;
        color: #742a2a;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit, .btn-delete {
        padding: 0.5rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-edit {
        background: #e6f2ff;
        color: #3182ce;
    }

    .btn-edit:hover {
        background: #3182ce;
        color: white;
    }

    .btn-delete {
        background: #fed7d7;
        color: #e53e3e;
    }

    .btn-delete:hover {
        background: #e53e3e;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="content-header-left">
        <h1 class="page-title">
            <span class="ar-text">إدارة الصفحات</span>
            <span class="en-text">Manage Pages</span>
        </h1>
        <p class="page-subtitle">
            <span class="ar-text">إدارة محتوى الصفحات الثابتة</span>
            <span class="en-text">Manage static pages content</span>
        </p>
    </div>
    <div class="content-header-right">
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="ar-text">إضافة صفحة جديدة</span>
            <span class="en-text">Add New Page</span>
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">
    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
    </svg>
    {{ session('success') }}
</div>
@endif

<!-- Special Pages Section -->
<div class="card mb-4" style="margin-bottom: 2rem;">
    <div class="card-header" style="background: #f7fafc;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: #2d3748;">
            <span class="ar-text">الصفحات الخاصة</span>
            <span class="en-text">Special Pages</span>
        </h2>
    </div>
    <div class="table-container">
        <table>
            <tbody>
                <tr>
                    <td style="padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; background: #ebf4ff; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <svg width="20" height="20" fill="none" stroke="#3182ce" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 style="font-weight: 600; color: #2d3748;">
                                    <span class="ar-text">الصفحة الرئيسية</span>
                                    <span class="en-text">Home Page</span>
                                </h3>
                                <p style="font-size: 0.875rem; color: #718096;">
                                    <span class="ar-text">إدارة محتوى الصفحة الرئيسية (Hero Slider, Banners, Sections)</span>
                                    <span class="en-text">Manage home page content (Hero Slider, Banners, Sections)</span>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.5rem; text-align: left;">
                        <a href="{{ route('admin.home.edit') }}" class="btn btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="ar-text">تحرير</span>
                            <span class="en-text">Edit</span>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; background: #ebf4ff; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <svg width="20" height="20" fill="none" stroke="#3182ce" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 style="font-weight: 600; color: #2d3748;">
                                    <span class="ar-text">صفحة من نحن</span>
                                    <span class="en-text">About Us Page</span>
                                </h3>
                                <p style="font-size: 0.875rem; color: #718096;">
                                    <span class="ar-text">تحديث معلومات الشركة والرؤية والرسالة</span>
                                    <span class="en-text">Update company information, vision and mission</span>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.5rem; text-align: left;">
                        <a href="{{ route('admin.about.edit') }}" class="btn btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="ar-text">تحرير</span>
                            <span class="en-text">Edit</span>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; background: #ebf4ff; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <svg width="20" height="20" fill="none" stroke="#3182ce" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 style="font-weight: 600; color: #2d3748;">
                                    <span class="ar-text">صفحة اتصل بنا</span>
                                    <span class="en-text">Contact Us Page</span>
                                </h3>
                                <p style="font-size: 0.875rem; color: #718096;">
                                    <span class="ar-text">تحديث معلومات الاتصال والعنوان</span>
                                    <span class="en-text">Update contact information and address</span>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.5rem; text-align: left;">
                        <a href="{{ route('admin.contact.edit') }}" class="btn btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="ar-text">تحرير</span>
                            <span class="en-text">Edit</span>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; background: #ebf4ff; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <svg width="20" height="20" fill="none" stroke="#3182ce" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 style="font-weight: 600; color: #2d3748;">
                                    <span class="ar-text">سياسة الخصوصية</span>
                                    <span class="en-text">Privacy Policy</span>
                                </h3>
                                <p style="font-size: 0.875rem; color: #718096;">
                                    <span class="ar-text">تحديث سياسة الخصوصية</span>
                                    <span class="en-text">Update privacy policy</span>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.5rem; text-align: left;">
                        <a href="{{ route('admin.privacy.edit') }}" class="btn btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="ar-text">تحرير</span>
                            <span class="en-text">Edit</span>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card"
    <div class="card-header">
        <form method="GET" action="{{ route('admin.pages.index') }}" class="search-form">
            <div class="search-input-group">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="البحث عن صفحة... / Search for page..."
                    class="search-input"
                >
                <button type="submit" class="btn btn-secondary">
                    <span class="ar-text">بحث</span>
                    <span class="en-text">Search</span>
                </button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>
                        <span class="ar-text">العنوان بالعربي</span>
                        <span class="en-text">Arabic Title</span>
                    </th>
                    <th>
                        <span class="ar-text">العنوان بالإنجليزي</span>
                        <span class="en-text">English Title</span>
                    </th>
                    <th>
                        <span class="ar-text">الرابط</span>
                        <span class="en-text">Slug</span>
                    </th>
                    <th>
                        <span class="ar-text">الحالة</span>
                        <span class="en-text">Status</span>
                    </th>
                    <th>
                        <span class="ar-text">الترتيب</span>
                        <span class="en-text">Order</span>
                    </th>
                    <th>
                        <span class="ar-text">الإجراءات</span>
                        <span class="en-text">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr>
                    <td>{{ $page->id }}</td>
                    <td>{{ $page->title_ar }}</td>
                    <td>{{ $page->title_en }}</td>
                    <td><code>{{ $page->slug }}</code></td>
                    <td>
                        <span class="badge badge-{{ $page->status === 'active' ? 'success' : 'danger' }}">
                            @if($page->status === 'active')
                                <span class="ar-text">نشط</span>
                                <span class="en-text">Active</span>
                            @else
                                <span class="ar-text">غير نشط</span>
                                <span class="en-text">Inactive</span>
                            @endif
                        </span>
                    </td>
                    <td>{{ $page->order }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn-icon btn-edit" title="تعديل / Edit">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟ / Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="حذف / Delete">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center empty-state">
                        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="ar-text">لا توجد صفحات حالياً</p>
                        <p class="en-text">No pages found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pages->hasPages())
    <div class="card-footer">
        {{ $pages->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Additional scripts if needed
</script>
@endpush
