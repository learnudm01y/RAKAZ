<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 اختبار نظام المفضلة - تشخيص شامل</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .section {
            padding: 30px;
            border-bottom: 2px solid #f0f0f0;
        }
        .section:last-child {
            border-bottom: none;
        }
        .section h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .test-item {
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .test-item.success {
            background: #d4edda;
            border-right: 5px solid #28a745;
        }
        .test-item.error {
            background: #f8d7da;
            border-right: 5px solid #dc3545;
        }
        .test-item.warning {
            background: #fff3cd;
            border-right: 5px solid #ffc107;
        }
        .badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge.success { background: #28a745; color: white; }
        .badge.error { background: #dc3545; color: white; }
        .badge.warning { background: #ffc107; color: black; }
        .code {
            background: #282c34;
            color: #61dafb;
            padding: 15px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            margin: 10px 0;
            overflow-x: auto;
        }
        .button-test {
            text-align: center;
            padding: 40px;
            background: #f8f9fa;
        }
        .wishlist-btn-demo {
            position: relative;
            width: 60px;
            height: 60px;
            border: none;
            background: white;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .wishlist-btn-demo:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        .wishlist-btn-demo svg {
            width: 30px;
            height: 30px;
            stroke: #667eea;
            fill: none;
            transition: all 0.3s ease;
        }
        .wishlist-btn-demo.active svg {
            fill: #e74c3c;
            stroke: #e74c3c;
        }
        .log-container {
            background: #282c34;
            color: #abb2bf;
            padding: 20px;
            border-radius: 10px;
            max-height: 400px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
        .log-entry {
            padding: 5px 0;
            border-bottom: 1px solid #3e4451;
        }
        .log-entry:last-child {
            border-bottom: none;
        }
        .log-entry.success { color: #98c379; }
        .log-entry.error { color: #e06c75; }
        .log-entry.info { color: #61dafb; }
        .log-entry.warning { color: #e5c07b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 نظام تشخيص المفضلة الشامل</h1>
            <p>اختبار جميع مكونات نظام Wishlist</p>
        </div>

        <div class="section">
            <h2>📋 1. فحص البيئة</h2>
            <div id="envTests"></div>
        </div>

        <div class="section">
            <h2>🌐 2. فحص الشبكة والـ Routes</h2>
            <div id="networkTests"></div>
        </div>

        <div class="section">
            <h2>🔐 3. فحص المصادقة</h2>
            <div id="authTests"></div>
        </div>

        <div class="section">
            <h2>🎯 4. اختبار الزر التفاعلي</h2>
            <div class="button-test">
                <p style="margin-bottom: 20px; color: #666;">انقر على الزر لاختبار الوظيفة</p>
                <button class="wishlist-btn-demo" data-product-id="3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                </button>
                <p style="margin-top: 20px; color: #999; font-size: 14px;">Product ID: 3</p>
            </div>
        </div>

        <div class="section">
            <h2>📊 5. سجل الأحداث المباشر</h2>
            <div class="log-container" id="logContainer">
                <div class="log-entry info">[جاهز] نظام التشخيص جاهز للعمل...</div>
            </div>
        </div>

        <div class="section">
            <h2>💡 6. معلومات النظام</h2>
            <div id="systemInfo"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Logging system
        function addLog(message, type = 'info') {
            const logContainer = document.getElementById('logContainer');
            const entry = document.createElement('div');
            entry.className = `log-entry ${type}`;
            const time = new Date().toLocaleTimeString('ar-EG');
            entry.textContent = `[${time}] ${message}`;
            logContainer.appendChild(entry);
            logContainer.scrollTop = logContainer.scrollHeight;
            console.log(`[${type.toUpperCase()}] ${message}`);
        }

        // Test item creator
        function createTestItem(name, status, message = '') {
            const icons = {
                success: '✅',
                error: '❌',
                warning: '⚠️'
            };
            return `
                <div class="test-item ${status}">
                    <span>${icons[status]} ${name}</span>
                    <div>
                        ${message ? `<span style="margin-left: 10px; color: #666; font-size: 12px;">${message}</span>` : ''}
                        <span class="badge ${status}">${status === 'success' ? 'نجح' : status === 'error' ? 'فشل' : 'تحذير'}</span>
                    </div>
                </div>
            `;
        }

        // Run all tests
        async function runTests() {
            addLog('🚀 بدء الاختبارات الشاملة...', 'info');

            // 1. Environment Tests
            let envHTML = '';

            // Check SweetAlert
            if (typeof Swal !== 'undefined') {
                envHTML += createTestItem('مكتبة SweetAlert2', 'success', 'محملة بنجاح');
                addLog('✅ SweetAlert2 جاهز', 'success');
            } else {
                envHTML += createTestItem('مكتبة SweetAlert2', 'error', 'غير محملة');
                addLog('❌ SweetAlert2 غير موجود', 'error');
            }

            // Check Fetch API
            if (typeof fetch !== 'undefined') {
                envHTML += createTestItem('Fetch API', 'success', 'متوفر');
                addLog('✅ Fetch API متوفر', 'success');
            } else {
                envHTML += createTestItem('Fetch API', 'error', 'غير متوفر');
                addLog('❌ Fetch API غير متوفر', 'error');
            }

            // Check async/await
            try {
                await new Promise(resolve => setTimeout(resolve, 10));
                envHTML += createTestItem('async/await Support', 'success', 'متوفر');
                addLog('✅ async/await مدعوم', 'success');
            } catch (e) {
                envHTML += createTestItem('async/await Support', 'error', 'غير متوفر');
                addLog('❌ async/await غير مدعوم', 'error');
            }

            document.getElementById('envTests').innerHTML = envHTML;

            // 2. Network Tests
            let networkHTML = '';

            // Check CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken && csrfToken.content) {
                networkHTML += createTestItem('CSRF Token', 'success', csrfToken.content.substring(0, 20) + '...');
                addLog('✅ CSRF Token موجود', 'success');
            } else {
                networkHTML += createTestItem('CSRF Token', 'error', 'غير موجود');
                addLog('❌ CSRF Token غير موجود', 'error');
            }

            // Check Route URL
            const routeUrl = "{{ route('wishlist.toggle') }}";
            if (routeUrl && !routeUrl.includes('{{')) {
                networkHTML += createTestItem('Wishlist Route', 'success', routeUrl);
                addLog(`✅ Route: ${routeUrl}`, 'success');
            } else {
                networkHTML += createTestItem('Wishlist Route', 'error', 'Route غير محدد');
                addLog('❌ Route غير صحيح', 'error');
            }

            document.getElementById('networkTests').innerHTML = networkHTML;

            // 3. Auth Tests
            let authHTML = '';
            @auth
                authHTML += createTestItem('حالة تسجيل الدخول', 'success', 'مسجل دخول');
                authHTML += createTestItem('User ID', 'success', '{{ auth()->id() }}');
                authHTML += createTestItem('اسم المستخدم', 'success', '{{ auth()->user()->name }}');
                addLog('✅ المستخدم مسجل دخول', 'success');
            @else
                authHTML += createTestItem('حالة تسجيل الدخول', 'warning', 'غير مسجل');
                addLog('⚠️ المستخدم غير مسجل دخول', 'warning');
            @endauth

            document.getElementById('authTests').innerHTML = authHTML;

            // 6. System Info
            let systemHTML = `
                <div class="code">
                    <div><strong>🌐 URL الحالي:</strong> ${window.location.href}</div>
                    <div><strong>🕐 الوقت:</strong> ${new Date().toLocaleString('ar-EG')}</div>
                    <div><strong>🖥️ المتصفح:</strong> ${navigator.userAgent}</div>
                    <div><strong>📱 الشاشة:</strong> ${window.innerWidth} x ${window.innerHeight}</div>
                </div>
            `;
            document.getElementById('systemInfo').innerHTML = systemHTML;

            addLog('✅ جميع الاختبارات اكتملت', 'success');
        }

        // Wishlist button handler
        document.addEventListener('DOMContentLoaded', function() {
            addLog('🎯 تهيئة زر المفضلة التجريبي...', 'info');

            const wishlistBtn = document.querySelector('.wishlist-btn-demo');

            wishlistBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                addLog('💗 تم النقر على الزر!', 'info');

                const productId = this.dataset.productId;
                addLog(`📦 Product ID: ${productId}`, 'info');

                @guest
                    addLog('⚠️ المستخدم غير مسجل', 'warning');
                    Swal.fire({
                        icon: 'warning',
                        title: 'يجب تسجيل الدخول',
                        text: 'يجب عليك تسجيل الدخول أولاً',
                        confirmButtonText: 'تسجيل الدخول',
                        showCancelButton: true,
                        cancelButtonText: 'إلغاء',
                        confirmButtonColor: '#667eea'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('login') }}";
                        }
                    });
                    return;
                @endguest

                this.disabled = true;
                this.style.opacity = '0.6';
                addLog('⏳ جاري الإرسال...', 'info');

                try {
                    const url = "{{ route('wishlist.toggle') }}";
                    const token = document.querySelector('meta[name="csrf-token"]').content;

                    addLog(`📤 POST → ${url}`, 'info');

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_id: productId })
                    });

                    addLog(`📥 Response Status: ${response.status}`, 'info');

                    const data = await response.json();
                    addLog(`📦 Data: ${JSON.stringify(data)}`, 'success');

                    if (data.success) {
                        this.classList.toggle('active');
                        addLog(`✅ ${data.isAdded ? 'تمت الإضافة' : 'تم الحذف'}`, 'success');

                        Swal.fire({
                            icon: 'success',
                            title: data.isAdded ? '✨ تمت الإضافة!' : '🗑️ تم الحذف',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            position: 'top-end',
                            toast: true,
                            background: data.isAdded ? '#d4edda' : '#f8d7da'
                        });
                    } else {
                        addLog('❌ فشلت العملية', 'error');
                    }
                } catch (error) {
                    addLog(`❌ خطأ: ${error.message}`, 'error');
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: error.message,
                        confirmButtonColor: '#dc3545'
                    });
                } finally {
                    this.disabled = false;
                    this.style.opacity = '1';
                }
            });

            addLog('✅ الزر جاهز للاختبار', 'success');
        });

        // Run tests on page load
        window.addEventListener('load', runTests);
    </script>
</body>
</html>
