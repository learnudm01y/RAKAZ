<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuColumn;
use App\Models\MenuColumnItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('columns.items.category')->ordered()->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_title_ar' => 'nullable|string|max:255',
            'image_title_en' => 'nullable|string|max:255',
            'image_description_ar' => 'nullable|string',
            'image_description_en' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => [
                'ar' => $request->name_ar,
                'en' => $request->name_en,
            ],
            'image_title' => [
                'ar' => $request->image_title_ar,
                'en' => $request->image_title_en,
            ],
            'image_description' => [
                'ar' => $request->image_description_ar,
                'en' => $request->image_description_en,
            ],
            'link' => $request->link,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        Menu::create($data);

        return redirect()->route('admin.menus.index', ['locale' => app()->getLocale()])
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم إضافة القائمة بنجاح'
                : 'Menu added successfully');
    }

    public function edit(Menu $menu)
    {
        $menu->load('columns.items.category');
        $categories = Category::active()->ordered()->get();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_title_ar' => 'nullable|string|max:255',
            'image_title_en' => 'nullable|string|max:255',
            'image_description_ar' => 'nullable|string',
            'image_description_en' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => [
                'ar' => $request->name_ar,
                'en' => $request->name_en,
            ],
            'image_title' => [
                'ar' => $request->image_title_ar,
                'en' => $request->image_title_en,
            ],
            'image_description' => [
                'ar' => $request->image_description_ar,
                'en' => $request->image_description_en,
            ],
            'link' => $request->link,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Handle image removal
        if ($request->has('remove_image') && $menu->image) {
            Storage::disk('public')->delete($menu->image);
            $data['image'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }

            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index', ['locale' => app()->getLocale()])
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم تحديث القائمة بنجاح'
                : 'Menu updated successfully');
    }

    public function destroy(Menu $menu)
    {
        // Delete image if exists
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index', ['locale' => app()->getLocale()])
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم حذف القائمة بنجاح'
                : 'Menu deleted successfully');
    }

    // Manage columns
    public function manageColumns(Menu $menu)
    {
        $menu->load('columns.items.category');
        $categories = Category::active()->ordered()->get();
        return view('admin.menus.columns', compact('menu', 'categories'));
    }

    public function storeColumn(Request $request, Menu $menu)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $menu->columns()->create([
            'title' => [
                'ar' => $request->title_ar,
                'en' => $request->title_en,
            ],
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        $message = app()->getLocale() == 'ar'
            ? 'تم إضافة العمود بنجاح'
            : 'Column added successfully';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function updateColumn(Request $request, MenuColumn $column)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $column->update([
            'title' => [
                'ar' => $request->title_ar,
                'en' => $request->title_en,
            ],
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        $message = app()->getLocale() == 'ar'
            ? 'تم تحديث العمود بنجاح'
            : 'Column updated successfully';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroyColumn(MenuColumn $column)
    {
        $column->delete();

        $message = app()->getLocale() == 'ar'
            ? 'تم حذف العمود بنجاح'
            : 'Column deleted successfully';

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    // Manage items
    public function storeItem(Request $request, MenuColumn $column)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sort_order' => 'nullable|integer',
        ]);

        $column->items()->create([
            'category_id' => $request->category_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        $message = app()->getLocale() == 'ar'
            ? 'تم إضافة العنصر بنجاح'
            : 'Item added successfully';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroyItem(MenuColumnItem $item)
    {
        $item->delete();

        $message = app()->getLocale() == 'ar'
            ? 'تم حذف العنصر بنجاح'
            : 'Item deleted successfully';

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }
}
