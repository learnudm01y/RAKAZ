@extends('layouts.app')

@section('content')
@endsection
    <!-- 404 Content -->
    <main class="error-page">
        <div class="error-container">
            <div class="error-content">
                <div class="error-number">404</div>
                <h1 class="error-title">الصفحة غير موجودة</h1>
                <p class="error-description">عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها إلى موقع آخر.</p>
                <div class="error-actions">
                    <a href="index.html" class="btn-primary">العودة للرئيسية</a>
                    <a href="shop.html" class="btn-secondary">تصفح المنتجات</a>
                </div>
                <div class="error-suggestions">
                    <p class="suggestions-title">قد تكون مهتماً بـ:</p>
                    <div class="suggestions-links">
                        <a href="shop.html">المتجر</a>
                        <a href="index.html#new-arrivals">المنتجات الجديدة</a>
                        <a href="contact.html">اتصل بنا</a>
                        <a href="about.html">من نحن</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
