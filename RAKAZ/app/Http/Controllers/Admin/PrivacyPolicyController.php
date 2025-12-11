<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicyPage;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function edit()
    {
        $page = PrivacyPolicyPage::firstOrCreate([]);
        return view('admin.privacy.edit', compact('page'));
    }

    public function update(Request $request)
    {
        try {
            $page = PrivacyPolicyPage::firstOrCreate([]);

            $validated = $request->validate([
                'hero_title_ar' => 'nullable|string|max:255',
                'hero_title_en' => 'nullable|string|max:255',
                'hero_subtitle_ar' => 'nullable|string',
                'hero_subtitle_en' => 'nullable|string',
                'content_ar' => 'nullable|string',
                'content_en' => 'nullable|string',
                'section_1_title_ar' => 'nullable|string',
                'section_1_title_en' => 'nullable|string',
                'section_1_content_ar' => 'nullable|string',
                'section_1_content_en' => 'nullable|string',
                'section_2_title_ar' => 'nullable|string',
                'section_2_title_en' => 'nullable|string',
                'section_2_content_ar' => 'nullable|string',
                'section_2_content_en' => 'nullable|string',
                'section_3_title_ar' => 'nullable|string',
                'section_3_title_en' => 'nullable|string',
                'section_3_content_ar' => 'nullable|string',
                'section_3_content_en' => 'nullable|string',
                'section_4_title_ar' => 'nullable|string',
                'section_4_title_en' => 'nullable|string',
                'section_4_content_ar' => 'nullable|string',
                'section_4_content_en' => 'nullable|string',
                'meta_description_ar' => 'nullable|string|max:255',
                'meta_description_en' => 'nullable|string|max:255',
                'meta_keywords_ar' => 'nullable|string|max:255',
                'meta_keywords_en' => 'nullable|string|max:255',
                'status' => 'nullable|in:active,inactive',
            ]);

            $page->update($validated);

            \Log::info('Privacy Policy page updated successfully', ['page_id' => $page->id]);

            return response()->json([
                'success' => true,
                'message' => 'تم التحديث بنجاح / Updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Privacy Policy update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل التحديث / Update failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
