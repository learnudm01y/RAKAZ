<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Checking Current Image Structure ===\n\n";

$homePage = App\Models\HomePage::first();

if ($homePage->gifts_items) {
    foreach ($homePage->gifts_items as $index => $gift) {
        echo "Gift #" . ($index + 1) . ":\n";
        echo "  Current structure: " . (isset($gift['image']) ? 'Single Image' : 'Unknown') . "\n";
        echo "  Image value: " . ($gift['image'] ?? 'null') . "\n\n";
    }
}

echo "\n=== QUESTION ===\n";
echo "Current: Each gift has ONE image for both languages.\n";
echo "Do you want: Each gift to have TWO images (one Arabic, one English)?\n";
echo "\nIf YES, I will update the structure to:\n";
echo "  'image' => [\n";
echo "    'ar' => '/path/to/arabic-image.jpg',\n";
echo "    'en' => '/path/to/english-image.jpg'\n";
echo "  ]\n";
