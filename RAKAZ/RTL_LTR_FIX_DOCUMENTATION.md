# إصلاح مشكلة RTL/LTR للمحررات النصية (Quill Editor)

## المشكلة
عند نسخ محتوى إنجليزي ولصقه في حقول اللغة الإنجليزية في صفحة إضافة/تعديل المنتجات، كان المحرر النصي (Quill Editor) ينكسر ويخرج من مكانه بشكل كامل.

السبب: الصفحة بالكامل تعمل بنظام RTL (من اليمين لليسار) لأن اللغة المختارة عربية، لكن حقول اللغة الإنجليزية تحتاج إلى العمل بنظام LTR (من اليسار لليمين).

## الحل المطبق

### 1. إضافة Attributes للمحررات
تم إضافة `dir` و `class` لكل محرر نصوص:

#### للمحررات العربية:
```html
<div id="short_description_ar_editor" class="quill-editor-rtl" dir="rtl" style="height: 150px; background: #fff;"></div>
<div id="description_ar_editor" class="quill-editor-rtl" dir="rtl" style="height: 250px; background: #fff;"></div>
```

#### للمحررات الإنجليزية:
```html
<div id="short_description_en_editor" class="quill-editor-ltr" dir="ltr" style="height: 150px; background: #fff;"></div>
<div id="description_en_editor" class="quill-editor-ltr" dir="ltr" style="height: 250px; background: #fff;"></div>
```

### 2. إضافة CSS قوي وشامل
تم إضافة CSS بقوة `!important` لفرض الاتجاه الصحيح:

```css
/* Force LTR for English Quill Editors - CRITICAL FIX */
.quill-editor-ltr,
.quill-editor-ltr .ql-editor,
.quill-editor-ltr .ql-container,
.quill-editor-ltr .ql-editor.ql-blank::before {
    direction: ltr !important;
    text-align: left !important;
}

.quill-editor-ltr .ql-editor {
    unicode-bidi: embed !important;
}

.quill-editor-ltr .ql-editor p,
.quill-editor-ltr .ql-editor h1,
.quill-editor-ltr .ql-editor h2,
.quill-editor-ltr .ql-editor h3,
.quill-editor-ltr .ql-editor ul,
.quill-editor-ltr .ql-editor ol,
.quill-editor-ltr .ql-editor li,
.quill-editor-ltr .ql-editor div,
.quill-editor-ltr .ql-editor span,
.quill-editor-ltr .ql-editor strong,
.quill-editor-ltr .ql-editor em {
    direction: ltr !important;
    text-align: left !important;
}

/* Force RTL for Arabic Quill Editors */
.quill-editor-rtl,
.quill-editor-rtl .ql-editor,
.quill-editor-rtl .ql-container,
.quill-editor-rtl .ql-editor.ql-blank::before {
    direction: rtl !important;
    text-align: right !important;
}

.quill-editor-rtl .ql-editor {
    unicode-bidi: embed !important;
}

.quill-editor-rtl .ql-editor p,
.quill-editor-rtl .ql-editor h1,
.quill-editor-rtl .ql-editor h2,
.quill-editor-rtl .ql-editor h3,
.quill-editor-rtl .ql-editor ul,
.quill-editor-rtl .ql-editor ol,
.quill-editor-rtl .ql-editor li,
.quill-editor-rtl .ql-editor div,
.quill-editor-rtl .ql-editor span,
.quill-editor-rtl .ql-editor strong,
.quill-editor-rtl .ql-editor em {
    direction: rtl !important;
    text-align: right !important;
}

/* Fix Quill toolbar alignment */
.quill-editor-ltr .ql-toolbar {
    text-align: left !important;
}

.quill-editor-rtl .ql-toolbar {
    text-align: right !important;
}
```

### 3. تحديث JavaScript للمحررات
تم فصل إعدادات Quill لكل لغة وإضافة `setAttribute` و `style.textAlign`:

```javascript
// Initialize Quill Editors
const quillOptionsRTL = {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            ['link'],
            ['clean']
        ]
    }
};

const quillOptionsLTR = {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            ['link'],
            ['clean']
        ]
    }
};

// Short Description Arabic (RTL)
const shortDescArEditor = new Quill('#short_description_ar_editor', quillOptionsRTL);
shortDescArEditor.root.setAttribute('dir', 'rtl');
shortDescArEditor.on('text-change', function() {
    $('#short_description_ar').val(shortDescArEditor.root.innerHTML);
});
if ($('#short_description_ar').val()) {
    shortDescArEditor.root.innerHTML = $('#short_description_ar').val();
}

// Short Description English (LTR)
const shortDescEnEditor = new Quill('#short_description_en_editor', quillOptionsLTR);
shortDescEnEditor.root.setAttribute('dir', 'ltr');
shortDescEnEditor.root.style.textAlign = 'left';
shortDescEnEditor.on('text-change', function() {
    $('#short_description_en').val(shortDescEnEditor.root.innerHTML);
});
if ($('#short_description_en').val()) {
    shortDescEnEditor.root.innerHTML = $('#short_description_en').val();
}

// Full Description Arabic (RTL)
const descArEditor = new Quill('#description_ar_editor', quillOptionsRTL);
descArEditor.root.setAttribute('dir', 'rtl');
descArEditor.on('text-change', function() {
    $('#description_ar').val(descArEditor.root.innerHTML);
});
if ($('#description_ar').val()) {
    descArEditor.root.innerHTML = $('#description_ar').val();
}

// Full Description English (LTR)
const descEnEditor = new Quill('#description_en_editor', quillOptionsLTR);
descEnEditor.root.setAttribute('dir', 'ltr');
descEnEditor.root.style.textAlign = 'left';
descEnEditor.on('text-change', function() {
    $('#description_en').val(descEnEditor.root.innerHTML);
});
if ($('#description_en').val()) {
    descEnEditor.root.innerHTML = $('#description_en').val();
}
```

### 4. إضافة dir لحقول الإدخال النصية
تم إضافة `dir` لجميع حقول الأسماء أيضاً:

#### للحقول العربية:
```html
<input type="text" name="name_ar" dir="rtl" class="form-control" required>
```

#### للحقول الإنجليزية:
```html
<input type="text" name="name_en" dir="ltr" class="form-control" required>
```

## الملفات المعدلة

1. **resources/views/admin/products/create.blade.php**
   - إضافة `dir` و `class` للمحررات
   - إضافة `dir` لحقول الأسماء
   - إضافة CSS قوي
   - تحديث JavaScript

2. **resources/views/admin/products/edit.blade.php**
   - نفس التعديلات المطبقة على create.blade.php

## النتيجة
الآن عند نسخ محتوى إنجليزي ولصقه في حقول اللغة الإنجليزية:
- ✅ المحرر يحافظ على شكله
- ✅ النص يظهر من اليسار لليمين بشكل صحيح
- ✅ لا يحدث انكسار في التصميم
- ✅ المحررات العربية تبقى تعمل بنظام RTL بشكل طبيعي
- ✅ جميع العناصر داخل المحرر (p, h1-h3, ul, ol, li, etc.) تحترم الاتجاه المحدد

## ملاحظات مهمة
- استخدمنا `!important` في CSS لضمان تجاوز أي styles موروثة من الصفحة الرئيسية
- استخدمنا `unicode-bidi: embed` لضمان عدم تأثر النصوص بالاتجاه الخارجي
- تم تطبيق الاتجاه على جميع العناصر الفرعية داخل المحرر
- الحل يعمل حتى عند لصق محتوى HTML منسق
