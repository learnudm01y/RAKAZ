<li class="category-item">
    <div class="category-row {{ $level == 1 ? 'subcategory-row' : ($level == 2 ? 'sub-subcategory-row' : '') }}">
        <div class="category-info">
            <div class="category-icon">
                @if($category->image)
                    <img src="{{ $category->image }}" alt="{{ $category->getName() }}" style="width: 100%; height: 100%; border-radius: 8px; object-fit: cover;">
                @else
                    📁
                @endif
            </div>

            <div class="category-details">
                <div class="category-name">
                    {{ $category->getName() }}
                </div>
                <div class="category-meta">
                    <span class="level-indicator level-{{ $level }}">
                        @if($level == 0)
                            <span class="ar-text">🏷️ تصنيف رئيسي</span>
                            <span class="en-text">🏷️ Main Category</span>
                        @elseif($level == 1)
                            <span class="ar-text">📂 تصنيف فرعي</span>
                            <span class="en-text">📂 Sub Category</span>
                        @else
                            <span class="ar-text">📄 تصنيف فرعي ثانوي</span>
                            <span class="en-text">📄 Sub-Sub Category</span>
                        @endif
                    </span>

                    @if($category->children->count() > 0)
                        <span>
                            <span class="ar-text">{{ $category->children->count() }} فرعي</span>
                            <span class="en-text">{{ $category->children->count() }} sub</span>
                        </span>
                    @endif

                    <span class="category-badge {{ $category->is_active ? 'badge-active' : 'badge-inactive' }}">
                        @if($category->is_active)
                            <span class="ar-text">✓ نشط</span>
                            <span class="en-text">✓ Active</span>
                        @else
                            <span class="ar-text">✕ غير نشط</span>
                            <span class="en-text">✕ Inactive</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="category-actions">
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="action-btn btn-edit">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span class="ar-text">تعديل</span>
                <span class="en-text">Edit</span>
            </a>

            <button type="button" onclick="deleteCategory({{ $category->id }}, '{{ $category->getName() }}')" class="action-btn btn-delete">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span class="ar-text">حذف</span>
                <span class="en-text">Delete</span>
            </button>
        </div>
    </div>

    @if($category->children->count() > 0)
        <ul class="subcategories">
            @foreach($category->children as $child)
                @include('admin.categories.partials.category-item', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif
</li>
