<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "===== إضافة منتجات إلى السلة =====\n\n";

$userId = 1; // User ID
$products = App\Models\Product::where('is_active', true)->limit(5)->get();

if ($products->isEmpty()) {
    echo "❌ لا توجد منتجات\n";
    exit(1);
}

echo "📦 تم العثور على {$products->count()} منتج\n\n";

// Clear existing cart
App\Models\Cart::where('user_id', $userId)->delete();
echo "🗑️ تم تفريغ السلة\n\n";

// Add random products
$addedCount = 0;
foreach ($products->random(min(3, $products->count())) as $product) {
    $quantity = rand(1, 2);
    $price = $product->sale_price ?? $product->price;

    $productName = $product->name;
    if (is_array($productName)) {
        $productName = $productName['ar'] ?? $productName['en'] ?? 'منتج';
    }

    App\Models\Cart::create([
        'user_id' => $userId,
        'session_id' => null,
        'product_id' => $product->id,
        'quantity' => $quantity,
        'price' => $price,
        'size' => null,
        'color' => null,
    ]);

    echo sprintf(
        "✅ تمت الإضافة: %s - الكمية: %d × %.2f AED\n",
        $productName,
        $quantity,
        $price
    );
    $addedCount++;
}

$cartTotal = App\Models\Cart::where('user_id', $userId)->sum(DB::raw('quantity * price'));

echo "\n📊 الإجمالي: {$cartTotal} AED\n";
echo "\n✅ تمت إضافة {$addedCount} منتج إلى السلة\n";
echo "\n🛒 الآن يمكنك:\n";
echo "1. الذهاب إلى السلة: http://127.0.0.1:8000/cart\n";
echo "2. إتمام الطلب: http://127.0.0.1:8000/checkout\n";
echo "\n💡 أو قم بإنشاء طلب مباشرة:\n";
echo "php create_test_order.php\n";
