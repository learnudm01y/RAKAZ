@extends('admin.layouts.app')

@section('title', __('labels.menus.title'))

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">{{ __('labels.menus.title') }}</h1>
        <div class="page-actions">
            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('labels.menus.add_new') }}
            </a>
        </div>
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
                            <th>{{ __('labels.menus.sort_order') }}</th>
                            <th>{{ __('labels.menus.name') }}</th>
                            <th>{{ __('labels.menus.image') }}</th>
                            <th>{{ __('labels.menus.columns_count') }}</th>
                            <th>{{ __('labels.menus.link') }}</th>
                            <th>{{ __('labels.menus.status') }}</th>
                            <th>{{ __('labels.actions') }}</th>
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
