@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة الألوان' : 'Manage Colors')

@section('page-title')
    <span class="ar-text">إدارة الألوان</span>
    <span class="en-text">Manage Colors</span>
@endsection

@push('styles')
<style>
    .colors-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
    }

    .colors-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .add-color-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
    }

    .add-color-btn:hover {
        background: #2c5aa0;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(49, 130, 206, 0.3);
    }

    .colors-table {
        width: 100%;
        border-collapse: collapse;
    }

    .colors-table th {
        background: #1a1a1a;
        color: white;
        padding: 16px 20px;
        font-weight: 600;
        border-bottom: 3px solid #000;
        text-transform: none;
        letter-spacing: 0.3px;
        font-size: 14px;
        white-space: nowrap;
        vertical-align: middle;
    }

    .colors-table th svg {
        margin-inline-end: 8px;
        vertical-align: middle;
    }

    [dir="rtl"] .colors-table th {
        text-align: right;
    }

    .colors-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .colors-table tbody tr:hover {
        background: var(--hover-bg);
    }

    .color-preview {
        display: inline-block;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid var(--border-color);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .color-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-btn {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
        margin: 0 0.25rem;
        transition: all 0.2s;
    }

    .btn-edit {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-edit:hover {
        background: #bfdbfe;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-color);
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-color);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
    }

    .color-picker-group {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .color-picker {
        width: 60px;
        height: 40px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        cursor: pointer;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    .btn-submit {
        padding: 0.75rem 1.5rem;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background: #2c5aa0;
    }

    .btn-cancel {
        padding: 0.75rem 1.5rem;
        background: #e5e7eb;
        color: #374151;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #d1d5db;
    }
</style>
@endpush

@section('content')
<div class="colors-container">
    <div class="colors-header">
        <h2 style="margin: 0; font-size: 1.5rem;">
            <span class="ar-text">جميع الألوان</span>
            <span class="en-text">All Colors</span>
        </h2>
        <button class="add-color-btn" onclick="openModal('create')">
            <i class="fas fa-plus"></i>
            <span class="ar-text">إضافة لون</span>
            <span class="en-text">Add Color</span>
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1.5rem; padding: 1rem; background: #d1fae5; color: #065f46; border-radius: 8px;">
            {{ session('success') }}
        </div>
    @endif

    <table class="colors-table">
        <thead>
            <tr>
                <th>
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                    <span class="ar-text">اللون</span><span class="en-text">Color</span>
                </th>
                <th>
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    <span class="ar-text">الاسم بالعربية</span><span class="en-text">Arabic Name</span>
                </th>
                <th>
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    <span class="ar-text">الاسم بالإنجليزية</span><span class="en-text">English Name</span>
                </th>
                <th>
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    <span class="ar-text">الكود</span><span class="en-text">Hex Code</span>
                </th>
                <th>
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    <span class="ar-text">الترتيب</span><span class="en-text">Sort Order</span>
                </th>
                <th><span class="ar-text">عدد المنتجات</span><span class="en-text">Products</span></th>
                <th><span class="ar-text">الحالة</span><span class="en-text">Status</span></th>
                <th><span class="ar-text">الإجراءات</span><span class="en-text">Actions</span></th>
            </tr>
        </thead>
        <tbody>
            @forelse($colors as $color)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span class="color-preview" style="background-color: {{ $color->hex_code }}"></span>
                    </div>
                </td>
                <td>{{ $color->name['ar'] ?? '-' }}</td>
                <td>{{ $color->name['en'] ?? '-' }}</td>
                <td><code>{{ $color->hex_code }}</code></td>
                <td>{{ $color->sort_order }}</td>
                <td>{{ $color->product_count }}</td>
                <td>
                    <span class="color-badge {{ $color->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $color->is_active ? __('Active') : __('Inactive') }}
                    </span>
                </td>
                <td>
                    <button class="action-btn btn-edit" onclick="openModal('edit', {{ $color->id }})">
                        <i class="fas fa-edit"></i> <span class="ar-text">تعديل</span><span class="en-text">Edit</span>
                    </button>
                    <form action="{{ route('admin.colors.destroy', $color) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete" onclick="return confirm('{{ __('Are you sure?') }}')">
                            <i class="fas fa-trash"></i> <span class="ar-text">حذف</span><span class="en-text">Delete</span>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 2rem; color: #666;">
                    <span class="ar-text">لا توجد ألوان</span>
                    <span class="en-text">No colors found</span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Create/Edit Modal -->
<div id="colorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">
                <span class="ar-text">إضافة لون</span>
                <span class="en-text">Add Color</span>
            </h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <form id="colorForm" method="POST" action="{{ route('admin.colors.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الاسم بالعربية</span>
                    <span class="en-text">Arabic Name</span>
                </label>
                <input type="text" name="name[ar]" id="colorNameAr" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الاسم بالإنجليزية</span>
                    <span class="en-text">English Name</span>
                </label>
                <input type="text" name="name[en]" id="colorNameEn" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">اللون</span>
                    <span class="en-text">Color</span>
                </label>
                <div class="color-picker-group">
                    <input type="color" id="colorPicker" class="color-picker" value="#000000">
                    <input type="text" name="hex_code" id="colorHexCode" class="form-control" value="#000000" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الترتيب</span>
                    <span class="en-text">Sort Order</span>
                </label>
                <input type="number" name="sort_order" id="colorSortOrder" class="form-control" value="0" min="0">
            </div>

            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="is_active" id="colorIsActive" value="1" checked>
                    <span class="ar-text">نشط</span>
                    <span class="en-text">Active</span>
                </label>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">
                    <span class="ar-text">إلغاء</span>
                    <span class="en-text">Cancel</span>
                </button>
                <button type="submit" class="btn-submit">
                    <span class="ar-text">حفظ</span>
                    <span class="en-text">Save</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const colors = @json($colors);

// Sync color picker with hex input
document.getElementById('colorPicker').addEventListener('input', function(e) {
    document.getElementById('colorHexCode').value = e.target.value;
});

document.getElementById('colorHexCode').addEventListener('input', function(e) {
    const hexValue = e.target.value;
    if (/^#[0-9A-F]{6}$/i.test(hexValue)) {
        document.getElementById('colorPicker').value = hexValue;
    }
});

function openModal(mode, colorId = null) {
    const modal = document.getElementById('colorModal');
    const form = document.getElementById('colorForm');
    const modalTitle = document.getElementById('modalTitle');
    const formMethod = document.getElementById('formMethod');

    if (mode === 'create') {
        modalTitle.innerHTML = '<span class="ar-text">إضافة لون</span><span class="en-text">Add Color</span>';
        form.action = '{{ route('admin.colors.store') }}';
        formMethod.value = 'POST';
        form.reset();
        document.getElementById('colorIsActive').checked = true;
        document.getElementById('colorPicker').value = '#000000';
        document.getElementById('colorHexCode').value = '#000000';
    } else if (mode === 'edit' && colorId) {
        const color = colors.find(c => c.id === colorId);
        if (color) {
            modalTitle.innerHTML = '<span class="ar-text">تعديل لون</span><span class="en-text">Edit Color</span>';
            form.action = `/admin/colors/${colorId}`;
            formMethod.value = 'PUT';

            document.getElementById('colorNameAr').value = color.name?.ar || '';
            document.getElementById('colorNameEn').value = color.name?.en || '';
            document.getElementById('colorHexCode').value = color.hex_code;
            document.getElementById('colorPicker').value = color.hex_code;
            document.getElementById('colorSortOrder').value = color.sort_order;
            document.getElementById('colorIsActive').checked = color.is_active;
        }
    }

    modal.classList.add('active');
}

function closeModal() {
    document.getElementById('colorModal').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('colorModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush
@endsection
