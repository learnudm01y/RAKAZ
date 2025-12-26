@extends('layouts.app')

@section('title', __('القوائم والتصنيفات'))

@section('content')
<div class="all-menus-page">
    <div class="container">
        <div class="page-header">
            <h1>
                <span class="ar-text">جميع القوائم والتصنيفات</span>
                <span class="en-text">All Menus & Categories</span>
            </h1>
        </div>

        <div class="menus-grid" id="menus-container">
            @if(isset($menus) && $menus->count() > 0)
                @foreach($menus as $menu)
                    @if($menu->activeColumns->count() > 0)
                        <div class="menu-section" id="menu-{{ $menu->id }}" data-menu-id="{{ $menu->id }}">
                            <div class="menu-header">
                                <h2>
                                    <span class="ar-text">{{ $menu->getName('ar') }}</span>
                                    <span class="en-text">{{ $menu->getName('en') }}</span>
                                </h2>
                            </div>

                            <div class="menu-columns">
                                @foreach($menu->activeColumns as $column)
                                    <div class="menu-column">
                                        <h3 class="column-title">
                                            <span class="ar-text">{{ $column->getTitle('ar') }}</span>
                                            <span class="en-text">{{ $column->getTitle('en') }}</span>
                                        </h3>

                                        <ul class="column-items">
                                            @foreach($column->items->where('is_active', true)->sortBy('sort_order') as $item)
                                                @if($item->category)
                                                    <li>
                                                        <a href="{{ $item->getLink() }}">
                                                            <span class="ar-text">{{ $item->getName('ar') }}</span>
                                                            <span class="en-text">{{ $item->getName('en') }}</span>
                                                        </a>

                                                        @if($item->category->children && $item->category->children->where('is_active', true)->count() > 0)
                                                            <ul class="sub-items">
                                                                @foreach($item->category->children->where('is_active', true)->sortBy('sort_order') as $childCategory)
                                                                    <li>
                                                                        <a href="{{ route('category.show', $childCategory->slug[app()->getLocale()] ?? $childCategory->slug['ar']) }}">
                                                                            <span class="ar-text">{{ $childCategory->name['ar'] ?? '' }}</span>
                                                                            <span class="en-text">{{ $childCategory->name['en'] ?? '' }}</span>
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>

                            @if($menu->image)
                                <div class="menu-featured-image">
                                    <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->getName(app()->getLocale()) }}" loading="lazy">
                                    <div class="menu-image-overlay">
                                        <h3>
                                            <span class="ar-text">{{ $menu->getImageTitle('ar') ?? $menu->getName('ar') }}</span>
                                            <span class="en-text">{{ $menu->getImageTitle('en') ?? $menu->getName('en') }}</span>
                                        </h3>
                                        <p>
                                            <span class="ar-text">{{ $menu->getImageDescription('ar') }}</span>
                                            <span class="en-text">{{ $menu->getImageDescription('en') }}</span>
                                        </p>
                                        <a href="{{ $menu->link ?? '#' }}" class="btn-primary">
                                            <span class="ar-text">تسوق الآن</span>
                                            <span class="en-text">SHOP NOW</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            @else
                <div class="no-menus">
                    <p>
                        <span class="ar-text">لا توجد قوائم متاحة حالياً</span>
                        <span class="en-text">No menus available at the moment</span>
                    </p>
                </div>
            @endif
        </div>

        @if($menus->hasMorePages())
            <div class="pagination-container">
                <button id="load-more-btn" class="load-more-btn" data-page="2">
                    <i class="fas fa-chevron-down btn-icon"></i>
                    <span class="ar-text">تحميل المزيد من القوائم</span>
                    <span class="en-text">Load More Menus</span>
                    <i class="fas fa-spinner fa-spin loading-spinner" style="display: none;"></i>
                </button>
            </div>
        @endif
    </div>
</div>

<style>
.all-menus-page {
    padding: 40px 0;
    background: #f8f8f8;
}

.all-menus-page .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.all-menus-page .page-header {
    text-align: center;
    margin-bottom: 50px;
}

