<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$homePage = App\Models\HomePage::first();

echo "=== Gifts Section Data ===\n\n";
echo "Gifts Section Active: " . ($homePage->gifts_section_active ? 'true' : 'false') . PHP_EOL;
echo "Gifts Section Title (AR): " . ($homePage->gifts_section_title['ar'] ?? 'null') . PHP_EOL;
echo "Gifts Section Title (EN): " . ($homePage->gifts_section_title['en'] ?? 'null') . PHP_EOL;
echo "\nGifts Items Count: " . count($homePage->gifts_items ?? []) . PHP_EOL;

if ($homePage->gifts_items) {
    echo "\n--- Gifts Items ---\n";
    foreach ($homePage->gifts_items as $index => $gift) {
        echo "\nGift #" . ($index + 1) . ":\n";
        echo "  Title (AR): " . ($gift['title']['ar'] ?? 'null') . PHP_EOL;
        echo "  Title (EN): " . ($gift['title']['en'] ?? 'null') . PHP_EOL;
        echo "  Image: " . ($gift['image'] ?? 'null') . PHP_EOL;
        echo "  Link: " . ($gift['link'] ?? 'null') . PHP_EOL;
    }
}
