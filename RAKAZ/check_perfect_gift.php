<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check Perfect Gift Section
$section = App\Models\PerfectGiftSection::with('products')->first();

if ($section) {
    echo "✅ Perfect Gift Section Found\n";
    echo "   Title (AR): " . $section->title['ar'] . "\n";
    echo "   Title (EN): " . $section->title['en'] . "\n";
    echo "   Link URL: " . $section->link_url . "\n";
    echo "   Active: " . ($section->is_active ? 'Yes' : 'No') . "\n";
    echo "   Products Count: " . $section->products->count() . "\n\n";

    if ($section->products->count() > 0) {
        echo "📦 Products:\n";
        foreach ($section->products->take(3) as $index => $product) {
            echo "   " . ($index + 1) . ". " . $product->getName() . " (ID: {$product->id})\n";
            echo "      Main Image: " . ($product->main_image ? '✓' : '✗') . "\n";
            echo "      Gallery Images: " . (is_array($product->gallery_images) ? count($product->gallery_images) : 0) . "\n";
        }
        if ($section->products->count() > 3) {
            echo "   ... and " . ($section->products->count() - 3) . " more products\n";
        }
    }
} else {
    echo "❌ Perfect Gift Section Not Found\n";
    echo "   Run: php artisan db:seed --class=PerfectGiftSectionSeeder\n";
}
