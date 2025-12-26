<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>🧪 اختبار حذف المنتجات</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .test-section {
            background: white;
            padding: 25px;
            margin: 20px 0;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .test-section h2 {
            color: #1a1a1a;
            margin-bottom: 15px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        .status {
            padding: 12px 20px;
            border-radius: 8px;
            margin: 15px 0;
            font-weight: 600;
        }
        .success { background: #10b981; color: white; }
        .error { background: #ef4444; color: white; }
        .warning { background: #f59e0b; color: white; }
        .info { background: #3b82f6; color: white; }
        button {
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            margin: 5px;
        }
        button:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        button.danger { background: #ef4444; }
        button.danger:hover { background: #dc2626; }
        pre {
            background: #1a1a1a;
            color: #10b981;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 14px;
            line-height: 1.6;
            direction: ltr;
            text-align: left;
        }
        .product-card {
            border: 2px solid #e5e5e5;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9f9f9;
        }
        .product-info { font-size: 16px; font-weight: 600; color: #1a1a1a; }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            font-size: 16px;
            margin: 10px 0;
        }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 اختبار شامل لنظام حذف المنتجات</h1>

        <!-- Test 1: Database Connection -->
        <div class="test-section">
            <h2>1️⃣ اختبار الاتصال بقاعدة البيانات</h2>
            <div id="db-status" class="status info">جاري الاختبار...</div>
            <button onclick="testDatabase()">اختبار قاعدة البيانات</button>
            <pre id="db-result"></pre>
        </div>

        <!-- Test 2: Current Products -->
        <div class="test-section">
            <h2>2️⃣ المنتجات الحالية في قاعدة البيانات</h2>
            <div id="products-status" class="status info">جاري التحميل...</div>
            <button onclick="loadProducts()">تحميل المنتجات</button>
            <pre id="products-result"></pre>
            <div id="products-list"></div>
        </div>

        <!-- Test 3: Form Submission Test -->
        <div class="test-section">
            <h2>3️⃣ اختبار إرسال الفورم</h2>
            <div id="form-status" class="status info">جاهز للاختبار</div>

            <form id="test-form" onsubmit="return submitTestForm(event)">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <label>العنوان (عربي):</label>
                <input type="text" name="title_ar" value="المنتجات المميزة - اختبار" required>

                <label>العنوان (إنجليزي):</label>
                <input type="text" name="title_en" value="Featured Products - Test" required>

                <label>رابط الصفحة:</label>
                <input type="text" name="link_url" value="/shop" required>

                <label>نص الرابط (عربي):</label>
                <input type="text" name="link_text_ar" value="تسوق الكل" required>

                <label>نص الرابط (إنجليزي):</label>
                <input type="text" name="link_text_en" value="Shop All" required>

                <label>
                    <input type="checkbox" name="is_active" checked>
                    تفعيل القسم
                </label>

                <h3>المنتجات المختارة (اختبار):</h3>
                <div id="selected-products">
                    <!-- سيتم ملؤها ديناميكياً -->
                </div>

                <button type="submit">إرسال الاختبار</button>
            </form>
            <pre id="form-result"></pre>
        </div>

        <!-- Test 4: Direct API Test -->
        <div class="test-section">
            <h2>4️⃣ اختبار مباشر للـ API</h2>
            <div id="api-status" class="status info">جاهز</div>

            <label>أدخل IDs المنتجات (مفصولة بفواصل):</label>
            <input type="text" id="api-product-ids" placeholder="1,2,3">

            <button onclick="testDirectAPI()">اختبار API مباشر</button>
            <pre id="api-result"></pre>
        </div>

        <!-- Test 5: Logger Test -->
        <div class="test-section">
            <h2>5️⃣ اختبار Laravel Logger</h2>
            <div id="logger-status" class="status info">جاهز</div>
            <button onclick="testLogger()">اختبار تسجيل البيانات</button>
            <pre id="logger-result"></pre>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        console.log('🧪 TEST PAGE LOADED');

        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Test 1: Database Connection
        function testDatabase() {
            $('#db-status').removeClass().addClass('status info').text('جاري الاختبار...');
            $('#db-result').text('Connecting to database...');

            $.get('/api/health-check', function(data) {
                $('#db-status').removeClass().addClass('status success').text('✅ قاعدة البيانات متصلة');
                $('#db-result').text(JSON.stringify(data, null, 2));
            }).fail(function(xhr) {
                $('#db-status').removeClass().addClass('status error').text('❌ خطأ في الاتصال');
                $('#db-result').text('Error: ' + xhr.statusText + '\n' + xhr.responseText);
            });
        }

        // Test 2: Load Products
        function loadProducts() {
            $('#products-status').removeClass().addClass('status info').text('جاري التحميل...');
            $('#products-result').text('Loading products from database...');

            $.get('/admin/featured-section', function(html) {
                // Extract products from the page
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const productCards = doc.querySelectorAll('.product-card');

                $('#products-status').removeClass().addClass('status success').text(`✅ تم العثور على ${productCards.length} منتج`);

                let productsData = [];
                productCards.forEach((card, index) => {
                    const id = card.getAttribute('data-product-id');
                    const title = card.querySelector('.product-title')?.textContent || 'Unknown';
                    productsData.push({ id, title });
                });

                $('#products-result').text(JSON.stringify(productsData, null, 2));

                // Display products
                let html = '';
                productsData.forEach(p => {
                    html += `
                        <div class="product-card" data-id="${p.id}">
                            <span class="product-info">ID: ${p.id} - ${p.title}</span>
                            <button class="danger" onclick="removeTestProduct('${p.id}')">حذف</button>
                        </div>
                    `;
                });
                $('#selected-products').html(html);

            }).fail(function(xhr) {
                $('#products-status').removeClass().addClass('status error').text('❌ خطأ في التحميل');
                $('#products-result').text('Error: ' + xhr.statusText);
            });
        }

        function removeTestProduct(id) {
            console.log('🗑️ Removing product:', id);
            $(`.product-card[data-id="${id}"]`).fadeOut(300, function() {
                $(this).remove();
                console.log('✅ Product removed from DOM');
            });
        }

        // Test 3: Form Submission
        function submitTestForm(e) {
            e.preventDefault();

            $('#form-status').removeClass().addClass('status info').text('جاري الإرسال...');
            $('#form-result').text('Sending form data...');

            console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━');
            console.log('📝 FORM SUBMISSION TEST');

            const formData = new FormData(document.getElementById('test-form'));

            // Collect product IDs from remaining cards
            $('.product-card').each(function() {
                const id = $(this).data('id');
                formData.append('product_ids[]', id);
                console.log('Adding product ID:', id);
            });

            // Log form data
            const formObject = {};
            formData.forEach((value, key) => {
                if (formObject[key]) {
                    if (!Array.isArray(formObject[key])) {
                        formObject[key] = [formObject[key]];
                    }
                    formObject[key].push(value);
                } else {
                    formObject[key] = value;
                }
            });

            console.log('Form Data:', formObject);
            $('#form-result').text('Form Data:\n' + JSON.stringify(formObject, null, 2));

            // Submit
            $.ajax({
                url: '/admin/featured-section',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data, status, xhr) {
                    console.log('✅ SUCCESS:', data);
                    $('#form-status').removeClass().addClass('status success').text('✅ تم الإرسال بنجاح!');
                    $('#form-result').append('\n\nResponse:\n' + JSON.stringify(data, null, 2));

                    // Now check logs
                    setTimeout(checkLogs, 1000);
                },
                error: function(xhr, status, error) {
                    console.error('❌ ERROR:', error);
                    $('#form-status').removeClass().addClass('status error').text('❌ فشل الإرسال!');
                    $('#form-result').append('\n\nError:\n' + xhr.responseText);
                }
            });

            console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━');

            return false;
        }

        // Test 4: Direct API
        function testDirectAPI() {
            $('#api-status').removeClass().addClass('status info').text('جاري الاختبار...');

            const idsInput = $('#api-product-ids').val();
            const ids = idsInput.split(',').map(id => id.trim()).filter(id => id);

            console.log('🧪 Testing direct API with IDs:', ids);

            const data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                title_ar: 'اختبار مباشر',
                title_en: 'Direct Test',
                link_url: '/shop',
                link_text_ar: 'تسوق',
                link_text_en: 'Shop',
                is_active: 1,
                product_ids: ids
            };

            $('#api-result').text('Sending:\n' + JSON.stringify(data, null, 2));

            $.post('/admin/featured-section', data)
                .done(function(response) {
                    console.log('✅ API Success:', response);
                    $('#api-status').removeClass().addClass('status success').text('✅ نجح!');
                    $('#api-result').append('\n\nResponse:\n' + JSON.stringify(response, null, 2));
                    checkLogs();
                })
                .fail(function(xhr) {
                    console.error('❌ API Error:', xhr.responseText);
                    $('#api-status').removeClass().addClass('status error').text('❌ فشل!');
                    $('#api-result').append('\n\nError:\n' + xhr.responseText);
                });
        }

        // Test 5: Logger
        function testLogger() {
            $('#logger-status').removeClass().addClass('status info').text('جاري التحقق...');
            $('#logger-result').text('Checking Laravel logs...');

            // This would need a route to read the log file
            $('#logger-status').removeClass().addClass('status warning').text('⚠️ افتح storage/logs/laravel.log يدوياً');
            $('#logger-result').text('تحقق من الملف:\nstorage/logs/laravel.log\n\nابحث عن:\n🚀 Featured Section Update STARTED');
        }

        function checkLogs() {
            alert('الآن افتح:\nstorage/logs/laravel.log\n\nوابحث عن:\n🚀 Featured Section Update STARTED');
        }

        // Auto-load products on page load
        setTimeout(loadProducts, 1000);
    </script>
</body>
</html>
