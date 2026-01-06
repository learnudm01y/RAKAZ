@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة الفوتر' : 'Footer Management')

@section('page-title')
    <span class="ar-text">إدارة الفوتر</span>
    <span class="en-text">Footer Management</span>
@endsection

@push('styles')
<style>
    .footer-management-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    .section-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .section-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
        border-radius: 12px 12px 0 0;
    }

    .section-card-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-card-body {
        padding: 1.5rem;
    }

    /* Settings Grid */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .setting-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .setting-label {
        font-weight: 500;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        width: 50px;
        height: 26px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 26px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: #28a745;
    }

    input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }

    /* Separator OR */
    .separator-or {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 1rem 0;
        color: #666;
        font-weight: 500;
    }

    .separator-or::before,
    .separator-or::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid var(--border-color);
    }

    .separator-or span {
        padding: 0 1rem;
        background: white;
    }

    /* Custom Link Section */
    .custom-link-section {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        border: 1px dashed var(--border-color);
    }

    .custom-link-section .section-label {
        display: block;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--primary-color);
    }

    .custom-link-section .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    /* Social Links */
    .social-links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }

    .social-link-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .social-link-item .platform-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-color);
        color: white;
        border-radius: 8px;
    }

    .social-link-item .platform-icon svg {
        width: 20px;
        height: 20px;
    }

    .social-link-info {
        flex: 1;
    }

    .social-link-info .platform-name {
        font-weight: 600;
        text-transform: capitalize;
    }

    .social-link-info .platform-url {
        font-size: 0.85rem;
        color: #666;
        word-break: break-all;
    }

    .social-link-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Footer Sections Grid */
    .footer-sections-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .footer-section-card {
        background: #f8f9fa;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        overflow: hidden;
    }

    .footer-section-header {
        padding: 1rem;
        background: white;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .footer-section-header .section-title {
        font-weight: 600;
        font-size: 1rem;
    }

    .footer-section-header .section-title small {
        display: block;
        color: #666;
        font-weight: 400;
        font-size: 0.85rem;
    }

    .footer-section-body {
        padding: 1rem;
        max-height: 300px;
        overflow-y: auto;
    }

    .footer-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: white;
        border-radius: 6px;
        margin-bottom: 0.5rem;
        border: 1px solid #e9ecef;
    }

    .footer-item:last-child {
        margin-bottom: 0;
    }

    .footer-item-info {
        flex: 1;
    }

    .footer-item-info .item-title {
        font-weight: 500;
    }

    .footer-item-info .item-link {
        font-size: 0.8rem;
        color: #666;
    }

    .footer-item-actions {
        display: flex;
        gap: 0.25rem;
    }

    .footer-section-footer {
        padding: 0.75rem 1rem;
        border-top: 1px solid var(--border-color);
        background: white;
    }

    /* Forms */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(201, 162, 39, 0.1);
    }

    /* Buttons */
    .btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background: #b8922a;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-success:hover {
        background: #218838;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .btn-outline {
        background: transparent;
        border: 1px solid var(--border-color);
        color: #333;
    }

    .btn-outline:hover {
        background: #f8f9fa;
    }

    .btn-sm {
        padding: 0.35rem 0.75rem;
        font-size: 0.85rem;
    }

    .btn-icon {
        padding: 0.35rem;
        min-width: 32px;
        height: 32px;
        justify-content: center;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 600px;
        min-height: 500px !important;
        overflow: visible !important;
    }

    .modal-body {
        padding: 2rem 1.5rem !important;
        min-height: 350px !important;
        overflow: visible !important;
    }

    .modal-body .form-control {
        position: relative;
    }

    .modal-body select.form-control {
        position: relative;
        z-index: 1001;
    }

    .modal-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #666;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<div class="footer-management-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- الإعدادات العامة -->
    <div class="section-card">
        <div class="section-card-header">
            <h3>
                <i class="fas fa-cog"></i>
                <span class="ar-text">الإعدادات العامة</span>
                <span class="en-text">General Settings</span>
            </h3>
        </div>
        <div class="section-card-body">
            <form action="{{ route('admin.footer.settings.update') }}" method="POST">
                @csrf
                <div class="settings-grid">
                    <div class="setting-item">
                        <span class="setting-label">
                            <span class="ar-text">إظهار النشرة الإخبارية</span>
                            <span class="en-text">Show Newsletter</span>
                        </span>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_newsletter" value="1" {{ ($settings['show_newsletter'] ?? true) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span class="setting-label">
                            <span class="ar-text">إظهار روابط التواصل</span>
                            <span class="en-text">Show Social Links</span>
                        </span>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_social_links" value="1" {{ ($settings['show_social_links'] ?? true) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span class="setting-label">
                            <span class="ar-text">إظهار قسم التطبيقات</span>
                            <span class="en-text">Show Apps Section</span>
                        </span>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_apps_section" value="1" {{ ($settings['show_apps_section'] ?? true) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <span class="setting-label">
                            <span class="ar-text">إظهار معلومات الاتصال</span>
                            <span class="en-text">Show Contact Info</span>
                        </span>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_contact_info" value="1" {{ ($settings['show_contact_info'] ?? true) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="form-row mt-4">
                    <div class="form-group">
                        <label>
                            <span class="ar-text">رقم خدمة العملاء</span>
                            <span class="en-text">Customer Service Phone</span>
                        </label>
                        <input type="text" name="customer_service_phone" class="form-control" value="{{ $settings['customer_service_phone'] ?? '800 717171' }}">
                    </div>
                    <div class="form-group">
                        <label>
                            <span class="ar-text">رقم الواتساب</span>
                            <span class="en-text">WhatsApp Number</span>
                        </label>
                        <input type="text" name="whatsapp_number" class="form-control" value="{{ $settings['whatsapp_number'] ?? '+971 55 300 7879' }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>
                            <span class="ar-text">نص حقوق النشر (عربي)</span>
                            <span class="en-text">Copyright Text (Arabic)</span>
                        </label>
                        <input type="text" name="copyright_ar" class="form-control" value="{{ $settings['copyright_ar'] ?? 'ركاز LLC. 2025. جميع الحقوق محفوظة' }}">
                    </div>
                    <div class="form-group">
                        <label>
                            <span class="ar-text">نص حقوق النشر (إنجليزي)</span>
                            <span class="en-text">Copyright Text (English)</span>
                        </label>
                        <input type="text" name="copyright_en" class="form-control" value="{{ $settings['copyright_en'] ?? 'Rakaz LLC. 2025. All Rights Reserved' }}">
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <span class="ar-text">حفظ الإعدادات</span>
                        <span class="en-text">Save Settings</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- روابط التواصل الاجتماعي -->
    <div class="section-card">
        <div class="section-card-header">
            <h3>
                <i class="fas fa-share-alt"></i>
                <span class="ar-text">روابط التواصل الاجتماعي</span>
                <span class="en-text">Social Links</span>
            </h3>
            <button type="button" class="btn btn-primary btn-sm" onclick="openModal('addSocialModal')">
                <i class="fas fa-plus"></i>
                <span class="ar-text">إضافة</span>
                <span class="en-text">Add</span>
            </button>
        </div>
        <div class="section-card-body">
            @if($socialLinks->count() > 0)
                <div class="social-links-grid">
                    @foreach($socialLinks as $social)
                        <div class="social-link-item">
                            <div class="platform-icon">
                                <i class="fab fa-{{ $social->platform }}"></i>
                            </div>
                            <div class="social-link-info">
                                <div class="platform-name">{{ $social->platform }}</div>
                                <div class="platform-url">{{ Str::limit($social->url, 40) }}</div>
                            </div>
                            <div class="social-link-actions">
                                <form action="{{ route('admin.footer.social-links.toggle', $social->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-sm {{ $social->is_active ? 'btn-success' : 'btn-outline' }}" title="{{ $social->is_active ? 'Active' : 'Inactive' }}">
                                        <i class="fas fa-{{ $social->is_active ? 'eye' : 'eye-slash' }}"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-icon btn-sm btn-outline" onclick="editSocialLink({{ json_encode($social) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.footer.social-links.destroy', $social->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-share-alt"></i>
                    <p>
                        <span class="ar-text">لا توجد روابط تواصل اجتماعي</span>
                        <span class="en-text">No social links yet</span>
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- أقسام الفوتر -->
    <div class="section-card">
        <div class="section-card-header">
            <h3>
                <i class="fas fa-columns"></i>
                <span class="ar-text">أقسام الفوتر</span>
                <span class="en-text">Footer Sections</span>
            </h3>
            <button type="button" class="btn btn-primary btn-sm" onclick="openModal('addSectionModal')">
                <i class="fas fa-plus"></i>
                <span class="ar-text">إضافة قسم</span>
                <span class="en-text">Add Section</span>
            </button>
        </div>
        <div class="section-card-body">
            @if($sections->count() > 0)
                <div class="footer-sections-grid">
                    @foreach($sections as $section)
                        <div class="footer-section-card">
                            <div class="footer-section-header">
                                <div class="section-title">
                                    {{ $section->getTitle('ar') }}
                                    <small>{{ $section->getTitle('en') }}</small>
                                </div>
                                <div class="action-buttons">
                                    <form action="{{ route('admin.footer.sections.toggle', $section->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-icon btn-sm {{ $section->is_active ? 'btn-success' : 'btn-outline' }}">
                                            <i class="fas fa-{{ $section->is_active ? 'eye' : 'eye-slash' }}"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-icon btn-sm btn-outline" onclick="editSection({{ json_encode($section) }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.footer.sections.destroy', $section->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم وجميع عناصره؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="footer-section-body">
                                @forelse($section->items as $item)
                                    <div class="footer-item">
                                        <div class="footer-item-info">
                                            <div class="item-title">{{ $item->getDisplayTitle('ar') ?: $item->getDisplayTitle('en') }}</div>
                                            <div class="item-link">
                                                @if($item->link_type == 'route')
                                                    <i class="fas fa-link"></i> {{ $item->route_name }}
                                                @elseif($item->link_type == 'menu')
                                                    <i class="fas fa-bars"></i> {{ $item->menu?->getName('ar') }}
                                                @elseif($item->link_type == 'category')
                                                    <i class="fas fa-tag"></i> {{ $item->category?->name['ar'] ?? '' }}
                                                @elseif($item->link_type == 'page')
                                                    <i class="fas fa-file-alt"></i> {{ $item->page?->title_ar ?? '' }}
                                                @else
                                                    <i class="fas fa-external-link-alt"></i> {{ Str::limit($item->link, 30) }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="footer-item-actions">
                                            <form action="{{ route('admin.footer.items.toggle', $item->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-icon btn-sm {{ $item->is_active ? 'btn-success' : 'btn-outline' }}">
                                                    <i class="fas fa-{{ $item->is_active ? 'eye' : 'eye-slash' }}"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-icon btn-sm btn-outline" onclick="editItem({{ json_encode($item) }}, {{ $section->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.footer.items.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <p>
                                            <span class="ar-text">لا توجد عناصر</span>
                                            <span class="en-text">No items</span>
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                            <div class="footer-section-footer">
                                <button type="button" class="btn btn-outline btn-sm" onclick="openAddItemModal({{ $section->id }})">
                                    <i class="fas fa-plus"></i>
                                    <span class="ar-text">إضافة عنصر</span>
                                    <span class="en-text">Add Item</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-columns"></i>
                    <p>
                        <span class="ar-text">لا توجد أقسام بعد. قم بإضافة قسم جديد.</span>
                        <span class="en-text">No sections yet. Add a new section.</span>
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: إضافة قسم -->
<div class="modal-overlay" id="addSectionModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <span class="ar-text">إضافة قسم جديد</span>
                <span class="en-text">Add New Section</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeModal('addSectionModal')">&times;</button>
        </div>
        <form action="{{ route('admin.footer.sections.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>
                            <span class="ar-text">العنوان (عربي)</span>
                            <span class="en-text">Title (Arabic)</span>
                        </label>
                        <input type="text" name="title_ar" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>
                            <span class="ar-text">العنوان (إنجليزي)</span>
                            <span class="en-text">Title (English)</span>
                        </label>
                        <input type="text" name="title_en" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <span class="ar-text">نوع القسم</span>
                        <span class="en-text">Section Type</span>
                    </label>
                    <select name="type" class="form-control">
                        <option value="links">روابط مخصصة / Custom Links</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addSectionModal')">
                    <span class="ar-text">إلغاء</span>
                    <span class="en-text">Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <span class="ar-text">إضافة</span>
                    <span class="en-text">Add</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: تعديل قسم -->
<div class="modal-overlay" id="editSectionModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <span class="ar-text">تعديل القسم</span>
                <span class="en-text">Edit Section</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeModal('editSectionModal')">&times;</button>
        </div>
        <form id="editSectionForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>
                            <span class="ar-text">العنوان (عربي)</span>
                            <span class="en-text">Title (Arabic)</span>
                        </label>
                        <input type="text" name="title_ar" id="editSectionTitleAr" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>
                            <span class="ar-text">العنوان (إنجليزي)</span>
                            <span class="en-text">Title (English)</span>
                        </label>
                        <input type="text" name="title_en" id="editSectionTitleEn" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editSectionModal')">
                    <span class="ar-text">إلغاء</span>
                    <span class="en-text">Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <span class="ar-text">حفظ</span>
                    <span class="en-text">Save</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: إضافة عنصر -->
<div class="modal-overlay" id="addItemModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <span class="ar-text">إضافة عنصر جديد</span>
                <span class="en-text">Add New Item</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeModal('addItemModal')">&times;</button>
        </div>
        <form action="{{ route('admin.footer.items.store') }}" method="POST">
            @csrf
            <input type="hidden" name="footer_section_id" id="addItemSectionId">
            <input type="hidden" name="link_type" value="menu" id="addLinkType">
            <div class="modal-body">
                <div class="form-group">
                    <label>
                        <span class="ar-text">اختر قائمة</span>
                        <span class="en-text">Select Menu</span>
                    </label>
                    <select name="menu_id" class="form-control" id="addMenuSelect">
                        <option value="">-- اختر قائمة --</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->getName('ar') }} / {{ $menu->getName('en') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="separator-or">
                    <span class="ar-text">أو</span>
                    <span class="en-text">OR</span>
                </div>
                <div class="form-group">
                    <label>
                        <span class="ar-text">اختر تصنيف رئيسي</span>
                        <span class="en-text">Select Main Category</span>
                    </label>
                    <select name="category_id" class="form-control" id="addCategorySelect">
                        <option value="">-- اختر تصنيف --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name['ar'] ?? '' }} / {{ $category->name['en'] ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="separator-or">
                    <span class="ar-text">أو</span>
                    <span class="en-text">OR</span>
                </div>
                <div class="form-group">
                    <label>
                        <span class="ar-text">اختر صفحة من الموقع</span>
                        <span class="en-text">Select Site Page</span>
                    </label>
                    <select name="page_id" class="form-control" id="addPageSelect">
                        <option value="">-- اختر صفحة --</option>
                        @foreach($pages as $page)
                            <option value="{{ $page->id }}">{{ $page->title_ar }} / {{ $page->title_en }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addItemModal')">
                    <span class="ar-text">إلغاء</span>
                    <span class="en-text">Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <span class="ar-text">إضافة</span>
                    <span class="en-text">Add</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: تعديل عنصر -->
<div class="modal-overlay" id="editItemModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <span class="ar-text">تعديل العنصر</span>
                <span class="en-text">Edit Item</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeModal('editItemModal')">&times;</button>
        </div>
        <form id="editItemForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="link_type" value="menu" id="editLinkType">
            <div class="modal-body">
                <div class="form-group">
                    <label>
                        <span class="ar-text">اختر قائمة</span>
                        <span class="en-text">Select Menu</span>
                    </label>
                    <select name="menu_id" class="form-control" id="editMenuSelect">
                        <option value="">-- اختر قائمة --</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->getName('ar') }} / {{ $menu->getName('en') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="separator-or">
                    <span class="ar-text">أو</span>
                    <span class="en-text">OR</span>
                </div>
                <div class="form-group">
                    <label>
                        <span class="ar-text">اختر تصنيف رئيسي</span>
                        <span class="en-text">Select Main Category</span>
                    </label>
                    <select name="category_id" class="form-control" id="editCategorySelect">
                        <option value="">-- اختر تصنيف --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name['ar'] ?? '' }} / {{ $category->name['en'] ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="separator-or">
                    <span class="ar-text">أو</span>
                    <span class="en-text">OR</span>
                </div>
                <div class="form-group">
                    <label>
                        <span class="ar-text">اختر صفحة من الموقع</span>
                        <span class="en-text">Select Site Page</span>
                    </label>
                    <select name="page_id" class="form-control" id="editPageSelect">
                        <option value="">-- اختر صفحة --</option>
                        @foreach($pages as $page)
                            <option value="{{ $page->id }}">{{ $page->title_ar }} / {{ $page->title_en }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editItemModal')">
                    <span class="ar-text">إلغاء</span>
                    <span class="en-text">Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <span class="ar-text">حفظ</span>
                    <span class="en-text">Save</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: إضافة رابط تواصل -->
<div class="modal-overlay" id="addSocialModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <span class="ar-text">إضافة رابط تواصل</span>
                <span class="en-text">Add Social Link</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeModal('addSocialModal')">&times;</button>
        </div>
        <form action="{{ route('admin.footer.social-links.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>
                        <span class="ar-text">المنصة</span>
                        <span class="en-text">Platform</span>
                    </label>
                    <select name="platform" class="form-control" required>
                        <option value="">-- اختر --</option>
                        <option value="facebook">Facebook</option>
                        <option value="twitter">Twitter / X</option>
                        <option value="instagram">Instagram</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="tiktok">TikTok</option>
                        <option value="youtube">YouTube</option>
                        <option value="snapchat">Snapchat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <span class="ar-text">الرابط</span>
                        <span class="en-text">URL</span>
                    </label>
                    <input type="url" name="url" class="form-control" placeholder="https://..." required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addSocialModal')">
                    <span class="ar-text">إلغاء</span>
                    <span class="en-text">Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <span class="ar-text">إضافة</span>
                    <span class="en-text">Add</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: تعديل رابط تواصل -->
<div class="modal-overlay" id="editSocialModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <span class="ar-text">تعديل رابط التواصل</span>
                <span class="en-text">Edit Social Link</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeModal('editSocialModal')">&times;</button>
        </div>
        <form id="editSocialForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>
                        <span class="ar-text">المنصة</span>
                        <span class="en-text">Platform</span>
                    </label>
                    <select name="platform" id="editSocialPlatform" class="form-control" required>
                        <option value="facebook">Facebook</option>
                        <option value="twitter">Twitter / X</option>
                        <option value="instagram">Instagram</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="tiktok">TikTok</option>
                        <option value="youtube">YouTube</option>
                        <option value="snapchat">Snapchat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <span class="ar-text">الرابط</span>
                        <span class="en-text">URL</span>
                    </label>
                    <input type="url" name="url" id="editSocialUrl" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editSocialModal')">
                    <span class="ar-text">إلغاء</span>
                    <span class="en-text">Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <span class="ar-text">حفظ</span>
                    <span class="en-text">Save</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Modal Functions
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    // Section Functions
    function editSection(section) {
        document.getElementById('editSectionForm').action = '{{ url("admin/footer/sections") }}/' + section.id;
        document.getElementById('editSectionTitleAr').value = section.title.ar || '';
        document.getElementById('editSectionTitleEn').value = section.title.en || '';
        openModal('editSectionModal');
    }

    // Item Functions
    function openAddItemModal(sectionId) {
        document.getElementById('addItemSectionId').value = sectionId;
        // Reset all fields
        document.getElementById('addMenuSelect').value = '';
        document.getElementById('addCategorySelect').value = '';
        document.getElementById('addPageSelect').value = '';
        document.getElementById('addLinkType').value = 'menu';
        openModal('addItemModal');
    }

    function editItem(item, sectionId) {
        document.getElementById('editItemForm').action = '{{ url("admin/footer/items") }}/' + item.id;

        // مسح جميع الحقول أولاً
        document.getElementById('editMenuSelect').value = '';
        document.getElementById('editCategorySelect').value = '';
        document.getElementById('editPageSelect').value = '';

        // تعيين نوع الرابط المناسب
        if (item.menu_id) {
            document.getElementById('editLinkType').value = 'menu';
            document.getElementById('editMenuSelect').value = item.menu_id || '';
        } else if (item.category_id) {
            document.getElementById('editLinkType').value = 'category';
            document.getElementById('editCategorySelect').value = item.category_id || '';
        } else if (item.page_id) {
            document.getElementById('editLinkType').value = 'page';
            document.getElementById('editPageSelect').value = item.page_id || '';
        }

        openModal('editItemModal');
    }

    // تحديث link_type تلقائياً عند اختيار قائمة أو تصنيف أو صفحة
    document.addEventListener('DOMContentLoaded', function() {
        // Add Modal
        const addMenuSelect = document.getElementById('addMenuSelect');
        const addCategorySelect = document.getElementById('addCategorySelect');
        const addPageSelect = document.getElementById('addPageSelect');
        const addLinkType = document.getElementById('addLinkType');

        if (addMenuSelect) {
            addMenuSelect.addEventListener('change', function() {
                if (this.value) {
                    addLinkType.value = 'menu';
                    addCategorySelect.value = '';
                    addPageSelect.value = '';
                }
            });
        }

        if (addCategorySelect) {
            addCategorySelect.addEventListener('change', function() {
                if (this.value) {
                    addLinkType.value = 'category';
                    addMenuSelect.value = '';
                    addPageSelect.value = '';
                }
            });
        }

        if (addPageSelect) {
            addPageSelect.addEventListener('change', function() {
                if (this.value) {
                    addLinkType.value = 'page';
                    addMenuSelect.value = '';
                    addCategorySelect.value = '';
                }
            });
        }

        // Edit Modal
        const editMenuSelect = document.getElementById('editMenuSelect');
        const editCategorySelect = document.getElementById('editCategorySelect');
        const editPageSelect = document.getElementById('editPageSelect');
        const editLinkType = document.getElementById('editLinkType');

        if (editMenuSelect) {
            editMenuSelect.addEventListener('change', function() {
                if (this.value) {
                    editLinkType.value = 'menu';
                    editCategorySelect.value = '';
                    editPageSelect.value = '';
                }
            });
        }

        if (editCategorySelect) {
            editCategorySelect.addEventListener('change', function() {
                if (this.value) {
                    editLinkType.value = 'category';
                    editMenuSelect.value = '';
                    editPageSelect.value = '';
                }
            });
        }

        if (editPageSelect) {
            editPageSelect.addEventListener('change', function() {
                if (this.value) {
                    editLinkType.value = 'page';
                    editMenuSelect.value = '';
                    editCategorySelect.value = '';
                }
            });
        }
    });

    // Social Link Functions
    function editSocialLink(social) {
        document.getElementById('editSocialForm').action = '{{ url("admin/footer/social-links") }}/' + social.id;
        document.getElementById('editSocialPlatform').value = social.platform || '';
        document.getElementById('editSocialUrl').value = social.url || '';
        openModal('editSocialModal');
    }

    // Close modal on outside click
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });
</script>
@endpush
