<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShoeSize;

class ShoeSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing shoe sizes
        ShoeSize::truncate();

        $shoeSizes = [
            ['size' => '36', 'sort_order' => 1, 'product_count' => 3],
            ['size' => '37', 'sort_order' => 2, 'product_count' => 4],
            ['size' => '37.5', 'sort_order' => 3, 'product_count' => 5],
            ['size' => '38', 'sort_order' => 4, 'product_count' => 4],
            ['size' => '38.5', 'sort_order' => 5, 'product_count' => 10],
            ['size' => '39', 'sort_order' => 6, 'product_count' => 15],
            ['size' => '39.5', 'sort_order' => 7, 'product_count' => 1],
            ['size' => '40', 'sort_order' => 8, 'product_count' => 212],
            ['size' => '40.5', 'sort_order' => 9, 'product_count' => 66],
            ['size' => '41', 'sort_order' => 10, 'product_count' => 279],
            ['size' => '41.5', 'sort_order' => 11, 'product_count' => 39],
            ['size' => '42', 'sort_order' => 12, 'product_count' => 391],
            ['size' => '42.5', 'sort_order' => 13, 'product_count' => 85],
            ['size' => '43', 'sort_order' => 14, 'product_count' => 365],
            ['size' => '43.5', 'sort_order' => 15, 'product_count' => 28],
            ['size' => '44', 'sort_order' => 16, 'product_count' => 291],
            ['size' => '44.5', 'sort_order' => 17, 'product_count' => 21],
            ['size' => '45', 'sort_order' => 18, 'product_count' => 189],
        ];

        foreach ($shoeSizes as $shoeSize) {
            ShoeSize::create([
                'size' => $shoeSize['size'],
                'name_translations' => [
                    'ar' => $shoeSize['size'],
                    'en' => $shoeSize['size']
                ],
                'sort_order' => $shoeSize['sort_order'],
                'product_count' => $shoeSize['product_count'],
                'is_active' => true,
            ]);
        }

        $this->command->info('Shoe sizes seeded successfully!');
    }
}
