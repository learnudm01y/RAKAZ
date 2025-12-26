<?php

namespace Database\Seeders;

use App\Models\PerfectGiftSection;
use App\Models\Product;
use Illuminate\Database\Seeder;

class PerfectGiftSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the perfect gift section
        $section = PerfectGiftSection::firstOrCreate(
            ['id' => 1],
            [
                'title' => [
                    'ar' => 'الهدية المثالية',
                    'en' => 'Perfect Gift'
                ],
                'link_url' => '/shop',
                'link_text' => [
                    'ar' => 'تسوق الكل',
                    'en' => 'Shop All'
                ],
                'is_active' => true,
            ]
        );

        // Get random products with images
        $products = Product::whereNotNull('main_image')
            ->whereNotNull('gallery_images')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        // Attach products with order
        $section->products()->detach();
        foreach ($products as $index => $product) {
            $section->products()->attach($product->id, ['order' => $index]);
        }

        $this->command->info('Perfect Gift Section seeded successfully with ' . $products->count() . ' products!');
    }
}
