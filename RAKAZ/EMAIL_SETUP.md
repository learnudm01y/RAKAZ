# ملف تعليمات إعداد البريد الإلكتروني

## الخطوات المطلوبة لتفعيل إرسال البريد الإلكتروني:

### 1. تحديث ملف `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. إعداد Gmail (إذا كنت تستخدم Gmail):

1. قم بتسجيل الدخول إلى حساب Gmail
2. اذهب إلى: https://myaccount.google.com/security
3. قم بتفعيل "التحقق بخطوتين" (2-Step Verification)
4. اذهب إلى "كلمات مرور التطبيقات" (App Passwords)
5. أنشئ كلمة مرور جديدة للتطبيق
6. استخدم كلمة المرور هذه في `MAIL_PASSWORD`

### 3. بدائل أخرى لخدمات البريد:

#### Mailtrap (للتطوير والاختبار):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

#### SendGrid:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

#### Mailgun:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-api-key
```

### 4. اختبار الإعداد:

بعد تحديث `.env`، قم بتشغيل:

```bash
php artisan config:clear
php artisan cache:clear
```

### 5. الميزات المتاحة الآن:

✅ إرسال رد على رسائل العملاء من داخل لوحة التحكم
✅ تصميم بريد إلكتروني احترافي ثنائي اللغة
✅ عرض الرسالة الأصلية في البريد المرسل
✅ تحديث حالة الرسالة تلقائياً إلى "تم الرد"
✅ واجهة مستخدم سهلة مع عداد للأحرف
✅ التحقق من صحة البيانات

### 6. كيفية الاستخدام:

1. افتح صفحة رسائل العملاء
2. اضغط على "عرض" لأي رسالة
3. اضغط على زر "رد على الرسالة"
4. اكتب ردك (10 أحرف على الأقل)
5. اضغط "إرسال الرد"
6. سيتم إرسال البريد وتحديث الحالة تلقائياً

### ملاحظات مهمة:

- تأكد من أن `allow_url_fopen` مفعل في PHP
- قد تحتاج إلى فتح منفذ 587 في الجدار الناري
- استخدم Mailtrap للتطوير لتجنب إرسال رسائل حقيقية
