# دليل تكامل تطبيق Capacitor مع بوابة MyFatoorah

## نظرة عامة
تم تحديث النظام لدعم الدفع عبر تطبيق Capacitor Native مع ظهور أزرار Apple Pay و Google Pay في بوابة MyFatoorah.

## التعديلات المنفذة

### 1. Routes الجديدة
```php
// في routes/web.php

// AJAX payment endpoint للتطبيقات Native
Route::post('/checkout/pay/ajax', [MyFatoorahController::class, 'payAjax'])
    ->name('myfatoorah.pay.ajax');

// API للتحقق من حالة الدفع
Route::get('/api/order/{orderId}/payment-status', [MyFatoorahController::class, 'getPaymentStatus'])
    ->name('myfatoorah.payment.status');
```

### 2. MyFatoorahController - Methods الجديدة

#### payAjax()
- نفس منطق `pay()` لكن يرجع JSON بدلاً من redirect
- يضيف Deep Link في CallBackUrl للتطبيق Native
- يكشف التطبيق من User-Agent

#### getPaymentStatus($orderId)
- يتحقق من حالة الدفع
- يستخدم للـ polling من التطبيق
- يحمي الوصول (user authentication)

#### isNativeApp(Request $request)
- يكشف التطبيق Native من:
  - User-Agent: `RakazApp-Capacitor`, `Capacitor`, `RakazNative`
  - Header: `X-Native-App: rakaz-capacitor`
  - Parameter: `is_native_app=1`

### 3. Deep Link Support

#### في الـ Payload
```php
// عند إنشاء فاتورة MyFatoorah
$callbackUrl = route('myfatoorah.callback');
if ($isNativeApp) {
    $callbackUrl .= '?app_redirect=rakaz-app://payment-callback&order_id=' . $order->id;
}

$payloadData = [
    'CallBackUrl' => $callbackUrl,
    'ErrorUrl' => $callbackUrl,
    // ...
];
```

#### في الـ Callback
```php
// بعد معالجة الدفع
if ($request->has('app_redirect')) {
    $appRedirect = $request->input('app_redirect');
    $redirectUrl = $appRedirect . '?paymentId=' . $paymentId 
                  . '&status=' . $status 
                  . '&order_id=' . $order->id;
    return redirect()->away($redirectUrl);
}
```

### 4. JavaScript - capacitor-payment.js

#### وظائف رئيسية:

1. **handlePaymentDeepLink(url)**
   - معالجة Deep Link من MyFatoorah
   - عرض رسالة النجاح/الفشل
   - التوجيه للصفحة المناسبة

2. **isCapacitorApp()**
   - كشف تطبيق Capacitor
   - فحص `window.Capacitor`
   - فحص User-Agent

3. **openInExternalBrowser(url)**
   - فتح رابط الدفع في المتصفح الخارجي (Safari/Chrome)
   - استخدام Capacitor Browser Plugin

4. **pollPaymentStatus(orderId)**
   - مراقبة حالة الدفع كل 5 ثواني
   - الحد الأقصى: 60 محاولة (5 دقائق)

5. **handleCapacitorCheckout(formElement)**
   - معالجة submit للدفع
   - إرسال AJAX request
   - إضافة headers للكشف عن Native app

## التكامل مع Capacitor App

### 1. إعداد App.tsx (أو main.ts)

```typescript
import { App } from '@capacitor/app';
import { Browser } from '@capacitor/browser';

// Handle Deep Links
App.addListener('appUrlOpen', (event) => {
    const url = event.url;
    
    if (url.startsWith('rakaz-app://payment-callback')) {
        // Call the payment handler
        if (window.handlePaymentDeepLink) {
            window.handlePaymentDeepLink(url);
        }
    }
});
```

### 2. إضافة Deep Link في capacitor.config.ts

```typescript
const config: CapacitorConfig = {
    appId: 'com.rakaz.app',
    appName: 'Rakaz',
    webDir: 'dist',
    server: {
        url: 'https://yoursite.com',
        cleartext: false
    },
    plugins: {
        App: {
            deepLinks: ['rakaz-app://']
        }
    }
};
```

### 3. إضافة URL Scheme في iOS (Info.plist)

```xml
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>rakaz-app</string>
        </array>
        <key>CFBundleURLName</key>
        <string>com.rakaz.app</string>
    </dict>
</array>
```

