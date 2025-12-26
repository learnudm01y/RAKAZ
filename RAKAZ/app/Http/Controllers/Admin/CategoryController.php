<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 25);
        $allowedPerPage = [10, 25, 50, 100, 1000];
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 25;
        }

        $categories = Category::with(['children.children'])
            ->roots()
            ->ordered()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Move a subcategory to another main category (AJAX).
     */
    public function move(Request $request)
    {
        // Supports two payload shapes:
        // 1) Move to a main category: {category_id, new_parent_id}
        // 2) Drag-drop reorder/move: {category_id, target_id, position: before|after}

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'new_parent_id' => 'nullable|exists:categories,id',
            'target_id' => 'nullable|exists:categories,id',
            'position' => 'nullable|in:before,after',
        ]);

        $category = Category::findOrFail($validated['category_id']);

        // Legacy move behavior
        if (!empty($validated['new_parent_id'])) {
            $newParent = Category::findOrFail($validated['new_parent_id']);

            if ($category->parent_id === null) {
                return response()->json([
                    'success' => false,
                    'message' => app()->getLocale() === 'ar' ? 'يمكن نقل التصنيفات الفرعية فقط.' : 'Only subcategories can be moved.',
                ], 422);
            }

            if ($newParent->parent_id !== null) {
                return response()->json([
                    'success' => false,
                    'message' => app()->getLocale() === 'ar' ? 'يجب اختيار تصنيف رئيسي كوجهة.' : 'Destination must be a main category.',
                ], 422);
            }

            if ((int) $newParent->id === (int) $category->parent_id) {
                return response()->json(['success' => true]);
            }

            $maxSort = (int) Category::where('parent_id', $newParent->id)->max('sort_order');
            $category->parent_id = $newParent->id;
            $category->sort_order = $maxSort + 1;
            $category->save();

            return response()->json(['success' => true]);
        }

        // Drag-drop reorder/move behavior
        if (empty($validated['target_id']) || empty($validated['position'])) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'ar' ? 'بيانات غير مكتملة.' : 'Incomplete payload.',
            ], 422);
        }

        $target = Category::findOrFail($validated['target_id']);
        $position = $validated['position'];

        if ((int) $category->id === (int) $target->id) {
            return response()->json(['success' => true]);
        }

        // Compute intended new parent:
        // - If dropping onto a root category and dragged is NOT root => move under that root (append).
        // - Otherwise, move/reorder within the target's parent list.
        $isTargetRoot = $target->parent_id === null;
        $isDraggedRoot = $category->parent_id === null;

        $newParentId = $target->parent_id;
        $appendToRootChildren = false;

        if ($isTargetRoot && !$isDraggedRoot) {
            $newParentId = $target->id;
            $appendToRootChildren = true;
        }

        // Prevent turning a main category into a subcategory.
        if ($isDraggedRoot && $newParentId !== null) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'ar' ? 'لا يمكن تحويل التصنيف الرئيسي إلى فرعي.' : 'Cannot convert a main category into a subcategory.',
            ], 422);
        }

        // When not appending into a root children list, the drop must be within the same parent list (or it becomes a move).
        // If parent differs, we will move into the target's parent list.

        return \Illuminate\Support\Facades\DB::transaction(function () use ($category, $target, $newParentId, $position, $appendToRootChildren) {
            // Update parent if needed.
            $category->parent_id = $newParentId;
            $category->save();

            // Fetch siblings under the new parent, excluding the dragged category.
            $siblingsQuery = Category::query()->where('parent_id', $newParentId);
            $siblings = $siblingsQuery
                ->where('id', '!=', $category->id)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->pluck('id')
                ->values();

            // Append to end when dropping onto a root category row.
            if ($appendToRootChildren) {
                $ordered = $siblings->push($category->id);
            } else {
                // Target must be in the same siblings list under new parent.
                $targetIndex = $siblings->search((int) $target->id);
                if ($targetIndex === false) {
                    // Fallback: append.
                    $ordered = $siblings->push($category->id);
                } else {
                    $ordered = $siblings->values();
                    $insertAt = $position === 'before' ? $targetIndex : $targetIndex + 1;
                    $ordered->splice($insertAt, 0, [$category->id]);
                }
            }

            // Re-number sort_order densely.
            foreach ($ordered as $idx => $id) {
                Category::where('id', $id)->update(['sort_order' => $idx]);
            }

            return response()->json(['success' => true]);
        });
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
            ->with('success', __('labels.categories.created_successfully'));
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
            ->with('success', __('labels.categories.updated_successfully'));
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
            ->with('success', __('labels.categories.deleted_successfully'));
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
