@extends('admin.layouts.app')

@section('title', __('labels.menus.title'))

@push('styles')
<style>
    .table thead th {
        background: #1a1a1a !important;
        color: white !important;
        padding: 16px 20px !important;
        font-weight: 600 !important;
        border-bottom: 3px solid #000 !important;
        text-transform: none !important;
        letter-spacing: 0.3px !important;
        font-size: 14px !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
    }

    .table thead th svg {
        margin-inline-end: 8px;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">{{ __('labels.menus.title') }}</h1>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('labels.menus.add_new') }}
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($menus->isEmpty())
            <div class="text-center py-5">
                <svg class="mx-auto mb-4" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #9ca3af;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <p class="text-muted mb-4">{{ __('labels.menus.no_menus') }}</p>
                <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                    {{ __('labels.menus.add_first') }}
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                {{ __('labels.menus.sort_order') }}
                            </th>
                            <th>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ __('labels.menus.name') }}
                            </th>
                            <th>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ __('labels.menus.image') }}
                            </th>
                            <th>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                </svg>
                                {{ __('labels.menus.columns_count') }}
                            </th>
                            <th>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                {{ __('labels.menus.link') }}
                            </th>
                            <th>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('labels.menus.status') }}
                            </th>
                            <th>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                                {{ __('labels.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                            <tr>
                                <td>{{ $menu->sort_order }}</td>
                                <td>
                                    <div style="font-weight: 600;">{{ $menu->getName('ar') }}</div>
                                    <div style="color: #6b7280; font-size: 0.875rem;">{{ $menu->getName('en') }}</div>
                                </td>
                                <td>
                                    @if($menu->image)
                                        <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->getName(app()->getLocale()) }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <span style="color: #9ca3af;">{{ __('labels.no_image') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $menu->columns_count }} {{ __('labels.menus.columns') }}
                                    </span>
                                </td>
                                <td>
                                    @if($menu->link)
                                        <a href="{{ $menu->link }}" target="_blank" style="color: #3b82f6; text-decoration: none;">
                                            {{ Str::limit($menu->link, 30) }}
                                        </a>
                                    @else
                                        <span style="color: #9ca3af;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($menu->is_active)
                                        <span class="badge badge-success">{{ __('labels.active') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('labels.inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.menus.columns', $menu) }}" class="btn btn-sm btn-info" title="{{ __('labels.menus.manage_columns') }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                                            </svg>
                                            {{ __('labels.menus.columns') }}
                                        </a>
                                        <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-warning" title="{{ __('labels.edit') }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" style="display: inline;" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" title="{{ __('labels.delete') }}">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');
            const isArabic = document.documentElement.getAttribute('dir') === 'rtl';

            Swal.fire({
                title: isArabic ? 'هل أنت متأكد؟' : 'Are you sure?',
                text: isArabic ? 'لن تتمكن من التراجع عن هذا الإجراء!' : 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53e3e',
                cancelButtonColor: '#6b7280',
                confirmButtonText: isArabic ? 'نعم، احذف!' : 'Yes, delete it!',
                cancelButtonText: isArabic ? 'إلغاء' : 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
