# نظام إدارة عناوين الأقسام (Section Titles System)

## نظرة عامة
تم إنشاء نظام جديد لإدارة عناوين الأقسام بشكل منفصل ومركزي باستخدام جدول `section_titles` بدلاً من تخزينها في جدول `home_pages`.

## المكونات المنشأة

### 1. قاعدة البيانات
**الجدول:** `section_titles`

```sql
CREATE TABLE section_titles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    section_key VARCHAR(255) UNIQUE,  -- مفتاح القسم (مثل: gifts_section)
    title_ar VARCHAR(255),             -- العنوان بالعربية
    title_en VARCHAR(255),             -- العنوان بالإنجليزية
    active BOOLEAN DEFAULT TRUE,       -- حالة التفعيل
    sort_order INT DEFAULT 0,          -- ترتيب العرض
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**البيانات الافتراضية:**
```json
{
    "section_key": "gifts_section",
    "title_ar": "الهدايا",
    "title_en": "Gifts",
    "active": true,
    "sort_order": 1
}
```

### 2. Model - SectionTitle

**الموقع:** `app/Models/SectionTitle.php`

**الخصائص:**
- `fillable`: section_key, title_ar, title_en, active, sort_order
- `casts`: active => boolean, sort_order => integer

**الدوال المهمة:**

```php
// الحصول على العنوان حسب اللغة
public function getTitle($locale = null)
{
    $locale = $locale ?: app()->getLocale();
    return $this->{"title_{$locale}"} ?? $this->title_ar;
}

// الحصول على عنوان قسم محدد
public static function getByKey($key, $locale = null)
{
    $section = static::where('section_key', $key)
        ->where('active', true)
        ->first();
    
    return $section ? $section->getTitle($locale) : null;
}
```

### 3. Controller - SectionTitleController

**الموقع:** `app/Http/Controllers/Admin/SectionTitleController.php`

**المسارات:**
```php
// صفحة التعديل
GET /admin/section-titles/edit?locale=ar

// حفظ التعديلات
POST /admin/section-titles/update

// API - الحصول على عنوان قسم
GET /admin/section-titles/get/{key}/{locale?}
```

**الدوال:**
1. `edit($locale)` - عرض صفحة التعديل
2. `update(Request $request)` - حفظ التعديلات
3. `getByKey($key, $locale)` - API للحصول على عنوان

### 4. Routes

**الموقع:** `routes/web.php`

```php
use App\Http\Controllers\Admin\SectionTitleController;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('section-titles/edit', [SectionTitleController::class, 'edit'])
        ->name('section-titles.edit');
    Route::post('section-titles/update', [SectionTitleController::class, 'update'])
        ->name('section-titles.update');
    Route::get('section-titles/get/{key}/{locale?}', [SectionTitleController::class, 'getByKey'])
        ->name('section-titles.get');
});
```

## التكامل مع الأنظمة الموجودة

### 1. HomePageController

**التحديثات:**
```php
use App\Models\SectionTitle;

public function edit()
{
    // ... existing code ...
    
    // Get section titles
    $giftsTitle = SectionTitle::where('section_key', 'gifts_section')->first();
    
    return view('admin.pages.home-edit', compact('homePage', 'locale', 'giftsTitle'));
}

public function update(Request $request)
{
    // ... existing code ...
    
    // Handle Gifts Section Title in section_titles table
    SectionTitle::updateOrCreate(
        ['section_key' => 'gifts_section'],
        [
            'title_ar' => $request->input('gifts_section_title_ar'),
            'title_en' => $request->input('gifts_section_title_en'),
            'active' => $request->boolean('gifts_section_active'),
        ]
    );
    
    // ... rest of code ...
}
```

### 2. FrontendController

**التحديثات:**
```php
use App\Models\SectionTitle;

public function index()
{
    $homePage = HomePage::getActive();
    
    // Get section titles from section_titles table
    $giftsTitle = SectionTitle::getByKey('gifts_section');
    
    return view('frontend.index', compact('homePage', 'giftsTitle'));
}
```

### 3. Frontend View (index.blade.php)

**قبل:**
```blade
<h2 class="section-title">{{ $homePage->getTranslation('gifts_section_title') }}</h2>
```

**بعد:**
```blade
<h2 class="section-title">{{ $giftsTitle ?? 'الهدايا' }}</h2>
```

### 4. Admin View (home-edit.blade.php)

**قبل:**
```blade
<input type="text" name="gifts_section_title_ar" 
       value="{{ $homePage->gifts_section_title['ar'] ?? '' }}" 
       class="form-control">
```

**بعد:**
```blade
<input type="text" name="gifts_section_title_ar" 
       value="{{ $giftsTitle->title_ar ?? '' }}" 
       class="form-control">
```

**تم حذف:** Tab "Section Titles" من واجهة الأدمن لتجنب التكرار

## كيفية الاستخدام

### في الكود (Backend):

```php
// الحصول على عنوان قسم الهدايا
$title = SectionTitle::getByKey('gifts_section', 'ar');

