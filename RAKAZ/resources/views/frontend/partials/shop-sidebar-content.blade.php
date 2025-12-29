<div class="sidebar-header">
    <h2>
        <span class="ar-text">الفلاتر</span>
        <span class="en-text">Filters</span>
    </h2>
    <button class="sidebar-close" id="sidebarClose">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

<!-- قياس الملابس (Clothing Size) -->
<div class="filter-section">
    <h3 class="filter-title">
        <span class="ar-text">قياس الملابس</span>
        <span class="en-text">Clothing size</span>
    </h3>
    <div class="size-grid">
        @foreach($sizes as $size)
        <label class="size-checkbox">
            <input type="checkbox" name="size" value="{{ $size->name }}">
            <span class="size-label">
                <span class="size-value">{{ $size->name }}</span>
                <span class="size-count">({{ $size->products_count ?? 0 }})</span>
            </span>
        </label>
        @endforeach
    </div>
</div>

<!-- التصنيفات الرئيسية (Main Categories) -->
<div class="filter-section">
    <h3 class="filter-title">
        <span class="ar-text">التصنيفات</span>
        <span class="en-text">Categories</span>
    </h3>
    <div class="categories-list-wrapper">
        @php
            $mainCategories = \App\Models\Category::where('is_active', true)
                ->whereNull('parent_id')
                ->whereHas('products', function($query) {
                    $query->where('is_active', true);
                })
                ->orderBy('sort_order')
                ->get();
            $totalCategories = $mainCategories->count();
            $maxDisplay = 10;
            $displayedCategories = $mainCategories->take($maxDisplay);
            $hasMore = $totalCategories > $maxDisplay;
        @endphp
        <div class="categories-checkbox-list" id="categoriesCheckboxList">
            @foreach($displayedCategories as $category)
                <label class="category-checkbox-item">
                    <span class="custom-checkbox">
                        <input type="checkbox" name="category" value="{{ $category->id }}" data-slug="{{ $category->getSlug() }}">
                        <span class="checkbox-mark">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                    </span>
                    <span class="category-label">
                        <span class="category-text">{{ $category->getName() }}</span>
                        <span class="category-count">({{ $category->products()->where('is_active', true)->count() }})</span>
                    </span>
                </label>
            @endforeach
        </div>
        @if($hasMore)
            <button class="show-more-categories" id="showMoreCategories" data-loaded="{{ $maxDisplay }}" data-total="{{ $totalCategories }}">
                <span class="ar-text">عرض المزيد</span>
                <span class="en-text">SHOW MORE</span>
            </button>
        @endif
    </div>
</div>

<!-- العلامات التجارية (Brands) -->
<div class="filter-section">
    <h3 class="filter-title">
        <span class="ar-text">العلامات التجارية</span>
        <span class="en-text">Brands</span>
    </h3>
    <div class="brands-list-wrapper">
        @php
            $brands = \App\Models\Brand::where('is_active', true)
                ->whereHas('products', function($query) {
                    $query->where('is_active', true);
                })
                ->orderBy('name_ar')
                ->get();
            $totalBrands = $brands->count();
            $maxDisplayBrands = 10;
            $displayedBrands = $brands->take($maxDisplayBrands);
            $hasMoreBrands = $totalBrands > $maxDisplayBrands;
        @endphp
        <div class="brands-checkbox-list" id="brandsCheckboxList">
            @foreach($displayedBrands as $brand)
                <label class="category-checkbox-item">
                    <span class="custom-checkbox">
                        <input type="checkbox" name="brand" value="{{ $brand->id }}" data-slug="{{ $brand->slug }}">
                        <span class="checkbox-mark">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                    </span>
                    <span class="category-label">
                        <span class="category-text">{{ $brand->getName() }}</span>
                        <span class="category-count">({{ $brand->products()->where('is_active', true)->count() }})</span>
                    </span>
                </label>
            @endforeach
        </div>
        @if($hasMoreBrands)
            <button class="show-more-categories" id="showMoreBrands" data-loaded="{{ $maxDisplayBrands }}" data-total="{{ $totalBrands }}">
                <span class="ar-text">عرض المزيد</span>
                <span class="en-text">SHOW MORE</span>
            </button>
        @endif
    </div>
</div>

<!-- مقاس الحذاء (Shoe Size) -->
<div class="filter-section">
    <h3 class="filter-title">
        <span class="ar-text">مقاس الحذاء</span>
        <span class="en-text">Shoe size</span>
    </h3>
    <div class="shoe-size-selector">
        <select class="shoe-size-dropdown custom-select">
            <option value="">EU</option>
            @foreach($shoeSizes as $shoeSize)
            <option value="{{ $shoeSize->size }}">{{ $shoeSize->size }} ({{ $shoeSize->products_count ?? 0 }})</option>
            @endforeach
        </select>
        <div class="shoe-size-scroll">
            @foreach($shoeSizes as $shoeSize)
            <label class="shoe-size-checkbox">
                <input type="checkbox" name="shoe-size" value="{{ $shoeSize->size }}">
                <span class="shoe-label">
                    <span class="shoe-count">({{ $shoeSize->products_count ?? 0 }})</span>
                    <span class="shoe-value">{{ $shoeSize->size }}</span>
                </span>
            </label>
            @endforeach
        </div>
    </div>
</div>

<!-- اللون (Color) -->
<div class="filter-section">
    <h3 class="filter-title">
        <span class="ar-text">اللون</span>
        <span class="en-text">Color</span>
    </h3>
    <div class="color-scroll">
        @foreach($colors as $color)
        <label class="color-checkbox">
            <input type="checkbox" name="color" value="{{ strtolower(app()->getLocale() == 'ar' ? ($color->name['ar'] ?? '') : ($color->name['en'] ?? $color->name['ar'] ?? '')) }}">
            <span class="color-label">
                <span class="color-circle" style="background-color: {{ $color->hex_code }};@if($color->hex_code == '#FFFFFF') border: 1px solid #ddd;@endif"></span>
                <span class="color-name">{{ app()->getLocale() == 'ar' ? ($color->name['ar'] ?? '') : ($color->name['en'] ?? $color->name['ar'] ?? '') }}</span>
                <span class="color-count">({{ $color->products_count ?? 0 }})</span>
            </span>
        </label>
        @endforeach
    </div>
</div>

<!-- السعر (Price) -->
<div class="filter-section">
    <h3 class="filter-title">
        <span class="ar-text">السعر</span>
        <span class="en-text">Price</span>
    </h3>
    <div class="price-range-wrapper">
        <div class="price-display">
            <div class="price-box">
                <label>
                    <span class="ar-text">السعر الأدنى</span>
                    <span class="en-text">Min price</span>
                </label>
                <div class="price-input-wrapper">
                    <input type="number" id="minPrice" value="{{ request('min_price', $minPrice) }}" min="{{ $minPrice }}"
                        max="{{ $maxPrice }}">
                    <span class="currency">AED</span>
                </div>
            </div>
            <div class="price-box">
                <label>
                    <span class="ar-text">السعر الأعلى</span>
                    <span class="en-text">Max price</span>
                </label>
                <div class="price-input-wrapper">
                    <input type="number" id="maxPrice" value="{{ request('max_price', $maxPrice) }}" min="{{ $minPrice }}"
                        max="{{ $maxPrice }}">
                    <span class="currency">AED</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- استخدام (Apply Button) -->
<div class="filter-section">
    <button class="apply-filters-btn">
        <span class="ar-text">استخدام</span>
        <span class="en-text">Apply</span>
    </button>
</div>
