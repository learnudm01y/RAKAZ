@if($products->hasPages())
<div class="pagination">
    @if($products->onFirstPage())
    <button class="pagination-btn" disabled>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    @else
    <a href="{{ $products->appends(request()->query())->previousPageUrl() }}" class="pagination-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 19l-7-7 7-7" />
        </svg>
    </a>
    @endif

    @foreach($products->appends(request()->query())->getUrlRange(1, $products->lastPage()) as $page => $url)
    <a href="{{ $url }}" class="pagination-btn {{ $page == $products->currentPage() ? 'active' : '' }}">{{ $page }}</a>
    @endforeach

    @if($products->hasMorePages())
    <a href="{{ $products->appends(request()->query())->nextPageUrl() }}" class="pagination-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 5l7 7-7 7" />
        </svg>
    </a>
    @else
    <button class="pagination-btn" disabled>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 5l7 7-7 7" />
        </svg>
    </button>
    @endif
</div>
@endif
