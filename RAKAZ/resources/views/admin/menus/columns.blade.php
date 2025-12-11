@extends('admin.layouts.app')

@section('title', __('labels.menus.manage_columns') . ' - ' . $menu->getName(app()->getLocale()))

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">{{ __('labels.menus.manage_columns') }}</h1>
        <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
            {{ $menu->getName('ar') }} / {{ $menu->getName('en') }}
        </div>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('labels.back_to_menus') }}
        </a>
        <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-info">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            {{ __('labels.menus.edit_menu') }}
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Add New Column -->
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">{{ __('labels.menus.add_column') }}</h3>
    </div>
    <div class="card-body">
        <form id="add-column-form" action="{{ route('admin.menus.columns.store', $menu) }}" method="POST">
            @csrf
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label class="form-label required">{{ __('labels.menus.column_title_ar') }}</label>
                    <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror" value="{{ old('title_ar') }}" required>
                    @error('title_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label required">{{ __('labels.menus.column_title_en') }}</label>
                    <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en') }}" required>
                    @error('title_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label required">{{ __('labels.menus.sort_order') }}</label>
                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}" required min="0">
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2 mb-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('labels.add') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Existing Columns -->
@if($menu->columns->isEmpty())
    <div class="card">
        <div class="card-body">
            <div class="text-center py-5">
                <svg class="mx-auto mb-4" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #9ca3af;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                </svg>
                <p class="text-muted">{{ __('labels.menus.no_columns') }}</p>
            </div>
        </div>
    </div>
@else
    @foreach($menu->columns as $column)
        <div class="card mb-4">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 class="card-title" style="margin: 0;">
                        {{ $column->getTitle('ar') }} / {{ $column->getTitle('en') }}
                    </h3>
                    <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
                        {{ __('labels.menus.sort_order') }}: {{ $column->sort_order }} |
                        {{ __('labels.menus.status') }}:
                        @if($column->is_active)
                            <span style="color: #10b981;">{{ __('labels.active') }}</span>
                        @else
                            <span style="color: #6b7280;">{{ __('labels.inactive') }}</span>
                        @endif
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-warning" onclick="toggleEditColumn({{ $column->id }})">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <form action="{{ route('admin.menus.columns.destroy', $column) }}" method="POST" style="display: inline;" class="delete-column-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger delete-column-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Edit Column Form (Hidden by default) -->
            <div id="edit-column-{{ $column->id }}" style="display: none; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <div style="padding: 1rem;">
                    <form class="edit-column-form" action="{{ route('admin.menus.columns.update', $column) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label class="form-label required">{{ __('labels.menus.column_title_ar') }}</label>
                                <input type="text" name="title_ar" class="form-control" value="{{ $column->getTitle('ar') }}" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label required">{{ __('labels.menus.column_title_en') }}</label>
                                <input type="text" name="title_en" class="form-control" value="{{ $column->getTitle('en') }}" required>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label required">{{ __('labels.menus.sort_order') }}</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ $column->sort_order }}" required min="0">
                            </div>

                            <div class="col-md-2 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active_{{ $column->id }}" value="1" {{ $column->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active_{{ $column->id }}">
                                        {{ __('labels.active') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="submit" class="btn btn-sm btn-primary">{{ __('labels.update') }}</button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="toggleEditColumn({{ $column->id }})">{{ __('labels.cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <!-- Add Item Form -->
                <div style="background: #f3f4f6; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <h4 style="font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">{{ __('labels.menus.add_item') }}</h4>
                    <form class="add-item-form" action="{{ route('admin.menus.items.store', $column) }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-10 mb-2">
                                <label class="form-label">{{ __('labels.menus.link_to_category') }} <span style="color: #dc2626;">*</span></label>
                                <div style="position: relative;">
                                    <select name="category_id" class="form-control" required style="appearance: none; -webkit-appearance: none; -moz-appearance: none; padding-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 2.5rem;">
                                        <option value="">{{ __('labels.menus.select_category') }}</option>
                                        @foreach($categories->whereNull('parent_id') as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->getName(app()->getLocale()) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg style="position: absolute; top: 50%; {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.75rem; transform: translateY(-50%); width: 16px; height: 16px; pointer-events: none; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>

                            <div class="col-md-1 mb-2">
                                <label class="form-label">{{ __('labels.menus.sort_order') }}</label>
                                <input type="number" name="sort_order" class="form-control" value="0" required min="0">
                            </div>

                            <div class="col-md-1 mb-2">
                                <button type="submit" class="btn btn-sm btn-primary w-100">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span style="margin-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 5px;">{{ app()->getLocale() == 'ar' ? 'إضافة' : 'Add' }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Items List -->
                @if($column->items->isEmpty())
                    <div class="text-center py-3" style="color: #9ca3af;">
                        <p style="margin: 0;">{{ __('labels.menus.no_items') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('labels.menus.sort_order') }}</th>
                                    <th>{{ __('labels.menus.name') }}</th>
                                    <th>{{ __('labels.menus.link') }}</th>
                                    <th>{{ __('labels.menus.type') }}</th>
                                    <th>{{ __('labels.menus.status') }}</th>
                                    <th>{{ __('labels.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($column->items as $item)
                                    <tr>
                                        <td>{{ $item->sort_order }}</td>
                                        <td>
                                            <div style="font-weight: 600;">{{ $item->getName('ar') }}</div>
                                            <div style="color: #6b7280; font-size: 0.875rem;">{{ $item->getName('en') }}</div>
                                        </td>
                                        <td>
                                            <a href="{{ $item->getLink() }}" target="_blank" style="color: #3b82f6; text-decoration: none; font-size: 0.875rem;">
                                                {{ Str::limit($item->getLink(), 30) }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($item->category_id)
                                                <span class="badge badge-primary">{{ __('labels.menus.category_link') }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ __('labels.menus.custom_link') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->is_active)
                                                <span class="badge badge-success">{{ __('labels.active') }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ __('labels.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.menus.items.destroy', $item) }}" method="POST" style="display: inline;" class="delete-item-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-item-btn">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endif

@push('scripts')
<script src="/assets/js/admin-menu-columns.js"></script>
@endpush
@endsection
