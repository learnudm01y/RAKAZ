<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>اختبار بسيط للفورم</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-box {
            background: white;
            padding: 30px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #1a1a1a; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
        h2 { color: #667eea; margin-top: 0; }
        label { display: block; margin: 15px 0 5px; font-weight: bold; }
        input, button { padding: 12px; margin: 5px 0; width: 100%; box-sizing: border-box; font-size: 16px; }
        button { background: #667eea; color: white; border: none; cursor: pointer; border-radius: 6px; }
        button:hover { background: #5568d3; }
        pre { background: #1a1a1a; color: #10b981; padding: 15px; border-radius: 6px; overflow-x: auto; }
        .success { background: #10b981; color: white; padding: 15px; border-radius: 6px; }
        .error { background: #ef4444; color: white; padding: 15px; border-radius: 6px; }
        .warning { background: #f59e0b; color: white; padding: 15px; border-radius: 6px; }
    </style>
</head>
<body>
    <h1>🧪 اختبار بسيط لإرسال الفورم</h1>

    <div class="test-box">
        <h2>1️⃣ اختبار POST العادي</h2>
        <form action="{{ route('admin.featured-section.update') }}" method="POST" id="normal-form">
            @csrf

            <label>العنوان (عربي):</label>
            <input type="text" name="title_ar" value="اختبار عادي" required>

            <label>العنوان (إنجليزي):</label>
            <input type="text" name="title_en" value="Normal Test" required>

            <label>رابط:</label>
            <input type="text" name="link_url" value="/shop" required>

            <label>نص الرابط (عربي):</label>
            <input type="text" name="link_text_ar" value="تسوق" required>

            <label>نص الرابط (إنجليزي):</label>
            <input type="text" name="link_text_en" value="Shop" required>

            <input type="hidden" name="product_ids[]" value="1">
            <input type="hidden" name="product_ids[]" value="2">
            <input type="hidden" name="product_ids[]" value="3">

            <button type="submit">إرسال فورم عادي (POST)</button>
        </form>
    </div>

    <div class="test-box">
        <h2>2️⃣ اختبار AJAX POST</h2>
        <div id="ajax-result"></div>
        <button onclick="testAjax()">إرسال AJAX POST</button>
    </div>

    <div class="test-box">
        <h2>3️⃣ معلومات Route</h2>
        <div id="route-info"></div>
        <pre>
Route Name: admin.featured-section.update
Route URL: {{ route('admin.featured-section.update') }}
CSRF Token: {{ csrf_token() }}
        </pre>
    </div>

    <div class="test-box">
        <h2>4️⃣ التحقق من Laravel Logs</h2>
        <p class="warning">بعد إرسال الفورم، افتح الملف التالي للتحقق من الـ Logs:</p>
        <pre>storage/logs/laravel.log</pre>
        <p>ابحث عن السطر:</p>
        <pre>🚀 Featured Section Update STARTED</pre>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Setup CSRF for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Monitor normal form submission
        $('#normal-form').on('submit', function(e) {
            console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            console.log('📝 NORMAL FORM SUBMISSION');
            console.log('Action:', this.action);
            console.log('Method:', this.method);
            console.log('Form Data:', $(this).serialize());
            console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

            // Let it submit normally
        });

        function testAjax() {
            console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            console.log('📡 AJAX POST TEST');

            const data = {
                _token: '{{ csrf_token() }}',
                title_ar: 'اختبار AJAX',
                title_en: 'AJAX Test',
                link_url: '/shop',
                link_text_ar: 'تسوق',
                link_text_en: 'Shop',
                product_ids: [1, 2, 3]
            };

            console.log('Data to send:', data);

            $('#ajax-result').html('<div class="warning">جاري الإرسال...</div>');

            $.ajax({
                url: '{{ route('admin.featured-section.update') }}',
                method: 'POST',
                data: data,
                success: function(response, status, xhr) {
                    console.log('✅ AJAX SUCCESS');
                    console.log('Response:', response);
                    console.log('Status:', status);
                    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

                    $('#ajax-result').html(`
                        <div class="success">
                            <strong>✅ نجح الإرسال!</strong><br>
                            Response: ${JSON.stringify(response, null, 2)}<br>
                            <br>
                            <strong>الآن افتح:</strong> storage/logs/laravel.log
                        </div>
                    `);
                },
                error: function(xhr, status, error) {
                    console.error('❌ AJAX ERROR');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response:', xhr.responseText);
                    console.error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

                    $('#ajax-result').html(`
                        <div class="error">
                            <strong>❌ فشل الإرسال!</strong><br>
                            Status: ${status}<br>
                            Error: ${error}<br>
                            Response: ${xhr.responseText}
                        </div>
                    `);
                }
            });
        }

        // Display route info
        $('#route-info').html(`
            <pre>
Current URL: ${window.location.href}
Target URL: {{ route('admin.featured-section.update') }}
            </pre>
        `);
    </script>
</body>
</html>
