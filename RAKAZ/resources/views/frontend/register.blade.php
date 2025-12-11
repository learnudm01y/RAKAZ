
@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="/assets/css/auth.css">
@endpush

@section('content')
    <div class="auth-page">
        <div class="auth-container">
            <!-- Header -->
            <div class="auth-header">
                <a href="{{ route('home') }}" class="auth-logo">
                    <img src="/assets/images/ركاز بني copy (1).png" alt="ركاز">
                </a>
                <div class="auth-nav">
                    <a href="{{ route('home') }}" class="close-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </a>
                    <span class="auth-lang">حسابي</span>
                    <a href="{{ route('cart') }}" class="cart-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span class="badge">0</span>
                    </a>
                </div>
            </div>

            <!-- Register Form -->
            <div class="auth-content">
                <div class="auth-form-wrapper">
                    <h1 class="auth-title">إنشاء حساب</h1>
                    <p class="auth-subtitle">املأ البيانات لإنشاء حساب جديد</p>

                    <form class="auth-form" id="registerForm">
                        <div class="form-group">
                            <input type="text" id="name" name="name" placeholder="الاسم الكامل" required>
                        </div>

                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="البريد الإلكتروني" required>
                        </div>

                        <div class="form-group">
                            <input type="password" id="password" name="password" placeholder="كلمة المرور" required>
                        </div>

                        <div class="form-group">
                            <input type="password" id="confirm-password" name="confirm-password" placeholder="تأكيد كلمة المرور" required>
                        </div>

                        <button type="submit" class="btn-submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                            <span>إنشاء حساب</span>
                        </button>

                        <div class="auth-divider">
                            <span>لديك حساب بالفعل؟</span>
                        </div>

                        <button type="button" class="btn-secondary" onclick="window.location.href='login.html'">
                            <span>تسجيل الدخول</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                alert('كلمة المرور غير متطابقة');
                return;
            }

            alert('تم إنشاء الحساب بنجاح');
            window.location.href = 'login.html';
        });
    </script>
@endpush
