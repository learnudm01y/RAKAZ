<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    public function edit()
    {
        $page = ContactPage::firstOrCreate([]);
        return view('admin.contact.edit', compact('page'));
    }

    public function update(Request $request)
    {
        try {
            $page = ContactPage::firstOrCreate([]);

            $validated = $request->validate([
                'hero_title_ar' => 'nullable|string|max:255',
                'hero_title_en' => 'nullable|string|max:255',
                'hero_subtitle_ar' => 'nullable|string',
                'hero_subtitle_en' => 'nullable|string',
                'phone' => 'nullable|string|max:50',
                'email' => 'nullable|email|max:100',
                'address_ar' => 'nullable|string',
                'address_en' => 'nullable|string',
                'map_url' => 'nullable|url|max:500',
                'working_hours_ar' => 'nullable|string',
                'working_hours_en' => 'nullable|string',
                'facebook_url' => 'nullable|url|max:255',
                'twitter_url' => 'nullable|url|max:255',
                'instagram_url' => 'nullable|url|max:255',
                'linkedin_url' => 'nullable|url|max:255',
                'youtube_url' => 'nullable|url|max:255',
                'additional_info_ar' => 'nullable|string',
                'additional_info_en' => 'nullable|string',
                'meta_description_ar' => 'nullable|string|max:255',
                'meta_description_en' => 'nullable|string|max:255',
                'meta_keywords_ar' => 'nullable|string|max:255',
                'meta_keywords_en' => 'nullable|string|max:255',
                'status' => 'nullable|in:active,inactive',
            ]);

            $page->update($validated);

            \Log::info('Contact page updated successfully', ['page_id' => $page->id]);

            return response()->json([
                'success' => true,
                'message' => 'تم التحديث بنجاح / Updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Contact page update failed', [
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
