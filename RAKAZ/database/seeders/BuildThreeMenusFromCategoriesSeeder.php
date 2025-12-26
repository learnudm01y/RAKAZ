<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BuildThreeMenusFromCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $rootsOrdered = Category::query()
            ->active()
            ->roots()
            ->ordered()
            ->get();

        if ($rootsOrdered->isEmpty()) {
            $this->command?->warn('No root categories found; skipping menu creation.');
            return;
        }

        $rootsMostChildren = Category::query()
            ->active()
            ->roots()
            ->withCount(['children as active_children_count' => fn ($q) => $q->where('is_active', true)])
            ->orderByDesc('active_children_count')
            ->orderBy('sort_order')
            ->get();

        $rootsNewest = Category::query()
            ->active()
            ->roots()
            ->orderByDesc('created_at')
            ->get();

        $menuDefinitions = [
            [
                'name' => ['ar' => 'الأقسام الرئيسية', 'en' => 'Main Categories'],
                'imageSource' => public_path('assets/images/MW_FWB_DSK_UAE_EN copy.jpg'),
                'categories' => $rootsOrdered->take(24),
            ],
            [
                'name' => ['ar' => 'تسوق حسب الفئة', 'en' => 'Shop by Category'],
                'imageSource' => public_path('assets/images/discount.png'),
                'categories' => $rootsMostChildren->take(24),
            ],
            [
                'name' => ['ar' => 'اكتشف المزيد', 'en' => 'Discover More'],
                'imageSource' => public_path('assets/images/logo.png'),
                'categories' => $rootsNewest->take(24),
            ],
        ];

        $nextSort = (int) (Menu::max('sort_order') ?? 0) + 1;

        foreach ($menuDefinitions as $index => $def) {
            $nameAr = $def['name']['ar'];
            $nameEn = $def['name']['en'];

            $menu = Menu::query()
                ->where('name->en', $nameEn)
                ->first();

            if (!$menu) {
                $imagePath = $this->ensurePublicDiskImage(
                    'menus/auto/' . ($index + 1) . '-' . $this->slugify($nameEn) . '.' . $this->guessExtension($def['imageSource']),
                    $def['imageSource']
                );

                $menu = Menu::create([
                    'name' => $def['name'],
                    'image' => $imagePath,
                    'image_title' => $def['name'],
                    'image_description' => [
                        'ar' => 'تصفح أحدث التصنيفات والتفاصيل',
                        'en' => 'Browse categories and details',
                    ],
                    'link' => '/shop',
                    'sort_order' => $nextSort + $index,
                    'is_active' => true,
                ]);
            } else {
                // Ensure it has an image (required by the request)
                if (!$menu->image) {
                    $menu->update([
                        'image' => $this->ensurePublicDiskImage(
                            'menus/auto/' . ($index + 1) . '-' . $this->slugify($nameEn) . '.' . $this->guessExtension($def['imageSource']),
                            $def['imageSource']
                        ),
                    ]);
                }

                // Rebuild columns/items to reflect current categories (idempotent)
                $menu->columns()->delete();
            }

            $categories = $def['categories']->values();
            if ($categories->isEmpty()) {
                continue;
            }

            $columnTitles = [
                ['ar' => 'الأقسام', 'en' => 'Categories'],
                ['ar' => 'تصفح', 'en' => 'Browse'],
                ['ar' => 'المزيد', 'en' => 'More'],
            ];

            $columnsCount = 3;
            $perColumn = (int) ceil($categories->count() / $columnsCount);

            for ($c = 0; $c < $columnsCount; $c++) {
                $column = $menu->columns()->create([
                    'title' => $columnTitles[$c] ?? ['ar' => 'الأقسام', 'en' => 'Categories'],
                    'sort_order' => $c,
                    'is_active' => true,
                ]);

                $slice = $categories->slice($c * $perColumn, $perColumn)->values();
                foreach ($slice as $pos => $category) {
                    $column->items()->create([
                        'category_id' => $category->id,
                        'sort_order' => $pos,
                        'is_active' => true,
                    ]);
                }
            }

            $this->command?->info('Menu created/updated: ' . $nameEn);
        }
    }

    private function ensurePublicDiskImage(string $destinationPath, string $sourcePath): ?string
    {
        if (!is_string($sourcePath) || $sourcePath === '' || !file_exists($sourcePath)) {
            return null;
        }

        if (Storage::disk('public')->exists($destinationPath)) {
            return $destinationPath;
        }

        Storage::disk('public')->put($destinationPath, file_get_contents($sourcePath));
        return $destinationPath;
    }

    private function guessExtension(string $sourcePath): string
    {
        $ext = strtolower((string) pathinfo($sourcePath, PATHINFO_EXTENSION));
        return $ext !== '' ? $ext : 'jpg';
    }

    private function slugify(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?? $value;
        $value = trim($value, '-');
        return $value !== '' ? $value : 'menu';
    }
}
