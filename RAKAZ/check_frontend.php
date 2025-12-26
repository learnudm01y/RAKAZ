<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\FeaturedSection;

echo "=== Checking Frontend Data Output ===" . PHP_EOL . PHP_EOL;

$featuredSection = FeaturedSection::where('is_active', true)
    ->with(['products' => function($query) {
        $query->where('is_active', true)
              ->with(['productSizes', 'productColors', 'productShoeSizes']);
    }])
    ->first();

if (!$featuredSection || !$featuredSection->products) {
    echo "❌ No featured section or products found!" . PHP_EOL;
    exit;
}

echo "✅ Featured Section: " . $featuredSection->getTitle() . PHP_EOL;
echo "✅ Products Count: " . $featuredSection->products->count() . PHP_EOL . PHP_EOL;

// Simulate what the Blade template sees
foreach ($featuredSection->products as $index => $product) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
    echo "Product #" . ($index + 1) . ": " . $product->getName() . PHP_EOL;
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;

    // Gallery Images (what Blade sees)
    $hasGalleryImages = $product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0;
    echo "Gallery Images Check: " . ($hasGalleryImages ? "✅ YES" : "❌ NO") . PHP_EOL;

    if ($hasGalleryImages) {
        $totalGalleryImages = 1 + count($product->gallery_images); // main + gallery
        echo "Total Gallery Images: {$totalGalleryImages}" . PHP_EOL;
        echo "Show Navigation Buttons: " . ($totalGalleryImages > 3 ? "✅ YES (more than 3)" : "❌ NO (3 or less)") . PHP_EOL;
        echo "Images:" . PHP_EOL;
        if ($product->main_image) {
            echo "  1. [MAIN] {$product->main_image}" . PHP_EOL;
        }
        foreach ($product->gallery_images as $idx => $img) {
            echo "  " . ($idx + 2) . ". [GALLERY] {$img}" . PHP_EOL;
        }
    }

    // Sizes (what Blade sees)
    $hasRegularSizes = $product->productSizes && $product->productSizes->count() > 0;
    $hasShoeSizes = $product->productShoeSizes && $product->productShoeSizes->count() > 0;
    $hasAnySizes = $hasRegularSizes || $hasShoeSizes;

    echo "Has Any Sizes: " . ($hasAnySizes ? "✅ YES" : "❌ NO") . PHP_EOL;

    if ($hasAnySizes) {
        $totalSizes = ($hasRegularSizes ? $product->productSizes->count() : 0) +
                     ($hasShoeSizes ? $product->productShoeSizes->count() : 0);
        echo "Total Sizes: {$totalSizes}" . PHP_EOL;
        echo "Show Navigation Buttons: " . ($totalSizes > 3 ? "✅ YES (more than 3)" : "❌ NO (3 or less)") . PHP_EOL;

        if ($hasRegularSizes) {
            echo "Regular Sizes: ";
            foreach ($product->productSizes as $size) {
                echo $size->name . " ";
            }
            echo PHP_EOL;
        }

        if ($hasShoeSizes) {
            echo "Shoe Sizes: ";
            foreach ($product->productShoeSizes as $size) {
                echo $size->size . " ";
            }
            echo PHP_EOL;
        }
    }

    // Colors (what Blade sees)
    $hasColors = $product->productColors && $product->productColors->count() > 0;
    echo "Has Colors: " . ($hasColors ? "✅ YES (" . $product->productColors->count() . ")" : "❌ NO") . PHP_EOL;

    if ($hasColors) {
        echo "Colors: ";
        foreach ($product->productColors as $color) {
            $colorName = is_array($color->name) ? ($color->name['ar'] ?? 'Unknown') : $color->name;
            echo "{$colorName} ({$color->hex_code}) ";
        }
        echo PHP_EOL;
    }

    echo PHP_EOL;
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
echo "✅ Frontend data check complete!" . PHP_EOL;
