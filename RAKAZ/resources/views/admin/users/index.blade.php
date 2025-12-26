@extends('admin.layouts.app')

@section('title', 'إدارة المستخدمين')

@section('page-title')
    <span class="ar-text">إدارة المستخدمين</span>
    <span class="en-text">Users Management</span>
@endsection

@push('styles')
<style>
    b, strong {
        font-weight: bolder;
        padding-right: 10px;
    }
    .align-items-center {
        align-items: center !important;
        margin-right: -35px;
    }

    :root {
        --primary: #1a1a1a;
        --danger: #ef4444;
        --success: #10b981;
        --warning: #f59e0b;
        --info: #3b82f6;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .stat-card {
        background: white;
        padding: 18px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.3s;
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin: 0 auto 12px;
    }

    .stat-icon.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .stat-icon.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .stat-icon.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
    .stat-icon.danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
    .stat-icon.info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; }

    .stat-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
    }

    .page-header {
        background: white;
        padding: 24px 30px;
        border-radius: 12px;
        margin-bottom: 24px;
        border: 1px solid #e5e7eb;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .table-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .search-box {
        flex: 1;
        min-width: 300px;
        max-width: 400px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 10px 40px 10px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
    }

    .search-box i {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #1a1a1a;
    }

    th {
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

    th svg {
        opacity: 0.9;
        display: inline-block;
        vertical-align: middle;
        margin-inline-end: 6px;
    }

    [dir="ltr"] th {
        text-align: left;
    }

    td {
        padding: 16px;
        border-top: 1px solid #f3f4f6;
    }

    tbody tr:hover {
        background: #f9fafb;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 16px;
    }

    .user-details h4 {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .user-details p {
        margin: 0;
        font-size: 12px;
        color: #6b7280;
    }

    .badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 6px;
    }

    .btn-info {
        background: #3b82f6;
        color: white;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

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
    }

    .pagination {
        display: flex;
        gap: 4px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .page-item {
        border-radius: 8px;
        overflow: hidden;
    }

    .page-link {
        padding: 10px 16px;
        border: 1px solid #e5e7eb;
        color: #1f2937;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        transition: all 0.2s;
        background: white;
        font-weight: 500;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .page-link:hover:not(.active) {
        background: #f3f4f6;
        border-color: #d1d5db;
        transform: translateY(-2px);
    }

    .page-item.disabled .page-link {
        background: #f9fafb;
        color: #d1d5db;
        cursor: not-allowed;
    }

    .per-page-select {
        padding: 6px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 13px;
    }

    /* Header Layout */
    .page-header {
        position: relative;
        margin-bottom: 24px;
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .header-right {
        order: 2;
        margin-right: auto;
    }

    .header-left {
        order: 1;
        margin-left: 0;
        padding-left: 0;
    }

    .btn-add-user {
        background: #1a1a1a;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-add-user:hover {
        background: #000000;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .btn-add-user i {
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-right">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-add-user">
                    <i class="fas fa-plus"></i>
                    إضافة مستخدم جديد
                </a>
            </div>
            <div class="header-left">
                <h1 class="page-title" style="margin: 0;">
                    <i class="fas fa-users me-2"></i>
                    إدارة المستخدمين
                </h1>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-label">إجمالي المستخدمين</div>
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-label">مستخدمين مفعلين</div>
            <div class="stat-value">{{ number_format($stats['verified_users']) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="stat-label">غير مفعلين</div>
            <div class="stat-value">{{ number_format($stats['unverified_users']) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-label">لديهم طلبات</div>
            <div class="stat-value">{{ number_format($stats['users_with_orders']) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-label">مستخدمين جدد هذا الشهر</div>
            <div class="stat-value">{{ number_format($stats['new_users_this_month']) }}</div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-card">
        <div class="table-header">
            <div class="search-box">
                <form action="{{ route('admin.users.index') }}" method="GET">
                    <input type="text"
                           name="search"
                           placeholder="بحث عن مستخدم (الاسم، البريد، ID)..."
                           value="{{ $search }}">
                    <i class="fas fa-search"></i>
                </form>
            </div>
            <div>
                <select class="per-page-select" onchange="window.location.href='?per_page=' + this.value + '{{ $search ? '&search='.$search : '' }}'">
                    <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 نتيجة</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 نتيجة</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 نتيجة</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 نتيجة</option>
                </select>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                        ID
                    </th>
                    <th>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="ar-text">المستخدم</span><span class="en-text">User</span>
                    </th>
                    <th>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="ar-text">البريد الإلكتروني</span><span class="en-text">Email</span>
                    </th>
                    <th>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ar-text">حالة التفعيل</span><span class="en-text">Status</span>
                    </th>
                    <th>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="ar-text">عدد الطلبات</span><span class="en-text">Orders</span>
                    </th>
                    <th>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="ar-text">تاريخ التسجيل</span><span class="en-text">Registration Date</span>
                    </th>
                    <th>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                        <span class="ar-text">الإجراءات</span><span class="en-text">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><strong>#{{ $user->id }}</strong></td>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="user-details">
                                <h4>{{ $user->name }}</h4>
                                <p>انضم منذ {{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->email_verified_at)
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> مفعّل
                            </span>
                        @else
                            <span class="badge badge-warning">
                                <i class="fas fa-clock"></i> غير مفعّل
                            </span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $user->orders_count }}</strong> طلب
                    </td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('admin.users.show', $user->id) }}"
                               class="btn btn-sm btn-info"
                               title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="btn btn-sm btn-warning"
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}"
                                  method="POST"
                                  style="display: inline;"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        <i class="fas fa-users" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;"></i>
                        <p style="color: #6b7280; font-size: 16px;">لا توجد مستخدمين</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                <span class="ar-text">عرض {{ $users->firstItem() }} إلى {{ $users->lastItem() }} من إجمالي {{ $users->total() }} مستخدم</span>
                <span class="en-text">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users</span>
            </div>
            <div class="pagination-links">
                {{ $users->appends(['search' => request('search'), 'per_page' => request('per_page')])->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    console.log('📊 User Management Page Loaded');
    console.log('Total Users:', {{ $stats['total_users'] }});
</script>
@endpush
