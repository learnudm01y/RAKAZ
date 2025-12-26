@extends('admin.layouts.app')

@section('title', 'إضافة مستخدم جديد')

@section('page-title')
    <span class="ar-text">إضافة مستخدم جديد</span>
    <span class="en-text">Add New User</span>
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
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user-plus"></i> إضافة مستخدم جديد</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">الاسم *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">كلمة المرور *</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">تأكيد كلمة المرور *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="verified" class="form-check-input" id="verified" {{ old('verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="verified">تفعيل البريد الإلكتروني</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
