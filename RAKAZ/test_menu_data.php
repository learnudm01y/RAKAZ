<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing menu_data column...\n";

$menu = App\Models\Menu::first();
if ($menu) {
    echo "Menu found: {$menu->name['ar']}\n";

    // Test writing
    $menu->menu_data = json_encode(['test' => 'data']);
    $menu->save();

    echo "✅ Successfully saved menu_data\n";

    // Test reading
    $menu->refresh();
    echo "✅ menu_data value: " . ($menu->menu_data ? 'EXISTS' : 'NULL') . "\n";
} else {
    echo "❌ No menu found\n";
}
