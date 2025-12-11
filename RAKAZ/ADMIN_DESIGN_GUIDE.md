# تصميم نظام إدارة القوائم - دليل الاستخدام

## نظرة عامة
تم إنشاء نظام تصميم متكامل وحديث لصفحات إدارة القوائم في لوحة التحكم. التصميم يدعم اللغة العربية والإنجليزية بالكامل مع دعم RTL/LTR.

## الملفات المضافة

### 1. ملف CSS الرئيسي
**المسار:** `public/assets/css/admin-menu.css`

يحتوي على جميع التصاميم الخاصة بنظام إدارة القوائم:
- تصميم الصفحات (Page Header, Cards, Forms)
- الأزرار بجميع أنواعها (Primary, Secondary, Info, Warning, Danger)
- الجداول والنماذج
- الشارات (Badges) والتنبيهات (Alerts)
- نظام Grid للتخطيط
- دعم كامل للـ RTL/LTR
- تصميم متجاوب (Responsive)

## المكونات الرئيسية

### 1. Page Header (رأس الصفحة)
```html
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">عنوان الصفحة</h1>
    </div>
    <div class="page-actions">
        <a href="#" class="btn btn-primary">إضافة جديد</a>
    </div>
</div>
```

**المميزات:**
- عنوان واضح وبارز
- منطقة للإجراءات (Actions)
- تصميم متجاوب للشاشات الصغيرة

### 2. Cards (البطاقات)
```html
<div class="card">
    <div class="card-header">
        <h3 class="card-title">عنوان البطاقة</h3>
    </div>
    <div class="card-body">
        <!-- محتوى البطاقة -->
    </div>
</div>
```

**المميزات:**
- خلفية بيضاء مع ظل خفيف
- رأس مميز بخلفية رمادية فاتحة
- حواف مستديرة (Border Radius)
- مسافات داخلية مريحة

### 3. الأزرار (Buttons)
الأزرار المتاحة:
- `btn-primary` - أزرق (للإجراءات الأساسية)
- `btn-secondary` - رمادي (للإجراءات الثانوية)
- `btn-info` - سماوي (للمعلومات)
- `btn-warning` - برتقالي (للتحذيرات)
- `btn-danger` - أحمر (للحذف)
- `btn-sm` - حجم صغير

**مثال:**
```html
<button class="btn btn-primary">
    <svg>...</svg>
    حفظ
</button>
```

**المميزات:**
- أيقونات داخلية (SVG)
- تأثيرات Hover و Active
- دعم حالة Loading
- دعم حالة Disabled

### 4. النماذج (Forms)

#### حقول الإدخال
```html
<div class="mb-3">
    <label class="form-label required">اسم الحقل</label>
    <input type="text" class="form-control" required>
</div>
```

#### قوائم منسدلة
```html
<select class="form-control">
    <option>اختر...</option>
    <option value="1">خيار 1</option>
</select>
```

#### مفتاح التبديل (Switch)
```html
<div class="form-check form-switch">
    <input type="checkbox" class="form-check-input" id="active">
    <label class="form-check-label" for="active">نشط</label>
</div>
```

**المميزات:**
- تصميم نظيف وواضح
- علامة (*) للحقول المطلوبة
- رسائل خطأ ملونة
- تأثيرات Focus مميزة
- دعم كامل للـ RTL

### 5. الجداول (Tables)
```html
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>العمود 1</th>
                <th>العمود 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>بيانات 1</td>
                <td>بيانات 2</td>
            </tr>
        </tbody>
    </table>
</div>
```

**المميزات:**
- رأس مميز بخلفية رمادية
- تأثير Hover على الصفوف
- حدود واضحة
- دعم التمرير الأفقي (Responsive)
- محاذاة صحيحة للـ RTL/LTR

### 6. الشارات (Badges)
```html
<span class="badge badge-success">نشط</span>
<span class="badge badge-danger">محذوف</span>
<span class="badge badge-info">معلومة</span>
```

**الأنواع:**
- `badge-primary` - أزرق
- `badge-success` - أخضر
- `badge-secondary` - رمادي
- `badge-info` - سماوي
- `badge-warning` - برتقالي
- `badge-danger` - أحمر

### 7. التنبيهات (Alerts)
```html
<div class="alert alert-success">
    تم الحفظ بنجاح!
</div>

<div class="alert alert-danger">
    حدث خطأ!
</div>
```

**الأنواع:**
- `alert-success` - نجاح (أخضر)
- `alert-danger` - خطأ (أحمر)
- `alert-info` - معلومة (أزرق)
- `alert-warning` - تحذير (برتقالي)

## نظام Grid

تم تضمين نظام Grid بسيط ومرن:

```html
<div class="row">
    <div class="col-md-6">نصف العرض</div>
    <div class="col-md-6">نصف العرض</div>
</div>

<div class="row">
    <div class="col-md-4">ثلث العرض</div>
    <div class="col-md-4">ثلث العرض</div>
    <div class="col-md-4">ثلث العرض</div>
</div>
```

