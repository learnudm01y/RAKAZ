<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brandNames = [
            'Nike', 'Adidas', 'Puma', 'Reebok', 'Under Armour',
            'New Balance', 'Asics', 'Converse', 'Vans', 'Fila',
            'Champion', 'Lacoste', 'Tommy Hilfiger', 'Calvin Klein', 'Ralph Lauren',
            'Hugo Boss', 'Armani', 'Versace', 'Gucci', 'Prada',
            'Dolce & Gabbana', 'Burberry', 'Chanel', 'Dior', 'Louis Vuitton',
            'Hermès', 'Balenciaga', 'Givenchy', 'Valentino', 'Fendi',
            'Zara', 'H&M', 'Mango', 'Pull&Bear', 'Bershka',
            'Stradivarius', 'Massimo Dutti', 'Forever 21', 'Gap', 'Uniqlo',
            'Levi\'s', 'Diesel', 'Wrangler', 'Lee', 'Pepe Jeans',
            'Guess', 'Hollister', 'Abercrombie & Fitch', 'American Eagle', 'Aeropostale',
        ];

        $arabicPrefixes = ['علامة', 'براند', 'ماركة', 'شركة', 'مؤسسة'];

        for ($i = 1; $i <= 1000; $i++) {
            $baseBrand = $brandNames[array_rand($brandNames)];
            $englishName = $baseBrand . ' Brand ' . $i;
            $arabicName = $arabicPrefixes[array_rand($arabicPrefixes)] . ' ' . $baseBrand . ' ' . $i;

            Brand::create([
                'name_ar' => $arabicName,
                'name_en' => $englishName,
                'slug' => Str::slug($englishName . '-' . uniqid()),
                'is_active' => rand(0, 10) > 2, // 80% active
                'sort_order' => $i,
            ]);
        }
    }
}
