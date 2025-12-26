<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerfectGiftSection;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerfectGiftSectionController extends Controller
{
    public function index()
    {
        $section = PerfectGiftSection::with('products')->first();
        return view('admin.perfect-gift-section.index', compact('section'));
    }

    public function update(Request $request)
    {
        try {
            Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            Log::info('🚀 Perfect Gift Section Update STARTED');
            Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            Log::info('📥 Request Data:', $request->all());
            Log::info('📋 Product IDs in request:', $request->product_ids ?? 'NULL');

            $request->validate([
                'title_ar' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'link_url' => 'required|string|max:255',
                'link_text_ar' => 'required|string|max:255',
                'link_text_en' => 'required|string|max:255',
                'is_active' => 'nullable|in:on,1,true',
                'product_ids' => 'array',
                'product_ids.*' => 'exists:products,id',
            ]);

            Log::info('✅ Validation passed');

            $section = PerfectGiftSection::firstOrCreate(['id' => 1]);

            Log::info('✅ Section found/created - ID: ' . $section->id);
            $currentProducts = $section->products->pluck('id')->toArray();
            Log::info('📦 Current products BEFORE update:', $currentProducts);
            Log::info('📊 Count BEFORE: ' . count($currentProducts));

            $section->update([
                'title' => [
                    'ar' => $request->title_ar,
                    'en' => $request->title_en,
                ],
                'link_url' => $request->link_url,
                'link_text' => [
                    'ar' => $request->link_text_ar,
                    'en' => $request->link_text_en,
                ],
                'is_active' => $request->has('is_active'),
            ]);

            Log::info('✅ Section updated successfully');

            // Update products with order
            Log::info('🔄 Detaching all products...');
            $section->products()->detach();
            Log::info('✅ All products detached');

            if ($request->has('product_ids') && !empty($request->product_ids)) {
                Log::info('🔄 Attaching products...');
                Log::info('📋 Products to attach:', $request->product_ids);
                Log::info('📊 Count to attach: ' . count($request->product_ids));

                foreach ($request->product_ids as $order => $productId) {
                    $section->products()->attach($productId, ['order' => $order]);
                    Log::info("  ✅ Attached product ID: {$productId} with order: {$order}");
                }

                Log::info('✅ All products attached successfully');
            } else {
                Log::warning('⚠️ No product_ids in request - all products remain detached');
            }

            $finalProducts = $section->fresh()->products->pluck('id')->toArray();
            Log::info('📦 Final products AFTER update:', $finalProducts);
            Log::info('📊 Count AFTER: ' . count($finalProducts));

            Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            Log::info('✅ Perfect Gift Section Update COMPLETED SUCCESSFULLY');
            Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

            return redirect()->route('admin.perfect-gift-section.index')
                ->with('success', 'تم تحديث قسم الهدية المثالية بنجاح');

        } catch (Exception $e) {
            Log::error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            Log::error('❌ CRITICAL ERROR in Perfect Gift Section Update');
            Log::error('Error Message: ' . $e->getMessage());
            Log::error('Error File: ' . $e->getFile());
            Log::error('Error Line: ' . $e->getLine());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            Log::error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

            return redirect()->route('admin.perfect-gift-section.index')
                ->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage());
        }
    }

    public function getProducts(Request $request)
    {
        $search = $request->get('q', '');

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
                    'image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
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
            Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            Log::info('🔥 PERFECT GIFT - DIRECT DELETE OPERATION STARTED');
            Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            Log::info('🎯 Product ID to remove: ' . $productId);
            Log::info('📋 Request Method: ' . $request->method());
            Log::info('🌐 Request URL: ' . $request->fullUrl());
            Log::info('📊 Request Data: ', $request->all());

            $section = PerfectGiftSection::first();

            if (!$section) {
                Log::error('❌ Perfect Gift Section not found!');
                return response()->json([
                    'success' => false,
                    'message' => 'قسم الهدية المثالية غير موجود'
                ], 404);
            }

            Log::info('✅ Perfect Gift Section found - ID: ' . $section->id);

            // Get current products before deletion
            $beforeProducts = $section->products->pluck('id')->toArray();
            Log::info('📦 Products BEFORE deletion: ', $beforeProducts);
            Log::info('📊 Count BEFORE: ' . count($beforeProducts));

            // Check if product exists in section
            if (!in_array($productId, $beforeProducts)) {
                Log::warning('⚠️ Product ' . $productId . ' not found in perfect gift section');
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج غير موجود في القائمة'
                ], 404);
            }

            // Perform deletion using detach
            Log::info('🗑️ Executing detach for product: ' . $productId);
            $section->products()->detach($productId);
            Log::info('✅ Detach executed successfully');

            // Verify deletion
            $afterProducts = $section->fresh()->products->pluck('id')->toArray();
            Log::info('📦 Products AFTER deletion: ', $afterProducts);
            Log::info('📊 Count AFTER: ' . count($afterProducts));

            $deleted = in_array($productId, $beforeProducts) && !in_array($productId, $afterProducts);

            if ($deleted) {
                Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                Log::info('✅ PERFECT GIFT - DIRECT DELETE COMPLETED SUCCESSFULLY');
                Log::info('🎉 Product ' . $productId . ' removed from database');
                Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف المنتج من قاعدة البيانات بنجاح',
                    'product_id' => $productId,
                    'before_count' => count($beforeProducts),
                    'after_count' => count($afterProducts)
                ]);
            } else {
                Log::error('❌ Delete operation failed - product still exists');
                return response()->json([
                    'success' => false,
                    'message' => 'فشل حذف المنتج من قاعدة البيانات'
                ], 500);
            }

        } catch (Exception $e) {
            Log::error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            Log::error('❌ CRITICAL ERROR in Perfect Gift removeProduct');
            Log::error('Error Message: ' . $e->getMessage());
            Log::error('Error File: ' . $e->getFile());
            Log::error('Error Line: ' . $e->getLine());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            Log::error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }
}

