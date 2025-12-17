<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Testing Admin Orders System\n";
echo str_repeat("=", 50) . "\n\n";

// 1. Check if admin orders route exists
echo "1. Checking Admin Orders Route:\n";
try {
    $route = \Route::getRoutes()->getByName('admin.orders.index');
    if ($route) {
        echo "   ✅ Route 'admin.orders.index' exists\n";
        echo "   📍 URI: " . $route->uri() . "\n";
        echo "   🎯 Action: " . $route->getActionName() . "\n";
    } else {
        echo "   ❌ Route 'admin.orders.index' NOT FOUND\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// 2. Check if OrderController exists
echo "2. Checking Admin OrderController:\n";
$controllerPath = app_path('Http/Controllers/Admin/OrderController.php');
if (file_exists($controllerPath)) {
    echo "   ✅ Controller exists at: $controllerPath\n";

    // Check if index method exists
    if (class_exists('App\Http\Controllers\Admin\OrderController')) {
        $reflection = new ReflectionClass('App\Http\Controllers\Admin\OrderController');
        if ($reflection->hasMethod('index')) {
            echo "   ✅ index() method exists\n";
        } else {
            echo "   ❌ index() method NOT FOUND\n";
        }
    }
} else {
    echo "   ❌ Controller NOT FOUND\n";
}

echo "\n";

// 3. Check if view exists
echo "3. Checking Admin Orders View:\n";
$viewPath = resource_path('views/admin/orders/index.blade.php');
if (file_exists($viewPath)) {
    echo "   ✅ View exists at: $viewPath\n";
} else {
    echo "   ❌ View NOT FOUND\n";
}

echo "\n";

// 4. Check if admin layout exists
echo "4. Checking Admin Layout:\n";
$layoutPath = resource_path('views/admin/layouts/app.blade.php');
if (file_exists($layoutPath)) {
    echo "   ✅ Layout exists at: $layoutPath\n";
} else {
    echo "   ❌ Layout NOT FOUND\n";
}

echo "\n";

// 5. Test database connection and get orders count
echo "5. Checking Orders in Database:\n";
try {
    $ordersCount = \App\Models\Order::count();
    echo "   ✅ Database connection OK\n";
    echo "   📊 Total orders: $ordersCount\n";

    if ($ordersCount > 0) {
        $latestOrder = \App\Models\Order::latest()->first();
        echo "   📦 Latest order: #{$latestOrder->order_number}\n";
        echo "   👤 Customer: {$latestOrder->customer_name}\n";
        echo "   💰 Total: {$latestOrder->total} AED\n";
        echo "   📅 Status: {$latestOrder->status}\n";
    }
} catch (Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n";

// 6. Try to simulate accessing the page
echo "6. Testing Controller Access:\n";
try {
    $controller = new \App\Http\Controllers\Admin\OrderController();
    $request = \Illuminate\Http\Request::create('/admin/orders', 'GET');

    // This will test if the controller can be instantiated
    echo "   ✅ Controller can be instantiated\n";

} catch (Exception $e) {
    echo "   ❌ Controller error: " . $e->getMessage() . "\n";
}

echo "\n";
echo str_repeat("=", 50) . "\n";
echo "✅ Test complete!\n\n";

echo "📋 Summary:\n";
echo "   - Route: " . (\Route::getRoutes()->getByName('admin.orders.index') ? 'OK' : 'MISSING') . "\n";
echo "   - Controller: " . (file_exists($controllerPath) ? 'OK' : 'MISSING') . "\n";
echo "   - View: " . (file_exists($viewPath) ? 'OK' : 'MISSING') . "\n";
echo "   - Layout: " . (file_exists($layoutPath) ? 'OK' : 'MISSING') . "\n";
echo "   - Orders in DB: " . (\App\Models\Order::count()) . "\n";

echo "\n🌐 Admin Orders URL: http://127.0.0.1:8000/admin/orders\n";
