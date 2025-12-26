@extends('admin.layouts.app')

@section('title', 'تعديل مسؤول')

@section('page-title')
    <span class="ar-text">تعديل مسؤول</span>
    <span class="en-text">Edit Administrator</span>
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
            <h2 class="ar-text">تعديل مسؤول</h2>
            <h2 class="en-text">Edit Administrator</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.administrators.update', $admin->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <span class="ar-text">الاسم</span>
                                <span class="en-text">Name</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $admin->name) }}" required>
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
                                   id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <span class="ar-text">كلمة المرور الجديدة (اتركه فارغاً للحفاظ على القديمة)</span>
                                <span class="en-text">New Password (leave blank to keep current)</span>
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password">
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
                                   name="password_confirmation">
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="email_verified"
                                   name="email_verified" {{ $admin->email_verified_at ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_verified">
                                <span class="ar-text">تم التحقق من البريد الإلكتروني</span>
                                <span class="en-text">Email Verified</span>
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                <span class="ar-text">حفظ التغييرات</span>
                                <span class="en-text">Save Changes</span>
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
