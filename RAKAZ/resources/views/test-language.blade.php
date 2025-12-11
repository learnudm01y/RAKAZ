<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" data-locale="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Language Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }

        /* Based on data-locale */
        html[data-locale="ar"] .en-text {
            display: none !important;
        }

        html[data-locale="en"] .ar-text {
            display: none !important;
        }

        .form-group {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .lang-toggle {
            position: fixed;
            top: 20px;
            {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 20px;
            padding: 10px 20px;
            background: #3182ce;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .info strong {
            color: #1976d2;
        }
    </style>
</head>
<body>
    <button class="lang-toggle" onclick="toggleLanguage()">
        <span class="ar-text">Switch to English</span>
        <span class="en-text">التبديل إلى العربية</span>
    </button>

    <h1>
        <span class="ar-text">اختبار نظام اللغات</span>
        <span class="en-text">Language System Test</span>
    </h1>

    <div class="info">
        <p><strong>
            <span class="ar-text">اللغة الحالية:</span>
            <span class="en-text">Current Language:</span>
        </strong> {{ app()->getLocale() }}</p>
        <p><strong>
            <span class="ar-text">الاتجاه:</span>
            <span class="en-text">Direction:</span>
        </strong> {{ app()->getLocale() == 'ar' ? 'RTL (يمين إلى يسار)' : 'LTR (Left to Right)' }}</p>
    </div>

    <div class="form-group">
        <label>
            <span class="ar-text">عنوان الصفحة الرئيسية</span>
            <span class="en-text">Homepage Title</span>
        </label>
        <input type="text" placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل العنوان هنا' : 'Enter title here' }}">
    </div>

    <div class="form-group">
        <label>
            <span class="ar-text">وصف المنتج</span>
            <span class="en-text">Product Description</span>
        </label>
        <input type="text" placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل الوصف هنا' : 'Enter description here' }}">
    </div>

    <div class="form-group">
        <label>
            <span class="ar-text">البريد الإلكتروني</span>
            <span class="en-text">Email Address</span>
        </label>
        <input type="text" placeholder="email@example.com">
    </div>

    <h2>
        <span class="ar-text">شرح النظام</span>
        <span class="en-text">System Explanation</span>
    </h2>

    <div class="info">
        <h3>
            <span class="ar-text">كيف يعمل النظام:</span>
            <span class="en-text">How the System Works:</span>
        </h3>
        <ol style="padding-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 20px;">
            <li>
                <span class="ar-text">عند تحميل الصفحة، Laravel يحدد اللغة من الـ session</span>
                <span class="en-text">When loading the page, Laravel determines the language from session</span>
            </li>
            <li>
                <span class="ar-text">يتم تطبيق <code>data-locale</code> attribute على HTML tag</span>
                <span class="en-text">The <code>data-locale</code> attribute is applied to the HTML tag</span>
            </li>
            <li>
                <span class="ar-text">CSS يخفي النص غير المناسب تلقائياً حسب اللغة</span>
                <span class="en-text">CSS automatically hides inappropriate text based on language</span>
            </li>
            <li>
                <span class="ar-text">عند الضغط على زر تغيير اللغة، يتم إرسال طلب للسيرفر</span>
                <span class="en-text">When clicking language toggle, a request is sent to the server</span>
            </li>
            <li>
                <span class="ar-text">يتم حفظ اللغة في الـ session وإعادة تحميل الصفحة</span>
                <span class="en-text">Language is saved in session and page is reloaded</span>
            </li>
        </ol>
    </div>

    <script>
        function toggleLanguage() {
            const currentLang = document.documentElement.getAttribute('lang');
            const newLang = currentLang === 'ar' ? 'en' : 'ar';

            fetch('/locale/' + newLang, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback: reload anyway
                window.location.reload();
            });
        }
    </script>
</body>
</html>
