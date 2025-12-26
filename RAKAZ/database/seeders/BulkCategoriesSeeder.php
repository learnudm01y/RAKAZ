<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BulkCategoriesSeeder extends Seeder
{
    private function arabicSlug(string $text): string
    {
        $text = trim($text);
        $text = preg_replace('/\s+/u', '-', $text);
        $text = preg_replace('/-+/u', '-', $text);
        return mb_strtolower($text);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 100 main categories + 900 subcategories = 1000 total
        $mainCount = 100;
        $childrenPerMain = 9;

        $existingCount = Category::count();
        $offset = $existingCount + 1;

        $created = 0;

        for ($i = 1; $i <= $mainCount; $i++) {
            $mainIndex = $offset + $created;

            $nameAr = "تصنيف رئيسي {$mainIndex}";
            $nameEn = "Main Category {$mainIndex}";

            $main = Category::create([
                'name' => ['ar' => $nameAr, 'en' => $nameEn],
                'slug' => [
                    'ar' => $this->arabicSlug($nameAr),
                    'en' => Str::slug($nameEn),
                ],
                'description' => [
                    'ar' => "وصف {$nameAr}",
                    'en' => "Description for {$nameEn}",
                ],
                'parent_id' => null,
                'sort_order' => $i,
                'is_active' => true,
                'image' => null,
            ]);

            $created++;

            for ($j = 1; $j <= $childrenPerMain; $j++) {
                $childIndex = $offset + $created;

                $childNameAr = "تصنيف فرعي {$mainIndex}-{$j}";
                $childNameEn = "Subcategory {$mainIndex}-{$j}";

                Category::create([
                    'name' => ['ar' => $childNameAr, 'en' => $childNameEn],
                    'slug' => [
                        'ar' => $this->arabicSlug($childNameAr),
                        'en' => Str::slug($childNameEn),
                    ],
                    'description' => [
                        'ar' => "وصف {$childNameAr}",
                        'en' => "Description for {$childNameEn}",
                    ],
                    'parent_id' => $main->id,
                    'sort_order' => $j,
                    'is_active' => true,
                    'image' => null,
                ]);

                $created++;
            }
        }

        $this->command?->info("BulkCategoriesSeeder: created {$created} categories ({$mainCount} main + " . ($mainCount * $childrenPerMain) . " sub). Existing before: {$existingCount}.");
    }
}
