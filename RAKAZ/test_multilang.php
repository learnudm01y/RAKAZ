<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Multi-language Support ===\n\n";

// Test Arabic
app()->setLocale('ar');
$homePage = App\Models\HomePage::first();
echo "Arabic Locale:\n";
echo "  Section Title: " . $homePage->getTranslation('gifts_section_title') . "\n";
if ($homePage->gifts_items && count($homePage->gifts_items) > 0) {
    foreach ($homePage->gifts_items as $index => $gift) {
        echo "  Gift " . ($index + 1) . " Title: " . ($gift['title'][app()->getLocale()] ?? 'N/A') . "\n";
    }
}

echo "\n";

// Test English
app()->setLocale('en');
$homePage = App\Models\HomePage::first();
echo "English Locale:\n";
echo "  Section Title: " . $homePage->getTranslation('gifts_section_title') . "\n";
if ($homePage->gifts_items && count($homePage->gifts_items) > 0) {
    foreach ($homePage->gifts_items as $index => $gift) {
        echo "  Gift " . ($index + 1) . " Title: " . ($gift['title'][app()->getLocale()] ?? 'N/A') . "\n";
    }
}

echo "\n=== Database Structure ===\n";
echo "gifts_section_title stored as: " . json_encode($homePage->gifts_section_title) . "\n";
echo "gifts_items[0] stored as: " . json_encode($homePage->gifts_items[0] ?? []) . "\n";
