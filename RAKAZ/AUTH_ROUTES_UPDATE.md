# تحديث روابط تسجيل الدخول - Authentication Routes Update

## التغييرات المطبقة

### 1. روابط تسجيل دخول المستخدمين العاديين
- **قبل التحديث**: `http://127.0.0.1:8000/user/login`
- **بعد التحديث**: `http://127.0.0.1:8000/login`
- **اسم الـ Route**: `login` (للـ GET) و `user.login.submit` (للـ POST)

### 2. روابط تسجيل دخول الأدمن
- **قبل التحديث**: `http://127.0.0.1:8000/login`
- **بعد التحديث**: `http://127.0.0.1:8000/admin/login`
- **اسم الـ Route**: `admin.login`

---

## الملفات المحدثة

### Routes
1. **routes/web.php**
   - تم تغيير `/user/login` إلى `/login`
   - Route name: `login` (GET) و `user.login.submit` (POST)

2. **routes/auth.php**
   - تم تغيير `/login` إلى `/admin/login`
   - Route name: `admin.login`

### Middleware
3. **app/Http/Middleware/Authenticate.php**
   - تم إضافة منطق للتفريق بين admin و user
   - Admin routes تحول إلى `admin.login`
   - User routes تحول إلى `login`

4. **app/Http/Middleware/SaveIntendedUrl.php**
   - تم تحديث الروابط المستثناة
   - تم إضافة `admin/login` و `admin/register`
   - تم إزالة `user/login` و `user/register`

### Exception Handling
5. **app/Exceptions/Handler.php**
   - تم تحديث معالجة CSRF token mismatch
   - يوجه Admin إلى `admin.login`
   - يوجه Users إلى `login`

### Views - Frontend (User Pages)
6. **resources/views/layouts/app.blade.php**
   - تم تغيير `route('user.login')` إلى `route('login')`

7. **resources/views/frontend/shop.blade.php**
   - جميع الروابط تستخدم `route('login')` (لا تحتاج تحديث)

8. **resources/views/frontend/product-details.blade.php**
   - تستخدم `route('login')` (لا تحتاج تحديث)

9. **resources/views/test-wishlist.blade.php**
   - تستخدم `route('login')` (لا تحتاج تحديث)

10. **resources/views/wishlist-diagnostic.blade.php**
    - تستخدم `route('login')` (لا تحتاج تحديث)

### Views - Admin Pages
11. **resources/views/livewire/welcome/navigation.blade.php**
    - تم تغيير `route('login')` إلى `route('admin.login')`

12. **resources/views/livewire/pages/auth/register.blade.php**
    - تم تغيير `route('login')` إلى `route('admin.login')`

13. **resources/views/livewire/pages/auth/reset-password.blade.php**
    - تم تغيير `redirectRoute('login')` إلى `redirectRoute('admin.login')`

---

## قائمة الروابط النهائية

### User Routes (Frontend)
```
GET  /login                  -> login (تسجيل الدخول)
POST /login                  -> user.login.submit
POST /register               -> user.register.submit
POST /user/logout            -> user.logout
```

### Admin Routes (Dashboard)
```
GET  /admin/login            -> admin.login
GET  /admin/register         -> register
POST /logout                 -> logout (for admin)
GET  /dashboard              -> dashboard
```

---

## ملاحظات مهمة

### التوافقية مع الكود الحالي
✅ **route('login')** - يعمل بشكل صحيح للمستخدمين العاديين
✅ **route('admin.login')** - يعمل بشكل صحيح للأدمن
✅ **route('user.login.submit')** - لا يزال يعمل للـ POST requests
✅ **route('user.register.submit')** - لا يزال يعمل للتسجيل

### Middleware Logic
- عند الوصول إلى `/admin/*` أو `/dashboard` بدون تسجيل دخول → تحويل إلى `/admin/login`
- عند الوصول إلى أي صفحة أخرى بدون تسجيل دخول → تحويل إلى `/login`

### Session Management
- تم تحديث معالج CSRF Token Mismatch
- يتعرف على نوع المستخدم (admin أو user) ويوجه للصفحة المناسبة

---

## الاختبار

### للتحقق من الروابط
```bash
php artisan route:list --name=login
php artisan route:list --name=logout
```

### تنظيف الـ Cache
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

---

تاريخ التحديث: 25 ديسمبر 2025
