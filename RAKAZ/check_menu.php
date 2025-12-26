<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$menu = App\Models\Menu::find(1);

if ($menu) {
    echo "Menu 1 exists: " . $menu->name['ar'] . "\n";
    echo "menu_data is null: " . (is_null($menu->menu_data) ? 'YES' : 'NO') . "\n";
    echo "menu_data is empty: " . (empty($menu->menu_data) ? 'YES' : 'NO') . "\n";

    if (!empty($menu->menu_data)) {
        $data = json_decode($menu->menu_data, true);
        echo "menu_data can be decoded: " . ($data ? 'YES' : 'NO') . "\n";
        if ($data) {
            echo "Number of columns: " . count($data) . "\n";
        }
    }
} else {
    echo "Menu ID 1 NOT FOUND\n";
}
