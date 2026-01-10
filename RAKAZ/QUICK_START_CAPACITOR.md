# 🚀 دليل البدء السريع - دعم Capacitor للدفع

## ✅ ما تم تنفيذه

### Backend (Laravel)
1. ✅ Route للدفع عبر AJAX: `POST /checkout/pay/ajax`
2. ✅ Route للتحقق من حالة الدفع: `GET /api/order/{orderId}/payment-status`
3. ✅ Deep Link support في MyFatoorah callback
4. ✅ كشف التطبيق Native من User-Agent/Headers
5. ✅ Methods: `payAjax()`, `getPaymentStatus()`, `isNativeApp()`

### Frontend (JavaScript)
1. ✅ ملف `capacitor-payment.js` مع جميع الوظائف
2. ✅ تم إضافته في `checkout.blade.php`
3. ✅ Deep Link handler: `handlePaymentDeepLink()`
4. ✅ Payment polling: `pollPaymentStatus()`
5. ✅ External browser opener: `openInExternalBrowser()`

## 📋 خطوات التكامل مع Capacitor App

### 1️⃣ تثبيت Capacitor Plugins
```bash
npm install @capacitor/app @capacitor/browser
npx cap sync
```

### 2️⃣ إضافة Deep Link في capacitor.config.ts
```typescript
import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.rakaz.app',
  appName: 'Rakaz',
  webDir: 'dist',
  plugins: {
    App: {
      deepLinks: ['rakaz-app://']
    }
  }
};

export default config;
```

### 3️⃣ iOS Setup (Info.plist)
أضف في `ios/App/App/Info.plist`:
```xml
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>rakaz-app</string>
        </array>
    </dict>
</array>
```

### 4️⃣ Android Setup (AndroidManifest.xml)
أضف في `android/app/src/main/AndroidManifest.xml`:
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

### 5️⃣ إضافة كود في App Component
نسخ من ملف `capacitor-app-integration-example.ts`:

```typescript
import { App } from '@capacitor/app';

// في useEffect أو componentDidMount
App.addListener('appUrlOpen', (event) => {
    if (event.url.startsWith('rakaz-app://payment-callback')) {
        if (window.handlePaymentDeepLink) {
            window.handlePaymentDeepLink(event.url);
        }
    }
});
```

## 🧪 الاختبار

### اختبار في المتصفح
1. افتح `/checkout`
2. املأ البيانات واختر الدفع الإلكتروني
3. يجب أن يعمل بشكل طبيعي (redirect عادي)

### اختبار في Capacitor iOS
```bash
npm run build
npx cap sync ios
npx cap open ios
```

ثم في Xcode:
- Run على Simulator أو جهاز حقيقي
- اختبر عملية الدفع
- يجب فتح Safari وظهور أزرار Apple Pay

### اختبار في Capacitor Android
```bash
npm run build
npx cap sync android
npx cap open android
```

### محاكاة Deep Link (iOS Simulator)
```bash
xcrun simctl openurl booted "rakaz-app://payment-callback?status=success&order_id=123&order_number=ORD-001"
```

### محاكاة Deep Link (Android)
```bash
adb shell am start -W -a android.intent.action.VIEW \
  -d "rakaz-app://payment-callback?status=success&order_id=123"
```

## 🔍 التحقق من التكامل

### ✅ Checklist
- [ ] Capacitor plugins مثبتة
- [ ] Deep Link مضاف في capacitor.config.ts
- [ ] iOS Info.plist محدث
- [ ] Android Manifest محدث
- [ ] Deep Link listener مضاف في App component
- [ ] التطبيق يبني بدون أخطاء
- [ ] يفتح المتصفح الخارجي عند الدفع
- [ ] أزرار Apple Pay/Google Pay تظهر
- [ ] Deep Link يعمل بعد الدفع
- [ ] يتم التوجيه لصفحة الطلب عند النجاح

## 🐛 استكشاف الأخطاء

### المشكلة: لا تظهر أزرار Apple Pay/Google Pay
**الحل:**
- تأكد من فتح الرابط في Safari/Chrome (ليس WebView)
- تحقق من استخدام `@capacitor/browser` plugin

### المشكلة: Deep Link لا يعمل
**الحل:**
```bash
# أعد build التطبيق
npm run build
npx cap sync
npx cap copy

# تحقق من Logs
# iOS: Xcode console
# Android: adb logcat
```

### المشكلة: التطبيق لا يُكتشف كـ Native
**الحل:**
أضف في ملف checkout.blade.php:
```javascript
// في بداية السكريبت
document.body.classList.add('capacitor-app');
window.isRakazNativeApp = () => true;
```

## 📁 الملفات المهمة

### Backend
- `routes/web.php` - Routes
- `app/Http/Controllers/MyFatoorahController.php` - Logic
- `public/js/capacitor-payment.js` - JavaScript handler
- `resources/views/frontend/checkout.blade.php` - UI

### Frontend (Capacitor)
- `capacitor.config.ts` - Capacitor config
- `ios/App/App/Info.plist` - iOS deep links
- `android/app/src/main/AndroidManifest.xml` - Android intents
- `src/App.tsx` (or similar) - Deep link listener

### Documentation
- `CAPACITOR_PAYMENT_INTEGRATION.md` - دليل شامل
- `capacitor-app-integration-example.ts` - مثال كود
- هذا الملف - دليل البدء السريع

## 🎯 النتيجة المتوقعة

عند الدفع من تطبيق Capacitor:
1. ✅ يفتح Safari/Chrome (ليس WebView)
2. ✅ تظهر أزرار Apple Pay و Google Pay
3. ✅ المستخدم يكمل الدفع
4. ✅ يتم التوجيه للتطبيق عبر Deep Link
5. ✅ تظهر رسالة النجاح
6. ✅ يتم فتح صفحة تفاصيل الطلب

---

## 📞 الدعم

في حالة وجود مشاكل:
1. تحقق من console logs في Xcode/Android Studio
2. راجع ملف `CAPACITOR_PAYMENT_INTEGRATION.md`
3. استخدم الأمثلة في `capacitor-app-integration-example.ts`

تم التحديث: 10 يناير 2026
