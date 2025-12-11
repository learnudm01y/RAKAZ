<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$homePage = App\Models\HomePage::first();

echo "Cyber Sale Active: " . ($homePage->cyber_sale_active ? 'true' : 'false') . PHP_EOL;
echo "Cyber Sale Image: " . ($homePage->cyber_sale_image ?? 'null') . PHP_EOL;
echo "Cyber Sale Link: " . ($homePage->cyber_sale_link ?? 'null') . PHP_EOL;
echo "Cyber Sale Alt: " . ($homePage->cyber_sale_alt ?? 'null') . PHP_EOL;
