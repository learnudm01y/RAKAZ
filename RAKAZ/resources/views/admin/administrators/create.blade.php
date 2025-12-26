@extends('admin.layouts.app')

@section('title', 'إضافة مسؤول')

@section('page-title')
    <span class="ar-text">إضافة مسؤول</span>
    <span class="en-text">Add Administrator</span>
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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="ar-text">إضافة مسؤول جديد</h2>
            <h2 class="en-text">Add New Administrator</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.administrators.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <span class="ar-text">الاسم</span>
                                <span class="en-text">Name</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <span class="ar-text">البريد الإلكتروني</span>
                                <span class="en-text">Email</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <span class="ar-text">كلمة المرور</span>
                                <span class="en-text">Password</span>
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                <span class="ar-text">تأكيد كلمة المرور</span>
                                <span class="en-text">Confirm Password</span>
                            </label>
                            <input type="password" class="form-control" id="password_confirmation"
                                   name="password_confirmation" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="email_verified"
                                   name="email_verified" checked>
                            <label class="form-check-label" for="email_verified">
                                <span class="ar-text">تفعيل البريد الإلكتروني تلقائياً</span>
                                <span class="en-text">Verify Email Automatically</span>
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                <span class="ar-text">حفظ</span>
                                <span class="en-text">Save</span>
                            </button>
                            <a href="{{ route('admin.administrators.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                <span class="ar-text">إلغاء</span>
                                <span class="en-text">Cancel</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
