<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $pages = Page::when($search, function($query) use ($search) {
            $query->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        })->ordered()->paginate(15);

        return view('admin.pages.index', compact('pages', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'meta_description_ar' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string|max:255',
            'meta_keywords_ar' => 'nullable|string|max:255',
            'meta_keywords_en' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'تم إضافة الصفحة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'meta_description_ar' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string|max:255',
            'meta_keywords_ar' => 'nullable|string|max:255',
            'meta_keywords_en' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'تم تحديث الصفحة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'تم حذف الصفحة بنجاح');
    }
}
