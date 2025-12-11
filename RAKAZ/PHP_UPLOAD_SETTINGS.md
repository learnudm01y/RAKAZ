# إعدادات PHP المطلوبة لرفع صور متعددة

## المشكلة
لا يتم رفع أكثر من 2-3 صور للمنتج الواحد بسبب قيود PHP.

## الحل
يجب تعديل ملف php.ini الموجود في:
```
C:\laragon\bin\php\php-8.3.26-Win32-vs16-x64\php.ini
```

## الإعدادات المطلوبة

### الإعدادات الحالية (المشكلة):
```ini
upload_max_filesize = 2M
post_max_size = 8M
max_file_uploads = 20
```

### الإعدادات الجديدة (الحل):
```ini
upload_max_filesize = 10M
post_max_size = 100M
max_file_uploads = 50
```

## الشرح:

1. **upload_max_filesize**: الحد الأقصى لحجم الملف الواحد
   - قديم: 2MB (صغير جداً للصور عالية الجودة)
   - جديد: 10MB (يسمح بصور عالية الجودة)

2. **post_max_size**: الحد الأقصى لحجم البيانات المرسلة في POST request
   - قديم: 8MB (يسمح بـ 2-3 صور فقط)
   - جديد: 100MB (يسمح بـ 10-20 صورة بجودة عالية)

3. **max_file_uploads**: الحد الأقصى لعدد الملفات في رفعة واحدة
   - قديم: 20 (جيد لكن يمكن زيادته)
   - جديد: 50 (ممتاز لرفع صور متعددة)

## خطوات التطبيق:

1. افتح ملف php.ini
2. ابحث عن السطور الثلاثة باستخدام Ctrl+F
3. غيّر القيم كما هو موضح أعلاه
4. احفظ الملف
5. أعد تشغيل الخادم

### إعادة تشغيل Laragon:
1. افتح Laragon
2. اضغط Stop All
3. اضغط Start All

أو باستخدام الأمر:
```bash
# إيقاف السيرفر
php artisan serve --stop

# إعادة تشغيل السيرفر
php artisan serve
```

## التحقق من التغييرات:

بعد إعادة التشغيل، تحقق من الإعدادات الجديدة:
```bash
php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL; echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL; echo 'max_file_uploads: ' . ini_get('max_file_uploads') . PHP_EOL;"
```

يجب أن ترى:
```
upload_max_filesize: 10M
post_max_size: 100M
max_file_uploads: 50
```

## اختبار رفع الصور:

1. اذهب إلى صفحة إضافة منتج جديد
2. اختر 10 صور أو أكثر
3. افتح Console في المتصفح (F12)
4. ستجد رسائل تظهر عدد الصور:
   ```
   Gallery files count: 10
   Input files count: 10
   ```
5. احفظ المنتج
6. تحقق من أن جميع الصور تم حفظها

## ملاحظات إضافية:

- التعديلات تم إجراؤها أيضاً على صفحات create.blade.php و edit.blade.php
- تم إضافة console logging لمتابعة عدد الصور
- تم إضافة logging في Controller لمتابعة الصور المستلمة
- التعديلات تضمن رفع عدد لا نهائي من الصور (محدود فقط بإعدادات PHP)
