@extends('layouts.app')

@section('content')
@push('styles')

    <style>
        /* Wishlist Page Specific Styles */
        .wishlist-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 40px;
        }

        .wishlist-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .wishlist-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 400;
            margin-bottom: 15px;
            color: #1a1a1a;
        }

        .wishlist-count {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        .wishlist-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .wishlist-action-btn {
            flex: 1;
            min-width: 180px;
            max-width: 220px;
            padding: 12px 20px;
            border: 2px solid #333;
            border-radius: 4px;
            background: white;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .wishlist-action-btn:hover {
            background: #f5f5f5;
            border-color: #555;
        }

        .wishlist-action-btn.primary {
            background: #333;
            color: #ffffff;
            border-color: #333;
        }

        .wishlist-action-btn.primary:hover {
            background: #555;
            border-color: #555;
        }

        .wishlist-action-btn svg {
            width: 18px;
            height: 18px;
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .wishlist-item {
            position: relative;
            background: #f8f8f8;
            border: none;
            transition: box-shadow 0.3s ease;
        }

        .wishlist-item:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .wishlist-item-image {
            position: relative;
            aspect-ratio: 0.85;
            overflow: hidden;
            background: #f5f5f5;
        }

        .wishlist-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-btn {
            position: absolute;
            top: 12px;
            left: 12px;
            width: 30px;
            height: 30px;
            background: transparent;
            border: none;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.2s ease;
            box-shadow: none;
        }

        .remove-btn:hover {
            background: transparent;
            transform: scale(1.1);
        }

        .remove-btn:hover svg {
            stroke: #333;
        }

        .remove-btn svg {
            width: 20px;
            height: 20px;
            stroke: #666;
            stroke-width: 1.5;
        }

        .wishlist-item-info {
            padding: 20px 15px;
            text-align: center;
            background: white;
        }

        .wishlist-item-brand {
            font-size: 11px;
            font-weight: 400;
            letter-spacing: 0.5px;
            text-transform: none;
            margin-bottom: 6px;
            color: #999;
        }

        .wishlist-item-name {
            font-size: 15px;
            font-weight: 500;
            line-height: 1.3;
            margin-bottom: 6px;
            color: #000;
            min-height: auto;
        }

        .wishlist-item-description {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .wishlist-item-price {
            font-size: 16px;
            font-weight: 600;
            color: #000;
            margin-bottom: 15px;
        }

        .product-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 12px;
        }

        .option-group {
            display: flex;
            gap: 8px;
        }

        .add-to-bag-btn {
            width: 100%;
            padding: 13px;
            background: #c9947a;
            border: none;
            border-radius: 0;
            color: #ffffff;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: background 0.3s ease;
            opacity: 1;
            transform: none;
            position: static;
        }

        .add-to-bag-btn:hover {
            background: #b8856a;
            color: #ffffff;
        }

        .wishlist-item-stock {
            font-size: 13px;
            margin-top: 8px;
        }

        .in-stock {
            color: #4caf50;
        }

        .low-stock {
            color: #ff9800;
        }

        .out-of-stock {
            color: #f44336;
        }

        .empty-wishlist {
            text-align: center;
            padding: 100px 20px;
        }

        .empty-wishlist-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            opacity: 0.3;
        }

        .empty-wishlist-title {
            font-size: 28px;
            margin-bottom: 15px;
            color: #1a1a1a;
        }

        .empty-wishlist-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        .empty-wishlist-btn {
            display: inline-block;
            padding: 15px 40px;
            background: #1a1a1a;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .empty-wishlist-btn:hover {
            background: #333;
        }

        /* Tablet Styles */
        @media (max-width: 1024px) {
            .wishlist-container {
                padding: 40px 25px;
            }

            .wishlist-title {
                font-size: 36px;
            }

            .wishlist-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
        }

        /* Mobile Styles */
        @media (max-width: 767px) {
            .wishlist-container {
                padding: 30px 15px;
            }

            .wishlist-title {
                font-size: 28px;
            }

            .wishlist-count {
                font-size: 14px;
            }

            .wishlist-actions {
                flex-direction: column;
            }

            .wishlist-action-btn {
                width: 100%;
                justify-content: center;
            }

            .wishlist-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .wishlist-item-info {
                padding: 15px 10px;
            }

            .wishlist-item-name {
                font-size: 13px;
                min-height: 36px;
            }

            .wishlist-item-price {
                font-size: 14px;
            }

            .empty-wishlist {
                padding: 60px 20px;
            }

            .empty-wishlist-title {
                font-size: 22px;
            }
        }
    </style>
    @endpush

    <!-- Wishlist Content -->
    <div class="wishlist-container">
        <div class="wishlist-header">
            <h1 class="wishlist-title">قائمة أمنياتي ({{ $wishlistItems ? count($wishlistItems) : 0 }})</h1>
            <p class="wishlist-count">لديك {{ $wishlistItems ? count($wishlistItems) : 0 }} {{ count($wishlistItems) == 1 ? 'منتج' : 'منتجات' }} في قائمة الأمنيات</p>
            <div class="wishlist-actions">
                <button class="wishlist-action-btn" onclick="window.location.href='{{ route('wishlist') }}'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    المفضلة
                </button>
                <button class="wishlist-action-btn" onclick="window.location.href='{{ route('orders.index') }}'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    طلباتي
                </button>
                <button class="wishlist-action-btn" onclick="window.location.href='{{ route('cart') }}'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    السلة
                </button>
            </div>
        </div>

        <!-- Wishlist Products Grid -->
        @if($wishlistItems && count($wishlistItems) > 0)
        <div class="wishlist-grid">
            @foreach($wishlistItems as $item)
                @php
                    $product = $item->product;
                    $mainImage = null;

                    if ($product->main_image) {
                        $mainImage = asset('storage/' . $product->main_image);
                    } elseif ($product->gallery_images && is_array($product->gallery_images) && count($product->gallery_images) > 0) {
                        $mainImage = asset('storage/' . $product->gallery_images[0]);
                    } else {
                        $mainImage = asset('assets/images/placeholder.jpg');
                    }

                    $productName = is_array($product->name)
                        ? ($product->name[app()->getLocale()] ?? $product->name['ar'] ?? $product->name['en'] ?? 'منتج')
                        : $product->name;

                    $productBrand = $product->brand ? (is_array($product->brand) ? ($product->brand[app()->getLocale()] ?? $product->brand['ar'] ?? '') : $product->brand) : '';

                    $productCategory = $product->category ? (is_array($product->category->name) ? ($product->category->name[app()->getLocale()] ?? $product->category->name['ar'] ?? '') : $product->category->name) : '';
                @endphp

                <div class="wishlist-item" data-wishlist-id="{{ $item->id }}">
                    <div class="wishlist-item-image">
                        <img src="{{ $mainImage }}" alt="{{ $productName }}">
                        <button class="remove-btn" onclick="removeFromWishlist({{ $item->id }})" title="إزالة من المفضلة">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="wishlist-item-info">
                        <p class="wishlist-item-brand">{{ $productCategory }}</p>
                        <h3 class="wishlist-item-name">{{ $productName }}</h3>
                        @if($productBrand)
                            <p class="wishlist-item-description">{{ $productBrand }}</p>
                        @endif
                        <p class="wishlist-item-price">
                            @if($product->sale_price && $product->sale_price > 0)
                                <span style="text-decoration: line-through; color: #999; margin-left: 10px;">{{ number_format($product->price, 0) }} د.إ</span>
                                <span style="color: #d33;">{{ number_format($product->sale_price, 0) }} د.إ</span>
                            @else
                                {{ number_format($product->price, 0) }} د.إ
                            @endif
                        </p>
                        <div class="product-options">
                            @if($product->colors && is_array($product->colors) && count($product->colors) > 0)
                            <div class="option-group">
                                <select class="option-select color-select-{{ $product->id }}">
                                    <option value="">اختيار اللون</option>
                                    @foreach($product->colors as $color)
                                        <option value="{{ $color }}">{{ $color }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            @if($product->sizes && is_array($product->sizes) && count($product->sizes) > 0)
                            <div class="option-group">
                                <select class="option-select size-select-{{ $product->id }}">
                                    <option value="">اختيار المقاس</option>
                                    @foreach($product->sizes as $size)
                                        <option value="{{ $size }}">{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        <button class="add-to-bag-btn" onclick="addToCartFromWishlist({{ $product->id }})">إضافة إلى حقيبة التسوق</button>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <!-- Empty Wishlist State -->
        <div class="empty-wishlist">
            <svg class="empty-wishlist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
            <h2 class="empty-wishlist-title">قائمة الأمنيات فارغة</h2>
            <p class="empty-wishlist-text">ابدأ بإضافة المنتجات التي تعجبك إلى قائمة الأمنيات</p>
            <a href="{{ route('shop') }}" class="empty-wishlist-btn">تسوق الآن</a>
        </div>
        @endif

        <!-- Empty Wishlist State (مخفي افتراضياً) -->
        <!-- <div class="empty-wishlist" style="display: none;">
            <svg class="empty-wishlist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
            <h2 class="empty-wishlist-title">قائمة الأمنيات فارغة</h2>
            <p class="empty-wishlist-text">ابدأ بإضافة المنتجات التي تعجبك إلى قائمة الأمنيات</p>
            <a href="{{ route('home') }}" class="empty-wishlist-btn">تسوق الآن</a>
        </div> -->
    </div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Remove from wishlist function
async function removeFromWishlist(wishlistId) {
    try {
        const result = await Swal.fire({
            title: 'إزالة من المفضلة',
            text: 'هل تريد إزالة هذا المنتج من قائمة الأمنيات؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذفه',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#666',
            reverseButtons: true
        });

        if (result.isConfirmed) {
            const response = await fetch(`{{ url('/wishlist') }}/${wishlistId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                const item = document.querySelector(`[data-wishlist-id="${wishlistId}"]`);
                item.style.opacity = '0';
                item.style.transform = 'scale(0.8)';

                setTimeout(() => {
                    item.remove();

                    // Check if wishlist is now empty
                    const remainingItems = document.querySelectorAll('.wishlist-item').length;
                    if (remainingItems === 0) {
                        location.reload(); // Reload to show empty state
                    }

                    Swal.fire({
                        title: 'تم الحذف!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#000',
                        timer: 2000,
                        timerProgressBar: true
                    });
                }, 300);
            } else {
                throw new Error(data.message || 'حدث خطأ');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'خطأ!',
            text: error.message || 'حدث خطأ أثناء حذف المنتج',
            confirmButtonColor: '#d33'
        });
    }
}

// Add to cart from wishlist
async function addToCartFromWishlist(productId) {
    try {
        // Get selected size and color if any
        const sizeSelect = document.querySelector(`.size-select-${productId}`);
        const colorSelect = document.querySelector(`.color-select-${productId}`);

        const size = sizeSelect ? sizeSelect.value : null;
        const color = colorSelect ? colorSelect.value : null;

        const response = await fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1,
                size: size,
                color: color
            })
        });

        const data = await response.json();

        if (data.success) {
            Swal.fire({
                title: 'تمت الإضافة!',
                text: 'تم إضافة المنتج إلى السلة',
                icon: 'success',
                confirmButtonText: 'متابعة التسوق',
                showCancelButton: true,
                cancelButtonText: 'عرض السلة',
                confirmButtonColor: '#000',
                cancelButtonColor: '#666',
                timer: 3000,
                timerProgressBar: true
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = '{{ route("cart") }}';
                }

                // Update cart count in header if exists
                const cartCount = document.querySelector('.cart-count');
                if (cartCount && data.cart_count) {
                    cartCount.textContent = data.cart_count;
                }
            });
        } else {
            throw new Error(data.message || 'حدث خطأ');
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'خطأ!',
            text: error.message || 'حدث خطأ أثناء إضافة المنتج إلى السلة',
            confirmButtonColor: '#d33'
        });
    }
}
</script>
@endpush

