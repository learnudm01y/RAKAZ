@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة المقاسات' : 'Manage Sizes')

@section('page-title')
    <span class="ar-text">إدارة المقاسات</span>
    <span class="en-text">Manage Sizes</span>
@endsection

@push('styles')
<style>
    .sizes-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
    }

    .sizes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .add-size-btn {
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

    .add-size-btn:hover {
        background: #2c5aa0;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(49, 130, 206, 0.3);
    }

    .sizes-table {
        width: 100%;
        border-collapse: collapse;
    }

    .sizes-table th {
        background: var(--hover-bg);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--text-color);
        border-bottom: 2px solid var(--border-color);
    }

    [dir="rtl"] .sizes-table th {
        text-align: right;
    }

    .sizes-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .sizes-table tbody tr:hover {
        background: var(--hover-bg);
    }

    .size-badge {
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
<div class="sizes-container">
    <div class="sizes-header">
        <h2 style="margin: 0; font-size: 1.5rem;">
            <span class="ar-text">جميع المقاسات</span>
            <span class="en-text">All Sizes</span>
        </h2>
        <button class="add-size-btn" onclick="openModal('create')">
            <i class="fas fa-plus"></i>
            <span class="ar-text">إضافة مقاس</span>
            <span class="en-text">Add Size</span>
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1.5rem; padding: 1rem; background: #d1fae5; color: #065f46; border-radius: 8px;">
            {{ session('success') }}
        </div>
    @endif

    <table class="sizes-table">
        <thead>
            <tr>
                <th><span class="ar-text">المقاس</span><span class="en-text">Size</span></th>
                <th><span class="ar-text">الاسم بالعربية</span><span class="en-text">Arabic Name</span></th>
                <th><span class="ar-text">الاسم بالإنجليزية</span><span class="en-text">English Name</span></th>
                <th><span class="ar-text">الترتيب</span><span class="en-text">Sort Order</span></th>
                <th><span class="ar-text">عدد المنتجات</span><span class="en-text">Products</span></th>
                <th><span class="ar-text">الحالة</span><span class="en-text">Status</span></th>
                <th><span class="ar-text">الإجراءات</span><span class="en-text">Actions</span></th>
            </tr>
        </thead>
        <tbody>
            @forelse($sizes as $size)
            <tr>
                <td><strong>{{ $size->name }}</strong></td>
                <td>{{ $size->name_translations['ar'] ?? '-' }}</td>
                <td>{{ $size->name_translations['en'] ?? '-' }}</td>
                <td>{{ $size->sort_order }}</td>
                <td>{{ $size->product_count }}</td>
                <td>
                    <span class="size-badge {{ $size->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $size->is_active ? __('Active') : __('Inactive') }}
                    </span>
                </td>
                <td>
                    <button class="action-btn btn-edit" onclick="openModal('edit', {{ $size->id }})">
                        <i class="fas fa-edit"></i> <span class="ar-text">تعديل</span><span class="en-text">Edit</span>
                    </button>
                    <form action="{{ route('admin.sizes.destroy', $size) }}" method="POST" style="display: inline;">
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
                <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                    <span class="ar-text">لا توجد مقاسات</span>
                    <span class="en-text">No sizes found</span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Create/Edit Modal -->
<div id="sizeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">
                <span class="ar-text">إضافة مقاس</span>
                <span class="en-text">Add Size</span>
            </h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <form id="sizeForm" method="POST" action="{{ route('admin.sizes.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">المقاس</span>
                    <span class="en-text">Size</span>
                </label>
                <input type="text" name="name" id="sizeName" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الاسم بالعربية</span>
                    <span class="en-text">Arabic Name</span>
                </label>
                <input type="text" name="name_translations[ar]" id="sizeNameAr" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الاسم بالإنجليزية</span>
                    <span class="en-text">English Name</span>
                </label>
                <input type="text" name="name_translations[en]" id="sizeNameEn" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span class="ar-text">الترتيب</span>
                    <span class="en-text">Sort Order</span>
                </label>
                <input type="number" name="sort_order" id="sizeSortOrder" class="form-control" value="0" min="0">
            </div>

            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="is_active" id="sizeIsActive" value="1" checked>
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
const sizes = @json($sizes);

function openModal(mode, sizeId = null) {
    const modal = document.getElementById('sizeModal');
    const form = document.getElementById('sizeForm');
    const modalTitle = document.getElementById('modalTitle');
    const formMethod = document.getElementById('formMethod');

    if (mode === 'create') {
        modalTitle.innerHTML = '<span class="ar-text">إضافة مقاس</span><span class="en-text">Add Size</span>';
        form.action = '{{ route('admin.sizes.store') }}';
        formMethod.value = 'POST';
        form.reset();
        document.getElementById('sizeIsActive').checked = true;
    } else if (mode === 'edit' && sizeId) {
        const size = sizes.find(s => s.id === sizeId);
        if (size) {
            modalTitle.innerHTML = '<span class="ar-text">تعديل مقاس</span><span class="en-text">Edit Size</span>';
            form.action = `/admin/sizes/${sizeId}`;
            formMethod.value = 'PUT';

            document.getElementById('sizeName').value = size.name;
            document.getElementById('sizeNameAr').value = size.name_translations?.ar || '';
            document.getElementById('sizeNameEn').value = size.name_translations?.en || '';
            document.getElementById('sizeSortOrder').value = size.sort_order;
            document.getElementById('sizeIsActive').checked = size.is_active;
        }
    }

    modal.classList.add('active');
}

function closeModal() {
    document.getElementById('sizeModal').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('sizeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush
@endsection
