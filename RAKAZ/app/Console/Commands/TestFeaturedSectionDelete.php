<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FeaturedSection;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestFeaturedSectionDelete extends Command
{
    protected $signature = 'test:featured-delete {--action=show : show|delete}';
    protected $description = 'اختبار شامل لعملية حذف المنتجات من Featured Section';

    public function handle()
    {
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('🧪 اختبار شامل لنظام Featured Section');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        Log::info('🧪 TEST COMMAND STARTED: featured-section-delete');
        Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        // Step 1: Check database tables
        $this->step1_checkTables();

        // Step 2: Check FeaturedSection record
        $this->step2_checkSection();

        // Step 3: Check current products
        $this->step3_checkProducts();

        // Step 4: Show pivot table data
        $this->step4_checkPivot();

        // Step 5: Perform delete action if requested
        if ($this->option('action') === 'delete') {
            $this->step5_performDelete();
        } else {
            $this->warn('⚠️  لم يتم الحذف - استخدم --action=delete لتنفيذ الحذف');
            $this->info('📝 للحذف استخدم: php artisan test:featured-delete --action=delete');
        }

        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('✅ الاختبار انتهى - راجع storage/logs/laravel.log للتفاصيل');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        Log::info('✅ TEST COMMAND COMPLETED');
        Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }

    private function step1_checkTables()
    {
        $this->newLine();
        $this->info('📊 STEP 1: فحص الجداول في قاعدة البيانات');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        Log::info('STEP 1: Checking database tables');

        $tables = [
            'featured_section',
            'featured_section_products',
            'products'
        ];

        foreach ($tables as $table) {
            try {
                $exists = DB::select("SHOW TABLES LIKE '$table'");
                if ($exists) {
                    $count = DB::table($table)->count();
                    $this->info("  ✅ جدول $table موجود - عدد السجلات: $count");
                    Log::info("✅ Table $table exists - Records: $count");
                } else {
                    $this->error("  ❌ جدول $table غير موجود!");
                    Log::error("❌ Table $table does not exist!");
                }
            } catch (\Exception $e) {
                $this->error("  ❌ خطأ في فحص جدول $table: " . $e->getMessage());
                Log::error("❌ Error checking table $table: " . $e->getMessage());
            }
        }
    }

    private function step2_checkSection()
    {
        $this->newLine();
        $this->info('🎯 STEP 2: فحص سجل Featured Section');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        Log::info('STEP 2: Checking FeaturedSection record');

        try {
            $section = FeaturedSection::first();

            if ($section) {
                $this->info("  ✅ تم العثور على Featured Section");
                $this->table(
                    ['الحقل', 'القيمة'],
                    [
                        ['ID', $section->id],
                        ['العنوان (عربي)', $section->title['ar'] ?? 'NULL'],
                        ['العنوان (إنجليزي)', $section->title['en'] ?? 'NULL'],
                        ['الرابط', $section->link_url ?? 'NULL'],
                        ['نشط', $section->is_active ? 'نعم' : 'لا'],
                        ['تاريخ الإنشاء', $section->created_at],
                        ['تاريخ التحديث', $section->updated_at],
                    ]
                );

                Log::info('✅ FeaturedSection found', [
                    'id' => $section->id,
                    'title_ar' => $section->title['ar'] ?? 'NULL',
                    'title_en' => $section->title['en'] ?? 'NULL',
                    'is_active' => $section->is_active
                ]);
            } else {
                $this->warn('  ⚠️  لا يوجد سجل Featured Section');
                Log::warning('⚠️  No FeaturedSection record found');
            }
        } catch (\Exception $e) {
            $this->error('  ❌ خطأ: ' . $e->getMessage());
            Log::error('❌ Error in step2: ' . $e->getMessage());
        }
    }

    private function step3_checkProducts()
    {
        $this->newLine();
        $this->info('📦 STEP 3: فحص المنتجات المرتبطة');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        Log::info('STEP 3: Checking associated products');

        try {
            $section = FeaturedSection::first();

            if (!$section) {
                $this->warn('  ⚠️  لا يوجد Featured Section للفحص');
                return;
            }

            $products = $section->products;

            $this->info("  📊 عدد المنتجات المرتبطة: " . $products->count());
            Log::info("📊 Associated products count: " . $products->count());

            if ($products->count() > 0) {
                $tableData = [];
                foreach ($products as $product) {
                    $tableData[] = [
                        'ID' => $product->id,
                        'الاسم (عربي)' => $product->name['ar'] ?? 'NULL',
                        'الاسم (إنجليزي)' => $product->name['en'] ?? 'NULL',
                        'الترتيب' => $product->pivot->order ?? 'NULL',
                    ];

                    Log::info("  Product ID: {$product->id} - {$product->name['ar']} - Order: " . ($product->pivot->order ?? 'NULL'));
                }

                $this->table(
                    ['ID', 'الاسم (عربي)', 'الاسم (إنجليزي)', 'الترتيب'],
                    $tableData
                );
            } else {
                $this->info('  📭 لا توجد منتجات مرتبطة حالياً');
                Log::info('📭 No products currently associated');
            }
        } catch (\Exception $e) {
            $this->error('  ❌ خطأ: ' . $e->getMessage());
            Log::error('❌ Error in step3: ' . $e->getMessage());
        }
    }

    private function step4_checkPivot()
    {
        $this->newLine();
        $this->info('🔗 STEP 4: فحص جدول الربط (Pivot Table)');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        Log::info('STEP 4: Checking pivot table');

        try {
            $pivotRecords = DB::table('featured_section_products')->get();

            $this->info("  📊 عدد السجلات في جدول الربط: " . $pivotRecords->count());
            Log::info("📊 Pivot table records count: " . $pivotRecords->count());

            if ($pivotRecords->count() > 0) {
                $tableData = [];
                foreach ($pivotRecords as $record) {
                    $tableData[] = [
                        'featured_section_id' => $record->featured_section_id,
                        'product_id' => $record->product_id,
                        'order' => $record->order ?? 'NULL',
                    ];

                    Log::info("  Pivot: section_id={$record->featured_section_id}, product_id={$record->product_id}, order=" . ($record->order ?? 'NULL'));
                }

                $this->table(
                    ['Section ID', 'Product ID', 'Order'],
                    $tableData
                );
            } else {
                $this->info('  📭 جدول الربط فارغ');
                Log::info('📭 Pivot table is empty');
            }
        } catch (\Exception $e) {
            $this->error('  ❌ خطأ: ' . $e->getMessage());
            Log::error('❌ Error in step4: ' . $e->getMessage());
        }
    }

    private function step5_performDelete()
    {
        $this->newLine();
        $this->info('🗑️  STEP 5: تنفيذ عملية الحذف');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        Log::info('STEP 5: Performing DELETE operation');

        try {
            $section = FeaturedSection::first();

            if (!$section) {
                $this->error('  ❌ لا يوجد Featured Section للحذف منه');
                Log::error('❌ No FeaturedSection to delete from');
                return;
            }

            $currentCount = $section->products->count();
            $this->info("  📊 عدد المنتجات الحالية: $currentCount");
            Log::info("📊 Current products count: $currentCount");

            if ($currentCount === 0) {
                $this->warn('  ⚠️  لا توجد منتجات للحذف');
                Log::warning('⚠️  No products to delete');
                return;
            }

            // Get product IDs before deletion
            $productIds = $section->products->pluck('id')->toArray();
            $this->info("  🎯 المنتجات التي سيتم حذفها: " . implode(', ', $productIds));
            Log::info("🎯 Products to be deleted: " . implode(', ', $productIds));

            // Ask for confirmation
            if (!$this->confirm('هل أنت متأكد من حذف جميع المنتجات؟', false)) {
                $this->warn('  ⚠️  تم إلغاء عملية الحذف');
                Log::info('⚠️  Delete operation cancelled by user');
                return;
            }

            // Perform deletion using detach (remove all)
            $this->info('  🗑️  جاري الحذف...');
            Log::info('🗑️  Starting detach operation...');

            DB::beginTransaction();

            try {
                // Method 1: Detach all products
                $section->products()->detach();
                Log::info('✅ Method 1: detach() executed');

                DB::commit();

                $this->info('  ✅ تم الحذف بنجاح!');
                Log::info('✅ DELETE SUCCESSFUL - Transaction committed');

                // Verify deletion
                $remainingCount = $section->fresh()->products->count();
                $this->info("  📊 عدد المنتجات المتبقية: $remainingCount");
                Log::info("📊 Remaining products count: $remainingCount");

                if ($remainingCount === 0) {
                    $this->info('  ✅ تم حذف جميع المنتجات بنجاح!');
                    Log::info('✅ All products successfully deleted!');
                } else {
                    $this->warn("  ⚠️  تبقى $remainingCount منتج!");
                    Log::warning("⚠️  $remainingCount products still remaining!");
                }

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error('  ❌ خطأ أثناء الحذف: ' . $e->getMessage());
                Log::error('❌ Error during delete: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
            }

        } catch (\Exception $e) {
            $this->error('  ❌ خطأ: ' . $e->getMessage());
            Log::error('❌ Error in step5: ' . $e->getMessage());
        }
    }
}
