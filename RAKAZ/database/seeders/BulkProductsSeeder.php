<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ShoeSize;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BulkProductsSeeder extends Seeder
{
    private function arSlug(string $text): string
    {
        $text = trim($text);
        $text = preg_replace('/\s+/u', '-', $text) ?? $text;
        return mb_strtolower($text);
    }

    private function uniqueSku(array &$usedSkus): string
    {
        do {
            $sku = 'PRD-' . strtoupper(Str::random(8));
        } while (isset($usedSkus[$sku]));

        $usedSkus[$sku] = true;
        return $sku;
    }

    private function copyFromDiskPublic(string $sourcePath, string $destDir, string $prefix): string
    {
        $disk = Storage::disk('public');

        $ext = pathinfo($sourcePath, PATHINFO_EXTENSION);
        $ext = $ext ? strtolower($ext) : 'jpg';

        $destPath = rtrim($destDir, '/') . '/' . $prefix . '_' . Str::random(16) . '.' . $ext;

        // Ensure we never overwrite an existing file
        while ($disk->exists($destPath)) {
            $destPath = rtrim($destDir, '/') . '/' . $prefix . '_' . Str::random(16) . '.' . $ext;
        }

        $disk->copy($sourcePath, $destPath);

        return $destPath;
    }

    public function run(): void
    {
        $disk = Storage::disk('public');

        // Ensure lookup tables exist for variants
        if (Size::count() === 0) {
            $this->call(ClothingSizeSeeder::class);
        }
        if (ShoeSize::count() === 0) {
            $this->call(ShoeSizeSeeder::class);
        }
        if (Color::count() === 0) {
            $this->call(ColorSeeder::class);
        }

        if (Category::count() === 0) {
            $categories = [
                ['ar' => 'ملابس', 'en' => 'Clothing'],
                ['ar' => 'أحذية', 'en' => 'Shoes'],
                ['ar' => 'حقائب', 'en' => 'Bags'],
                ['ar' => 'إكسسوارات', 'en' => 'Accessories'],
                ['ar' => 'رياضة', 'en' => 'Sportswear'],
                ['ar' => 'عطور', 'en' => 'Fragrances'],
            ];

            foreach ($categories as $idx => $cat) {
                Category::create([
                    'name' => ['ar' => $cat['ar'], 'en' => $cat['en']],
                    'slug' => ['ar' => $this->arSlug($cat['ar']), 'en' => Str::slug($cat['en'])],
                    'description' => ['ar' => 'تصنيف تلقائي للمنتجات', 'en' => 'Auto seeded category'],
                    'sort_order' => $idx + 1,
                    'is_active' => true,
                ]);
            }
        }

        $sourceMain = $disk->files('products');
        $sourceMain = array_values(array_filter($sourceMain, fn($p) => is_string($p) && preg_match('/\.(jpg|jpeg|png|webp)$/i', $p)));

        $sourceGallery = $disk->files('products/gallery');
        $sourceGallery = array_values(array_filter($sourceGallery, fn($p) => is_string($p) && preg_match('/\.(jpg|jpeg|png|webp)$/i', $p)));

        if (count($sourceMain) + count($sourceGallery) < 5) {
            throw new \RuntimeException('Not enough source images in storage/app/public/products and storage/app/public/products/gallery');
        }

        $categories = Category::where('is_active', true)->pluck('id')->values();
        $sizes = Size::where('is_active', true)->pluck('id')->values();
        $shoeSizes = ShoeSize::where('is_active', true)->pluck('id')->values();
        $colors = Color::where('is_active', true)->pluck('id')->values();

        $brands = [
            ['ar' => 'راكاز', 'en' => 'Rakaz'],
            ['ar' => 'أوريجنال', 'en' => 'Original'],
            ['ar' => 'إليت', 'en' => 'Elite'],
            ['ar' => 'ستريت', 'en' => 'Street'],
            ['ar' => 'سبورت', 'en' => 'Sport'],
        ];

        $productTemplates = [
            ['ar' => 'تيشيرت قطن', 'en' => 'Cotton T‑Shirt', 'type' => 'clothing'],
            ['ar' => 'هودي شتوي', 'en' => 'Winter Hoodie', 'type' => 'clothing'],
            ['ar' => 'بنطال رياضي', 'en' => 'Sport Pants', 'type' => 'clothing'],
            ['ar' => 'حذاء رياضي', 'en' => 'Running Shoes', 'type' => 'shoes'],
            ['ar' => 'حذاء كاجوال', 'en' => 'Casual Sneakers', 'type' => 'shoes'],
            ['ar' => 'حقيبة ظهر', 'en' => 'Backpack', 'type' => 'accessory'],
            ['ar' => 'حقيبة يد', 'en' => 'Handbag', 'type' => 'accessory'],
            ['ar' => 'قبعة', 'en' => 'Cap', 'type' => 'accessory'],
        ];

        $usedSkus = [];

        DB::transaction(function () use (
            $categories,
            $sizes,
            $shoeSizes,
            $colors,
            $sourceMain,
            $sourceGallery,
            $brands,
            $productTemplates,
            &$usedSkus
        ) {
            for ($i = 1; $i <= 50; $i++) {
                $tpl = $productTemplates[array_rand($productTemplates)];

                $brand = $brands[array_rand($brands)];
                $modelNo = strtoupper(Str::random(4));
                $nameAr = $tpl['ar'] . ' ' . $modelNo;
                $nameEn = $tpl['en'] . ' ' . $modelNo;

                $price = random_int(79, 499) + (random_int(0, 1) ? 0.99 : 0.00);
                $isOnSale = (bool) random_int(0, 1);
                $salePrice = null;
                if ($isOnSale) {
                    $discount = random_int(10, 35) / 100;
                    $salePrice = round($price * (1 - $discount), 2);
                }

                $categoryId = $categories->isNotEmpty() ? $categories->random() : null;

                // Images (5 total): main + hover + 3 gallery
                $mainSource = !empty($sourceMain) ? $sourceMain[array_rand($sourceMain)] : $sourceGallery[array_rand($sourceGallery)];
                $hoverSource = !empty($sourceMain) ? $sourceMain[array_rand($sourceMain)] : $sourceGallery[array_rand($sourceGallery)];

                $mainImagePath = $this->copyFromDiskPublic($mainSource, 'products', 'seed_main_' . $i);
                $hoverImagePath = $this->copyFromDiskPublic($hoverSource, 'products', 'seed_hover_' . $i);

                $galleryImages = [];
                $galleryPickCount = 3;
                for ($g = 1; $g <= $galleryPickCount; $g++) {
                    $src = $sourceGallery[array_rand($sourceGallery)];
                    $galleryImages[] = $this->copyFromDiskPublic($src, 'products/gallery', 'seed_g' . $i . '_' . $g);
                }

                // Variants (random but structured)
                $sizesData = [];
                $shoeSizesData = [];
                $colorsData = [];

                // Always select colors (at least 2)
                $colorsCount = max(2, min(5, $colors->count()));
                $selectedColors = $colors->isNotEmpty() ? $colors->random(random_int(1, $colorsCount)) : collect();
                foreach ($selectedColors as $colorId) {
                    $colorsData[$colorId] = ['stock_quantity' => random_int(0, 30)];
                }

                if ($tpl['type'] === 'clothing' && $sizes->isNotEmpty()) {
                    $count = min($sizes->count(), random_int(2, 5));
                    foreach ($sizes->random($count) as $sizeId) {
                        $sizesData[$sizeId] = ['stock_quantity' => random_int(0, 40)];
                    }
                } elseif ($tpl['type'] === 'shoes' && $shoeSizes->isNotEmpty()) {
                    $count = min($shoeSizes->count(), random_int(3, 6));
                    foreach ($shoeSizes->random($count) as $shoeSizeId) {
                        $shoeSizesData[$shoeSizeId] = ['stock_quantity' => random_int(0, 25)];
                    }
                } else {
                    // Mixed accessories: sometimes add clothing sizes or shoe sizes
                    if (random_int(0, 1) && $sizes->isNotEmpty()) {
                        $count = min($sizes->count(), random_int(1, 3));
                        foreach ($sizes->random($count) as $sizeId) {
                            $sizesData[$sizeId] = ['stock_quantity' => random_int(0, 20)];
                        }
                    }
                    if (random_int(0, 1) && $shoeSizes->isNotEmpty()) {
                        $count = min($shoeSizes->count(), random_int(1, 3));
                        foreach ($shoeSizes->random($count) as $shoeSizeId) {
                            $shoeSizesData[$shoeSizeId] = ['stock_quantity' => random_int(0, 15)];
                        }
                    }
                }

                // Ensure at least one variant exists
                if (empty($sizesData) && empty($shoeSizesData) && empty($colorsData)) {
                    if ($colors->isNotEmpty()) {
                        $colorId = $colors->random();
                        $colorsData[$colorId] = ['stock_quantity' => random_int(1, 20)];
                    }
                }

                $totalVariantStock = 0;
                foreach ([$sizesData, $shoeSizesData, $colorsData] as $group) {
                    foreach ($group as $row) {
                        $totalVariantStock += (int) ($row['stock_quantity'] ?? 0);
                    }
                }

                $stockQuantity = $totalVariantStock;
                $stockStatus = $stockQuantity > 0 ? 'in_stock' : 'out_of_stock';

                $sku = $this->uniqueSku($usedSkus);

                $product = Product::create([
                    'name' => ['ar' => $nameAr, 'en' => $nameEn],
                    'slug' => ['ar' => $this->arSlug($nameAr), 'en' => Str::slug($nameEn)],
                    'description' => [
                        'ar' => 'وصف مختصر للمنتج مع تفاصيل خامات وتصميم واستخدامات يومية.',
                        'en' => 'A concise product description with materials, design, and everyday usage notes.',
                    ],
                    'sizing_info' => [
                        'ar' => 'يرجى اختيار المقاس المناسب حسب جدول القياسات. إن كنت بين مقاسين اختر الأكبر.',
                        'en' => 'Choose your size based on the size chart. If between sizes, pick the larger.',
                    ],
                    'design_details' => [
                        'ar' => 'تفاصيل تصميم دقيقة مع تشطيبات عالية الجودة.',
                        'en' => 'Carefully crafted design details with premium finishing.',
                    ],
                    'category_id' => $categoryId,
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'cost' => round($price * random_int(55, 80) / 100, 2),
                    'sku' => $sku,
                    'stock_quantity' => $stockQuantity,
                    'manage_stock' => true,
                    'stock_status' => $stockStatus,
                    'low_stock_threshold' => random_int(2, 10),
                    'main_image' => $mainImagePath,
                    'hover_image' => $hoverImagePath,
                    'gallery_images' => $galleryImages,
                    'weight' => ['value' => (string) random_int(200, 1200), 'unit' => 'g'],
                    'dimensions' => [
                        'length' => (string) random_int(10, 40),
                        'width' => (string) random_int(10, 35),
                        'height' => (string) random_int(2, 15),
                        'unit' => 'cm',
                    ],
                    'meta_title' => ['ar' => $nameAr, 'en' => $nameEn],
                    'meta_description' => [
                        'ar' => 'شراء ' . $nameAr . ' بسعر مميز وتوصيل سريع.',
                        'en' => 'Buy ' . $nameEn . ' with fast delivery.',
                    ],
                    'meta_keywords' => [
                        'ar' => implode(',', ['ملابس', 'أحذية', 'راكاز', 'جودة']),
                        'en' => implode(',', ['fashion', 'shoes', 'rakaz', 'quality']),
                    ],
                    'tags' => ['new', 'popular', $tpl['type']],
                    'specifications' => [
                        'material' => $tpl['type'] === 'shoes' ? 'Mesh / Rubber' : 'Cotton Blend',
                        'care' => 'Hand wash cold / Do not bleach',
                        'origin' => 'SA',
                    ],
                    'brand' => $brand['en'],
                    'manufacturer' => $brand['en'] . ' Factory',
                    'available_from' => now()->subDays(random_int(0, 30))->toDateString(),
                    'available_until' => null,
                    'is_active' => true,
                    'is_featured' => (bool) random_int(0, 1),
                    'is_new' => (bool) random_int(0, 1),
                    'is_on_sale' => $isOnSale,
                    'sort_order' => random_int(0, 200),
                    'views_count' => random_int(0, 5000),
                    'sales_count' => random_int(0, 300),
                    'rating_average' => round(random_int(35, 50) / 10, 2),
                    'rating_count' => random_int(0, 250),
                ]);

                if (!empty($sizesData)) {
                    $product->productSizes()->sync($sizesData);
                }

                if (!empty($shoeSizesData)) {
                    $product->productShoeSizes()->sync($shoeSizesData);
                }

                if (!empty($colorsData)) {
                    $product->productColors()->sync($colorsData);
                }
            }
        });

        $this->command->info('BulkProductsSeeder: created 50 products with images and variants.');
    }
}
