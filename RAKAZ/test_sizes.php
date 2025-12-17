<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "Testing Products with Sizes:\n";
echo "=============================\n\n";

$products = Product::whereNotNull('sizes')->take(5)->get(['id', 'name', 'sizes', 'slug']);

if ($products->isEmpty()) {
    echo "❌ No products found with sizes!\n\n";

    // Check all products
    $allProducts = Product::take(3)->get(['id', 'name', 'sizes']);
    echo "Sample of all products:\n";
    foreach ($allProducts as $product) {
        $name = is_array($product->name) ? ($product->name['ar'] ?? $product->name['en'] ?? 'No name') : $product->name;
        echo "ID: {$product->id} | Name: {$name} | Sizes: " . json_encode($product->sizes) . "\n";
    }
} else {
    echo "✅ Found " . $products->count() . " products with sizes:\n\n";

    foreach ($products as $product) {
        $name = is_array($product->name) ? ($product->name['ar'] ?? $product->name['en'] ?? 'No name') : $product->name;
        $slug = is_array($product->slug) ? ($product->slug['ar'] ?? $product->slug['en'] ?? 'no-slug') : $product->slug;

        echo "ID: {$product->id}\n";
        echo "Name: {$name}\n";
        echo "Slug: {$slug}\n";
        echo "Sizes: " . json_encode($product->sizes, JSON_UNESCAPED_UNICODE) . "\n";
        echo "Sizes Count: " . (is_array($product->sizes) ? count($product->sizes) : 0) . "\n";
        echo "---\n\n";
    }
}
