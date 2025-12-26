<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;

class AssignBrandsToProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على جميع البراندات النشطة
        $brands = Brand::where('is_active', true)->pluck('id')->toArray();

        if (empty($brands)) {
            $this->command->error('لا توجد براندات نشطة في قاعدة البيانات!');
            return;
        }

        $this->command->info('عدد البراندات المتاحة: ' . count($brands));

        // الحصول على جميع المنتجات
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->error('لا توجد منتجات في قاعدة البيانات!');
            return;
        }

        $this->command->info('عدد المنتجات: ' . $products->count());

        $progressBar = $this->command->getOutput()->createProgressBar($products->count());
        $progressBar->start();

        $updatedCount = 0;

        foreach ($products as $product) {
            // اختيار براند عشوائي
            $randomBrandId = $brands[array_rand($brands)];

            // تحديث المنتج
            $product->update(['brand_id' => $randomBrandId]);

            $updatedCount++;
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine();
        $this->command->info("تم تحديث {$updatedCount} منتج بنجاح!");
    }
}
