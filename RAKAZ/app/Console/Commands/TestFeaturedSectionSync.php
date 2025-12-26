<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FeaturedSection;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestFeaturedSectionSync extends Command
{
    protected $signature = 'test:featured-sync {product_ids?* : Product IDs to sync (leave empty to test with empty array)}';
    protected $description = 'اختبار عملية Sync للمنتجات - محاكاة عملية الحفظ من Controller';

    public function handle()
    {
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('🔄 اختبار عملية SYNC (محاكاة Controller)');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        Log::info('🔄 TEST COMMAND STARTED: featured-section-sync');
        Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        try {
            // Get section
            $section = FeaturedSection::first();

            if (!$section) {
                $this->error('❌ لا يوجد Featured Section - سيتم إنشاء واحد جديد');
                Log::error('❌ No FeaturedSection found - creating new one');

                $section = FeaturedSection::create([
                    'title' => [
                        'ar' => 'المنتجات المميزة',
                        'en' => 'Featured Products'
                    ],
                    'link_url' => '/shop',
                    'link_text' => [
                        'ar' => 'تسوق الكل',
                        'en' => 'Shop All'
                    ],
                    'is_active' => true
                ]);

                $this->info('✅ تم إنشاء Featured Section جديد - ID: ' . $section->id);
                Log::info('✅ Created new FeaturedSection - ID: ' . $section->id);
            }

            // Show current state
            $this->showCurrentState($section);

            // Get product IDs from arguments or use empty array
            $productIds = $this->argument('product_ids');

            if (empty($productIds)) {
                $this->warn('⚠️  لم يتم تحديد منتجات - سيتم استخدام مصفوفة فارغة (حذف الكل)');
                Log::warning('⚠️  No product IDs provided - using empty array (delete all)');
                $productIds = [];
            } else {
                // Validate product IDs
                $validIds = [];
                foreach ($productIds as $id) {
                    if (Product::find($id)) {
                        $validIds[] = $id;
                        $this->info("  ✅ Product ID $id موجود");
                    } else {
                        $this->error("  ❌ Product ID $id غير موجود!");
                    }
                }
                $productIds = $validIds;
            }

            $this->info("📊 عدد المنتجات التي سيتم مزامنتها: " . count($productIds));
            $this->info("🎯 IDs: " . (count($productIds) > 0 ? implode(', ', $productIds) : 'EMPTY (سيتم حذف الكل)'));

            Log::info('📊 Products to sync: ' . count($productIds));
            Log::info('🎯 Product IDs: ' . json_encode($productIds));

            // Confirm action
            if (!$this->confirm('هل تريد المتابعة؟', true)) {
                $this->warn('تم الإلغاء');
                return;
            }

            // Perform sync operation (EXACTLY like Controller)
            $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->info('🔄 جاري تنفيذ عملية SYNC...');
            Log::info('🔄 Starting SYNC operation...');

            DB::beginTransaction();

            try {
                // Prepare sync data with order
                $syncData = [];
                foreach ($productIds as $index => $productId) {
                    $syncData[$productId] = ['order' => $index];
                }

                Log::info('📦 Sync data prepared:', $syncData);
                $this->info('📦 Sync data: ' . json_encode($syncData));

                // Execute sync
                $result = $section->products()->sync($syncData);

                Log::info('✅ SYNC executed successfully');
                Log::info('📊 Sync result:', $result);

                $this->info('✅ تم تنفيذ SYNC بنجاح!');
                $this->table(
                    ['النوع', 'العدد'],
                    [
                        ['تم الإضافة', count($result['attached'] ?? [])],
                        ['تم الحذف', count($result['detached'] ?? [])],
                        ['تم التحديث', count($result['updated'] ?? [])],
                    ]
                );

                DB::commit();
                Log::info('✅ Transaction committed');
                $this->info('✅ تم حفظ التغييرات في قاعدة البيانات');

                // Show new state
                $this->newLine();
                $this->showCurrentState($section->fresh());

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error('❌ خطأ أثناء SYNC: ' . $e->getMessage());
                $this->error('Stack: ' . $e->getTraceAsString());

                Log::error('❌ SYNC failed: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
            }

        } catch (\Exception $e) {
            $this->error('❌ خطأ عام: ' . $e->getMessage());
            Log::error('❌ General error: ' . $e->getMessage());
        }

        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('✅ الاختبار انتهى - راجع storage/logs/laravel.log');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        Log::info('✅ TEST COMMAND COMPLETED');
        Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }

    private function showCurrentState($section)
    {
        $this->newLine();
        $this->info('📊 الحالة الحالية:');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $products = $section->products;
        $this->info("عدد المنتجات: " . $products->count());

        Log::info('📊 Current state - Products count: ' . $products->count());

        if ($products->count() > 0) {
            $tableData = [];
            foreach ($products as $product) {
                $tableData[] = [
                    $product->id,
                    $product->name['ar'] ?? 'NULL',
                    $product->pivot->order ?? 'NULL'
                ];

                Log::info("  Product: ID={$product->id}, Name={$product->name['ar']}, Order=" . ($product->pivot->order ?? 'NULL'));
            }

            $this->table(
                ['ID', 'الاسم', 'الترتيب'],
                $tableData
            );
        } else {
            $this->info('📭 لا توجد منتجات');
            Log::info('📭 No products');
        }

        // Also show pivot table directly
        $pivotCount = DB::table('featured_section_products')->count();
        $this->info("📊 عدد السجلات في جدول الربط: $pivotCount");
        Log::info("📊 Pivot table records: $pivotCount");
    }
}
