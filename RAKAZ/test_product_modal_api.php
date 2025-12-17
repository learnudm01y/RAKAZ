<?php
/**
 * Test Product Modal API Response
 *
 * This script tests the product details API endpoint
 * to ensure it returns complete product data for the modal
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::create('/', 'GET')
);

use Illuminate\Support\Facades\Route;

// Get first product for testing
$product = \App\Models\Product::first();

if (!$product) {
    echo "❌ No products found in database!\n";
    exit(1);
}

echo "🧪 Testing Product Modal API\n";
echo str_repeat("=", 50) . "\n\n";

echo "📦 Test Product:\n";
echo "   ID: {$product->id}\n";
echo "   Name: {$product->getName()}\n";
echo "   Slug: {$product->getSlug()}\n";
echo "\n";

// Simulate AJAX request
$url = route('product.details', $product->getSlug());
echo "🌐 API URL: {$url}\n\n";

// Create request with AJAX headers
$request = \Illuminate\Http\Request::create(
    $url,
    'GET',
    [],
    [],
    [],
    [
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
        'HTTP_ACCEPT' => 'application/json'
    ]
);

$app = app();
$app->instance('request', $request);

// Get controller
$controller = new \App\Http\Controllers\FrontendController();
$response = $controller->productDetails($product->getSlug());

$data = $response->getData(true);

echo "📊 API Response Structure:\n";
echo str_repeat("-", 50) . "\n";

$checks = [
    'id' => 'Product ID',
    'name' => 'Product Name',
    'brand' => 'Brand',
    'price' => 'Price',
    'images' => 'Images Array',
    'hasNewSeason' => 'New Season Flag',
    'sizes' => 'Sizes Array',
    'description' => 'Description',
    'sizing_info' => 'Sizing Info',
    'design_details' => 'Design Details',
    'sku' => 'SKU'
];

$allPresent = true;
foreach ($checks as $key => $label) {
    $present = isset($data[$key]);
    $hasValue = $present && !empty($data[$key]);

    $status = $hasValue ? '✅' : ($present ? '⚠️' : '❌');
    echo "{$status} {$label}: ";

    if (!$present) {
        echo "MISSING\n";
        $allPresent = false;
    } elseif (!$hasValue) {
        echo "EMPTY\n";
    } else {
        if (is_array($data[$key])) {
            echo "Array with " . count($data[$key]) . " items\n";
        } else {
            $value = strlen($data[$key]) > 50 ? substr($data[$key], 0, 50) . '...' : $data[$key];
            echo "{$value}\n";
        }
    }
}

echo "\n";
echo str_repeat("=", 50) . "\n";

if ($allPresent) {
    echo "✅ All required fields present in API response\n";
} else {
    echo "❌ Some fields are missing from API response\n";
}

echo "\n📋 Detailed Data:\n";
echo str_repeat("-", 50) . "\n";

if (isset($data['images'])) {
    $imgCount = is_array($data['images']) ? count($data['images']) : 0;
    echo "\n🖼️  Images ({$imgCount}):\n";
    foreach ($data['images'] as $i => $img) {
        echo "   " . ($i + 1) . ". " . basename($img) . "\n";
    }
}

if (isset($data['sizes'])) {
    $sizeCount = is_array($data['sizes']) ? count($data['sizes']) : 0;
    echo "\n📏 Sizes ({$sizeCount}):\n";
    if (is_array($data['sizes']) && count($data['sizes']) > 0) {
        foreach ($data['sizes'] as $size) {
            if (is_array($size)) {
                $sizeText = $size['ar'] ?? $size['en'] ?? json_encode($size);
            } else {
                $sizeText = $size;
            }
            echo "   - {$sizeText}\n";
        }
    } else {
        echo "   No sizes available\n";
    }
}

if (isset($data['description'])) {
    echo "\n📝 Description:\n";
    $desc = strip_tags($data['description']);
    $desc = strlen($desc) > 200 ? substr($desc, 0, 200) . '...' : $desc;
    echo "   " . $desc . "\n";
}

if (isset($data['sizing_info'])) {
    echo "\n📐 Sizing Info:\n";
    $info = strip_tags($data['sizing_info']);
    if (trim($info)) {
        $info = strlen($info) > 150 ? substr($info, 0, 150) . '...' : $info;
        echo "   " . $info . "\n";
    } else {
        echo "   (Empty)\n";
    }
}

if (isset($data['design_details'])) {
    echo "\n🎨 Design Details:\n";
    $details = strip_tags($data['design_details']);
    if (trim($details)) {
        $details = strlen($details) > 150 ? substr($details, 0, 150) . '...' : $details;
        echo "   " . $details . "\n";
    } else {
        echo "   (Empty)\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ Test completed successfully!\n";
