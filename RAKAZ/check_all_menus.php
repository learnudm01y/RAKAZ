<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$menus = App\Models\Menu::all();

foreach ($menus as $menu) {
    echo "ID: {$menu->id} - {$menu->name['ar']}\n";
    echo "  menu_data: " . (is_null($menu->menu_data) ? 'NULL' : 'HAS DATA') . "\n";

    if (!is_null($menu->menu_data)) {
        $data = json_decode($menu->menu_data, true);
        if ($data) {
            $totalItems = 0;
            foreach ($data as $column) {
                if (isset($column['items'])) {
                    $totalItems += count($column['items']);
                }
            }
            echo "  Columns: " . count($data) . ", Items: $totalItems\n";
        }
    }
    echo "\n";
}
