@extends('admin.layouts.app')

@section('title', 'تفاصيل المسؤول')

@section('page-title')
    <span class="ar-text">تفاصيل المسؤول</span>
    <span class="en-text">Administrator Details</span>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="ar-text">تفاصيل المسؤول</h2>
                <h2 class="en-text">Administrator Details</h2>
            </div>
            <div>
                <a href="{{ route('admin.administrators.edit', $admin->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    <span class="ar-text">تعديل</span>
                    <span class="en-text">Edit</span>
                </a>
                <a href="{{ route('admin.administrators.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i>
                    <span class="ar-text">رجوع</span>
                    <span class="en-text">Back</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="admin-avatar-large">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                    </div>
                    <h4>{{ $admin->name }}</h4>
                    <p class="text-muted">{{ $admin->email }}</p>
                    @if($admin->email_verified_at)
                        <span class="badge bg-success mb-2">
                            <i class="fas fa-check-circle"></i>
                            <span class="ar-text">تم التحقق</span>
                            <span class="en-text">Verified</span>
                        </span>
                    @else
                        <span class="badge bg-warning mb-2">
                            <i class="fas fa-exclamation-circle"></i>
                            <span class="ar-text">غير محقق</span>
                            <span class="en-text">Not Verified</span>
                        </span>
                    @endif

                    @if(auth()->id() == $admin->id)
                        <div class="mt-2">
                            <span class="badge bg-primary">
                                <i class="fas fa-user"></i>
                                <span class="ar-text">أنت</span>
                                <span class="en-text">You</span>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <span class="ar-text">معلومات الحساب</span>
                        <span class="en-text">Account Information</span>
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <div class="info-item">
                                <div class="info-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div class="info-content">
                                    <small class="text-muted">
                                        <span class="ar-text">الدور</span>
                                        <span class="en-text">Role</span>
                                    </small>
                                    <div class="fw-bold">
                                        <span class="ar-text">مسؤول</span>
                                        <span class="en-text">Administrator</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="info-item">
                                <div class="info-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="info-content">
                                    <small class="text-muted">
                                        <span class="ar-text">تاريخ الإضافة</span>
                                        <span class="en-text">Created At</span>
                                    </small>
                                    <div class="fw-bold">{{ $admin->created_at->format('Y-m-d H:i') }}</div>
                                    <small class="text-muted">{{ $admin->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="info-item">
                                <div class="info-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="info-content">
                                    <small class="text-muted">
                                        <span class="ar-text">آخر تحديث</span>
                                        <span class="en-text">Last Updated</span>
                                    </small>
                                    <div class="fw-bold">{{ $admin->updated_at->format('Y-m-d H:i') }}</div>
                                    <small class="text-muted">{{ $admin->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </li>
                        @if($admin->email_verified_at)
                            <li>
                                <div class="info-item">
                                    <div class="info-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="info-content">
                                        <small class="text-muted">
                                            <span class="ar-text">تاريخ التحقق</span>
                                            <span class="en-text">Verified At</span>
                                        </small>
                                        <div class="fw-bold">{{ $admin->email_verified_at->format('Y-m-d H:i') }}</div>
                                        <small class="text-muted">{{ $admin->email_verified_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <span class="ar-text">الصلاحيات والأنشطة</span>
                        <span class="en-text">Permissions & Activities</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span class="ar-text">هذا المسؤول لديه صلاحيات كاملة لإدارة النظام</span>
                        <span class="en-text">This administrator has full system management permissions</span>
                    </div>

                    <h6 class="mt-4 mb-3">
                        <span class="ar-text">الصلاحيات المتاحة:</span>
                        <span class="en-text">Available Permissions:</span>
                    </h6>
                    <ul class="permissions-list">
                        <li>
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="ar-text">إدارة المستخدمين والعملاء</span>
                            <span class="en-text">Manage Users & Customers</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="ar-text">إدارة المنتجات والتصنيفات</span>
                            <span class="en-text">Manage Products & Categories</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="ar-text">إدارة الطلبات</span>
                            <span class="en-text">Manage Orders</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="ar-text">إدارة الصفحات والقوائم</span>
                            <span class="en-text">Manage Pages & Menus</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="ar-text">إعدادات الموقع</span>
                            <span class="en-text">Site Settings</span>
                        </li>
                    </ul>
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

.admin-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: bold;
    margin: 0 auto;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.info-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.permissions-list {
    list-style: none;
    padding-left: 0;
}

.permissions-list li {
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.permissions-list li:last-child {
    border-bottom: none;
}

.permissions-list i {
    margin-right: 10px;
}
</style>
@endsection
