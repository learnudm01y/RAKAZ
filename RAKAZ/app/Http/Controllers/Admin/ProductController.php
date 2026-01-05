<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductColorImage;
use App\Services\ImageCompressionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected ImageCompressionService $imageService;

    public function __construct(ImageCompressionService $imageService)
    {
        $this->imageService = $imageService;
    }

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
        $perPage = (int) $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 30, 50, 100];
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 15;
        }
        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');
        $status = $request->get('status');

        $products = Product::with(['category', 'brand', 'productSizes', 'productShoeSizes', 'productColors'])
            ->when($search, function($query) use ($search) {
                $query->search($search);
            })
            ->when($categoryId, function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($brandId, function($query) use ($brandId) {
                $query->where('brand_id', $brandId);
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

        $brands = \App\Models\Brand::where('is_active', true)
            ->orderBy('name_ar')
            ->get();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'search'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $brands = \App\Models\Brand::where('is_active', true)
            ->orderBy('name_ar')
            ->get();

        $sizes = \App\Models\Size::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $shoeSizes = \App\Models\ShoeSize::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $colors = \App\Models\Color::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.products.create', compact('categories', 'brands', 'sizes', 'shoeSizes', 'colors'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        try {
            Log::info('Product creation attempt', [
                'user_id' => auth()->id(),
                'data' => $request->except(['main_image', 'gallery_images'])
            ]);

            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'slug_ar' => 'required|string|max:255',
                'slug_en' => 'required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'sizing_info_ar' => 'nullable|string',
                'sizing_info_en' => 'nullable|string',
                'design_details_ar' => 'nullable|string',
                'design_details_en' => 'nullable|string',
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
                'hover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'brand_id' => 'nullable|exists:brands,id',
                'brand' => 'nullable|string|max:255',
                'manufacturer' => 'nullable|string|max:255',
                'is_active' => 'nullable|in:on,1,true',
                'is_featured' => 'nullable|in:on,1,true',
                'is_new' => 'nullable|in:on,1,true',
                'is_on_sale' => 'nullable|in:on,1,true',
                'sort_order' => 'nullable|integer',
            ]);

            // Handle main image - check for pre-compressed image first
            $mainImagePath = null;
            $mobileMainImagePath = null;
            if ($request->filled('compressed_main_image')) {
                // Move from temp to permanent location
                $tempPath = $request->input('compressed_main_image');
                $newPath = str_replace('/temp', '', $tempPath);
                if (Storage::disk('public')->exists($tempPath)) {
                    Storage::disk('public')->move($tempPath, $newPath);
                    $mainImagePath = $newPath;
                    Log::info('Main image moved from temp', ['from' => $tempPath, 'to' => $newPath]);

                    // Create mobile version (max 15KB)
                    $mobileMainImagePath = $this->imageService->createMobileVersionFromExisting($mainImagePath, 'products/mobile');
                    Log::info('Mobile main image created', ['path' => $mobileMainImagePath]);
                }
            } elseif ($request->hasFile('main_image')) {
                $mainImagePath = $this->imageService->compressAndStore($request->file('main_image'), 'products');
                Log::info('Main image compressed and stored', ['path' => $mainImagePath]);

                // Create mobile version (max 15KB)
                $mobileMainImagePath = $this->imageService->compressAndStoreForMobile($request->file('main_image'), 'products/mobile');
                Log::info('Mobile main image created', ['path' => $mobileMainImagePath]);
            }

            // Handle hover image - check for pre-compressed image first
            $hoverImagePath = null;
            $mobileHoverImagePath = null;
            if ($request->filled('compressed_hover_image')) {
                // Move from temp to permanent location
                $tempPath = $request->input('compressed_hover_image');
                $newPath = str_replace('/temp', '', $tempPath);
                if (Storage::disk('public')->exists($tempPath)) {
                    Storage::disk('public')->move($tempPath, $newPath);
                    $hoverImagePath = $newPath;
                    Log::info('Hover image moved from temp', ['from' => $tempPath, 'to' => $newPath]);

                    // Create mobile version (max 15KB)
                    $mobileHoverImagePath = $this->imageService->createMobileVersionFromExisting($hoverImagePath, 'products/mobile');
                    Log::info('Mobile hover image created', ['path' => $mobileHoverImagePath]);
                }
            } elseif ($request->hasFile('hover_image')) {
                $hoverImagePath = $this->imageService->compressAndStore($request->file('hover_image'), 'products');
                Log::info('Hover image compressed and stored', ['path' => $hoverImagePath]);

                // Create mobile version (max 15KB)
                $mobileHoverImagePath = $this->imageService->compressAndStoreForMobile($request->file('hover_image'), 'products/mobile');
                Log::info('Mobile hover image created', ['path' => $mobileHoverImagePath]);
            }

            // Handle gallery images - check for pre-compressed images first
            $galleryImages = [];
            $mobileGalleryImages = [];
            if ($request->filled('compressed_gallery_images')) {
                $compressedGalleryImages = $request->input('compressed_gallery_images');
                foreach ($compressedGalleryImages as $tempPath) {
                    $newPath = str_replace('/temp', '', $tempPath);
                    if (Storage::disk('public')->exists($tempPath)) {
                        Storage::disk('public')->move($tempPath, $newPath);
                        $galleryImages[] = $newPath;
                        Log::info('Gallery image moved from temp', ['from' => $tempPath, 'to' => $newPath]);

                        // Create mobile version (max 15KB)
                        $mobilePath = $this->imageService->createMobileVersionFromExisting($newPath, 'products/mobile/gallery');
                        if ($mobilePath) {
                            $mobileGalleryImages[] = $mobilePath;
                            Log::info('Mobile gallery image created', ['path' => $mobilePath]);
                        }
                    }
                }
            } elseif ($request->hasFile('gallery_images')) {
                Log::info('Gallery images received:', [
                    'count' => count($request->file('gallery_images'))
                ]);
                $galleryImages = $this->imageService->compressAndStoreMultiple(
                    $request->file('gallery_images'),
                    'products/gallery'
                );
                Log::info('Gallery images compressed and stored:', [
                    'count' => count($galleryImages),
                    'paths' => $galleryImages
                ]);

                // Create mobile versions (max 15KB each)
                $mobileGalleryImages = $this->imageService->compressAndStoreMultipleForMobile(
                    $request->file('gallery_images'),
                    'products/mobile/gallery'
                );
                Log::info('Mobile gallery images created:', [
                    'count' => count($mobileGalleryImages),
                    'paths' => $mobileGalleryImages
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
                'description' => [
                    'ar' => $request->description_ar ?? '',
                    'en' => $request->description_en ?? '',
                ],
                'sizing_info' => [
                    'ar' => $request->sizing_info_ar ?? '',
                    'en' => $request->sizing_info_en ?? '',
                ],
                'design_details' => [
                    'ar' => $request->design_details_ar ?? '',
                    'en' => $request->design_details_en ?? '',
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
                'mobile_main_image' => $mobileMainImagePath,
                'hover_image' => $hoverImagePath,
                'mobile_hover_image' => $mobileHoverImagePath,
                'gallery_images' => $galleryImages,
                'mobile_gallery_images' => $mobileGalleryImages,
                'brand_id' => $request->brand_id ?? null,
                'brand' => $validated['brand'] ?? null,
                'manufacturer' => $validated['manufacturer'] ?? null,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
                'is_new' => $request->has('is_new'),
                'is_on_sale' => $request->has('is_on_sale'),
                'sort_order' => $validated['sort_order'] ?? 0,
            ];

            $product = Product::create($productData);

            // Sync sizes with stock quantities
            if ($request->has('sizes')) {
                $sizesData = [];
                foreach ($request->sizes as $sizeId) {
                    $sizesData[$sizeId] = [
                        'stock_quantity' => $request->size_stock[$sizeId] ?? 0
                    ];
                }
                $product->productSizes()->sync($sizesData);
            }

            // Sync shoe sizes with stock quantities
            if ($request->has('shoe_sizes')) {
                $shoeSizesData = [];
                foreach ($request->shoe_sizes as $shoeSizeId) {
                    $shoeSizesData[$shoeSizeId] = [
                        'stock_quantity' => $request->shoe_size_stock[$shoeSizeId] ?? 0
                    ];
                }
                $product->productShoeSizes()->sync($shoeSizesData);
            }

            // Sync colors with stock quantities
            if ($request->has('colors')) {
                $colorsData = [];
                foreach ($request->colors as $colorId) {
                    $colorsData[$colorId] = [
                        'stock_quantity' => $request->color_stock[$colorId] ?? 0
                    ];
                }
                $product->productColors()->sync($colorsData);

                // Handle color-specific images - check for pre-compressed images first
                if ($request->filled('compressed_color_images')) {
                    $compressedColorImages = $request->input('compressed_color_images');
                    foreach ($compressedColorImages as $colorId => $tempPath) {
                        // Check if this color is selected
                        if (in_array($colorId, $request->colors)) {
                            $newPath = str_replace('/temp', '', $tempPath);
                            if (Storage::disk('public')->exists($tempPath)) {
                                Storage::disk('public')->move($tempPath, $newPath);
                                Log::info('Color image moved from temp', ['color_id' => $colorId, 'from' => $tempPath, 'to' => $newPath]);

                                ProductColorImage::create([
                                    'product_id' => $product->id,
                                    'color_id' => $colorId,
                                    'image' => $newPath,
                                    'is_primary' => isset($request->color_image_primary[$colorId]),
                                    'sort_order' => 0
                                ]);
                            }
                        }
                    }
                } elseif ($request->hasFile('color_images')) {
                    foreach ($request->file('color_images') as $colorId => $imageFile) {
                        // Check if this color is selected
                        if (in_array($colorId, $request->colors)) {
                            $imagePath = $this->imageService->compressAndStore($imageFile, 'products/colors');
                            Log::info('Color image compressed and stored', ['color_id' => $colorId, 'path' => $imagePath]);

                            ProductColorImage::create([
                                'product_id' => $product->id,
                                'color_id' => $colorId,
                                'image' => $imagePath,
                                'is_primary' => isset($request->color_image_primary[$colorId]),
                                'sort_order' => 0
                            ]);
                        }
                    }
                }
            }

            Log::info('Product created successfully', [
                'product_id' => $product->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.products.index')
                ->with('success', __('labels.products.created_successfully'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Product validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except(['main_image', 'gallery_images']),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'يرجى التحقق من البيانات المدخلة');
        } catch (Exception $e) {
            Log::error('Product creation error', [
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

        $brands = \App\Models\Brand::where('is_active', true)
            ->orderBy('name_ar')
            ->get();

        $sizes = \App\Models\Size::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $shoeSizes = \App\Models\ShoeSize::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $colors = \App\Models\Color::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Load existing relationships
        $product->load(['productSizes', 'productShoeSizes', 'productColors']);

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'sizes', 'shoeSizes', 'colors'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        try {
            Log::info('Product update attempt', [
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'data' => $request->except(['main_image', 'gallery_images'])
            ]);

            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'slug_ar' => 'required|string|max:255',
                'slug_en' => 'required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'sizing_info_ar' => 'nullable|string',
                'sizing_info_en' => 'nullable|string',
                'design_details_ar' => 'nullable|string',
                'design_details_en' => 'nullable|string',
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
                'hover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'brand_id' => 'nullable|exists:brands,id',
                'brand' => 'nullable|string|max:255',
                'manufacturer' => 'nullable|string|max:255',
                'is_active' => 'nullable|in:on,1,true',
                'is_featured' => 'nullable|in:on,1,true',
                'is_new' => 'nullable|in:on,1,true',
                'is_on_sale' => 'nullable|in:on,1,true',
                'sort_order' => 'nullable|integer',
            ]);

            // Handle main image - check for pre-compressed image first
            $mobileMainImagePath = $product->mobile_main_image;
            if ($request->filled('compressed_main_image')) {
                // Delete old images
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                if ($product->mobile_main_image) {
                    Storage::disk('public')->delete($product->mobile_main_image);
                }
                // Move from temp to permanent location
                $tempPath = $request->input('compressed_main_image');
                $newPath = str_replace('/temp', '', $tempPath);
                if (Storage::disk('public')->exists($tempPath)) {
                    Storage::disk('public')->move($tempPath, $newPath);
                    $mainImagePath = $newPath;
                    Log::info('Main image moved from temp (update)', ['from' => $tempPath, 'to' => $newPath]);

                    // Create mobile version (max 15KB)
                    $mobileMainImagePath = $this->imageService->createMobileVersionFromExisting($mainImagePath, 'products/mobile');
                    Log::info('Mobile main image created (update)', ['path' => $mobileMainImagePath]);
                } else {
                    $mainImagePath = $product->main_image;
                }
            } elseif ($request->hasFile('main_image')) {
                // Delete old images
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                if ($product->mobile_main_image) {
                    Storage::disk('public')->delete($product->mobile_main_image);
                }
                $mainImagePath = $this->imageService->compressAndStore($request->file('main_image'), 'products');
                Log::info('Main image compressed and stored (update)', ['path' => $mainImagePath]);

                // Create mobile version (max 15KB)
                $mobileMainImagePath = $this->imageService->compressAndStoreForMobile($request->file('main_image'), 'products/mobile');
                Log::info('Mobile main image created (update)', ['path' => $mobileMainImagePath]);
            } else {
                $mainImagePath = $product->main_image;
            }

            // Handle hover image - check for pre-compressed image first
            $mobileHoverImagePath = $product->mobile_hover_image;
            if ($request->filled('compressed_hover_image')) {
                // Delete old images
                if ($product->hover_image) {
                    Storage::disk('public')->delete($product->hover_image);
                }
                if ($product->mobile_hover_image) {
                    Storage::disk('public')->delete($product->mobile_hover_image);
                }
                // Move from temp to permanent location
                $tempPath = $request->input('compressed_hover_image');
                $newPath = str_replace('/temp', '', $tempPath);
                if (Storage::disk('public')->exists($tempPath)) {
                    Storage::disk('public')->move($tempPath, $newPath);
                    $hoverImagePath = $newPath;
                    Log::info('Hover image moved from temp (update)', ['from' => $tempPath, 'to' => $newPath]);

                    // Create mobile version (max 15KB)
                    $mobileHoverImagePath = $this->imageService->createMobileVersionFromExisting($hoverImagePath, 'products/mobile');
                    Log::info('Mobile hover image created (update)', ['path' => $mobileHoverImagePath]);
                } else {
                    $hoverImagePath = $product->hover_image;
                }
            } elseif ($request->hasFile('hover_image')) {
                // Delete old images
                if ($product->hover_image) {
                    Storage::disk('public')->delete($product->hover_image);
                }
                if ($product->mobile_hover_image) {
                    Storage::disk('public')->delete($product->mobile_hover_image);
                }
                $hoverImagePath = $this->imageService->compressAndStore($request->file('hover_image'), 'products');
                Log::info('Hover image compressed and stored (update)', ['path' => $hoverImagePath]);

                // Create mobile version (max 15KB)
                $mobileHoverImagePath = $this->imageService->compressAndStoreForMobile($request->file('hover_image'), 'products/mobile');
                Log::info('Mobile hover image created (update)', ['path' => $mobileHoverImagePath]);
            } else {
                $hoverImagePath = $product->hover_image;
            }

            // Handle gallery images
            $galleryImages = $product->gallery_images ?? [];
            $mobileGalleryImages = $product->mobile_gallery_images ?? [];

            // Remove deleted images
            if ($request->filled('removed_images')) {
                $removedImages = explode(',', $request->removed_images);
                foreach ($removedImages as $index => $removedImage) {
                    if (($key = array_search($removedImage, $galleryImages)) !== false) {
                        unset($galleryImages[$key]);
                        Storage::disk('public')->delete($removedImage);

                        // Also remove corresponding mobile image if exists
                        if (isset($mobileGalleryImages[$key])) {
                            Storage::disk('public')->delete($mobileGalleryImages[$key]);
                            unset($mobileGalleryImages[$key]);
                        }
                    }
                }
                $galleryImages = array_values($galleryImages);
                $mobileGalleryImages = array_values($mobileGalleryImages);
            }

            // Add new gallery images - check for pre-compressed images first
            if ($request->filled('compressed_gallery_images')) {
                $compressedGalleryImages = $request->input('compressed_gallery_images');
                foreach ($compressedGalleryImages as $tempPath) {
                    $newPath = str_replace('/temp', '', $tempPath);
                    if (Storage::disk('public')->exists($tempPath)) {
                        Storage::disk('public')->move($tempPath, $newPath);
                        $galleryImages[] = $newPath;
                        Log::info('Gallery image moved from temp (update)', ['from' => $tempPath, 'to' => $newPath]);

                        // Create mobile version (max 15KB)
                        $mobilePath = $this->imageService->createMobileVersionFromExisting($newPath, 'products/mobile/gallery');
                        if ($mobilePath) {
                            $mobileGalleryImages[] = $mobilePath;
                            Log::info('Mobile gallery image created (update)', ['path' => $mobilePath]);
                        }
                    }
                }
            } elseif ($request->hasFile('gallery_images')) {
                Log::info('Gallery images received for update:', [
                    'count' => count($request->file('gallery_images')),
                    'existing_count' => count($galleryImages)
                ]);
                $newGalleryImages = $this->imageService->compressAndStoreMultiple(
                    $request->file('gallery_images'),
                    'products/gallery'
                );
                $galleryImages = array_merge($galleryImages, $newGalleryImages);
                Log::info('Gallery images after update:', [
                    'total_count' => count($galleryImages),
                    'paths' => $galleryImages
                ]);

                // Create mobile versions (max 15KB each)
                $newMobileGalleryImages = $this->imageService->compressAndStoreMultipleForMobile(
                    $request->file('gallery_images'),
                    'products/mobile/gallery'
                );
                $mobileGalleryImages = array_merge($mobileGalleryImages, $newMobileGalleryImages);
                Log::info('Mobile gallery images after update:', [
                    'total_count' => count($mobileGalleryImages),
                    'paths' => $mobileGalleryImages
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
                'description' => [
                    'ar' => $request->description_ar ?? '',
                    'en' => $request->description_en ?? '',
                ],
                'sizing_info' => [
                    'ar' => $request->sizing_info_ar ?? '',
                    'en' => $request->sizing_info_en ?? '',
                ],
                'design_details' => [
                    'ar' => $request->design_details_ar ?? '',
                    'en' => $request->design_details_en ?? '',
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
                'mobile_main_image' => $mobileMainImagePath,
                'hover_image' => $hoverImagePath,
                'mobile_hover_image' => $mobileHoverImagePath,
                'gallery_images' => $galleryImages,
                'mobile_gallery_images' => $mobileGalleryImages,
                'brand_id' => $request->brand_id ?? null,
                'brand' => $validated['brand'] ?? null,
                'manufacturer' => $validated['manufacturer'] ?? null,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
                'is_new' => $request->has('is_new'),
                'is_on_sale' => $request->has('is_on_sale'),
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            // Sync sizes with stock quantities
            if ($request->has('sizes')) {
                $sizesData = [];
                foreach ($request->sizes as $sizeId) {
                    $sizesData[$sizeId] = [
                        'stock_quantity' => $request->size_stock[$sizeId] ?? 0
                    ];
                }
                $product->productSizes()->sync($sizesData);
            } else {
                $product->productSizes()->detach();
            }

            // Sync shoe sizes with stock quantities
            if ($request->has('shoe_sizes')) {
                $shoeSizesData = [];
                foreach ($request->shoe_sizes as $shoeSizeId) {
                    $shoeSizesData[$shoeSizeId] = [
                        'stock_quantity' => $request->shoe_size_stock[$shoeSizeId] ?? 0
                    ];
                }
                $product->productShoeSizes()->sync($shoeSizesData);
            } else {
                $product->productShoeSizes()->detach();
            }

            // Sync colors with stock quantities
            if ($request->has('colors')) {
                $colorsData = [];
                foreach ($request->colors as $colorId) {
                    $colorsData[$colorId] = [
                        'stock_quantity' => $request->color_stock[$colorId] ?? 0
                    ];
                }
                $product->productColors()->sync($colorsData);

                // Handle color-specific images - check for pre-compressed images first
                if ($request->filled('compressed_color_images')) {
                    $compressedColorImages = $request->input('compressed_color_images');
                    foreach ($compressedColorImages as $colorId => $tempPath) {
                        // Check if this color is selected
                        if (in_array($colorId, $request->colors)) {
                            // Delete old image if exists
                            $existingImage = ProductColorImage::where('product_id', $product->id)
                                ->where('color_id', $colorId)
                                ->first();

                            if ($existingImage) {
                                Storage::disk('public')->delete($existingImage->image);
                                $existingImage->delete();
                            }

                            $newPath = str_replace('/temp', '', $tempPath);
                            if (Storage::disk('public')->exists($tempPath)) {
                                Storage::disk('public')->move($tempPath, $newPath);
                                Log::info('Color image moved from temp (update)', ['color_id' => $colorId, 'from' => $tempPath, 'to' => $newPath]);

                                ProductColorImage::create([
                                    'product_id' => $product->id,
                                    'color_id' => $colorId,
                                    'image' => $newPath,
                                    'is_primary' => isset($request->color_image_primary[$colorId]),
                                    'sort_order' => 0
                                ]);
                            }
                        }
                    }
                } elseif ($request->hasFile('color_images')) {
                    foreach ($request->file('color_images') as $colorId => $imageFile) {
                        // Check if this color is selected
                        if (in_array($colorId, $request->colors)) {
                            // Delete old image if exists
                            $existingImage = ProductColorImage::where('product_id', $product->id)
                                ->where('color_id', $colorId)
                                ->first();

                            if ($existingImage) {
                                Storage::disk('public')->delete($existingImage->image);
                                $existingImage->delete();
                            }

                            $imagePath = $this->imageService->compressAndStore($imageFile, 'products/colors');
                            Log::info('Color image compressed and stored (update)', ['color_id' => $colorId, 'path' => $imagePath]);

                            ProductColorImage::create([
                                'product_id' => $product->id,
                                'color_id' => $colorId,
                                'image' => $imagePath,
                                'is_primary' => isset($request->color_image_primary[$colorId]),
                                'sort_order' => 0
                            ]);
                        }
                    }
                }

                // Handle manually removed color images
                if ($request->has('remove_color_images')) {
                    foreach ($request->remove_color_images as $colorId) {
                        $imgToRemove = ProductColorImage::where('product_id', $product->id)
                            ->where('color_id', $colorId)
                            ->first();
                        if ($imgToRemove) {
                            Storage::disk('public')->delete($imgToRemove->image);
                            $imgToRemove->delete();
                        }
                    }
                }

                // Delete color images for unselected colors
                ProductColorImage::where('product_id', $product->id)
                    ->whereNotIn('color_id', $request->colors)
                    ->get()
                    ->each(function ($img) {
                        Storage::disk('public')->delete($img->image);
                        $img->delete();
                    });
            } else {
                // Delete all color images if no colors selected
                $product->colorImages()->get()->each(function ($img) {
                    Storage::disk('public')->delete($img->image);
                    $img->delete();
                });
                $product->productColors()->detach();
            }

            Log::info('Product updated successfully', [
                'product_id' => $product->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.products.index')
                ->with('success', __('labels.products.updated_successfully'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Product update validation failed', [
                'product_id' => $product->id,
                'errors' => $e->errors(),
                'input' => $request->except(['main_image', 'gallery_images']),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', app()->getLocale() == 'ar' ? 'يرجى التحقق من البيانات المدخلة' : 'Please check the entered data');
        } catch (Exception $e) {
            Log::error('Product update error', [
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
        // Delete product main images (desktop and mobile)
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }
        if ($product->mobile_main_image) {
            Storage::disk('public')->delete($product->mobile_main_image);
        }

        // Delete hover images (desktop and mobile)
        if ($product->hover_image) {
            Storage::disk('public')->delete($product->hover_image);
        }
        if ($product->mobile_hover_image) {
            Storage::disk('public')->delete($product->mobile_hover_image);
        }

        // Delete gallery images if any (desktop)
        if ($product->gallery_images) {
            foreach ($product->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // Delete mobile gallery images if any
        if ($product->mobile_gallery_images) {
            foreach ($product->mobile_gallery_images as $image) {
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
