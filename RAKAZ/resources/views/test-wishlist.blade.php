<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>اختبار زر المفضلة</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f5f5f5;
        }
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .test-section {
            margin: 30px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .wishlist-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border: none;
            background: white;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .wishlist-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .wishlist-btn svg {
            width: 20px;
            height: 20px;
            stroke: #333;
            fill: none;
            transition: all 0.3s ease;
        }
        .wishlist-btn.active svg {
            fill: #e74c3c;
            stroke: #e74c3c;
        }
        .status {
            margin-top: 20px;
            padding: 15px;
            background: #e8f5e9;
            border-radius: 4px;
            display: none;
        }
        .status.error {
            background: #ffebee;
        }
        .log {
            margin-top: 20px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 4px;
            max-height: 300px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 12px;
        }
        .log-entry {
            margin: 5px 0;
            padding: 5px;
        }
        .log-entry.success {
            color: #27ae60;
        }
        .log-entry.error {
            color: #e74c3c;
        }
        .log-entry.info {
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🧪 اختبار نظام المفضلة</h1>

        <div class="test-section">
            <h2>الاختبار 1: زر المفضلة الأساسي</h2>
            <p>انقر على الزر أدناه لاختبار إضافة/حذف المنتج من المفضلة</p>
            <button class="wishlist-btn" data-product-id="3">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
            </button>
            <div class="status" id="status1"></div>
        </div>

        <div class="test-section">
            <h2>الاختبار 2: اختبار منتجات متعددة</h2>
            <p>اختبر إضافة منتجات مختلفة</p>
            @foreach([1, 2, 3, 4, 5] as $productId)
            <button class="wishlist-btn" data-product-id="{{ $productId }}" style="margin: 5px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
            </button>
            @endforeach
        </div>

        <div class="test-section">
            <h2>📊 سجل الأحداث (Console Log)</h2>
            <div class="log" id="logContainer">
                <div class="log-entry info">جاهز للاختبار...</div>
            </div>
        </div>

        <div class="test-section">
            <h2>ℹ️ معلومات النظام</h2>
            <ul>
                <li>CSRF Token: <code>{{ csrf_token() }}</code></li>
                <li>Route: <code>{{ route('wishlist.toggle') }}</code></li>
                <li>User: <strong>{{ auth()->check() ? auth()->user()->name : 'غير مسجل' }}</strong></li>
                <li>User ID: <strong>{{ auth()->check() ? auth()->id() : 'N/A' }}</strong></li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Logging function
        function addLog(message, type = 'info') {
            const logContainer = document.getElementById('logContainer');
            const entry = document.createElement('div');
            entry.className = `log-entry ${type}`;
            entry.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
            logContainer.appendChild(entry);
            logContainer.scrollTop = logContainer.scrollHeight;
            console.log(`[${type.toUpperCase()}] ${message}`);
        }

        document.addEventListener('DOMContentLoaded', function() {
            addLog('✅ Wishlist test system initialized');

            const wishlistButtons = document.querySelectorAll('.wishlist-btn');
            addLog(`Found ${wishlistButtons.length} wishlist buttons`);

            wishlistButtons.forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.preventDefault();

                    const productId = this.dataset.productId;
                    addLog(`💗 Button clicked for product ID: ${productId}`, 'info');

                    @guest
                        addLog('⚠️ User not authenticated', 'error');
                        Swal.fire({
                            icon: 'warning',
                            title: 'يجب تسجيل الدخول',
                            text: 'يجب عليك تسجيل الدخول أولاً',
                            confirmButtonText: 'تسجيل الدخول',
                            confirmButtonColor: '#333'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route("login") }}';
                            }
                        });
                        return;
                    @endguest

                    if (!productId) {
                        addLog('❌ Product ID is missing!', 'error');
                        return;
                    }

                    const button = this;
                    button.disabled = true;
                    button.style.opacity = '0.6';

                    addLog('📤 Sending POST request to {{ route("wishlist.toggle") }}', 'info');

                    try {
                        const response = await fetch('{{ route("wishlist.toggle") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ product_id: productId })
                        });

                        addLog(`📥 Response status: ${response.status}`, response.ok ? 'success' : 'error');

                        const data = await response.json();
                        addLog(`📦 Response data: ${JSON.stringify(data)}`, 'info');

                        if (data.success) {
                            button.classList.toggle('active');
                            addLog(`✅ Success! isAdded: ${data.isAdded}`, 'success');

                            Swal.fire({
                                icon: 'success',
                                title: data.isAdded ? '✨ تمت الإضافة بنجاح!' : '🗑️ تم الحذف',
                                text: data.isAdded
                                    ? 'تم إضافة المنتج إلى قائمة الأمنيات بنجاح 💝'
                                    : 'تم حذف المنتج من قائمة الأمنيات',
                                timer: 2000,
                                showConfirmButton: false,
                                position: 'top-end',
                                toast: true
                            });
                        } else {
                            addLog(`❌ Failed: ${data.message}`, 'error');
                            throw new Error(data.message || 'Unknown error');
                        }
                    } catch (error) {
                        addLog(`❌ Error: ${error.message}`, 'error');
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: error.message,
                            confirmButtonColor: '#d33'
                        });
                    } finally {
                        button.disabled = false;
                        button.style.opacity = '1';
                    }
                });
            });

            addLog('🎯 All event listeners attached successfully!', 'success');
        });
    </script>
</body>
</html>
