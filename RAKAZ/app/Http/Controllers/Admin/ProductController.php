<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Generate Arabic-friendly slug
     */
    private function generateSlug($text)
    {
        // If text contains Arabic characters, keep them
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $text)) {
            return strtolower(trim(preg_replace('/\s+/', '-', $text)));
        }
        // Otherwise use Laravel's default slug
        return Str::slug($text);
    }

    /**
     * Display a listing of products with search and pagination
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 15);
        $categoryId = $request->get('category_id');
        $status = $request->get('status');

        $products = Product::with('category')
            ->when($search, function($query) use ($search) {
                $query->search($search);
            })
            ->when($categoryId, function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($status !== null, function($query) use ($status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($status === 'featured') {
                    $query->where('is_featured', true);
                } elseif ($status === 'low_stock') {
                    $query->where('manage_stock', true)
                          ->whereRaw('stock_quantity <= low_stock_threshold');
                }
            })
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('admin.products.index', compact('products', 'categories', 'search'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        try {
            \Log::info('Product creation attempt', [
                'user_id' => auth()->id(),
                'data' => $request->except(['main_image', 'gallery_images'])
            ]);

            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'slug_ar' => 'required|string|max:255',
                'slug_en' => 'required|string|max:255',
                'short_description_ar' => 'nullable|string',
                'short_description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'cost' => 'nullable|numeric|min:0',
                'sku' => 'nullable|string|unique:products,sku',
                'stock_quantity' => 'nullable|integer|min:0',
                'manage_stock' => 'nullable|in:on,1,true',
                'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
                'low_stock_threshold' => 'nullable|integer|min:0',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'brand' => 'nullable|string|max:255',
                'manufacturer' => 'nullable|string|max:255',
                'is_active' => 'nullable|in:on,1,true',
                'is_featured' => 'nullable|in:on,1,true',
                'is_new' => 'nullable|in:on,1,true',
                'is_on_sale' => 'nullable|in:on,1,true',
                'sort_order' => 'nullable|integer',
            ]);

            // Handle main image upload
            $mainImagePath = null;
            if ($request->hasFile('main_image')) {
                $mainImagePath = $request->file('main_image')->store('products', 'public');
            }

            // Handle gallery images upload
            $galleryImages = [];
            if ($request->hasFile('gallery_images')) {
                \Log::info('Gallery images received:', [
                    'count' => count($request->file('gallery_images'))
                ]);
                foreach ($request->file('gallery_images') as $image) {
                    $galleryImages[] = $image->store('products/gallery', 'public');
                }
                \Log::info('Gallery images stored:', [
                    'count' => count($galleryImages),
                    'paths' => $galleryImages
                ]);
            }

            // Prepare data
            $productData = [
                'name' => [
                    'ar' => $validated['name_ar'],
                    'en' => $validated['name_en'],
                ],
                'slug' => [
                    'ar' => $validated['slug_ar'] ?: $this->generateSlug($validated['name_ar']),
                    'en' => $validated['slug_en'] ?: $this->generateSlug($validated['name_en']),
                ],
                'short_description' => [
                    'ar' => $request->short_description_ar ?? '',
                    'en' => $request->short_description_en ?? '',
                ],
                'description' => [
                    'ar' => $request->description_ar ?? '',
                    'en' => $request->description_en ?? '',
                ],
                'category_id' => $validated['category_id'] ?? null,
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'cost' => $validated['cost'] ?? null,
                'sku' => $validated['sku'] ?? 'PRD-' . strtoupper(Str::random(8)),
                'stock_quantity' => $request->stock_quantity ?? 0,
                'manage_stock' => $request->has('manage_stock'),
                'stock_status' => $validated['stock_status'],
                'low_stock_threshold' => $validated['low_stock_threshold'] ?? null,
                'main_image' => $mainImagePath,
                'gallery_images' => $galleryImages,
                'brand' => $validated['brand'] ?? null,
                'manufacturer' => $validated['manufacturer'] ?? null,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
                'is_new' => $request->has('is_new'),
                'is_on_sale' => $request->has('is_on_sale'),
                'sort_order' => $validated['sort_order'] ?? 0,
            ];

            $product = Product::create($productData);

            \Log::info('Product created successfully', [
                'product_id' => $product->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.products.index')
                ->with('success', app()->getLocale() == 'ar' ? 'تم إضافة المنتج بنجاح' : 'Product added successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Product validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except(['main_image', 'gallery_images']),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'يرجى التحقق من البيانات المدخلة');
        } catch (\Exception $e) {
            \Log::error('Product creation error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['main_image', 'gallery_images']),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', (app()->getLocale() == 'ar' ? 'حدث خطأ أثناء إضافة المنتج: ' : 'Error adding product: ') . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        try {
            \Log::info('Product update attempt', [
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'data' => $request->except(['main_image', 'gallery_images'])
            ]);

            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'slug_ar' => 'required|string|max:255',
                'slug_en' => 'required|string|max:255',
                'short_description_ar' => 'nullable|string',
                'short_description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'cost' => 'nullable|numeric|min:0',
                'sku' => 'nullable|string|unique:products,sku,' . $product->id,
                'stock_quantity' => 'nullable|integer|min:0',
                'manage_stock' => 'nullable|in:on,1,true',
                'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
                'low_stock_threshold' => 'nullable|integer|min:0',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'brand' => 'nullable|string|max:255',
                'manufacturer' => 'nullable|string|max:255',
                'is_active' => 'nullable|in:on,1,true',
                'is_featured' => 'nullable|in:on,1,true',
                'is_new' => 'nullable|in:on,1,true',
                'is_on_sale' => 'nullable|in:on,1,true',
                'sort_order' => 'nullable|integer',
            ]);

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                // Delete old image
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                $mainImagePath = $request->file('main_image')->store('products', 'public');
            } else {
                $mainImagePath = $product->main_image;
            }

            // Handle gallery images
            $galleryImages = $product->gallery_images ?? [];

            // Remove deleted images
            if ($request->filled('removed_images')) {
                $removedImages = explode(',', $request->removed_images);
                foreach ($removedImages as $removedImage) {
                    if (($key = array_search($removedImage, $galleryImages)) !== false) {
                        unset($galleryImages[$key]);
                        Storage::disk('public')->delete($removedImage);
                    }
                }
                $galleryImages = array_values($galleryImages);
            }

            // Add new gallery images
            if ($request->hasFile('gallery_images')) {
                \Log::info('Gallery images received for update:', [
                    'count' => count($request->file('gallery_images')),
                    'existing_count' => count($galleryImages)
                ]);
                foreach ($request->file('gallery_images') as $image) {
                    $galleryImages[] = $image->store('products/gallery', 'public');
                }
                \Log::info('Gallery images after update:', [
                    'total_count' => count($galleryImages),
                    'paths' => $galleryImages
                ]);
            }

            // Update data
            $product->update([
                'name' => [
                    'ar' => $validated['name_ar'],
                    'en' => $validated['name_en'],
                ],
                'slug' => [
                    'ar' => $validated['slug_ar'] ?: $this->generateSlug($validated['name_ar']),
                    'en' => $validated['slug_en'] ?: $this->generateSlug($validated['name_en']),
                ],
                'short_description' => [
                    'ar' => $request->short_description_ar ?? '',
                    'en' => $request->short_description_en ?? '',
                ],
                'description' => [
                    'ar' => $request->description_ar ?? '',
                    'en' => $request->description_en ?? '',
                ],
                'category_id' => $validated['category_id'] ?? null,
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'cost' => $validated['cost'] ?? null,
                'sku' => $validated['sku'],
                'stock_quantity' => $request->stock_quantity ?? 0,
                'manage_stock' => $request->has('manage_stock'),
                'stock_status' => $validated['stock_status'],
                'low_stock_threshold' => $validated['low_stock_threshold'] ?? null,
                'main_image' => $mainImagePath,
                'gallery_images' => $galleryImages,
                'brand' => $validated['brand'] ?? null,
                'manufacturer' => $validated['manufacturer'] ?? null,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
                'is_new' => $request->has('is_new'),
                'is_on_sale' => $request->has('is_on_sale'),
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            \Log::info('Product updated successfully', [
                'product_id' => $product->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.products.index')
                ->with('success', app()->getLocale() == 'ar' ? 'تم تحديث المنتج بنجاح' : 'Product updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Product update validation failed', [
                'product_id' => $product->id,
                'errors' => $e->errors(),
                'input' => $request->except(['main_image', 'gallery_images']),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', app()->getLocale() == 'ar' ? 'يرجى التحقق من البيانات المدخلة' : 'Please check the entered data');
        } catch (\Exception $e) {
            \Log::error('Product update error', [
                'product_id' => $product->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['main_image', 'gallery_images']),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', (app()->getLocale() == 'ar' ? 'حدث خطأ أثناء تحديث المنتج: ' : 'Error updating product: ') . $e->getMessage());
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        // Delete product image
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }

        // Delete gallery images if any
        if ($product->gallery_images) {
            foreach ($product->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', __('labels.products.deleted_successfully'));
    }

    /**
     * Toggle product active status
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        $message = $product->is_active
            ? __('labels.products.activated_successfully')
            : __('labels.products.deactivated_successfully');

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }
}
