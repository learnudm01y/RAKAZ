<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        // Auto-generate slug from English title if not provided
        if (!$request->filled('slug') && $request->filled('title_en')) {
            $request->merge(['slug' => Str::slug($request->title_en)]);
        }

        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'meta_description_ar' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string|max:255',
            'meta_keywords_ar' => 'nullable|string|max:255',
            'meta_keywords_en' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'page_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/pages'), $imageName);
            $validated['image'] = '/storage/pages/' . $imageName;
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', __('labels.pages.created_successfully'));
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
        // Auto-generate slug from English title if provided
        if ($request->filled('title_en')) {
            $request->merge(['slug' => Str::slug($request->title_en)]);
        }

        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'meta_description_ar' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string|max:255',
            'meta_keywords_ar' => 'nullable|string|max:255',
            'meta_keywords_en' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($page->image && file_exists(public_path($page->image))) {
                unlink(public_path($page->image));
            }

            $image = $request->file('image');
            $imageName = 'page_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/pages'), $imageName);
            $validated['image'] = '/storage/pages/' . $imageName;
        }

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image == '1') {
            if ($page->image && file_exists(public_path($page->image))) {
                unlink(public_path($page->image));
            }
            $validated['image'] = null;
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', __('labels.pages.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        // Delete image if exists
        if ($page->image && file_exists(public_path($page->image))) {
            unlink(public_path($page->image));
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', __('labels.pages.deleted_successfully'));
    }
}
