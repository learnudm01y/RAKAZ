# نظام الذاكرة المؤقتة للمفضلة - Pending Wishlist System

## الميزة
عند محاولة المستخدم إضافة منتج إلى المفضلة دون تسجيل الدخول، يتم حفظ المنتج في ذاكرة مؤقتة (session)، وبعد تسجيل الدخول يتم حفظه تلقائياً في قائمة الأمنيات.

---

## كيف يعمل النظام؟

### 1. عند محاولة الإضافة دون تسجيل دخول
```javascript
// في shop.blade.php
User clicks wishlist button → 
  Request sent to /wishlist/toggle →
    User not logged in →
      Product ID stored in session['pending_wishlist'] →
        Show login prompt
```

### 2. عند تسجيل الدخول
```php
// في UserAuthController
User logs in successfully →
  savePendingWishlist() called →
    Get session['pending_wishlist'] →
      Loop through product IDs →
        Add each to database wishlist →
          Clear session['pending_wishlist']
```

### 3. عند التسجيل كمستخدم جديد
```php
// في UserAuthController
User registers successfully →
  Auth::login($user) →
    savePendingWishlist() called →
      Same process as login
```

---

## الملفات المعدلة

### 1. WishlistController.php
**الموقع**: `app/Http/Controllers/WishlistController.php`

**التعديلات**:
```php
public function toggle(Request $request)
{
    // التحقق من validation أولاً
    $request->validate([
        'product_id' => 'required|exists:products,id',
    ]);

    // إذا لم يكن مسجل دخول
    if (!auth()->check()) {
        // جلب المصفوفة المؤقتة أو إنشاء واحدة فارغة
        $pendingWishlist = session()->get('pending_wishlist', []);
        
        // إضافة المنتج إذا لم يكن موجوداً
        if (!in_array($request->product_id, $pendingWishlist)) {
            $pendingWishlist[] = $request->product_id;
            session()->put('pending_wishlist', $pendingWishlist);
        }

        // إرجاع استجابة تطلب تسجيل الدخول
        return response()->json([
            'success' => false,
            'requiresAuth' => true,  // مهم للـ JavaScript
            'message' => 'يجب تسجيل الدخول أولاً',
        ], 401);
    }

    // إذا كان مسجل دخول - العملية العادية
    $isAdded = Wishlist::toggle(auth()->id(), $request->product_id);
    // ...
}
```

**الفرق الرئيسي**:
- ✅ تم نقل `validate()` قبل التحقق من المصادقة
- ✅ تخزين في session بدلاً من الرفض المباشر
- ✅ إضافة `requiresAuth: true` في الاستجابة

---

### 2. UserAuthController.php
**الموقع**: `app/Http/Controllers/Auth/UserAuthController.php`

**التعديلات الرئيسية**:

#### أ) دالة login()
```php
if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
    $request->session()->regenerate();

    // ✨ جديد: حفظ المنتجات المعلقة
    $this->savePendingWishlist();

    $intendedUrl = session()->pull('url.intended', route('home'));
    
    return response()->json([
        'success' => true,
        'message' => 'تم تسجيل الدخول بنجاح',
        'redirect' => $intendedUrl
    ]);
}
```

#### ب) دالة register()
```php
Auth::login($user);

// ✨ جديد: حفظ المنتجات المعلقة
$this->savePendingWishlist();

$intendedUrl = session()->pull('url.intended', route('home'));
```

#### ج) دالة جديدة: savePendingWishlist()
```php
protected function savePendingWishlist()
{
    // جلب المنتجات المعلقة من الـ session
    $pendingWishlist = session()->get('pending_wishlist', []);
    
    // التحقق من وجود منتجات وتسجيل دخول
    if (!empty($pendingWishlist) && auth()->check()) {
        foreach ($pendingWishlist as $productId) {
            try {
                // التحقق من وجود المنتج
                if (\App\Models\Product::find($productId)) {
                    // إضافة للمفضلة (أو تجاهل إذا موجود)
                    \App\Models\Wishlist::firstOrCreate([
                        'user_id' => auth()->id(),
                        'product_id' => $productId,
                    ]);
                }
            } catch (\Exception $e) {
                // تسجيل الخطأ لكن استمر مع المنتجات الأخرى
                Log::error('Failed to save pending wishlist item: ' . $e->getMessage());
            }
        }
        
        // حذف المصفوفة المؤقتة من الـ session
        session()->forget('pending_wishlist');
    }
}
```

**الميزات**:
- ✅ معالجة الأخطاء لكل منتج على حدة
- ✅ استخدام `firstOrCreate` لتجنب التكرار
- ✅ التحقق من وجود المنتج قبل الإضافة
- ✅ تسجيل الأخطاء في الـ logs
- ✅ حذف الـ session بعد الحفظ الناجح

---

## سيناريوهات الاستخدام

### السيناريو 1: مستخدم غير مسجل يضيف منتج واحد
```
1. User clicks ❤️ on Product A
2. Alert: "يجب تسجيل الدخول أولاً"
3. session['pending_wishlist'] = [101]
4. User clicks "تسجيل الدخول الآن"
5. User logs in successfully
6. Product A automatically added to wishlist
7. session['pending_wishlist'] deleted
```

