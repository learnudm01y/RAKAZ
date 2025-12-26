@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="/assets/css/auth.css">
@endpush

@section('content')
    <!-- Auth Page Content -->
    <main class="auth-page">
        <div class="auth-container">
            <!-- Login Form -->
            <div class="auth-box" id="loginBox">
                <h2 class="auth-title">تسجيل الدخول</h2>
                <p class="auth-subtitle">مرحباً بعودتك!</p>

                <form class="auth-form" id="loginForm" action="{{ route('user.login.submit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="loginEmail">البريد الإلكتروني</label>
                        <input type="email" id="loginEmail" name="email" placeholder="example@email.com" required value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="loginPassword">كلمة المرور</label>
                        <div class="password-input">
                            <input type="password" id="loginPassword" name="password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('loginPassword')">
                                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember">
                            <span>تذكرني</span>
                        </label>
                        <a href="#" class="forgot-link">نسيت كلمة المرور؟</a>
                    </div>

                    <button type="submit" class="auth-btn">تسجيل الدخول</button>

                    <div class="divider">
                        <span>أو</span>
                    </div>

                    <div class="social-login">
                        <button type="button" class="social-btn google-btn">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <span>تسجيل الدخول عبر Google</span>
                        </button>

                        <button type="button" class="social-btn apple-btn">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.05 20.28c-.98.95-2.05.88-3.08.4-1.09-.5-2.08-.48-3.24 0-1.44.62-2.2.44-3.06-.4C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                            </svg>
                            <span>تسجيل الدخول عبر Apple</span>
                        </button>
                    </div>

                    <div class="auth-switch">
                        <p>ليس لديك حساب؟ <a href="#" onclick="switchToRegister(event)">إنشاء حساب جديد</a></p>
                    </div>
                </form>
            </div>

            <!-- Register Form -->
            <div class="auth-box" id="registerBox" style="display: none;">
                <h2 class="auth-title">إنشاء حساب جديد</h2>
                <p class="auth-subtitle">انضم إلى عائلة ركاز</p>

                <form class="auth-form" id="registerForm" action="{{ route('user.register.submit') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">الاسم الأول *</label>
                            <input type="text" id="firstName" name="firstName" required value="{{ old('firstName') }}">
                        </div>
                        <div class="form-group">
                            <label for="lastName">الاسم الأخير *</label>
                            <input type="text" id="lastName" name="lastName" required value="{{ old('lastName') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="registerEmail">البريد الإلكتروني *</label>
                        <input type="email" id="registerEmail" name="email" placeholder="example@email.com" required value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="phone">رقم الهاتف *</label>
                        <input type="tel" id="phone" name="phone" placeholder="+971 50 123 4567" required value="{{ old('phone') }}">
                    </div>

                    <div class="form-group">
                        <label for="registerPassword">كلمة المرور *</label>
                        <div class="password-input">
                            <input type="password" id="registerPassword" name="password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('registerPassword')">
                                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                        <small class="form-hint">يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل</small>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">تأكيد كلمة المرور *</label>
                        <div class="password-input">
                            <input type="password" id="confirmPassword" name="password_confirmation" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="newsletter">
                            <span>أرغب في تلقي العروض والأخبار عبر البريد الإلكتروني</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms" required>
                            <span>أوافق على <a href="#">الشروط والأحكام</a> و <a href="{{ route('privacy.policy') }}">سياسة الخصوصية</a></span>
                        </label>
                    </div>

                    <button type="submit" class="auth-btn">إنشاء الحساب</button>

                    <div class="divider">
                        <span>أو</span>
                    </div>

                    <div class="social-login">
                        <button type="button" class="social-btn google-btn">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <span>التسجيل عبر Google</span>
                        </button>

                        <button type="button" class="social-btn apple-btn">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.05 20.28c-.98.95-2.05.88-3.08.4-1.09-.5-2.08-.48-3.24 0-1.44.62-2.2.44-3.06-.4C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                            </svg>
                            <span>التسجيل عبر Apple</span>
                        </button>
                    </div>

                    <div class="auth-switch">
                        <p>لديك حساب بالفعل؟ <a href="#" onclick="switchToLogin(event)">تسجيل الدخول</a></p>
                    </div>
                </form>
            </div>
        </div>
    </main>

