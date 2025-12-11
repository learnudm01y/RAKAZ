<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SectionTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SectionTitleController extends Controller
{
    /**
     * Display a listing of section titles
     */
    public function index()
    {
        $sectionTitles = SectionTitle::orderBy('sort_order')->get();
        return view('admin.section-titles.index', compact('sectionTitles'));
    }

    /**
     * Show the form for editing section titles
     */
    public function edit($locale = 'ar')
    {
        $sectionTitles = SectionTitle::orderBy('sort_order')->get();
        return view('admin.section-titles.edit', compact('sectionTitles', 'locale'));
    }

    /**
     * Update section titles
     */
    public function update(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.section_key' => 'required|string',
            'sections.*.title_ar' => 'nullable|string|max:255',
            'sections.*.title_en' => 'nullable|string|max:255',
            'sections.*.active' => 'nullable|boolean',
        ]);

        try {
            foreach ($request->sections as $sectionData) {
                SectionTitle::updateOrCreate(
                    ['section_key' => $sectionData['section_key']],
                    [
                        'title_ar' => $sectionData['title_ar'] ?? null,
                        'title_en' => $sectionData['title_en'] ?? null,
                        'active' => isset($sectionData['active']) ? (bool)$sectionData['active'] : true,
                    ]
                );
            }

            $locale = $request->input('locale', 'ar');

            return redirect()
                ->route('admin.section-titles.edit', ['locale' => $locale])
                ->with('success', $locale === 'ar'
                    ? 'تم تحديث العناوين بنجاح'
                    : 'Titles updated successfully');

        } catch (\Exception $e) {
            Log::error('Section titles update error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء تحديث العناوين']);
        }
    }

    /**
     * Get section title by key (API endpoint)
     */
    public function getByKey($key, $locale = 'ar')
    {
        $section = SectionTitle::where('section_key', $key)
            ->where('active', true)
            ->first();

        if (!$section) {
            return response()->json(['title' => ''], 404);
        }

        return response()->json([
            'title' => $section->getTitle($locale),
            'section_key' => $section->section_key,
        ]);
    }
}
