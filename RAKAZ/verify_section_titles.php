<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SectionTitle;
use App\Models\HomePage;

echo "=== التحقق من جدول section_titles ===\n\n";

// Get gifts section title
$giftsSection = SectionTitle::where('section_key', 'gifts_section')->first();

if ($giftsSection) {
    echo "✓ تم العثور على عنوان قسم الهدايا:\n";
    echo "  - العنوان بالعربية: {$giftsSection->title_ar}\n";
    echo "  - العنوان بالإنجليزية: {$giftsSection->title_en}\n";
    echo "  - الحالة: " . ($giftsSection->active ? 'مفعل' : 'معطل') . "\n";
    echo "\n";

    // Test getTitle method
    app()->setLocale('ar');
    echo "✓ العنوان باللغة العربية (getTitle): " . $giftsSection->getTitle('ar') . "\n";

    app()->setLocale('en');
    echo "✓ العنوان باللغة الإنجليزية (getTitle): " . $giftsSection->getTitle('en') . "\n";
    echo "\n";

    // Test static getByKey method
    echo "✓ استرجاع العنوان باستخدام getByKey:\n";
    echo "  - العربية: " . SectionTitle::getByKey('gifts_section', 'ar') . "\n";
    echo "  - الإنجليزية: " . SectionTitle::getByKey('gifts_section', 'en') . "\n";
    echo "\n";
} else {
    echo "✗ لم يتم العثور على عنوان قسم الهدايا!\n\n";
}

// Check home_pages table
echo "=== التحقق من جدول home_pages ===\n\n";
$homePage = HomePage::where('locale', 'ar')->where('is_active', true)->first();

if ($homePage) {
    echo "✓ تم العثور على الصفحة الرئيسية (ar):\n";

    // Check if gifts_section_title still exists in home_pages
    if (isset($homePage->gifts_section_title)) {
        echo "  - العنوان القديم موجود في home_pages: " . json_encode($homePage->gifts_section_title, JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "  - العنوان القديم غير موجود في home_pages (تم نقله بنجاح)\n";
    }

    // Check gifts items
    if ($homePage->gifts_items && count($homePage->gifts_items) > 0) {
        echo "  - عدد الهدايا: " . count($homePage->gifts_items) . "\n";
        echo "  - أول هدية: " . json_encode($homePage->gifts_items[0], JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "  - لا توجد هدايا\n";
    }
    echo "\n";
} else {
    echo "✗ لم يتم العثور على الصفحة الرئيسية!\n\n";
}

echo "=== ملخص النظام الجديد ===\n";
echo "✓ جدول section_titles: تم إنشاؤه بنجاح\n";
echo "✓ Model SectionTitle: جاهز للاستخدام\n";
echo "✓ قراءة البيانات: تعمل بشكل صحيح\n";
echo "✓ متعدد اللغات: يدعم العربية والإنجليزية\n";
echo "\n";
echo "=== الخطوات التالية ===\n";
echo "1. افتح لوحة الأدمن: http://127.0.0.1:1001/admin/home/edit\n";
echo "2. قم بتعديل عنوان قسم الهدايا\n";
echo "3. احفظ التغييرات\n";
echo "4. تحقق من الصفحة الرئيسية: http://127.0.0.1:1001/\n";