### السيناريو 2: مستخدم يضيف عدة منتجات
```
1. User clicks ❤️ on Product A
2. session['pending_wishlist'] = [101]
3. User clicks ❤️ on Product B
4. session['pending_wishlist'] = [101, 202]
5. User clicks ❤️ on Product C
6. session['pending_wishlist'] = [101, 202, 303]
7. User logs in
8. All 3 products added to wishlist
9. session cleared
```

### السيناريو 3: مستخدم يضيف نفس المنتج مرتين
```
1. User clicks ❤️ on Product A
2. session['pending_wishlist'] = [101]
3. User clicks ❤️ on Product A again
4. session['pending_wishlist'] = [101]  (لا تكرار)
5. User logs in
6. Product A added once only
```

### السيناريو 4: التسجيل كمستخدم جديد
```
1. User clicks ❤️ on Product A
2. session['pending_wishlist'] = [101]
3. User clicks "إنشاء حساب جديد"
4. User completes registration
5. Auto login + Product A added to wishlist
6. Welcome to Rakaz! ✨
```

---

## الأمان والحماية

### 1. التحقق من صحة البيانات
```php
// في WishlistController
$request->validate([
    'product_id' => 'required|exists:products,id',
]);
```
✅ يضمن أن product_id موجود في قاعدة البيانات

### 2. منع التكرار في Session
```php
if (!in_array($request->product_id, $pendingWishlist)) {
    $pendingWishlist[] = $request->product_id;
}
```
✅ يمنع إضافة نفس المنتج مرتين في الـ session

### 3. منع التكرار في Database
```php
\App\Models\Wishlist::firstOrCreate([
    'user_id' => auth()->id(),
    'product_id' => $productId,
]);
```
✅ يستخدم `firstOrCreate` لتجنب الإدخال المكرر

### 4. معالجة الأخطاء
```php
try {
    // عملية الحفظ
} catch (\Exception $e) {
    Log::error('Failed to save pending wishlist item: ' . $e->getMessage());
}
```
✅ لا يتوقف النظام إذا فشل منتج واحد

---

## اختبار النظام

### الاختبار اليدوي

#### 1. اختبار الإضافة قبل تسجيل الدخول
```bash
# افتح المتصفح
http://127.0.0.1:8000/shop

# انقر على زر المفضلة لأي منتج
# يجب أن ترى:
- ✅ Alert: "يجب تسجيل الدخول أولاً"
- ✅ زر "تسجيل الدخول الآن"

# تحقق من الـ session
php artisan tinker
>>> session()->all()
```

#### 2. اختبار الحفظ بعد تسجيل الدخول
```bash
# سجل الدخول
# انتظر إعادة التوجيه
# تحقق من قاعدة البيانات

php artisan tinker
>>> \App\Models\Wishlist::where('user_id', 1)->get()
```

#### 3. اختبار التسجيل الجديد
```bash
# أضف منتج للمفضلة (دون تسجيل)
# انقر "إنشاء حساب جديد"
# أكمل النموذج
# يجب أن يُحفظ المنتج تلقائياً
```

### فحص الـ Session
```bash
php artisan tinker
>>> session()->get('pending_wishlist')
# يجب أن ترى: [101, 202, 303]
```

### فحص قاعدة البيانات
```sql
SELECT * FROM wishlists WHERE user_id = 1;
```

---

## الصيانة والتطوير المستقبلي

### إضافة تنبيه بعد تسجيل الدخول
يمكن إضافة رسالة تنبيه للمستخدم:
```php
// في savePendingWishlist()
if (!empty($pendingWishlist)) {
    $count = count($pendingWishlist);
    session()->flash('success', "تمت إضافة {$count} منتج إلى المفضلة");
}
```

### تنظيف الـ Session تلقائياً
يمكن إضافة middleware لتنظيف الـ sessions القديمة:
```php
// في Kernel.php
'web' => [
    // ...
    \App\Http\Middleware\CleanOldSessions::class,
],
```

### إضافة limit للمنتجات
لمنع إساءة الاستخدام:
```php
if (count($pendingWishlist) >= 50) {
    return response()->json([
        'success' => false,
        'message' => 'تم الوصول للحد الأقصى',
    ], 429);
}
```

---

## الملخص

### ما تم تنفيذه ✅
- ✅ تخزين مؤقت في session للمنتجات
- ✅ حفظ تلقائي بعد تسجيل الدخول
- ✅ حفظ تلقائي بعد التسجيل الجديد
- ✅ منع التكرار في session وdatabase
- ✅ معالجة الأخطاء بشكل آمن
- ✅ تنظيف الـ session بعد الحفظ

### الفوائد للمستخدم 🎯
- 🎁 لا يفقد اختياراته عند تسجيل الدخول
- 🚀 تجربة سلسة وسريعة
- 💝 يمكنه إضافة عدة منتجات قبل التسجيل
- ✨ كل شيء يُحفظ تلقائياً

---

تاريخ الإنشاء: 25 ديسمبر 2025