// الحصول على كائن القسم كاملاً
$section = SectionTitle::where('section_key', 'gifts_section')->first();
echo $section->getTitle('en'); // "Gifts"

// تحديث عنوان القسم
SectionTitle::updateOrCreate(
    ['section_key' => 'gifts_section'],
    [
        'title_ar' => 'هدايا فاخرة',
        'title_en' => 'Luxury Gifts',
        'active' => true,
    ]
);
```

### في Views:

```blade
{{-- الحصول مباشرة من Controller --}}
<h2>{{ $giftsTitle }}</h2>

{{-- الحصول من Model --}}
<h2>{{ \App\Models\SectionTitle::getByKey('gifts_section') }}</h2>

{{-- مع fallback --}}
<h2>{{ $giftsTitle ?? 'عنوان افتراضي' }}</h2>
```

### في لوحة الأدمن:

1. افتح: `http://127.0.0.1:1001/admin/home/edit?locale=ar`
2. اذهب إلى تبويب "الهدايا" (Gifts)
3. عدّل "عنوان قسم الهدايا"
4. احفظ التغييرات
5. سيتم حفظ العنوان في جدول `section_titles` تلقائياً

## مميزات النظام الجديد

### ✅ المزايا:
1. **فصل البيانات**: عناوين الأقسام منفصلة عن محتوى الصفحة
2. **سهولة الإدارة**: جدول مخصص لجميع العناوين
3. **متعدد اللغات**: دعم كامل للعربية والإنجليزية
4. **قابل للتوسع**: سهولة إضافة أقسام جديدة
5. **API Ready**: يمكن استخدامه كـ API
6. **Caching Friendly**: يمكن عمل cache للعناوين بسهولة

### 🔄 التوافق مع الإصدار السابق:
- البيانات القديمة في `home_pages.gifts_section_title` لا تزال موجودة
- يمكن الترحيل التدريجي
- النظام القديم لن يتأثر

## إضافة أقسام جديدة

لإضافة عنوان قسم جديد:

```php
// في Migration أو Seeder
DB::table('section_titles')->insert([
    'section_key' => 'spotlight_section',
    'title_ar' => 'في دائرة الضوء',
    'title_en' => 'In The Spotlight',
    'active' => true,
    'sort_order' => 2,
    'created_at' => now(),
    'updated_at' => now(),
]);
```

## الملفات المعدّلة

### ملفات جديدة:
1. ✅ `database/migrations/2025_12_06_000001_create_section_titles_table.php`
2. ✅ `app/Models/SectionTitle.php`
3. ✅ `app/Http/Controllers/Admin/SectionTitleController.php`
4. ✅ `verify_section_titles.php` (للاختبار)

### ملفات معدّلة:
1. ✅ `routes/web.php`
2. ✅ `app/Http/Controllers/Admin/HomePageController.php`
3. ✅ `app/Http/Controllers/FrontendController.php`
4. ✅ `resources/views/admin/pages/home-edit.blade.php`
5. ✅ `resources/views/frontend/index.blade.php`

## الاختبار

شغّل سكريبت التحقق:
```bash
php verify_section_titles.php
```

**النتيجة المتوقعة:**
```
✓ جدول section_titles: تم إنشاؤه بنجاح
✓ Model SectionTitle: جاهز للاستخدام
✓ قراءة البيانات: تعمل بشكل صحيح
✓ متعدد اللغات: يدعم العربية والإنجليزية
```

## الخطوات التالية (اختياري)

### 1. إنشاء صفحة إدارة منفصلة
يمكنك إنشاء صفحة مستقلة لإدارة جميع عناوين الأقسام:
- `/admin/section-titles/edit` - لإدارة جميع العناوين في مكان واحد

### 2. ترحيل العناوين الأخرى
يمكنك نقل باقي العناوين من `home_pages` إلى `section_titles`:
- `cyber_sale_title`
- `spotlight_title`
- `discover_title`
- إلخ...

### 3. إضافة Cache
لتحسين الأداء:
```php
Cache::remember("section_title_{$key}_{$locale}", 3600, function() use ($key, $locale) {
    return SectionTitle::getByKey($key, $locale);
});
```

## ملاحظات مهمة

⚠️ **تنبيهات:**
- العنوان القديم في `home_pages.gifts_section_title` لا يزال موجود (للتوافق)
- النظام الجديد له الأولوية عند القراءة
- تأكد من تفعيل `active = true` للأقسام المطلوبة

✅ **التحقق من نجاح التطبيق:**
1. افتح لوحة الأدمن وعدّل عنوان قسم الهدايا
2. احفظ واذهب للصفحة الرئيسية
3. تحقق من ظهور العنوان الجديد

---

**تاريخ الإنشاء:** 6 ديسمبر 2025  
**الحالة:** ✅ جاهز للاستخدام  
**الإصدار:** 1.0
