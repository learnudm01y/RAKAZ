# هيكلية لوحة التحكم - Admin Dashboard Structure

## الملفات الرئيسية

### 1. Layout الأساسي
- **الموقع**: `resources/views/admin/layouts/app.blade.php`
- **الوظيفة**: القالب الأساسي الذي يحتوي على:
  - HTML Head مع CSS الأساسية
  - Topbar (شريط علوي)
  - محتوى الصفحة
  - Scripts الأساسية
- **يستخدم**: `@yield`, `@stack`, `@include`

### 2. Sidebar القائمة الجانبية
- **الموقع**: `resources/views/admin/partials/sidebar.blade.php`
- **الوظيفة**: القائمة الجانبية الموحدة لجميع الصفحات
- **المحتويات**:
  - روابط Dashboard
  - روابط إدارة المحتوى (Pages, About)
  - روابط إدارة المنتجات
  - روابط إدارة الطلبات
  - روابط إدارة المستخدمين
  - الإعدادات
- **يتم تضمينه في**: `admin/layouts/app.blade.php` باستخدام `@include`

## كيفية استخدام الهيكلية الجديدة

### إنشاء صفحة جديدة في لوحة التحكم

```blade
@extends('admin.layouts.app')

@section('title', 'عنوان الصفحة')

@section('page-title')
    <span class="ar-text">عنوان الصفحة بالعربي</span>
    <span class="en-text">Page Title in English</span>
@endsection

@push('styles')
<style>
    /* CSS خاص بهذه الصفحة فقط */
    .custom-class {
        color: red;
    }
</style>
@endpush

@section('content')
    <!-- محتوى الصفحة هنا -->
    <div class="container">
        <h1>مرحباً بك!</h1>
    </div>
@endsection

@push('scripts')
<script>
    // JavaScript خاص بهذه الصفحة فقط
    console.log('Page loaded');
</script>
@endpush
```

## المزايا

### ✅ مركزية الكود
- Sidebar موحد في مكان واحد
- تغيير واحد يطبق على جميع الصفحات
- سهولة الصيانة

### ✅ استخدام صحيح لـ Blade
- `@extends` للوراثة
- `@yield` للمحتوى المتغير
- `@section` لتعريف المحتوى
- `@push` و `@stack` للـ CSS والـ JavaScript
- `@include` لتضمين الـ partials

### ✅ فصل المسؤوليات
- Layout أساسي منفصل
- Sidebar منفصل
- كل صفحة تحتوي على CSS و JS الخاص بها فقط

### ✅ سهولة التوسع
- إضافة روابط جديدة للـ Sidebar بسهولة
- إضافة صفحات جديدة بدون تكرار الكود
- إمكانية إنشاء layouts مختلفة إذا لزم الأمر

## الصفحات المحدثة

- ✅ `dashboard.blade.php` - الصفحة الرئيسية
- ✅ `admin/pages/index.blade.php` - قائمة الصفحات
- ✅ `admin/pages/create.blade.php` - إنشاء صفحة جديدة
- ✅ `admin/pages/edit.blade.php` - تعديل صفحة
- ✅ `admin/about/edit.blade.php` - تعديل صفحة من نحن

## الملفات القديمة (للحذف لاحقاً)

- `admin/layout.blade.php.old` - النسخة القديمة من layout
- `dashboard.blade.php.backup` - النسخة القديمة من dashboard

## ملاحظات مهمة

1. **الـ Sidebar يظهر في جميع الصفحات** - لأنه محمّل في الـ layout الأساسي
2. **@push و @stack** - تستخدم لإضافة CSS و JS خاصة بكل صفحة
3. **الروابط النشطة** - تتحدد تلقائياً باستخدام `request()->routeIs()`
4. **الـ RTL/LTR** - يعمل تلقائياً مع كل الصفحات
5. **الـ Sidebar Collapse** - يحفظ الحالة في localStorage

## التحديثات المستقبلية

- [ ] إضافة صفحات Products
- [ ] إضافة صفحات Orders
- [ ] إضافة صفحات Users
- [ ] إضافة Settings page
- [ ] تحسين الـ Responsive design
