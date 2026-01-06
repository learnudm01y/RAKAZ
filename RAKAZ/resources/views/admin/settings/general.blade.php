@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إعدادات عامة' : 'General Settings')

@section('page-title')
    <span class="ar-text">إعدادات عامة</span>
    <span class="en-text">General Settings</span>
@endsection

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.settings.general.update') }}" method="POST">
        @csrf

        <!-- Tax Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                    </svg>
                    <span class="ar-text">إعدادات الضريبة</span>
                    <span class="en-text">Tax Settings</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <span class="ar-text">تفعيل الضريبة</span>
                            <span class="en-text">Enable Tax</span>
                        </label>
                        <!-- Hidden field BEFORE checkbox to send 0 when unchecked -->
                        <input type="hidden" name="tax_enabled" value="0">
                        <div class="form-check form-switch">
                            <input class="form-check-input tax-switch" type="checkbox" id="tax_enabled" name="tax_enabled" value="1" {{ $settings['tax_enabled'] == '1' ? 'checked' : '' }}>
                            <label class="form-check-label ms-2" for="tax_enabled" id="tax_enabled_label">
                                <span class="ar-text">{{ $settings['tax_enabled'] == '1' ? 'مفعّل' : 'معطّل' }}</span>
                                <span class="en-text">{{ $settings['tax_enabled'] == '1' ? 'Enabled' : 'Disabled' }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tax_rate" class="form-label">
                            <span class="ar-text">نسبة الضريبة (%)</span>
                            <span class="en-text">Tax Rate (%)</span>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('tax_rate') is-invalid @enderror"
                                   id="tax_rate" name="tax_rate" value="{{ old('tax_rate', $settings['tax_rate']) }}"
                                   min="0" max="100" step="0.01">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('tax_rate')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <span class="ar-text">أدخل النسبة المئوية للضريبة (مثال: 5 تعني 5%)</span>
                            <span class="en-text">Enter the tax percentage (e.g., 5 means 5%)</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="ar-text">إعدادات العملة</span>
                    <span class="en-text">Currency Settings</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="currency_ar" class="form-label">
                            <span class="ar-text">رمز العملة (عربي)</span>
                            <span class="en-text">Currency Symbol (Arabic)</span>
                        </label>
                        <input type="text" class="form-control @error('currency_ar') is-invalid @enderror"
                               id="currency_ar" name="currency_ar" value="{{ old('currency_ar', $settings['currency_ar']) }}">
                        @error('currency_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="currency_en" class="form-label">
                            <span class="ar-text">رمز العملة (إنجليزي)</span>
                            <span class="en-text">Currency Symbol (English)</span>
                        </label>
                        <input type="text" class="form-control @error('currency_en') is-invalid @enderror"
                               id="currency_en" name="currency_en" value="{{ old('currency_en', $settings['currency_en']) }}">
                        @error('currency_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                    <span class="ar-text">إعدادات الشحن</span>
                    <span class="en-text">Shipping Settings</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="default_shipping_cost" class="form-label">
                            <span class="ar-text">تكلفة الشحن الافتراضية</span>
                            <span class="en-text">Default Shipping Cost</span>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('default_shipping_cost') is-invalid @enderror"
                                   id="default_shipping_cost" name="default_shipping_cost"
                                   value="{{ old('default_shipping_cost', $settings['default_shipping_cost']) }}"
                                   min="0" step="0.01">
                            <span class="input-group-text">{{ $settings['currency_ar'] }}</span>
                        </div>
                        @error('default_shipping_cost')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="free_shipping_threshold" class="form-label">
                            <span class="ar-text">الحد الأدنى للشحن المجاني</span>
                            <span class="en-text">Free Shipping Threshold</span>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('free_shipping_threshold') is-invalid @enderror"
                                   id="free_shipping_threshold" name="free_shipping_threshold"
                                   value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold']) }}"
                                   min="0" step="0.01">
                            <span class="input-group-text">{{ $settings['currency_ar'] }}</span>
                        </div>
                        @error('free_shipping_threshold')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <span class="ar-text">اترك 0 لتعطيل الشحن المجاني</span>
                            <span class="en-text">Leave 0 to disable free shipping</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="ar-text">حفظ الإعدادات</span>
                <span class="en-text">Save Settings</span>
            </button>
        </div>
    </form>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.5rem;
    }
    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.25rem;
    }
    .card-header h5 {
        display: flex;
        align-items: center;
        color: #495057;
    }
    /* Custom Switch Styling */
    .form-switch {
        padding-left: 3.5em;
    }
    .form-switch .form-check-input.tax-switch {
        width: 50px;
        height: 26px;
        margin-left: -3.5em;
        cursor: pointer;
        background-color: #dc3545;
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, background-position 0.15s ease-in-out;
    }
    .form-switch .form-check-input.tax-switch:checked {
        background-color: #198754;
        border-color: #198754;
        background-position: right center;
    }
    .form-switch .form-check-input.tax-switch:focus {
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        border-color: #198754;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #ced4da;
    }
</style>

<script>
    // Handle label text update
    document.getElementById('tax_enabled').addEventListener('change', function() {
        const label = document.getElementById('tax_enabled_label');
        const arText = label.querySelector('.ar-text');
        const enText = label.querySelector('.en-text');

        if (this.checked) {
            arText.textContent = 'مفعّل';
            enText.textContent = 'Enabled';
        } else {
            arText.textContent = 'معطّل';
            enText.textContent = 'Disabled';
        }
    });
</script>
@endsection
