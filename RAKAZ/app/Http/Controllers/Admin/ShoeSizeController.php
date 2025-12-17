<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShoeSize;
use Illuminate\Http\Request;

class ShoeSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shoeSizes = ShoeSize::orderBy('sort_order')->get();
        return view('admin.shoe-sizes.index', compact('shoeSizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'size' => 'required|string|max:255',
            'name_translations' => 'nullable|array',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ShoeSize::create($validated);

        return redirect()->route('admin.shoe-sizes.index')
            ->with('success', __('labels.shoe_sizes.created_successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShoeSize $shoeSize)
    {
        $validated = $request->validate([
            'size' => 'required|string|max:255',
            'name_translations' => 'nullable|array',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $shoeSize->update($validated);

        return redirect()->route('admin.shoe-sizes.index')
            ->with('success', __('labels.shoe_sizes.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShoeSize $shoeSize)
    {
        $shoeSize->delete();

        return redirect()->route('admin.shoe-sizes.index')
            ->with('success', __('labels.shoe_sizes.deleted_successfully'));
    }
}
