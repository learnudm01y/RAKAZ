<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار قسم الهدية المثالية</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .test-info {
            background: #f0f0f0;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .test-info h2 {
            margin-top: 0;
        }
        .success {
            color: #28a745;
        }
        .error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="test-info">
        <h2>اختبار قسم الهدية المثالية</h2>
        <p>هذه الصفحة تعرض قسم الهدية المثالية للتأكد من أن جميع العناصر تعمل بشكل صحيح:</p>
        <ul>
            <li>✅ عرض المنتجات</li>
            <li>✅ الصور الرئيسية والثانوية</li>
            <li>✅ Overlay عند الـ Hover</li>
            <li>✅ معرض الصور</li>
            <li>✅ القياسات</li>
            <li>✅ الألوان</li>
            <li>✅ أزرار التنقل</li>
            <li>✅ Session Storage للصور</li>
        </ul>
    </div>

    <?php
    require __DIR__ . '/vendor/autoload.php';

    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make('Illuminate\Contracts\Console\Kernel');
    $kernel->bootstrap();

    // Set locale
    app()->setLocale('ar');

    // Get Perfect Gift Section
    $perfectGiftSection = App\Models\PerfectGiftSection::where('is_active', true)
        ->with(['products' => function($query) {
            $query->where('is_active', true)
                  ->with(['productSizes', 'productColors', 'productShoeSizes']);
        }])
        ->first();

    if ($perfectGiftSection && $perfectGiftSection->products->count() > 0) {
        echo '<div class="test-info success">';
        echo '<p><strong>✅ تم العثور على قسم الهدية المثالية</strong></p>';
        echo '<p>العنوان: ' . $perfectGiftSection->getTitle('ar') . '</p>';
        echo '<p>عدد المنتجات: ' . $perfectGiftSection->products->count() . '</p>';
        echo '</div>';

        // Include the section template
        include __DIR__ . '/resources/views/frontend/partials/perfect-gift-section.blade.php';
    } else {
        echo '<div class="test-info error">';
        echo '<p><strong>❌ لم يتم العثور على قسم الهدية المثالية أو لا يحتوي على منتجات</strong></p>';
        echo '<p>قم بتشغيل: <code>php artisan db:seed --class=PerfectGiftSectionSeeder</code></p>';
        echo '</div>';
    }
    ?>

    <script src="/assets/js/perfect-gift-section.js"></script>
</body>
</html>
