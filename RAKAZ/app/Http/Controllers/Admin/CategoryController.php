<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->roots()->ordered()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::roots()->active()->ordered()->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug_ar' => 'nullable|string|max:255',
            'slug_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => [
                'ar' => $request->name_ar,
                'en' => $request->name_en,
            ],
            'slug' => [
                'ar' => $request->slug_ar ?: Str::slug($request->name_ar),
                'en' => $request->slug_en ?: Str::slug($request->name_en),
            ],
            'description' => [
                'ar' => $request->description_ar,
                'en' => $request->description_en,
            ],
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image'] = Storage::url($imagePath);
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم إضافة التصنيف بنجاح'
                : 'Category added successfully');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::roots()->active()->ordered()
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug_ar' => 'nullable|string|max:255',
            'slug_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => [
                'ar' => $request->name_ar,
                'en' => $request->name_en,
            ],
            'slug' => [
                'ar' => $request->slug_ar ?: Str::slug($request->name_ar),
                'en' => $request->slug_en ?: Str::slug($request->name_en),
            ],
            'description' => [
                'ar' => $request->description_ar,
                'en' => $request->description_en,
            ],
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                $oldImagePath = str_replace('/storage/', '', $category->image);
                Storage::disk('public')->delete($oldImagePath);
            }

            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image'] = Storage::url($imagePath);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم تحديث التصنيف بنجاح'
                : 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        // Delete image if exists
        if ($category->image) {
            $imagePath = str_replace('/storage/', '', $category->image);
            Storage::disk('public')->delete($imagePath);
        }

        // Delete category (cascade will handle children)
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم حذف التصنيف بنجاح'
                : 'Category deleted successfully');
    }

    /**
     * Get subcategories for a parent category (AJAX)
     */
    public function getSubcategories(Request $request)
    {
        $parentId = $request->input('parent_id');

        if (!$parentId) {
            return response()->json([]);
        }

        $subcategories = Category::where('parent_id', $parentId)
            ->active()
            ->ordered()
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->getName(),
                    'has_children' => $category->hasChildren(),
                ];
            });

        return response()->json($subcategories);
    }
}