### 4. إضافة Intent Filter في Android (AndroidManifest.xml)

```xml
<activity android:name=".MainActivity">
    <intent-filter>
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="rakaz-app" />
    </intent-filter>
</activity>
```

### 5. تعديل User-Agent (اختياري)

```typescript
// في Capacitor config أو plugin
WebView.setServerBasePath({
    path: 'https://yoursite.com',
    headers: {
        'User-Agent': 'RakazApp-Capacitor/1.0.0'
    }
});
```

## تدفق العمل (Workflow)

### 1. المستخدم يضغط "إتمام الطلب"
```
User clicks submit → handleCapacitorCheckout() called
                  ↓
            AJAX POST /checkout/pay/ajax
            Headers: X-Native-App: rakaz-capacitor
                  ↓
            MyFatoorahController::payAjax()
            - Detects Native app
            - Creates order
            - Adds Deep Link to CallBackUrl
            - Returns JSON with payment_url
```

### 2. فتح صفحة الدفع
```
Get payment_url from response
            ↓
    openInExternalBrowser(payment_url)
            ↓
    Opens Safari/Chrome with MyFatoorah
            ↓
    User completes payment (Apple Pay/Google Pay/Card)
```

### 3. العودة من الدفع
```
MyFatoorah redirects to:
rakaz-app://payment-callback?paymentId=xxx&status=success&order_id=123
            ↓
    Capacitor intercepts Deep Link
            ↓
    App.addListener('appUrlOpen') triggered
            ↓
    handlePaymentDeepLink() called
            ↓
    Show success message
            ↓
    Redirect to order details
```

## الاختبار

### 1. اختبار في المتصفح العادي
- يعمل بشكل طبيعي (redirect مباشر)

### 2. اختبار في Capacitor
```bash
# Build the app
npm run build
npx cap sync

# iOS
npx cap open ios

# Android
npx cap open android
```

### 3. محاكاة Deep Link في iOS Simulator
```bash
xcrun simctl openurl booted "rakaz-app://payment-callback?paymentId=123&status=success&order_id=456&order_number=ORD-001"
```

### 4. محاكاة Deep Link في Android
```bash
adb shell am start -W -a android.intent.action.VIEW -d "rakaz-app://payment-callback?paymentId=123&status=success&order_id=456"
```

## استكشاف الأخطاء

### مشكلة: أزرار Apple Pay/Google Pay لا تظهر
**الحل:**
- تأكد من فتح الرابط في Safari/Chrome (ليس WebView)
- استخدم `openInExternalBrowser()` بدلاً من `window.open()`

### مشكلة: Deep Link لا يعمل
**الحل:**
- تحقق من `capacitor.config.ts`
- تحقق من Info.plist (iOS) أو AndroidManifest.xml
- أعد build التطبيق بعد التعديلات

### مشكلة: لا يتم كشف التطبيق Native
**الحل:**
- أضف header: `X-Native-App: rakaz-capacitor`
- أضف parameter: `is_native_app=1`
- عدّل User-Agent في Capacitor

## ملفات التعديل

1. ✅ `routes/web.php` - routes جديدة
2. ✅ `app/Http/Controllers/MyFatoorahController.php` - methods وlogic
3. ✅ `public/js/capacitor-payment.js` - JavaScript handler
4. ✅ `resources/views/frontend/checkout.blade.php` - include script

## المتطلبات

### Capacitor Plugins
```bash
npm install @capacitor/app @capacitor/browser
```

### Laravel Packages
- لا يوجد packages إضافية مطلوبة
- يعمل مع MyFatoorah SDK الحالي

## الأمان

1. ✅ التحقق من صلاحية الوصول للطلب
2. ✅ CSRF Token في AJAX requests
3. ✅ Deep Link validation
4. ✅ Order ownership check في getPaymentStatus()

## الإنتاج (Production)

قبل النشر:
1. غيّر `rakaz-app://` إلى URL scheme الفعلي
2. حدّث `RakazApp-Capacitor` في User-Agent
3. اختبر على أجهزة حقيقية (iOS/Android)
4. فعّل HTTPS للموقع
5. سجّل التطبيق في Apple/Google للدفع

---

تم التحديث: 10 يناير 2026
النسخة: 1.0.0
