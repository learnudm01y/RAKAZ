<?php

/**
 * Test Cart Sidebar API
 *
 * This script tests the cart sidebar API endpoint
 */

echo "=== Testing Cart Sidebar API ===\n\n";

// Test 1: Check if route file contains API route
echo "1. Checking API route in web.php...\n";
$webRoutes = file_get_contents(__DIR__ . '/routes/web.php');
if (strpos($webRoutes, "Route::get('/api/cart'") !== false) {
    echo "   ✓ API route /api/cart exists in web.php\n";
    if (strpos($webRoutes, 'apiIndex') !== false) {
        echo "   ✓ Route points to apiIndex method\n";
    }
    if (strpos($webRoutes, "name('cart.api')") !== false) {
        echo "   ✓ Route named 'cart.api'\n";
    }
    echo "\n";
} else {
    echo "   ✗ API route /api/cart not found in web.php\n\n";
}

// Test 2: Check CartController has apiIndex method
echo "2. Checking CartController...\n";
if (class_exists('App\Http\Controllers\CartController')) {
    $controller = new ReflectionClass('App\Http\Controllers\CartController');
    if ($controller->hasMethod('apiIndex')) {
        echo "   ✓ CartController has apiIndex() method\n";
        $method = $controller->getMethod('apiIndex');
        echo "   Method is public: " . ($method->isPublic() ? 'Yes' : 'No') . "\n\n";
    } else {
        echo "   ✗ CartController missing apiIndex() method\n\n";
    }
} else {
    echo "   ✗ CartController not found\n\n";
}

// Test 3: Check if Cart model exists
echo "3. Checking Cart model...\n";
if (class_exists('App\Models\Cart')) {
    echo "   ✓ Cart model exists\n";

    $cart = new ReflectionClass('App\Models\Cart');
    $methods = ['getCartCount', 'getCartTotal', 'getCartItems'];

    foreach ($methods as $methodName) {
        if ($cart->hasMethod($methodName)) {
            echo "   ✓ Cart has $methodName() method\n";
        }
    }
    echo "\n";
} else {
    echo "   ✗ Cart model not found\n\n";
}

// Test 4: Check JavaScript files
echo "4. Checking JavaScript files...\n";
$jsFile = __DIR__ . '/public/assets/js/cart-sidebar.js';
if (file_exists($jsFile)) {
    echo "   ✓ cart-sidebar.js exists\n";

    $content = file_get_contents($jsFile);

    if (strpos($content, 'loadCartFromServer') !== false) {
        echo "   ✓ loadCartFromServer() method found\n";
    }

    if (strpos($content, '/api/cart') !== false) {
        echo "   ✓ API endpoint /api/cart referenced\n";
    }

    if (strpos($content, 'window.cartSidebarInstance') !== false) {
        echo "   ✓ cartSidebarInstance exported to window\n";
    }
    echo "\n";
} else {
    echo "   ✗ cart-sidebar.js not found\n\n";
}

// Test 5: Check if carts table exists
echo "5. Checking database...\n";
try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=rakaz',
        'root',
        ''
    );

    $stmt = $pdo->query("SHOW TABLES LIKE 'carts'");
    if ($stmt->rowCount() > 0) {
        echo "   ✓ carts table exists\n";

        // Check columns
        $stmt = $pdo->query("DESCRIBE carts");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "   Table columns: " . implode(', ', $columns) . "\n";
    } else {
        echo "   ✗ carts table not found\n";
    }
} catch (Exception $e) {
    echo "   ⚠ Could not connect to database: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