**الأحجام المتاحة:**
- `col-md-1` - 8.33%
- `col-md-2` - 16.67%
- `col-md-3` - 25%
- `col-md-4` - 33.33%
- `col-md-6` - 50%
- `col-md-12` - 100%

## التصميم المتجاوب (Responsive)

### نقاط التحول (Breakpoints)
- **Mobile:** أقل من 768px
- **Tablet & Desktop:** 768px فما فوق

### التغييرات على الشاشات الصغيرة:
- الأزرار تصبح بعرض كامل
- الجداول قابلة للتمرير أفقياً
- الـ Grid يصبح عمود واحد
- مسافات أصغر للتوفير في المساحة

## دعم RTL/LTR

التصميم يدعم الاتجاهين تلقائياً:

### للعربية (RTL):
```css
[dir="rtl"] .element {
    /* تصميم خاص بالعربية */
}
```

### للإنجليزية (LTR):
```css
[dir="ltr"] .element {
    /* تصميم خاص بالإنجليزية */
}
```

**العناصر المدعومة:**
- محاذاة النصوص
- أيقونات الأزرار
- القوائم المنسدلة
- الجداول
- النماذج

## الألوان المستخدمة

### الألوان الرئيسية:
- **Primary:** #3b82f6 (أزرق)
- **Success:** #10b981 (أخضر)
- **Danger:** #ef4444 (أحمر)
- **Warning:** #f59e0b (برتقالي)
- **Info:** #0ea5e9 (سماوي)
- **Secondary:** #6b7280 (رمادي)

### الألوان الثانوية:
- **Background:** #f7fafc (رمادي فاتح جداً)
- **Text:** #1a202c (رمادي داكن)
- **Border:** #e5e7eb (رمادي فاتح)
- **Hover:** #f9fafb (رمادي فاتح جداً)

## التأثيرات والانتقالات

### Hover Effects:
- تكبير طفيف للأزرار
- تغيير لون الخلفية
- ظل أوضح

### Transitions:
جميع التحولات تستخدم `cubic-bezier(0.4, 0, 0.2, 1)` لحركة سلسة.

### Focus States:
- حدود زرقاء بعرض 2px
- مسافة 2px من العنصر
- وضوح عالي للوصول السهل

## مميزات إضافية

### 1. Loading States
```html
<button class="btn btn-primary loading" disabled>
    جاري الحفظ...
</button>
```

### 2. Disabled States
```html
<button class="btn btn-primary" disabled>
    معطل
</button>
```

### 3. Image Preview
```html
<div id="image-preview">
    <img id="preview-img" src="..." alt="Preview">
</div>
```

### 4. Custom Link Toggle
الحقول المخصصة تُعطل تلقائياً عند اختيار تصنيف، وتُفعل عند تركه فارغاً.

## الوصول (Accessibility)

التصميم يدعم معايير الوصول:
- ✅ تباين ألوان عالي
- ✅ حجم خط مقروء
- ✅ Focus States واضحة
- ✅ دعم لوحة المفاتيح
- ✅ علامات ARIA (عند الحاجة)
- ✅ رسائل خطأ وصفية

## نصائح للاستخدام

### 1. استخدم الفئات الجاهزة
بدلاً من كتابة CSS جديد، استخدم الفئات الموجودة:
```html
<!-- ❌ لا تفعل -->
<div style="margin-bottom: 1rem;">

<!-- ✅ افعل -->
<div class="mb-4">
```

### 2. حافظ على التناسق
استخدم نفس التصاميم في جميع الصفحات للحصول على تجربة موحدة.

### 3. اختبر على الشاشات المختلفة
تأكد من أن التصميم يعمل على:
- Desktop (1920px+)
- Laptop (1366px)
- Tablet (768px)
- Mobile (375px)

### 4. استخدم الأزرار المناسبة
- `btn-primary` للحفظ والإضافة
- `btn-danger` للحذف
- `btn-secondary` للإلغاء والرجوع
- `btn-warning` للتعديل
- `btn-info` للمعلومات

## الصيانة والتطوير

### إضافة مكون جديد:
1. أضف CSS في `admin-menu.css`
2. استخدم نفس نمط التسمية
3. تأكد من دعم RTL/LTR
4. اختبر على الشاشات المختلفة

### تعديل الألوان:
يمكن تعديل الألوان من خلال متغيرات CSS في `app.blade.php`:
```css
:root {
    --primary-color: #3182ce;
    /* ... باقي المتغيرات */
}
```

## الدعم الفني

في حال وجود مشاكل:
1. تحقق من تحميل ملف CSS بشكل صحيح
2. افحص Console للأخطاء
3. تأكد من استخدام الفئات الصحيحة
4. راجع هذا الدليل للمراجع

---

**آخر تحديث:** 10 ديسمبر 2025
**الإصدار:** 1.0.0
**الحالة:** ✅ جاهز للإنتاج
