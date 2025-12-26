<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\FeaturedSection;

echo "=== Checking Featured Section Data ===" . PHP_EOL . PHP_EOL;

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

echo "✅ Found Featured Section with " . $featuredSection->products->count() . " products" . PHP_EOL . PHP_EOL;

foreach ($featuredSection->products->take(5) as $index => $product) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
    echo "Product #" . ($index + 1) . ": " . $product->getName() . " (ID: {$product->id})" . PHP_EOL;
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;

    // Check Main Image
    echo "📸 Main Image: " . ($product->main_image ? "✅ {$product->main_image}" : "❌ NONE") . PHP_EOL;

    // Check Gallery Images
    $galleryCount = is_array($product->gallery_images) ? count($product->gallery_images) : 0;
    echo "🖼️  Gallery Images: " . ($galleryCount > 0 ? "✅ {$galleryCount} images" : "❌ NONE") . PHP_EOL;
    if ($galleryCount > 0) {
        foreach ($product->gallery_images as $idx => $img) {
            echo "   " . ($idx + 1) . ". {$img}" . PHP_EOL;
        }
    }

    // Total images
    $totalImages = ($product->main_image ? 1 : 0) + $galleryCount;
    echo "📊 Total Images: {$totalImages}" . PHP_EOL;

    // Check Sizes
    $sizesCount = $product->productSizes->count();
    echo "📏 Regular Sizes: " . ($sizesCount > 0 ? "✅ {$sizesCount} sizes" : "❌ NONE") . PHP_EOL;
    if ($sizesCount > 0) {
        echo "   Sizes: ";
        foreach ($product->productSizes as $size) {
            echo $size->name . " ";
        }
        echo PHP_EOL;
    }

    // Check Shoe Sizes
    $shoeSizesCount = $product->productShoeSizes->count();
    echo "👞 Shoe Sizes: " . ($shoeSizesCount > 0 ? "✅ {$shoeSizesCount} sizes" : "❌ NONE") . PHP_EOL;
    if ($shoeSizesCount > 0) {
        echo "   Sizes: ";
        foreach ($product->productShoeSizes as $size) {
            echo $size->size . " ";
        }
        echo PHP_EOL;
    }

    // Check Colors
    $colorsCount = $product->productColors->count();
    echo "🎨 Colors: " . ($colorsCount > 0 ? "✅ {$colorsCount} colors" : "❌ NONE") . PHP_EOL;
    if ($colorsCount > 0) {
        echo "   Colors: ";
        foreach ($product->productColors as $color) {
            $colorName = is_array($color->name) ? ($color->name['ar'] ?? 'Unknown') : $color->name;
            echo $colorName . " ({$color->hex_code}) ";
        }
        echo PHP_EOL;
    }

    echo PHP_EOL;
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
echo "✅ Data check complete!" . PHP_EOL;