.all-menus-page .page-header h1 {
    font-size: 36px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.all-menus-page .menus-grid {
    display: flex;
    flex-direction: column;
    gap: 60px;
}

.all-menus-page .menu-section {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.all-menus-page .menu-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e5e5e5;
}

.all-menus-page .menu-header h2 {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.all-menus-page .menu-columns {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 30px;
}

.all-menus-page .menu-column {
    display: flex;
    flex-direction: column;
}

.all-menus-page .column-title {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.all-menus-page .column-items {
    list-style: none;
    margin: 0;
    padding: 0;
}

.all-menus-page .column-items > li {
    margin-bottom: 12px;
}

.all-menus-page .column-items > li > a {
    color: #4a4a4a;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s ease;
    display: inline-block;
}

.all-menus-page .column-items > li > a:hover {
    color: #000;
}

.all-menus-page .sub-items {
    list-style: none;
    margin: 8px 0 0 20px;
    padding: 0;
}

.all-menus-page .sub-items li {
    margin-bottom: 8px;
}

.all-menus-page .sub-items a {
    color: #888;
    text-decoration: none;
    font-size: 13px;
    transition: color 0.2s ease;
}

.all-menus-page .sub-items a:hover {
    color: #000;
}

.all-menus-page .menu-featured-image {
    margin-top: 30px;
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    max-height: 400px;
}

.all-menus-page .menu-featured-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.all-menus-page .menu-image-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    padding: 40px;
    color: white;
}

.all-menus-page .menu-image-overlay h3 {
    font-size: 24px;
    margin-bottom: 10px;
}

.all-menus-page .menu-image-overlay p {
    font-size: 14px;
    margin-bottom: 20px;
    opacity: 0.9;
}

.all-menus-page .btn-primary {
    display: inline-block;
    padding: 12px 30px;
    background: white;
    color: #1a1a1a;
    text-decoration: none;
    font-weight: 600;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.all-menus-page .btn-primary:hover {
    background: #f0f0f0;
}

.all-menus-page .no-menus {
    text-align: center;
    padding: 60px 20px;
    color: #888;
}

/* RTL Support */
[dir="rtl"] .all-menus-page .sub-items {
    margin-right: 20px;
    margin-left: 0;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .all-menus-page {
        padding: 20px 0;
    }

    .all-menus-page .page-header h1 {
        font-size: 24px;
    }

    .all-menus-page .menu-section {
        padding: 20px;
    }

    .all-menus-page .menu-header h2 {
        font-size: 22px;
    }

    .all-menus-page .menu-columns {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .all-menus-page .menu-image-overlay {
        padding: 20px;
    }

    .all-menus-page .menu-image-overlay h3 {
        font-size: 18px;
    }
}

/* Smooth scroll target highlight */
.menu-section.highlighted {
    animation: highlightPulse 1.5s ease-out;
}

@keyframes highlightPulse {
    0% {
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    50% {
        box-shadow: 0 0 30px rgba(139, 94, 60, 0.3);
    }
    100% {
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
}

/* Pagination Container */
.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 40px;
    padding: 20px 0;
}

.load-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 36px;
    background: #b78953;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.load-more-btn:hover:not(:disabled) {
    background: #956f42;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.load-more-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.6;
}

.load-more-btn .btn-icon {
    font-size: 14px;
    transition: transform 0.3s ease;
}

.load-more-btn:hover .btn-icon {
    transform: translateY(3px);
}

.load-more-btn .loading-spinner {
    font-size: 16px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a menu ID in localStorage
    const targetMenuId = localStorage.getItem('scrollToMenuId');

    if (targetMenuId) {
        // Clear the stored ID
        localStorage.removeItem('scrollToMenuId');

        // Find the target menu section
        const targetSection = document.getElementById('menu-' + targetMenuId);

        if (targetSection) {
            // Wait a bit for page to fully render
            setTimeout(() => {
                // Scroll to the section
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                // Add highlight effect
                targetSection.classList.add('highlighted');

                // Remove highlight after animation
                setTimeout(() => {
                    targetSection.classList.remove('highlighted');
                }, 1500);
            }, 300);
        }
    }

    // Load More Button Handler
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const currentPage = parseInt(this.dataset.page);
            const container = document.getElementById('menus-container');
            const spinner = this.querySelector('.loading-spinner');
            const btnIcon = this.querySelector('.btn-icon');
            const arText = this.querySelector('.ar-text');
            const enText = this.querySelector('.en-text');

            // Disable button and show loading
            this.disabled = true;
            if (btnIcon) btnIcon.style.display = 'none';
            spinner.style.display = 'inline-block';
            arText.textContent = 'جاري التحميل...';
            enText.textContent = 'Loading...';

            // Fetch next page (قائمتين فقط)
            fetch('/all-menus?page=' + currentPage, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.html) {
                    // Add new content
                    container.insertAdjacentHTML('beforeend', data.html);

                    // Update button state
                    if (data.hasMore) {
                        this.dataset.page = data.nextPage;
                        this.disabled = false;
                        if (btnIcon) btnIcon.style.display = 'inline-block';
                        arText.textContent = 'تحميل المزيد من القوائم';
                        enText.textContent = 'Load More Menus';
                        spinner.style.display = 'none';
                    } else {
                        // No more pages
                        this.remove();
                    }
                } else {
                    console.error('Failed to load more menus');
                    this.disabled = false;
                    if (btnIcon) btnIcon.style.display = 'inline-block';
                    arText.textContent = 'حاول مرة أخرى';
                    enText.textContent = 'Try Again';
                    spinner.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading menus:', error);
                this.disabled = false;
                if (btnIcon) btnIcon.style.display = 'inline-block';
                arText.textContent = 'حاول مرة أخرى';
                enText.textContent = 'Try Again';
                spinner.style.display = 'none';
            });
        });
    }
});
</script>
@endsection
