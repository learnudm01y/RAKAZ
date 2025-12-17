<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Size;
use Illuminate\Support\Facades\DB;

class ClothingSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing sizes
        Size::truncate();

        $sizes = [
            [
                'name' => 'XXS',
                'name_translations' => [
                    'ar' => 'صغير جداً جداً',
                    'en' => 'XXS'
                ],
                'sort_order' => 1,
                'product_count' => 24,
                'is_active' => true,
            ],
            [
                'name' => 'XS',
                'name_translations' => [
                    'ar' => 'صغير جداً',
                    'en' => 'XS'
                ],
                'sort_order' => 2,
                'product_count' => 214,
                'is_active' => true,
            ],
            [
                'name' => 'S',
                'name_translations' => [
                    'ar' => 'صغير',
                    'en' => 'S'
                ],
                'sort_order' => 3,
                'product_count' => 1331,
                'is_active' => true,
            ],
            [
                'name' => 'M',
                'name_translations' => [
                    'ar' => 'وسط',
                    'en' => 'M'
                ],
                'sort_order' => 4,
                'product_count' => 1329,
                'is_active' => true,
            ],
            [
                'name' => 'L',
                'name_translations' => [
                    'ar' => 'كبير',
                    'en' => 'L'
                ],
                'sort_order' => 5,
                'product_count' => 1323,
                'is_active' => true,
            ],
            [
                'name' => 'XL',
                'name_translations' => [
                    'ar' => 'كبير جداً',
                    'en' => 'XL'
                ],
                'sort_order' => 6,
                'product_count' => 1211,
                'is_active' => true,
            ],
            [
                'name' => 'XXL',
                'name_translations' => [
                    'ar' => 'كبير جداً جداً',
                    'en' => 'XXL'
                ],
                'sort_order' => 7,
                'product_count' => 764,
                'is_active' => true,
            ],
            [
                'name' => 'XXXL',
                'name_translations' => [
                    'ar' => 'كبير جداً جداً جداً',
                    'en' => 'XXXL'
                ],
                'sort_order' => 8,
                'product_count' => 52,
                'is_active' => true,
            ],
        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }

        $this->command->info('Clothing sizes seeded successfully!');
    }
}
