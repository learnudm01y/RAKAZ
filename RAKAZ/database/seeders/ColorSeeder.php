<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing colors
        Color::truncate();

        $colors = [
            [
                'name' => [
                    'ar' => 'أسود',
                    'en' => 'Black'
                ],
                'hex_code' => '#000000',
                'sort_order' => 1,
                'product_count' => 760,
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'أزرق',
                    'en' => 'Blue'
                ],
                'hex_code' => '#4A90E2',
                'sort_order' => 2,
                'product_count' => 484,
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'رمادي',
                    'en' => 'Gray'
                ],
                'hex_code' => '#808080',
                'sort_order' => 3,
                'product_count' => 290,
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'أخضر',
                    'en' => 'Green'
                ],
                'hex_code' => '#7ED321',
                'sort_order' => 4,
                'product_count' => 263,
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'لون محايد',
                    'en' => 'Beige'
                ],
                'hex_code' => '#D4C5B9',
                'sort_order' => 5,
                'product_count' => 250,
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'ملون',
                    'en' => 'Multicolor'
                ],
                'hex_code' => '#667eea', // Base color for gradient
                'sort_order' => 6,
                'product_count' => 247,
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'بني',
                    'en' => 'Brown'
                ],
                'hex_code' => '#8B4513',
                'sort_order' => 7,
                'product_count' => 211,
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'عديم اللون',
                    'en' => 'Metallic'
                ],
                'hex_code' => '#C0C0C0',
                'sort_order' => 8,
                'product_count' => 167,
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'أبيض',
                    'en' => 'White'
                ],
                'hex_code' => '#FFFFFF',
                'sort_order' => 9,
                'product_count' => 159,
                'is_active' => true,
            ],
            [
                'name' => [
                    'ar' => 'أحمر',
                    'en' => 'Red'
                ],
                'hex_code' => '#E53935',
                'sort_order' => 10,
                'product_count' => 55,
                'is_active' => true,
            ],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }

        $this->command->info('Colors seeded successfully!');
    }
}
