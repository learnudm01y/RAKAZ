<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedSection;
use App\Models\Product;
use Illuminate\Http\Request;

class FeaturedSectionController extends Controller
{
    public function index()
    {
        $section = FeaturedSection::first();

        if (!$section) {
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
        }

        return view('admin.featured-section.index', compact('section'));
    }

    public function update(Request $request)
    {
        try {
            \Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            \Log::info('🚀 Featured Section Update STARTED');
            \Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            \Log::info('📥 Request Data:', $request->all());
            \Log::info('📋 Product IDs in request:', $request->product_ids ?? 'NULL');

            $request->validate([
                'title_ar' => 'required|string',
                'title_en' => 'required|string',
                'link_url' => 'required|string',
                'link_text_ar' => 'required|string',
                'link_text_en' => 'required|string',
                'product_ids' => 'nullable|array',
                'product_ids.*' => 'exists:products,id'
            ]);

            \Log::info('✅ Validation passed');

            $section = FeaturedSection::first();

            if (!$section) {
                \Log::warning('⚠️ No existing section found - Creating new one');
                $section = new FeaturedSection();
            } else {
                \Log::info('✅ Existing section found - ID: ' . $section->id);
                $currentProducts = $section->products->pluck('id')->toArray();
                \Log::info('📦 Current products BEFORE update:', $currentProducts);
                \Log::info('📊 Count BEFORE: ' . count($currentProducts));
            }

            $section->title = [
                'ar' => $request->title_ar,
                'en' => $request->title_en
            ];
            $section->link_url = $request->link_url;
            $section->link_text = [
                'ar' => $request->link_text_ar,
                'en' => $request->link_text_en
            ];
            $section->is_active = $request->has('is_active');

            \Log::info('💾 Saving section...');
            $section->save();
            \Log::info('✅ Section saved successfully');

            // Sync products with order
            if ($request->has('product_ids') && !empty($request->product_ids)) {
                $syncData = [];
                foreach ($request->product_ids as $index => $productId) {
                    $syncData[$productId] = ['order' => $index];
                }
                \Log::info('🔄 Syncing products...');
                \Log::info('📋 Products to sync:', array_keys($syncData));
                \Log::info('📊 Count to sync: ' . count($syncData));

                $section->products()->sync($syncData);
                \Log::info('✅ Products synced successfully');
            } else {
                \Log::warning('⚠️ No product_ids in request - Detaching all products');
                $section->products()->detach();
                \Log::info('✅ All products detached');
            }

            $finalProducts = $section->fresh()->products->pluck('id')->toArray();
            \Log::info('📦 Final products AFTER update:', $finalProducts);
            \Log::info('📊 Count AFTER: ' . count($finalProducts));

            \Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            \Log::info('✅ Featured Section Update COMPLETED SUCCESSFULLY');
            \Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

            return redirect()->route('admin.featured-section.index')
                ->with('success', 'تم تحديث قسم المنتجات المميزة بنجاح');

        } catch (\Exception $e) {
            \Log::error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            \Log::error('❌ CRITICAL ERROR in Featured Section Update');
            \Log::error('Error Message: ' . $e->getMessage());
            \Log::error('Error File: ' . $e->getFile());
            \Log::error('Error Line: ' . $e->getLine());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());
            \Log::error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

            return redirect()->route('admin.featured-section.index')
                ->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage());
        }
    }

    public function getProducts(Request $request)
    {
        $search = $request->get('q');

        $products = Product::where(function($query) use ($search) {
                $query->where('name->ar', 'LIKE', "%{$search}%")
                      ->orWhere('name->en', 'LIKE', "%{$search}%")
                      ->orWhere('id', 'LIKE', "%{$search}%");
            })
            ->limit(20)
            ->get()
            ->map(function($product) {
                $nameAr = $product->name['ar'] ?? '';
                $nameEn = $product->name['en'] ?? '';
                $name = app()->getLocale() === 'ar' ? ($nameAr ?: $nameEn) : ($nameEn ?: $nameAr);

                return [
                    'id' => $product->id,
                    'text' => $name . ' (ID: ' . $product->id . ')',
                    'image' => $product->main_image ? asset('storage/' . $product->main_image) : null
                ];
            });

        return response()->json($products);
    }

    /**
     * 🔥 NEW METHOD: حذف منتج مباشرة من قاعدة البيانات
     * هذا الـ method يحذف المنتج فوراً بدون انتظار حفظ الفورم
     */
    public function removeProduct(Request $request, $productId)
    {
        try {
            \Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            \Log::info('🔥 DIRECT DELETE OPERATION STARTED');
            \Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            \Log::info('🎯 Product ID to remove: ' . $productId);
            \Log::info('📋 Request Method: ' . $request->method());
            \Log::info('🌐 Request URL: ' . $request->fullUrl());
            \Log::info('📊 Request Data: ', $request->all());

            $section = FeaturedSection::first();

            if (!$section) {
                \Log::error('❌ Featured Section not found!');
                return response()->json([
                    'success' => false,
                    'message' => 'قسم المنتجات المميزة غير موجود'
                ], 404);
            }

            \Log::info('✅ Featured Section found - ID: ' . $section->id);

            // Get current products before deletion
            $beforeProducts = $section->products->pluck('id')->toArray();
            \Log::info('📦 Products BEFORE deletion: ', $beforeProducts);
            \Log::info('📊 Count BEFORE: ' . count($beforeProducts));

            // Check if product exists in featured section
            if (!in_array($productId, $beforeProducts)) {
                \Log::warning('⚠️ Product ' . $productId . ' not found in featured section');
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج غير موجود في القائمة'
                ], 404);
            }

            // Perform deletion using detach
            \Log::info('🗑️ Executing detach for product: ' . $productId);
            $section->products()->detach($productId);
            \Log::info('✅ Detach executed successfully');

            // Verify deletion
            $afterProducts = $section->fresh()->products->pluck('id')->toArray();
            \Log::info('📦 Products AFTER deletion: ', $afterProducts);
            \Log::info('📊 Count AFTER: ' . count($afterProducts));

            $deleted = in_array($productId, $beforeProducts) && !in_array($productId, $afterProducts);

            if ($deleted) {
                \Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                \Log::info('✅ DIRECT DELETE COMPLETED SUCCESSFULLY');
                \Log::info('🎉 Product ' . $productId . ' removed from database');
                \Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف المنتج من قاعدة البيانات بنجاح',
                    'product_id' => $productId,
                    'before_count' => count($beforeProducts),
                    'after_count' => count($afterProducts)
                ]);
            } else {
                \Log::error('❌ Delete operation failed - product still exists');
                return response()->json([
                    'success' => false,
                    'message' => 'فشل حذف المنتج من قاعدة البيانات'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            \Log::error('❌ CRITICAL ERROR in removeProduct');
            \Log::error('Error Message: ' . $e->getMessage());
            \Log::error('Error File: ' . $e->getFile());
            \Log::error('Error Line: ' . $e->getLine());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());
            \Log::error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }
}