@endsection

@push('scripts')

    <script>
        // Check URL parameter on page load to show correct form
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const mode = urlParams.get('mode');

            if (mode === 'register') {
                switchToRegister(new Event('load'));
            } else {
                // Default to login
                document.getElementById('loginBox').style.display = 'block';
                document.getElementById('registerBox').style.display = 'none';
            }
        });

        function switchToRegister(e) {
            e.preventDefault();
            document.getElementById('loginBox').style.display = 'none';
            document.getElementById('registerBox').style.display = 'block';
            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('mode', 'register');
            window.history.pushState({}, '', url);
        }

        function switchToLogin(e) {
            e.preventDefault();
            document.getElementById('registerBox').style.display = 'none';
            document.getElementById('loginBox').style.display = 'block';
            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('mode', 'login');
            window.history.pushState({}, '', url);
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        // Handle Login Form
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = 'جاري التسجيل...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(async data => {
                if (data.success) {
                    console.log('✅ Login successful');

                    // التحقق من وجود منتجات معلقة في localStorage
                    const STORAGE_KEY = 'rakaz_pending_wishlist';
                    const pendingWishlist = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');

                    console.log('🔍 Checking for pending wishlist items...');
                    console.log('📦 Found items:', pendingWishlist);

                    if (pendingWishlist.length > 0) {
                        // توجيه المستخدم مباشرة للمفضلة - سيتم الحفظ هناك تلقائياً
                        console.log('🔄 Redirecting to wishlist page for auto-save...');
                        Swal.fire({
                            title: 'تم تسجيل الدخول بنجاح!',
                            text: 'جاري حفظ المنتجات في المفضلة...',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('wishlist') }}";
                        });
                        return;
                    }

                    // إذا لم يكن هناك منتجات معلقة، انتقل للصفحة الافتراضية
                    Swal.fire({
                        title: 'تم تسجيل الدخول بنجاح!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#1a1a1a'
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'خطأ',
                    text: error.message || 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
                    icon: 'error',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#1a1a1a'
                });
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });

        // Handle Register Form
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (password !== confirmPassword) {
                Swal.fire({
                    title: 'خطأ',
                    text: 'كلمة المرور غير متطابقة',
                    icon: 'error',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#1a1a1a'
                });
                return;
            }

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = 'جاري إنشاء الحساب...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(async data => {
                if (data.success) {
                    console.log('✅ Registration successful');

                    // التحقق من وجود منتجات معلقة في localStorage
                    const STORAGE_KEY = 'rakaz_pending_wishlist';
                    const pendingWishlist = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');

                    console.log('🔍 Checking for pending wishlist items...');
                    console.log('📦 Found items:', pendingWishlist);

                    if (pendingWishlist.length > 0) {
                        // توجيه المستخدم مباشرة للمفضلة - سيتم الحفظ هناك تلقائياً
                        console.log('🔄 Redirecting to wishlist page for auto-save...');
                        Swal.fire({
                            title: 'تم إنشاء الحساب بنجاح!',
                            text: 'جاري حفظ المنتجات في المفضلة...',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('wishlist') }}";
                        });
                        return;
                    }

                    // إذا لم يكن هناك منتجات معلقة، انتقل للصفحة الافتراضية
                    Swal.fire({
                        title: 'تم إنشاء الحساب بنجاح!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#1a1a1a'
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                let errorMessage = 'حدث خطأ أثناء إنشاء الحساب';

                if (error.message) {
                    errorMessage = error.message;
                }

                Swal.fire({
                    title: 'خطأ',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#1a1a1a'
                });
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    </script>
@endpush
