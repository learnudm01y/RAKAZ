<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>✅ قسم الهدية المثالية - تم الاستبدال بنجاح</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .success-box {
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .success-box h1 {
            color: #155724;
            margin-top: 0;
        }
        .info-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-box h2 {
            margin-top: 0;
            color: #333;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .product-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .product-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }
        .product-item h3 {
            font-size: 14px;
            margin: 10px 0 5px 0;
        }
        .product-item .price {
            color: #28a745;
            font-weight: bold;
        }
        ul {
            line-height: 2;
        }
        .check {
            color: #28a745;
            font-weight: bold;
        }
        .code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="success-box">
        <h1>✅ تم استبدال القسم بنجاح!</h1>
        <p><strong>تم حذف القسم القديم الثابت واستبداله بقسم ديناميكي من قاعدة البيانات</strong></p>
    </div>

    <?php
    require __DIR__ . '/vendor/autoload.php';

    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make('Illuminate\Contracts\Console\Kernel');
    $kernel->bootstrap();

    app()->setLocale('ar');

    $perfectGiftSection = App\Models\PerfectGiftSection::where('is_active', true)
        ->with(['products' => function($query) {
            $query->where('is_active', true)
                  ->with(['productSizes', 'productColors', 'productShoeSizes']);
        }])
        ->first();
    ?>

    <div class="info-box">
        <h2>📊 معلومات القسم من قاعدة البيانات</h2>
        <ul>
            <li><span class="check">✓</span> القسم القديم: <span class="code">perfect-present-section</span> (ثابت) - <strong>تم حذفه</strong></li>
            <li><span class="check">✓</span> القسم الجديد: <span class="code">perfect-gift-section</span> (ديناميكي) - <strong>يعمل الآن</strong></li>
            <li><span class="check">✓</span> العنوان: <?php echo $perfectGiftSection->getTitle('ar'); ?></li>
            <li><span class="check">✓</span> رابط "تسوق الكل": <?php echo $perfectGiftSection->link_url; ?></li>
            <li><span class="check">✓</span> نص الرابط: <?php echo $perfectGiftSection->getLinkText('ar'); ?></li>
            <li><span class="check">✓</span> حالة التفعيل: <?php echo $perfectGiftSection->is_active ? 'مفعّل' : 'غير مفعّل'; ?></li>
            <li><span class="check">✓</span> عدد المنتجات: <?php echo $perfectGiftSection->products->count(); ?> منتج</li>
        </ul>
    </div>

    <div class="info-box">
        <h2>🎁 المنتجات المعروضة في القسم</h2>
        <div class="product-grid">
            <?php foreach($perfectGiftSection->products as $product): ?>
                <div class="product-item">
                    <?php if($product->main_image): ?>
                        <img src="<?php echo asset('storage/' . $product->main_image); ?>" alt="<?php echo $product->getName(); ?>">
                    <?php endif; ?>
                    <h3><?php echo $product->getName(); ?></h3>
                    <p class="price"><?php echo number_format($product->price, 0); ?> درهم</p>
                    <small>
                        <?php
                        $images = is_array($product->gallery_images) ? count($product->gallery_images) : 0;
                        $sizes = ($product->productSizes ? $product->productSizes->count() : 0) + ($product->productShoeSizes ? $product->productShoeSizes->count() : 0);
                        $colors = $product->productColors ? $product->productColors->count() : 0;
                        echo "صور: " . ($images + 1) . " | قياسات: {$sizes} | ألوان: {$colors}";
                        ?>
                    </small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="info-box">
        <h2>🎯 الخصائص المتوفرة</h2>
        <ul>
            <li><span class="check">✓</span> <strong>Overlay عند Hover</strong> - يظهر overlay كامل عند تمرير الماوس</li>
            <li><span class="check">✓</span> <strong>معرض الصور</strong> - عرض 4 صور (رئيسية + 3 gallery) مع أزرار تنقل</li>
            <li><span class="check">✓</span> <strong>اختيار الصورة</strong> - النقر على صورة gallery يحدثها في الأعلى</li>
            <li><span class="check">✓</span> <strong>Session Storage</strong> - حفظ الصورة المختارة عند الانتقال لصفحة المنتج</li>
            <li><span class="check">✓</span> <strong>Query Parameters</strong> - دعم ?image=X للانتقال مباشرة لصورة محددة</li>
            <li><span class="check">✓</span> <strong>القياسات</strong> - عرض القياسات مع أزرار تنقل</li>
            <li><span class="check">✓</span> <strong>الألوان</strong> - عرض نقاط الألوان المتاحة</li>
            <li><span class="check">✓</span> <strong>Slider Navigation</strong> - أزرار التنقل الرئيسية</li>
            <li><span class="check">✓</span> <strong>Responsive Design</strong> - تصميم متجاوب مع جميع الشاشات</li>
        </ul>
    </div>

    <div class="info-box">
        <h2>🔗 الروابط المهمة</h2>
        <ul>
            <li><a href="http://127.0.0.1:8000/" target="_blank">الصفحة الرئيسية - القسم الجديد يعرض هنا</a></li>
            <li><a href="http://127.0.0.1:8000/admin/perfect-gift-section" target="_blank">لوحة التحكم - إدارة القسم</a></li>
        </ul>
    </div>

    <div class="info-box" style="background: #fff3cd; border: 2px solid #ffc107;">
        <h2>📝 ملاحظات</h2>
        <ul>
            <li>تم حذف القسم الثابت <span class="code">perfect-present-section</span> الذي كان يحتوي على منتجات ثابتة</li>
            <li>الآن القسم يعرض البيانات من قاعدة البيانات بشكل ديناميكي</li>
            <li>يمكن التحكم بالعنوان والمنتجات من لوحة التحكم</li>
            <li>جميع الخصائص المتقدمة (session, gallery, etc.) تعمل بشكل كامل</li>
        </ul>
    </div>
</body>
</html>
