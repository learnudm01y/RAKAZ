<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$section = App\Models\PerfectGiftSection::first();
if($section) {
    echo 'قسم الهدية المثالية موجود:' . PHP_EOL;
    echo '- العنوان (عربي): ' . $section->getTitle('ar') . PHP_EOL;
    echo '- العنوان (إنجليزي): ' . $section->getTitle('en') . PHP_EOL;
    echo '- عدد المنتجات: ' . $section->products()->count() . PHP_EOL;

    echo PHP_EOL . 'المنتجات المرتبطة:' . PHP_EOL;
    foreach($section->products as $product) {
        $name = is_array($product->name) ? $product->name : json_decode($product->name, true);
        $gallery = is_array($product->gallery_images) ? $product->gallery_images : json_decode($product->gallery_images, true);
        echo '  ' . $product->id . '. ' . ($name['ar'] ?? 'غير محدد') . ' - ' . $product->price . ' ريال';
        echo ' - عدد الصور: ' . count($gallery) . PHP_EOL;
    }
} else {
    echo 'القسم غير موجود في قاعدة البيانات!' . PHP_EOL;
}
