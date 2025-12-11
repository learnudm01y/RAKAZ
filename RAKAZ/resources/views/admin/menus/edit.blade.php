@extends('admin.layouts.app')

@section('title', __('labels.menus.edit'))

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">{{ __('labels.menus.edit') }}</h1>
        <div class="page-actions">
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('labels.back') }}
            </a>
            <a href="{{ route('admin.menus.columns', $menu) }}" class="btn btn-info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                </svg>
                {{ __('labels.menus.manage_columns') }}
            </a>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">{{ __('labels.menus.basic_info') }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">{{ __('labels.menus.name_ar') }}</label>
                    <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $menu->getName('ar')) }}" required>
                    @error('name_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label required">{{ __('labels.menus.name_en') }}</label>
                    <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $menu->getName('en')) }}" required>
                    @error('name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('labels.menus.link') }}</label>
                    <input type="url" name="link" class="form-control @error('link') is-invalid @enderror" value="{{ old('link', $menu->link) }}" placeholder="https://example.com">
                    @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">{{ __('labels.menus.link_hint') }}</small>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label required">{{ __('labels.menus.sort_order') }}</label>
                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $menu->sort_order) }}" required min="0">
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">{{ __('labels.menus.status') }}</label>
                    <div class="form-check form-switch" style="padding-top: 8px;">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            {{ __('labels.active') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">{{ __('labels.menus.mega_menu_image') }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">{{ __('labels.menus.image') }}</label>

                @if($menu->image)
                    <div style="margin-bottom: 15px;">
                        <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->getName(app()->getLocale()) }}" style="max-width: 300px; border-radius: 8px; border: 1px solid #e5e7eb;">
                        <div style="margin-top: 10px;">
                            <label class="form-check">
                                <input type="checkbox" name="remove_image" value="1" class="form-check-input">
                                <span class="form-check-label" style="color: #dc2626;">{{ __('labels.menus.remove_image') }}</span>
                            </label>
                        </div>
                    </div>
                @endif

                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">{{ __('labels.menus.image_hint') }}</small>

                <div id="image-preview" style="margin-top: 15px; display: none;">
                    <img id="preview-img" src="" alt="Preview" style="max-width: 300px; border-radius: 8px; border: 1px solid #e5e7eb;">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('labels.menus.image_title_ar') }}</label>
                    <input type="text" name="image_title_ar" class="form-control @error('image_title_ar') is-invalid @enderror" value="{{ old('image_title_ar', $menu->getImageTitle('ar')) }}">
                    @error('image_title_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('labels.menus.image_title_en') }}</label>
                    <input type="text" name="image_title_en" class="form-control @error('image_title_en') is-invalid @enderror" value="{{ old('image_title_en', $menu->getImageTitle('en')) }}">
                    @error('image_title_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('labels.menus.image_description_ar') }}</label>
                    <textarea name="image_description_ar" class="form-control @error('image_description_ar') is-invalid @enderror" rows="2">{{ old('image_description_ar', $menu->getImageDescription('ar')) }}</textarea>
                    @error('image_description_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('labels.menus.image_description_en') }}</label>
                    <textarea name="image_description_en" class="form-control @error('image_description_en') is-invalid @enderror" rows="2">{{ old('image_description_en', $menu->getImageDescription('en')) }}</textarea>
                    @error('image_description_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ __('labels.update') }}
        </button>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">{{ __('labels.cancel') }}</a>
    </div>
</form>

@push('scripts')
<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection
