<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$homePage = App\Models\HomePage::first();

// Update gifts section title
$homePage->gifts_section_title = [
    'ar' => 'هدايا مثالية للرجل الإماراتي',
    'en' => 'Perfect Gifts for Emirati Men'
];

// Update gifts items with titles
$giftsItems = [
    [
        'title' => [
            'ar' => 'كنادر فاخرة',
            'en' => 'Luxury Kanduras'
        ],
        'image' => '/assets/images/New folder/Emirati_Gold_Edition_White.jpg',
        'link' => '#'
    ],
    [
        'title' => [
            'ar' => 'شالات قطنية',
            'en' => 'Cotton Shawls'
        ],
        'image' => '/assets/images/New folder/49.jpg',
        'link' => '#'
    ],
    [
        'title' => [
            'ar' => 'فانيل فاخرة',
            'en' => 'Luxury Undershirts'
        ],
        'image' => '/assets/images/New folder/50.jpg',
        'link' => '#'
    ]
];

$homePage->gifts_items = $giftsItems;
$homePage->save();

echo "✓ Gifts section data updated successfully!\n\n";
echo "Gifts Section Title (AR): " . $homePage->gifts_section_title['ar'] . PHP_EOL;
echo "Gifts Section Title (EN): " . $homePage->gifts_section_title['en'] . PHP_EOL;
echo "\nGifts Items:\n";
foreach ($homePage->gifts_items as $index => $gift) {
    echo "\n" . ($index + 1) . ". " . $gift['title']['ar'] . " / " . $gift['title']['en'] . PHP_EOL;
}
