<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "Adding Sizes to Products:\n";
echo "=========================\n\n";

// Define default sizes for different product types
$clothingSizes = ['S', 'M', 'L', 'XL', 'XXL'];

// Get all products
$products = Product::all();

echo "Total products: " . $products->count() . "\n\n";

foreach ($products as $product) {
    // Add sizes if not exists
    if (empty($product->sizes) || !is_array($product->sizes)) {
        $product->sizes = $clothingSizes;
        $product->save();

        $name = is_array($product->name) ? ($product->name['ar'] ?? $product->name['en'] ?? 'No name') : $product->name;
        echo "✅ Added sizes to Product ID {$product->id}: {$name}\n";
        echo "   Sizes: " . implode(', ', $clothingSizes) . "\n\n";
    } else {
        $name = is_array($product->name) ? ($product->name['ar'] ?? $product->name['en'] ?? 'No name') : $product->name;
        echo "⚪ Product ID {$product->id} already has sizes: {$name}\n";
        echo "   Sizes: " . implode(', ', $product->sizes) . "\n\n";
    }
}

echo "\n=========================\n";
echo "✅ Done! All products now have sizes.\n";

// Verify
echo "\nVerification:\n";
$productsWithSizes = Product::whereNotNull('sizes')->count();
echo "Products with sizes: {$productsWithSizes}\n";
