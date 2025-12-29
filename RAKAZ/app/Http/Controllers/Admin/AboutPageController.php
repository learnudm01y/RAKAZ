<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AboutPageController extends Controller
{
    protected ImageCompressionService $imageService;

    public function __construct(ImageCompressionService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function edit()
    {
        $page = Page::where('slug', 'about-us')->firstOrFail();

        // Get settings grouped
        $settings = SiteSetting::orderBy('group')->orderBy('order')->get();
        $grouped = $settings->groupBy('group');

        return view('admin.about.edit', compact('page', 'grouped'));
    }

    public function update(Request $request)
    {
        try {
            $page = Page::where('slug', 'about-us')->firstOrFail();

            $validated = $request->validate([
                'hero_title_ar' => 'nullable|string|max:255',
                'hero_title_en' => 'nullable|string|max:255',
                'hero_subtitle_ar' => 'nullable|string',
                'hero_subtitle_en' => 'nullable|string',
                'story_title_ar' => 'nullable|string|max:255',
                'story_title_en' => 'nullable|string|max:255',
                'story_content_ar' => 'nullable|string',
                'story_content_en' => 'nullable|string',
                'story_image' => 'nullable|string',
                'story_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'values_title_ar' => 'nullable|string|max:255',
                'values_title_en' => 'nullable|string|max:255',
            ]);

            // Handle image upload with compression
            // Check for pre-compressed image first
            if ($request->has('compressed_story_image') && !empty($request->input('compressed_story_image'))) {
                $tempPath = $request->input('compressed_story_image');
                $tempFullPath = storage_path('app/public/' . $tempPath);

                if (file_exists($tempFullPath)) {
                    // Move from temp to permanent directory
                    $filename = basename($tempPath);
                    $newPath = 'about/' . $filename;
                    $newFullPath = storage_path('app/public/' . $newPath);

                    // Ensure directory exists
                    $dirPath = dirname($newFullPath);
                    if (!is_dir($dirPath)) {
                        mkdir($dirPath, 0755, true);
                    }

                    // Delete old image if exists
                    if ($page->story_image && file_exists(public_path($page->story_image))) {
                        @unlink(public_path($page->story_image));
                    }

                    // Move file
                    rename($tempFullPath, $newFullPath);
                    $validated['story_image'] = '/storage/' . $newPath;

                    Log::info('Pre-compressed image moved successfully', [
                        'path' => $validated['story_image']
                    ]);
                }
            } elseif ($request->hasFile('story_image_file')) {
                // Fall back to direct compression
                // Delete old image if exists
                if ($page->story_image && file_exists(public_path($page->story_image))) {
                    @unlink(public_path($page->story_image));
                }

                $path = $this->imageService->compressAndStore($request->file('story_image_file'), 'about');
                $validated['story_image'] = '/storage/' . $path;

                Log::info('Image compressed and uploaded successfully', [
                    'path' => $validated['story_image']
                ]);
            }

            // Add value cards validation
            for ($i = 1; $i <= 4; $i++) {
                $validated["value{$i}_title_ar"] = $request->input("value{$i}_title_ar");
                $validated["value{$i}_title_en"] = $request->input("value{$i}_title_en");
                $validated["value{$i}_description_ar"] = $request->input("value{$i}_description_ar");
                $validated["value{$i}_description_en"] = $request->input("value{$i}_description_en");
                $validated["value{$i}_icon"] = $request->input("value{$i}_icon");
            }

            $page->update($validated);

            // Update site settings if provided
            if ($request->has('settings')) {
                foreach ($request->input('settings') as $settingData) {
                    if (isset($settingData['id'])) {
                        $setting = SiteSetting::find($settingData['id']);
                        if ($setting) {
                            $setting->update([
                                'value_ar' => $settingData['value_ar'] ?? $setting->value_ar,
                                'value_en' => $settingData['value_en'] ?? $setting->value_en,
                            ]);
                        }
                    }
                }
            }

            Log::info('About page updated successfully', ['page_id' => $page->id]);

            return response()->json([
                'success' => true,
                'message' => 'تم التحديث بنجاح / Updated successfully',
                'image_path' => $validated['story_image'] ?? $page->story_image
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('About Page Validation Failed', [
                'errors' => $e->errors(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من البيانات / Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('About Page Update Failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحديث / Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function preview()
    {
        $page = Page::where('slug', 'about-us')->firstOrFail();
        return view('about', compact('page'));
    }
}
