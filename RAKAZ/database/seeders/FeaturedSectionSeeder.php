<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class FeaturedSectionSeeder extends Seeder
{
    public function run(): void
    {
        // Get 10 random products
        $products = Product::inRandomOrder()->limit(10)->get();

        foreach ($products as $index => $product) {
            DB::table('featured_section_products')->insert([
                'featured_section_id' => 1,
                'product_id' => $product->id,
                'order' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "Added {$products->count()} products to featured section\n";
    }
}
