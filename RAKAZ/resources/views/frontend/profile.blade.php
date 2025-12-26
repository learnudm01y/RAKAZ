@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'الملف الشخصي' : 'My Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/custom-select.css') }}">
@endpush

@section('content')
<main class="profile-page">
    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="profile-info">
                <h1 class="profile-name">{{ $user->name }}</h1>
                <p class="profile-email">{{ $user->email }}</p>
                <p class="profile-member-since">
                    <span class="ar-text">عضو منذ {{ $user->created_at->format('Y/m/d') }}</span>
                    <span class="en-text">Member since {{ $user->created_at->format('M d, Y') }}</span>
                </p>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Profile Form -->
        <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
            @csrf

            <!-- Personal Information Section -->
            <div class="profile-section">
                <h2 class="section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="ar-text">المعلومات الشخصية</span>
                    <span class="en-text">Personal Information</span>
                </h2>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">
                            <span class="ar-text">الاسم الكامل</span>
                            <span class="en-text">Full Name</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <span class="ar-text">البريد الإلكتروني</span>
                            <span class="en-text">Email Address</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <span class="ar-text">رقم الهاتف</span>
                            <span class="en-text">Phone Number</span>
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="{{ app()->getLocale() == 'ar' ? '+971 50 000 0000' : '+971 50 000 0000' }}">
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Delivery Address Section -->
            <div class="profile-section">
                <h2 class="section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span class="ar-text">عنوان التوصيل</span>
                    <span class="en-text">Delivery Address</span>
                </h2>

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="address">
                            <span class="ar-text">العنوان التفصيلي</span>
                            <span class="en-text">Street Address</span>
                        </label>
                        <textarea id="address" name="address" rows="3" placeholder="{{ app()->getLocale() == 'ar' ? 'الشارع، رقم المبنى، الشقة...' : 'Street, Building number, Apartment...' }}">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="city">
                            <span class="ar-text">المدينة</span>
                            <span class="en-text">City</span>
                        </label>
                        <input type="text" id="city" name="city" value="{{ old('city', $user->city) }}" placeholder="{{ app()->getLocale() == 'ar' ? 'دبي' : 'Dubai' }}">
                        @error('city')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="state">
                            <span class="ar-text">الإمارة / المنطقة</span>
                            <span class="en-text">State / Region</span>
                        </label>
                        <input type="text" id="state" name="state" value="{{ old('state', $user->state) }}" placeholder="{{ app()->getLocale() == 'ar' ? 'دبي' : 'Dubai' }}">
                        @error('state')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="country">
                            <span class="ar-text">الدولة</span>
                            <span class="en-text">Country</span>
                        </label>
                        <select id="country" name="country" class="custom-select">
                            <option value="">{{ app()->getLocale() == 'ar' ? 'اختر الدولة' : 'Select Country' }}</option>
                            <option value="UAE" {{ old('country', $user->country) == 'UAE' ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? 'الإمارات العربية المتحدة' : 'United Arab Emirates' }}
                            </option>
                            <option value="SA" {{ old('country', $user->country) == 'SA' ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? 'المملكة العربية السعودية' : 'Saudi Arabia' }}
                            </option>
                            <option value="KW" {{ old('country', $user->country) == 'KW' ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? 'الكويت' : 'Kuwait' }}
                            </option>
                            <option value="BH" {{ old('country', $user->country) == 'BH' ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? 'البحرين' : 'Bahrain' }}
                            </option>
                            <option value="QA" {{ old('country', $user->country) == 'QA' ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? 'قطر' : 'Qatar' }}
                            </option>
                            <option value="OM" {{ old('country', $user->country) == 'OM' ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? 'عمان' : 'Oman' }}
                            </option>
                        </select>
                        @error('country')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="postal_code">
                            <span class="ar-text">الرمز البريدي</span>
                            <span class="en-text">Postal Code</span>
                        </label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" placeholder="{{ app()->getLocale() == 'ar' ? '00000' : '00000' }}">
                        @error('postal_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    <span class="ar-text">حفظ التغييرات</span>
                    <span class="en-text">Save Changes</span>
                </button>
            </div>
        </form>

        <!-- Quick Links -->
        <div class="profile-quick-links">
            <h3 class="quick-links-title">
                <span class="ar-text">روابط سريعة</span>
                <span class="en-text">Quick Links</span>
            </h3>
            <div class="quick-links-grid">
                <a href="{{ route('orders.index') }}" class="quick-link-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    </svg>
                    <span class="ar-text">طلباتي</span>
                    <span class="en-text">My Orders</span>
                </a>
                <a href="{{ route('wishlist') }}" class="quick-link-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    <span class="ar-text">المفضلة</span>
                    <span class="en-text">Wishlist</span>
                </a>
                <a href="{{ route('orders.track') }}" class="quick-link-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span class="ar-text">تتبع الطلب</span>
                    <span class="en-text">Track Order</span>
                </a>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('user.logout') }}" id="logoutForm" style="width: 100%;">
                    @csrf
                    <button type="submit" class="quick-link-card logout-btn" style="border: none; cursor: pointer; width: 100%; text-align: center; background: #fff; transition: all 0.3s ease;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span class="ar-text" style="color: #dc2626; font-weight: 600;">تسجيل الخروج</span>
                        <span class="en-text" style="color: #dc2626; font-weight: 600;">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

@push('styles')
<style>
    .logout-btn:hover {
        background: #fef2f2 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15) !important;
    }

    .logout-btn:active {
        transform: translateY(0);
    }

    .logout-btn svg {
        transition: transform 0.3s ease;
    }

    .logout-btn:hover svg {
        transform: translateX({{ app()->getLocale() == 'ar' ? '-' : '' }}5px);
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/custom-select.js') }}"></script>
@endpush
@endsection
