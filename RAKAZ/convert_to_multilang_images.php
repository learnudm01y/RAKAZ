<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$homePage = App\Models\HomePage::first();

echo "=== Converting to Multi-Language Image Structure ===\n\n";

// Convert existing images to multi-language format
$giftsItems = [
    [
        'title' => [
            'ar' => 'كنادر فاخرة',
            'en' => 'Luxury Kanduras'
        ],
        'image' => [
            'ar' => '/assets/images/New folder/Emirati_Gold_Edition_White.jpg',
            'en' => '/assets/images/New folder/Emirati_Gold_Edition_White.jpg'
        ],
        'link' => '#'
    ],
    [
        'title' => [
            'ar' => 'شالات قطنية',
            'en' => 'Cotton Shawls'
        ],
        'image' => [
            'ar' => '/assets/images/New folder/49.jpg',
            'en' => '/assets/images/New folder/49.jpg'
        ],
        'link' => '#'
    ],
    [
        'title' => [
            'ar' => 'فانيل فاخرة',
            'en' => 'Luxury Undershirts'
        ],
        'image' => [
            'ar' => '/assets/images/New folder/50.jpg',
            'en' => '/assets/images/New folder/50.jpg'
        ],
        'link' => '#'
    ]
];

$homePage->gifts_items = $giftsItems;
$homePage->save();

echo "✓ Successfully converted to multi-language image structure!\n\n";
echo "Structure:\n";
foreach ($homePage->gifts_items as $index => $gift) {
    echo "\nGift #" . ($index + 1) . ":\n";
    echo "  Title AR: " . $gift['title']['ar'] . "\n";
    echo "  Title EN: " . $gift['title']['en'] . "\n";
    echo "  Image AR: " . $gift['image']['ar'] . "\n";
    echo "  Image EN: " . $gift['image']['en'] . "\n";
}
