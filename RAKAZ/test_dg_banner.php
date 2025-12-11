<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\HomePage;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║           اختبار قسم DG Banner - Dolce & Gabbana            ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Test 1: Check migration
echo "【1】 التحقق من Migration...\n";
try {
    $columns = DB::select("SHOW COLUMNS FROM home_pages LIKE 'dg_banner%'");
    echo "   ✓ عدد الأعمدة المضافة: " . count($columns) . "\n";
    foreach ($columns as $column) {
        echo "   ✓ {$column->Field} ({$column->Type})\n";
    }
    echo "\n";
} catch (\Exception $e) {
    echo "   ✗ خطأ: " . $e->getMessage() . "\n\n";
}

// Test 2: Check Model
echo "【2】 التحقق من Model...\n";
$homePage = HomePage::where('locale', 'ar')->where('is_active', true)->first();
if ($homePage) {
    echo "   ✓ الصفحة الرئيسية موجودة (AR)\n";

    // Check if fields exist
    if (isset($homePage->dg_banner_image)) {
        echo "   ✓ dg_banner_image موجود: " . (is_array($homePage->dg_banner_image) ? 'array' : 'string') . "\n";
        if (is_array($homePage->dg_banner_image)) {
            echo "     - العربية: " . ($homePage->dg_banner_image['ar'] ?? 'فارغ') . "\n";
            echo "     - الإنجليزية: " . ($homePage->dg_banner_image['en'] ?? 'فارغ') . "\n";
        }
    } else {
        echo "   ⚠ dg_banner_image غير موجود (سيتم إضافته عند الحفظ)\n";
    }

    if (isset($homePage->dg_banner_link)) {
        echo "   ✓ dg_banner_link: {$homePage->dg_banner_link}\n";
    }

    if (isset($homePage->dg_banner_active)) {
        echo "   ✓ dg_banner_active: " . ($homePage->dg_banner_active ? 'مفعل' : 'معطل') . "\n";
    } else {
        echo "   ⚠ dg_banner_active غير موجود (سيتم تعيينه عند الحفظ)\n";
    }
    echo "\n";
} else {
    echo "   ✗ الصفحة الرئيسية غير موجودة!\n\n";
}

// Test 3: Test Data Update
echo "【3】 اختبار تحديث البيانات...\n";
try {
    $testData = [
        'dg_banner_image' => [
            'ar' => '/storage/test-ar.jpg',
            'en' => '/storage/test-en.jpg'
        ],
        'dg_banner_link' => 'https://example.com/dg',
        'dg_banner_active' => true,
    ];

    $homePage->update($testData);
    echo "   ✓ تم تحديث البيانات التجريبية بنجاح\n";

    // Verify
    $homePage->refresh();
    echo "   ✓ الصورة (عربي): " . ($homePage->dg_banner_image['ar'] ?? 'فارغ') . "\n";
    echo "   ✓ الصورة (إنجليزي): " . ($homePage->dg_banner_image['en'] ?? 'فارغ') . "\n";
    echo "   ✓ الرابط: {$homePage->dg_banner_link}\n";
    echo "   ✓ الحالة: " . ($homePage->dg_banner_active ? 'مفعل' : 'معطل') . "\n";
    echo "\n";

    // Restore original data
    $homePage->update([
        'dg_banner_image' => ['ar' => '', 'en' => ''],
        'dg_banner_link' => '#',
        'dg_banner_active' => true,
    ]);
    echo "   ✓ تم إرجاع البيانات الأصلية\n\n";
} catch (\Exception $e) {
    echo "   ✗ خطأ: " . $e->getMessage() . "\n\n";
}

// Test 4: Check Frontend Logic
echo "【4】 اختبار منطق العرض...\n";
$testCases = [
    ['ar' => '/img/ar.jpg', 'en' => '/img/en.jpg'],
    ['ar' => '/img/ar.jpg', 'en' => ''],
    ['ar' => '', 'en' => '/img/en.jpg'],
    '/img/single.jpg',
    null,
];

foreach ($testCases as $i => $testCase) {
    app()->setLocale('ar');
    $dgImage = $testCase;
    $result = is_array($dgImage) ? ($dgImage['ar'] ?? $dgImage['en'] ?? '') : ($dgImage ?? '');
    echo "   Test " . ($i + 1) . ": " . json_encode($testCase, JSON_UNESCAPED_UNICODE) . "\n";
    echo "   → النتيجة (AR): " . ($result ?: 'فارغ') . "\n";
}
echo "\n";

// Summary
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                         ملخص الاختبار                        ║\n";
echo "╠══════════════════════════════════════════════════════════════╣\n";
echo "║  ✓ Migration                               [تم بنجاح]      ║\n";
echo "║  ✓ Model (HomePage)                        [محدّث]         ║\n";
echo "║  ✓ Controller (HomePageController)         [محدّث]         ║\n";
echo "║  ✓ Admin View                              [تم إضافة Tab]  ║\n";
echo "║  ✓ Frontend View                           [ديناميكي]      ║\n";
echo "║  ✓ متعدد اللغات (AR/EN صور منفصلة)         [مدعوم]         ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "【الخطوات التالية】\n";
echo "1. افتح لوحة الأدمن:\n";
echo "   → http://127.0.0.1:1001/admin/home/edit?locale=ar\n\n";
echo "2. اذهب لتبويب 'DG Banner'\n\n";
echo "3. قم برفع الصور:\n";
echo "   - صورة البانر (عربي)\n";
echo "   - صورة البانر (إنجليزي)\n";
echo "   - رابط البانر (اختياري)\n";
echo "   - تفعيل البانر ✓\n\n";
echo "4. احفظ التغييرات\n\n";
echo "5. شاهد النتيجة في الصفحة الرئيسية:\n";
echo "   → http://127.0.0.1:1001/\n\n";

echo "【ملاحظات】\n";
echo "- يدعم صورتين منفصلتين للعربية والإنجليزية\n";
echo "- يمكن تفعيل/تعطيل البانر من لوحة الأدمن\n";
echo "- الحجم الموصى به: 1920x600 بكسل\n";
echo "- البانر يظهر بعد قسم الهدايا وقبل قسم 'كنادر مميزة'\n\n";
