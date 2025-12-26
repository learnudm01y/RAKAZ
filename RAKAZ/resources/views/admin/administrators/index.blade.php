@extends('admin.layouts.app')

@section('title', 'إدارة المسؤولين')

@section('page-title')
    <span class="ar-text">إدارة المسؤولين</span>
    <span class="en-text">Administrators Management</span>
@endsection

@push('styles')
<style>
    .table thead th {
        background: #1a1a1a !important;
        color: white !important;
        padding: 16px 20px !important;
        font-weight: 600 !important;
        border-bottom: 3px solid #000 !important;
        text-transform: none !important;
        letter-spacing: 0.3px !important;
        font-size: 14px !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
    }

    .table thead th svg {
        margin-inline-end: 8px;
        vertical-align: middle;
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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="ar-text">إدارة المسؤولين</h2>
                <h2 class="en-text">Administrators Management</h2>
            </div>
            <a href="{{ route('admin.administrators.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                <span class="ar-text">إضافة مسؤول</span>
                <span class="en-text">Add Administrator</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $statistics['total_admins'] }}</div>
                    <div class="stats-label">
                        <span class="ar-text">إجمالي المسؤولين</span>
                        <span class="en-text">Total Administrators</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $statistics['verified_admins'] }}</div>
                    <div class="stats-label">
                        <span class="ar-text">محققون</span>
                        <span class="en-text">Verified</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $statistics['unverified_admins'] }}</div>
                    <div class="stats-label">
                        <span class="ar-text">غير محققين</span>
                        <span class="en-text">Unverified</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $statistics['new_this_month'] }}</div>
                    <div class="stats-label">
                        <span class="ar-text">جدد هذا الشهر</span>
                        <span class="en-text">New This Month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-3">
        <div class="col-md-6">
            <form method="GET" action="{{ route('admin.administrators.index') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                           placeholder="ابحث بالاسم أو البريد الإلكتروني..."
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <form method="GET" action="{{ route('admin.administrators.index') }}" class="d-inline">
                <select name="per_page" class="form-select d-inline w-auto" onchange="this.form.submit()">
                    <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Administrators Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($administrators->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                            #
                                        </th>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            <span class="ar-text">المسؤول</span>
                                            <span class="en-text">Administrator</span>
                                        </th>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="ar-text">البريد الإلكتروني</span>
                                            <span class="en-text">Email</span>
                                        </th>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="ar-text">الحالة</span>
                                            <span class="en-text">Status</span>
                                        </th>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="ar-text">تاريخ الإضافة</span>
                                            <span class="en-text">Created At</span>
                                        </th>
                                        <th>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                            <span class="ar-text">الإجراءات</span>
                                            <span class="en-text">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($administrators as $admin)
                                        <tr>
                                            <td>{{ $admin->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="admin-avatar me-2">
                                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $admin->name }}</strong>
                                                        @if(auth()->id() == $admin->id)
                                                            <span class="badge bg-primary ms-1">You</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $admin->email }}</td>
                                            <td>
                                                @if($admin->email_verified_at)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span class="ar-text">محقق</span>
                                                        <span class="en-text">Verified</span>
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                        <span class="ar-text">غير محقق</span>
                                                        <span class="en-text">Not Verified</span>
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <a href="{{ route('admin.administrators.show', $admin->id) }}"
                                                   class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.administrators.edit', $admin->id) }}"
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if(auth()->id() != $admin->id)
                                                    <button class="btn btn-sm btn-danger"
                                                            onclick="deleteAdministrator({{ $admin->id }})"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pagination-wrapper">
                            <div class="pagination-info">
                                <span class="ar-text">عرض {{ $administrators->firstItem() }} إلى {{ $administrators->lastItem() }} من إجمالي {{ $administrators->total() }} مسؤول</span>
                                <span class="en-text">Showing {{ $administrators->firstItem() }} to {{ $administrators->lastItem() }} of {{ $administrators->total() }} total administrators</span>
                            </div>
                            <div class="pagination-links">
                                {{ $administrators->appends(['search' => request('search'), 'per_page' => request('per_page')])->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-shield fa-4x text-muted mb-3"></i>
                            <p class="text-muted">
                                <span class="ar-text">لا يوجد مسؤولين</span>
                                <span class="en-text">No administrators found</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
b, strong {
    font-weight: bolder;
    padding-right: 10px;
}
.align-items-center {
    align-items: center !important;
    margin-right: -35px;
}

.stats-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stats-icon svg {
    width: 30px;
    height: 30px;
    color: white;
}

.stats-content {
    flex: 1;
}

.stats-value {
    font-size: 28px;
    font-weight: bold;
    color: #2d3748;
}

.stats-label {
    font-size: 13px;
    color: #718096;
    margin-top: 4px;
}

.admin-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.pagination-wrapper {
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f9fafb;
    border-radius: 0 0 12px 12px;
}

.pagination-info {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    font-size: 14px;
    font-weight: 500;
}

.pagination-info i {
    color: #3b82f6;
}

.pagination-links {
    display: flex;
    gap: 8px;
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
</style>

<script>
function deleteAdministrator(id) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "سيتم حذف المسؤول نهائياً!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/administrators/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire('تم الحذف!', response.message, 'success')
                        .then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire('خطأ!', xhr.responseJSON?.message || 'حدث خطأ أثناء الحذف', 'error');
                }
            });
        }
    });
}
</script>
@endsection
