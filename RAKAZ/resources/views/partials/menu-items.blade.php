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
