@props([
    'title',
    'subtitle' => null,
    'active' => null,
])

@php
    $resolvedActive = $active;

    if ($resolvedActive === null) {
        if (request()->routeIs('wishlist')) {
            $resolvedActive = 'wishlist';
        } elseif (request()->routeIs('orders.index')) {
            $resolvedActive = 'orders';
        } elseif (request()->routeIs('cart') || request()->routeIs('cart.index')) {
            $resolvedActive = 'cart';
        }
    }
@endphp

@once
    @push('styles')
        <style>
            .account-nav-header {
                text-align: center;
                margin-bottom: 50px;
            }

            .account-nav-title {
                font-family: 'Playfair Display', serif;
                font-size: 42px;
                font-weight: 400;
                margin-bottom: 15px;
                color: #1a1a1a;
            }

            .account-nav-subtitle {
                font-size: 16px;
                color: #666;
                margin-bottom: 30px;
            }

            .account-nav-actions {
                display: flex;
                gap: 15px;
                justify-content: center;
                flex-wrap: wrap;
            }

            .account-nav-btn {
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

            .account-nav-btn:hover {
                background: #f5f5f5;
                border-color: #555;
            }

            .account-nav-btn.is-active {
                background: #333;
                color: #ffffff;
                border-color: #333;
            }

            .account-nav-btn.is-active:hover {
                background: #555;
                border-color: #555;
            }

            .account-nav-btn svg {
                width: 18px;
                height: 18px;
            }

            @media (max-width: 768px) {
                .account-nav-title {
                    font-size: 32px;
                }

                .account-nav-subtitle {
                    font-size: 14px;
                }

                .account-nav-actions {
                    display: flex !important;
                    flex-direction: row !important;
                    gap: 8px !important;
                    flex-wrap: nowrap !important;
                    justify-content: space-between !important;
                }

                .account-nav-btn {
                    flex: 1 1 33.333% !important;
                    min-width: 0 !important;
                    max-width: none !important;
                    padding: 10px 8px !important;
                    font-size: 11px !important;
                    gap: 4px !important;
                    white-space: nowrap !important;
                }

                .account-nav-btn svg {
                    width: 14px !important;
                    height: 14px !important;
                    flex-shrink: 0 !important;
                }

                .account-nav-btn span {
                    font-size: 11px !important;
                }
            }
        </style>
    @endpush
@endonce

<div class="account-nav-header">
    <h1 class="account-nav-title">{{ $title }}</h1>

    @if($subtitle)
        <p class="account-nav-subtitle">{{ $subtitle }}</p>
    @endif

    <div class="account-nav-actions">
        <a href="{{ route('wishlist') }}" class="account-nav-btn {{ $resolvedActive === 'wishlist' ? 'is-active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
            <span class="ar-text">المفضلة</span>
            <span class="en-text">Wishlist</span>
        </a>

        <a href="{{ route('orders.index') }}" class="account-nav-btn {{ $resolvedActive === 'orders' ? 'is-active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <line x1="1" y1="10" x2="23" y2="10"></line>
            </svg>
            <span class="ar-text">طلباتي</span>
            <span class="en-text">My Orders</span>
        </a>

        <a href="{{ route('cart.index') }}" class="account-nav-btn {{ $resolvedActive === 'cart' ? 'is-active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span class="ar-text">السلة</span>
            <span class="en-text">Cart</span>
        </a>
    </div>
</div>
