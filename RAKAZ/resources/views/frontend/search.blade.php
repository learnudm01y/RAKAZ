@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'نتائج البحث' : 'Search Results')

@section('content')
<div class="search-results-page">
    <div class="container">
        <div class="search-header">
            <h1>
                <span class="ar-text">نتائج البحث عن: </span>
                <span class="en-text">Search results for: </span>
                <strong>"{{ $searchQuery }}"</strong>
            </h1>
            @if($products->total() > 0 || $categories->count() > 0)
                <p class="search-meta">
                    <span class="ar-text">وجدنا {{ $products->total() }} منتج و {{ $categories->count() }} تصنيف</span>
                    <span class="en-text">Found {{ $products->total() }} products and {{ $categories->count() }} categories</span>
                </p>
            @endif
        </div>

        @if($categories->count() > 0)
            <div class="categories-section mb-5">
                <h2 class="section-title">
                    <span class="ar-text">التصنيفات</span>
                    <span class="en-text">Categories</span>
                </h2>
                <div class="categories-grid">
                    @foreach($categories as $category)
                        <a href="{{ route('category.show', $category->getSlug()) }}" class="category-card">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->getName() }}">
                            @endif
                            <h3>{{ $category->getName() }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($products->total() > 0)
            <div class="products-section">
                <h2 class="section-title">
                    <span class="ar-text">المنتجات</span>
                    <span class="en-text">Products</span>
                </h2>
                <div class="products-grid">
                    @foreach($products as $product)
                        <div class="product-card">
                            <a href="{{ route('product.details', $product->getSlug()) }}">
                                <div class="product-image">
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->getName() }}" class="main-img">
                                    @endif
                                    @if($product->hover_image)
                                        <img src="{{ asset('storage/' . $product->hover_image) }}" alt="{{ $product->getName() }}" class="hover-img">
                                    @endif
                                    @if($product->is_new)
                                        <span class="badge new-badge">
                                            <span class="ar-text">جديد</span>
                                            <span class="en-text">New</span>
                                        </span>
                                    @endif
                                    @if($product->hasDiscount())
                                        <span class="badge sale-badge">
                                            <span class="ar-text">تخفيض</span>
                                            <span class="en-text">Sale</span>
                                        </span>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name">{{ $product->getName() }}</h3>
                                    @if($product->brand)
                                        <p class="product-brand">{{ $product->brand->getName() }}</p>
                                    @endif
                                    <div class="product-price">
                                        @if($product->hasDiscount())
                                            <span class="sale-price">{{ number_format($product->sale_price, 2) }} <span class="ar-text">ر.س</span><span class="en-text">SAR</span></span>
                                            <span class="original-price">{{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="regular-price">{{ number_format($product->price, 2) }} <span class="ar-text">ر.س</span><span class="en-text">SAR</span></span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="pagination-wrapper">
                    {{ $products->appends(['q' => $searchQuery])->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @elseif(empty($searchQuery))
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="64" height="64">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3><span class="ar-text">ابحث عن منتج أو تصنيف</span><span class="en-text">Search for a product or category</span></h3>
                <p><span class="ar-text">استخدم صندوق البحث أعلاه للعثور على ما تبحث عنه</span><span class="en-text">Use the search box above to find what you're looking for</span></p>
            </div>
        @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="64" height="64">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3><span class="ar-text">لم نجد أي نتائج</span><span class="en-text">No results found</span></h3>
                <p><span class="ar-text">جرب البحث بكلمات مختلفة</span><span class="en-text">Try searching with different keywords</span></p>
            </div>
        @endif
    </div>
</div>

<style>
.search-results-page {
    padding: 60px 0;
    min-height: 60vh;
}

.search-header {
    text-align: center;
    margin-bottom: 50px;
}

.search-header h1 {
    font-size: 32px;
    font-weight: 600;
    margin-bottom: 10px;
}

.search-meta {
    color: #666;
    font-size: 16px;
}

.section-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 50px;
}

.category-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.category-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.category-card h3 {
    padding: 15px;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.product-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.product-card a {
    text-decoration: none;
    color: inherit;
}

.product-image {
    position: relative;
    padding-bottom: 125%;
    overflow: hidden;
    background: #f8f8f8;
}

.product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.3s ease;
}

.product-image .hover-img {
    opacity: 0;
}

.product-card:hover .product-image .hover-img {
    opacity: 1;
}

.product-image .badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    z-index: 2;
}

[dir="rtl"] .product-image .badge {
    right: auto;
    left: 10px;
}

.new-badge {
    background: #000;
    color: #fff;
}

.sale-badge {
    background: #ef4444;
    color: #fff;
    top: 45px;
}

.product-info {
    padding: 15px;
}

.product-name {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-brand {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
}

.product-price {
    font-size: 16px;
    font-weight: 600;
}

.sale-price {
    color: #ef4444;
    margin-right: 10px;
}

[dir="ltr"] .sale-price {
    margin-right: 0;
    margin-left: 10px;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 14px;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-state svg {
    color: #ccc;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 24px;
    margin-bottom: 10px;
}

.empty-state p {
    color: #666;
    font-size: 16px;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}
</style>
@endsection
