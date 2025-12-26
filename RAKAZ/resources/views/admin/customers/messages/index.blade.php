@extends('admin.layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'رسائل العملاء' : 'Customer Messages')

@push('styles')
<style>
    .messages-table thead th {
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

    .messages-table thead th svg {
        margin-inline-end: 8px;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<style>
    .messages-page {
        padding: 24px;
    }

    .messages-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .messages-header h1 {
        font-size: 28px;
        font-weight: 600;
        color: #1a202c;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .messages-header h1 i {
        color: #3182ce;
    }

    .header-controls {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(170px, 220px);
        gap: 12px;
        align-items: center;
        min-width: 0;
        flex: 1 1 900px;
        width: 100%;
        max-width: 1400px;
    }

    .search-group {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        width: 100%;
        max-width: none;
        flex-wrap: nowrap;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .stat-card-content {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .stat-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-card.new .stat-card-icon {
        background: #dbeafe;
        color: #1e40af;
    }

    .stat-card.read .stat-card-icon {
        background: #dcfce7;
        color: #15803d;
    }

    .stat-card.replied .stat-card-icon {
        background: #fef3c7;
        color: #a16207;
    }

    .stat-card.archived .stat-card-icon {
        background: #f3f4f6;
        color: #6b7280;
    }

    .stat-card-info {
        flex: 1;
    }

    .stat-card-title {
        font-size: 15px;
        color: #64748b;
        font-weight: 500;
        white-space: nowrap;
    }

    .stat-card-value {
        font-size: 32px;
        font-weight: 700;
        color: #1a202c;
        text-align: end;
    }

    /* RTL Support for stats */
    [dir="rtl"] .stat-card-value {
        text-align: start;
    }

    [dir="ltr"] .stat-card-value {
        text-align: end;
    }

    .filter-select {
        padding: 10px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        font-size: 14px;
        color: #4a5568;
        cursor: pointer;
        transition: all 0.2s;
        min-width: 160px;
    }

    /* The status filter is upgraded by JS into .custom-select-wrapper.
       Force it to stay compact and not take the full row width. */
    .header-controls .custom-select-wrapper {
        justify-self: end;
        width: 210px !important;
        min-width: 170px;
        max-width: 240px;
        flex: 0 0 auto;
    }

    .header-controls .custom-select-trigger {
        height: 46px;
        display: flex;
        align-items: center;
    }

    .header-controls .custom-select-options {
        width: 100% !important;
        max-width: 240px;
    }

    .search-input {
        padding: 12px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        background: white;
        font-size: 15px;
        color: #4a5568;
        transition: all 0.2s;
        min-width: 0;
        width: 100%;
        height: 46px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        flex: 1 1 auto;
    }

    /* Stack filters only on narrow screens */
    @media (max-width: 720px) {
        .header-controls {
            grid-template-columns: 1fr;
        }

        .header-controls .custom-select-wrapper {
            justify-self: stretch;
            width: 100% !important;
            max-width: none;
        }

        .header-controls .custom-select-options {
            max-width: none;
        }
    }

    .search-input:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .search-input:focus {
        outline: none;
        border-color: #3182ce;
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.12);
    }

    .search-btn {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        font-size: 14px;
        color: #1a202c;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 42px;
        flex-shrink: 0;
    }

    .search-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .filter-select:focus {
        outline: none;
        border-color: #3182ce;
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
    }

    .messages-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .messages-table {
        width: 100%;
        border-collapse: collapse;
    }

    .messages-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s;
    }

    .messages-table tbody tr:hover {
        background: #f8fafc;
    }

    .messages-table tbody tr.unread-row {
        background: #eff6ff;
        border-left: 3px solid #3182ce;
    }

    .messages-table tbody tr.unread-row:hover {
        background: #dbeafe;
    }

    .messages-table td {
        padding: 16px 20px;
        font-size: 14px;
        color: #2d3748;
    }

    .message-id {
        font-weight: 600;
        color: #718096;
    }

    .message-name {
        font-weight: 500;
        color: #1a202c;
    }

    .message-email {
        color: #4a5568;
        font-size: 13px;
    }

    .message-subject {
        color: #2d3748;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
    }

    .status-badge.new {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-badge.read {
        background: #dcfce7;
        color: #15803d;
    }

    .status-badge.replied {
        background: #fef3c7;
        color: #a16207;
    }

    .status-badge.archived {
        background: #f3f4f6;
        color: #6b7280;
    }

    .status-select-dropdown {
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        background: white;
        transition: all 0.2s;
    }

    .status-select-dropdown:focus {
        outline: none;
        border-color: #3182ce;
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
    }

    .message-date {
        color: #718096;
        font-size: 13px;
        white-space: nowrap;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 8px 14px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-view {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-view:hover {
        background: #bfdbfe;
        transform: translateY(-1px);
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #fecaca;
        transform: translateY(-1px);
    }

    .empty-state {
        padding: 80px 20px;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 64px;
        color: #cbd5e0;
        margin-bottom: 16px;
    }

    .empty-state-text {
        font-size: 18px;
        color: #718096;
        margin: 0;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: white;
        border-top: 2px solid #f0f0f0;
        margin-top: 0;
    }

    [dir="rtl"] .pagination-wrapper {
        flex-direction: row;
    }

    .pagination-wrapper .pagination-info {
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
    }

    html[lang="ar"] .en-text,
    html[data-locale="ar"] .en-text,
    [dir="rtl"] .en-text {
        display: none !important;
    }

    html[lang="en"] .ar-text,
    html[data-locale="en"] .ar-text,
    [dir="ltr"] .ar-text {
        display: none !important;
    }

    .pagination-links {
        display: flex;
        align-items: center;
    }

    .pagination-links nav > div:first-child {
        display: none !important;
    }

    .pagination-links nav {
        display: block !important;
    }

    .pagination-links .d-none.flex-sm-fill div:first-child,
    .pagination-links .small.text-muted,
    .pagination-links p.small {
        display: none !important;
    }

    .pagination-links nav.d-flex {
        display: flex !important;
        justify-content: flex-end !important;
    }

    .pagination-links nav.d-flex > div:last-child {
        display: flex !important;
    }

    .pagination-wrapper .pagination {
        display: flex;
        gap: 6px;
        margin: 0;
        list-style: none;
        padding: 0;
    }

    [dir="rtl"] .pagination-wrapper .pagination {
        flex-direction: row-reverse;
    }

    .pagination .page-item {
        margin: 0;
    }

    .pagination-wrapper .page-link {
        padding: 8px 14px;
        border: 2px solid #e5e7eb;
        background: white;
        color: #374151;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s ease;
        min-width: 40px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pagination .page-link:hover {
        background: #1a1a1a;
        border-color: #1a1a1a;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .pagination .page-item.active .page-link {
        background: #1a1a1a;
        border-color: #1a1a1a;
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(26, 26, 26, 0.2);
    }

    .pagination .page-item.disabled .page-link {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination .page-item.disabled .page-link:hover {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        transform: none;
        box-shadow: none;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        overflow-y: auto;
        padding: 20px;
    }

    /* SweetAlert z-index override */
    .swal2-container {
        z-index: 10000 !important;
    }

    .swal2-popup {
        z-index: 10001 !important;
    }

    .modal.show {
        display: block;
    }

    .modal-dialog {
        background: white;
        border-radius: 3px;
        max-width: 800px;
        width: 100%;
        margin: 40px auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-content {
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 80px);
    }

    .modal-header {
        padding: 24px 30px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #1a202c;
        border-radius: 3px 3px 0 0;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 600;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-title i {
        font-size: 24px;
    }

    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        font-size: 24px;
        color: white;
        cursor: pointer;
        padding: 0;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 0;
        overflow-y: auto;
        flex: 1;
        max-height: calc(100vh - 240px);
    }

    .modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .message-header-section {
        background: #f8fafc;
        padding: 30px;
        border-bottom: 1px solid #e2e8f0;
    }

    .sender-info {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
    }

    .sender-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #1a202c;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .sender-details {
        flex: 1;
    }

    .sender-name {
        font-size: 22px;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 5px;
    }

    .sender-email {
        font-size: 14px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sender-email i {
        color: #1a202c;
    }

    .message-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: white;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .meta-icon {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .meta-icon.phone {
        background: #f1f5f9;
        color: #1a202c;
    }

    .meta-icon.calendar {
        background: #f1f5f9;
        color: #1a202c;
    }

    .meta-icon.tag {
        background: #f1f5f9;
        color: #1a202c;
    }

    .meta-content {
        flex: 1;
    }

    .meta-label {
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .meta-value {
        font-size: 14px;
        color: #1a202c;
        font-weight: 500;
    }

    .message-subject-section {
        padding: 25px 30px;
        background: white;
        border-bottom: 1px solid #e2e8f0;
    }

    .subject-label {
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .subject-label i {
        color: #1a202c;
    }

    .subject-text {
        font-size: 18px;
        color: #1a202c;
        font-weight: 600;
        line-height: 1.5;
    }

    .message-content-section {
        padding: 30px;
        background: white;
    }

    .content-label {
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .content-label i {
        color: #1a202c;
    }

    .message-text {
        font-size: 15px;
        color: #1a202c;
        line-height: 1.8;
        white-space: pre-wrap;
        padding: 20px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        min-height: 120px;
    }

    .modal-footer {
        padding: 20px 30px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
        border-radius: 0 0 3px 3px;
    }

    .footer-actions {
        display: flex;
        gap: 10px;
    }

    .btn-reply {
        padding: 10px 24px;
        background: #1a202c;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-reply:hover {
        background: #2d3748;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    .btn-close-modal {
        padding: 10px 24px;
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-close-modal:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #1a202c;
    }

    /* RTL Support */
    /* RTL Support */
    [dir="rtl"] .messages-table th,
    [dir="rtl"] .messages-table td {
        text-align: start;
    }

    [dir="rtl"] .action-buttons {
        flex-direction: row-reverse;
    }

    /* Responsive */
    @media (max-width: 540px) {
        .messages-header {
            flex-direction: column;
            align-items: stretch;
        }

        .stats-cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .messages-table {
            font-size: 13px;
        }

        .messages-table th,
        .messages-table td {
            padding: 12px;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="messages-page">
    <div class="messages-header">
        <h1>
            <i class="fas fa-envelope"></i>
            <span class="ar-text">رسائل العملاء</span>
            <span class="en-text">Customer Messages</span>
        </h1>
        <form class="header-controls" method="GET" action="{{ route('admin.customers.messages.index') }}" id="messagesFiltersForm">
            <div class="search-group">
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    value="{{ request('search') }}"
                    placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث بالاسم أو الإيميل...' : 'Search by name or email...' }}"
                />

                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                    <span class="ar-text">بحث</span>
                    <span class="en-text">Search</span>
                </button>
            </div>

            <select id="statusFilter" name="status" class="filter-select">
                <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>
                    {{ app()->getLocale() == 'ar' ? 'جميع الحالات' : 'All Status' }}
                </option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? '🔵 جديد' : '🔵 New' }}</option>
                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? '✅ مقروء' : '✅ Read' }}</option>
                <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? '💬 تم الرد' : '💬 Replied' }}</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? '📦 مؤرشف' : '📦 Archived' }}</option>
            </select>
        </form>
    </div>

    <div class="stats-cards">
        <div class="stat-card new">
            <div class="stat-card-content">
                <div class="stat-card-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-title">
                        <span class="ar-text">رسائل جديدة</span>
                        <span class="en-text">New Messages</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-value">{{ $newCount ?? 0 }}</div>
        </div>

        <div class="stat-card read">
            <div class="stat-card-content">
                <div class="stat-card-icon">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-title">
                        <span class="ar-text">مقروءة</span>
                        <span class="en-text">Read</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-value">{{ $readCount ?? 0 }}</div>
        </div>

        <div class="stat-card replied">
            <div class="stat-card-content">
                <div class="stat-card-icon">
                    <i class="fas fa-reply"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-title">
                        <span class="ar-text">تم الرد</span>
                        <span class="en-text">Replied</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-value">{{ $repliedCount ?? 0 }}</div>
        </div>

        <div class="stat-card archived">
            <div class="stat-card-content">
                <div class="stat-card-icon">
                    <i class="fas fa-archive"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-title">
                        <span class="ar-text">مؤرشفة</span>
                        <span class="en-text">Archived</span>
                    </div>
                </div>
            </div>
            <div class="stat-card-value">{{ $archivedCount ?? 0 }}</div>
        </div>
    </div>

    <div class="messages-card">
        @if($messages->count() > 0)
        <table class="messages-table">
                <thead>
                    <tr>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            <span class="ar-text">رقم</span>
                            <span class="en-text">ID</span>
                        </th>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="ar-text">الاسم</span>
                            <span class="en-text">Name</span>
                        </th>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="ar-text">البريد الإلكتروني</span>
                            <span class="en-text">Email</span>
                        </th>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="ar-text">الموضوع</span>
                            <span class="en-text">Subject</span>
                        </th>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="ar-text">الحالة</span>
                            <span class="en-text">Status</span>
                        </th>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="ar-text">التاريخ</span>
                            <span class="en-text">Date</span>
                        </th>
                        <th>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-inline-end: 8px; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                            <span class="ar-text">الإجراءات</span>
                            <span class="en-text">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                    <tr class="{{ $message->status === 'new' ? 'unread-row' : '' }}">
                        <td><span class="message-id">#{{ $message->id }}</span></td>
                        <td><span class="message-name">{{ $message->full_name }}</span></td>
                        <td><span class="message-email">{{ $message->email }}</span></td>
                        <td><span class="message-subject">{{ Str::limit($message->subject, 50) }}</span></td>
                        <td>
                            <select class="status-select-dropdown" data-id="{{ $message->id }}">
                                <option value="new" {{ $message->status === 'new' ? 'selected' : '' }}>
                                    @if(app()->getLocale() == 'ar') جديد @else New @endif
                                </option>
                                <option value="read" {{ $message->status === 'read' ? 'selected' : '' }}>
                                    @if(app()->getLocale() == 'ar') مقروء @else Read @endif
                                </option>
                                <option value="replied" {{ $message->status === 'replied' ? 'selected' : '' }}>
                                    @if(app()->getLocale() == 'ar') تم الرد @else Replied @endif
                                </option>
                                <option value="archived" {{ $message->status === 'archived' ? 'selected' : '' }}>
                                    @if(app()->getLocale() == 'ar') مؤرشف @else Archived @endif
                                </option>
                            </select>
                        </td>
                        <td><span class="message-date">{{ $message->created_at->format('Y-m-d H:i') }}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-view view-message" data-id="{{ $message->id }}">
                                    <i class="fas fa-eye"></i>
                                    <span class="ar-text">عرض</span>
                                    <span class="en-text">View</span>
                                </button>
                                <button class="btn-action btn-delete delete-message" data-id="{{ $message->id }}">
                                    <i class="fas fa-trash"></i>
                                    <span class="ar-text">حذف</span>
                                    <span class="en-text">Delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-wrapper">
                <div class="pagination-info">
                    <span class="ar-text">عرض {{ $messages->firstItem() }} إلى {{ $messages->lastItem() }} من إجمالي {{ $messages->total() }} رسالة</span>
                    <span class="en-text">Showing {{ $messages->firstItem() }} to {{ $messages->lastItem() }} of {{ $messages->total() }} total messages</span>
                </div>
                <div class="pagination-links">
                    {{ $messages->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <p class="empty-state-text">
                <span class="ar-text">لا توجد رسائل</span>
                <span class="en-text">No messages found</span>
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Modal -->
<div class="modal" id="messageModal" onclick="if(event.target === this) closeModal()">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-envelope-open"></i>
                    <span class="ar-text">رسالة من العميل</span>
                    <span class="en-text">Customer Message</span>
                </h5>
                <button type="button" class="modal-close" onclick="closeModal()">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Sender Info Section -->
                <div class="message-header-section">
                    <div class="sender-info">
                        <div class="sender-avatar" id="modal-avatar">
                            A
                        </div>
                        <div class="sender-details">
                            <div class="sender-name" id="modal-name"></div>
                            <div class="sender-email">
                                <i class="fas fa-envelope"></i>
                                <span id="modal-email"></span>
                            </div>
                        </div>
                    </div>

                    <div class="message-meta">
                        <div class="meta-item">
                            <div class="meta-icon phone">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">
                                    <span class="ar-text">الهاتف</span>
                                    <span class="en-text">Phone</span>
                                </div>
                                <div class="meta-value" id="modal-phone"></div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon calendar">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">
                                    <span class="ar-text">التاريخ</span>
                                    <span class="en-text">Date</span>
                                </div>
                                <div class="meta-value" id="modal-date"></div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon tag">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">
                                    <span class="ar-text">الحالة</span>
                                    <span class="en-text">Status</span>
                                </div>
                                <div class="meta-value" id="modal-status"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subject Section -->
                <div class="message-subject-section">
                    <div class="subject-label">
                        <i class="fas fa-bookmark"></i>
                        <span class="ar-text">الموضوع</span>
                        <span class="en-text">Subject</span>
                    </div>
                    <div class="subject-text" id="modal-subject"></div>
                </div>

                <!-- Message Content Section -->
                <div class="message-content-section">
                    <div class="content-label">
                        <i class="fas fa-comment-dots"></i>
                        <span class="ar-text">محتوى الرسالة</span>
                        <span class="en-text">Message Content</span>
                    </div>
                    <div class="message-text" id="modal-message"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-actions">
                    <button type="button" class="btn-reply" onclick="showReplyForm()">
                        <i class="fas fa-reply"></i>
                        <span class="ar-text">رد على الرسالة</span>
                        <span class="en-text">Reply</span>
                    </button>
                </div>
                <button type="button" class="btn-close-modal" onclick="closeModal()">
                    <span class="ar-text">إغلاق</span>
                    <span class="en-text">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal" id="replyModal" onclick="if(event.target === this) closeReplyModal()">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-paper-plane"></i>
                    <span class="ar-text">إرسال رد</span>
                    <span class="en-text">Send Reply</span>
                </h5>
                <button type="button" class="modal-close" onclick="closeReplyModal()">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="replyForm">
                    <input type="hidden" id="reply-message-id">

                    <div class="form-group-modal">
                        <label class="form-label-modal">
                            <i class="fas fa-user"></i>
                            <span class="ar-text">إلى</span>
                            <span class="en-text">To</span>
                        </label>
                        <div class="recipient-info" id="reply-recipient"></div>
                    </div>

                    <div class="form-group-modal">
                        <label class="form-label-modal">
                            <i class="fas fa-bookmark"></i>
                            <span class="ar-text">الموضوع</span>
                            <span class="en-text">Subject</span>
                        </label>
                        <div class="recipient-info" id="reply-subject"></div>
                    </div>

                    <div class="form-group-modal">
                        <label class="form-label-modal">
                            <i class="fas fa-comment-dots"></i>
                            <span class="ar-text">رسالتك</span>
                            <span class="en-text">Your Reply</span>
                        </label>
                        <textarea
                            id="reply-message"
                            name="reply_message"
                            rows="8"
                            class="form-textarea-modal"
                            placeholder="اكتب ردك هنا... / Write your reply here..."
                            required
                        ></textarea>
                        <div class="char-counter">
                            <span id="char-count">0</span>
                            <span class="ar-text">حرف</span>
                            <span class="en-text">characters</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-reply" onclick="submitReply()">
                    <i class="fas fa-paper-plane"></i>
                    <span class="ar-text">إرسال الرد</span>
                    <span class="en-text">Send Reply</span>
                </button>
                <button type="button" class="btn-close-modal" onclick="closeReplyModal()">
                    <span class="ar-text">إلغاء</span>
                    <span class="en-text">Cancel</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .form-group-modal {
        margin-bottom: 24px;
    }

    .form-label-modal {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-label-modal i {
        color: #1a202c;
    }

    .recipient-info {
        padding: 12px 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        color: #1a202c;
        font-size: 15px;
    }

    .form-textarea-modal {
        width: 100%;
        padding: 16px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 15px;
        font-family: inherit;
        line-height: 1.6;
        resize: vertical;
        transition: all 0.2s;
    }

    .form-textarea-modal:focus {
        outline: none;
        border-color: #1a202c;
        box-shadow: 0 0 0 3px rgba(26, 32, 44, 0.1);
    }

    .char-counter {
        text-align: end;
        font-size: 12px;
        color: #64748b;
        margin-top: 8px;
    }

    .char-counter span#char-count {
        font-weight: 600;
        color: #1a202c;
    }
</style>

@push('scripts')
<script>
let currentMessageData = {};

function closeModal() {
    $('#messageModal').removeClass('show');
}

function closeReplyModal() {
    $('#replyModal').removeClass('show');
    $('#replyForm')[0].reset();
    $('#char-count').text('0');
}

function showReplyForm() {
    // Check if message data is available
    if (!currentMessageData || !currentMessageData.id) {
        Swal.fire({
            icon: 'error',
            title: $('[data-locale="ar"]').length ? 'خطأ' : 'Error',
            text: $('[data-locale="ar"]').length ? 'لم يتم تحميل بيانات الرسالة' : 'Message data not loaded'
        });
        return;
    }

    $('#replyModal').addClass('show');
    $('#reply-message-id').val(currentMessageData.id);
    $('#reply-recipient').text(`${currentMessageData.name} <${currentMessageData.email}>`);
    $('#reply-subject').text(`Re: ${currentMessageData.subject}`);
    $('#reply-message').focus();
}

function submitReply() {
    const messageId = $('#reply-message-id').val();
    const replyMessage = $('#reply-message').val().trim();

    if (!replyMessage || replyMessage.length < 10) {
        Swal.fire({
            icon: 'warning',
            title: $('[data-locale="ar"]').length ? 'تنبيه' : 'Warning',
            text: $('[data-locale="ar"]').length ? 'يجب أن تكون الرسالة 10 أحرف على الأقل' : 'Reply message must be at least 10 characters'
        });
        return;
    }

    // Show loading
    Swal.fire({
        title: $('[data-locale="ar"]').length ? 'جاري الإرسال...' : 'Sending...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.post(`/admin/customers/messages/${messageId}/reply`, {
        _token: '{{ csrf_token() }}',
        reply_message: replyMessage
    }).done(function(response) {
        closeReplyModal();
        closeModal();

        Swal.fire({
            icon: 'success',
            title: $('[data-locale="ar"]').length ? 'تم الإرسال' : 'Sent',
            text: response.message,
            timer: 2000,
            showConfirmButton: false
        });

        // Reload to update status
        setTimeout(() => location.reload(), 2000);

    }).fail(function(xhr) {
        Swal.fire({
            icon: 'error',
            title: $('[data-locale="ar"]').length ? 'خطأ' : 'Error',
            text: xhr.responseJSON?.message || ($('[data-locale="ar"]').length ? 'فشل في إرسال الرد' : 'Failed to send reply')
        });
    });
}

// Character counter
$('#reply-message').on('input', function() {
    $('#char-count').text($(this).val().length);
});

// Get status text in current language
function getStatusText(status) {
    const isArabic = $('[data-locale="ar"]').length > 0;
    const statusTexts = {
        'new': isArabic ? 'جديد' : 'New',
        'read': isArabic ? 'مقروء' : 'Read',
        'replied': isArabic ? 'تم الرد' : 'Replied',
        'archived': isArabic ? 'مؤرشف' : 'Archived'
    };
    return statusTexts[status] || status;
}

// Get initials from name
function getInitials(name) {
    if (!name || typeof name !== 'string') return 'NA';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
}

$(document).ready(function() {
    // View message
    $('.view-message').click(function() {
        const id = $(this).data('id');

        $.get(`/admin/customers/messages/${id}`, function(response) {
            const msg = response.message;

            console.log('Message Data:', msg); // Debug log

            // Store current message data
            currentMessageData = {
                id: msg.id,
                name: msg.full_name,
                email: msg.email,
                subject: msg.subject
            };

            console.log('Stored currentMessageData:', currentMessageData); // Debug log

            // Set avatar with initials
            $('#modal-avatar').text(getInitials(msg.full_name));

            // Set sender info
            $('#modal-name').text(msg.full_name);
            $('#modal-email').text(msg.email);
            $('#modal-phone').text(msg.phone || 'N/A');
            $('#modal-subject').text(msg.subject);
            $('#modal-message').text(msg.message);
            $('#modal-date').text(new Date(msg.created_at).toLocaleString());
            $('#modal-status').text(getStatusText(msg.status));

            $('#messageModal').addClass('show');

            // Update row to mark as read
            $(`.view-message[data-id="${id}"]`).closest('tr').removeClass('unread-row');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: $('[data-locale="ar"]').length ? 'خطأ' : 'Error',
                text: $('[data-locale="ar"]').length ? 'فشل في تحميل الرسالة' : 'Failed to load message'
            });
        });
    });

    // Change status
    $('.status-select-dropdown').change(function() {
        const id = $(this).data('id');
        const status = $(this).val();

        $.post(`/admin/customers/messages/${id}/status`, {
            _token: '{{ csrf_token() }}',
            status: status
        }).done(function() {
            Swal.fire({
                icon: 'success',
                title: $('[data-locale="ar"]').length ? 'تم التحديث' : 'Updated',
                text: $('[data-locale="ar"]').length ? 'تم تحديث حالة الرسالة بنجاح' : 'Message status updated successfully',
                timer: 1500,
                showConfirmButton: false
            });

            // Reload to update stats
            setTimeout(() => location.reload(), 1500);
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: $('[data-locale="ar"]').length ? 'خطأ' : 'Error',
                text: $('[data-locale="ar"]').length ? 'فشل في تحديث الحالة' : 'Failed to update status'
            });
        });
    });

    // Delete message
    $('.delete-message').click(function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: $('[data-locale="ar"]').length ? 'هل أنت متأكد؟' : 'Are you sure?',
            text: $('[data-locale="ar"]').length ? 'لن تتمكن من استرجاع هذه الرسالة' : 'You will not be able to recover this message',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#718096',
            confirmButtonText: $('[data-locale="ar"]').length ? 'نعم، احذفها' : 'Yes, delete it',
            cancelButtonText: $('[data-locale="ar"]').length ? 'إلغاء' : 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/customers/messages/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                }).done(function() {
                    row.fadeOut(function() {
                        $(this).remove();
                    });

                    Swal.fire({
                        icon: 'success',
                        title: $('[data-locale="ar"]').length ? 'تم الحذف' : 'Deleted',
                        text: $('[data-locale="ar"]').length ? 'تم حذف الرسالة بنجاح' : 'Message deleted successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Reload to update stats
                    setTimeout(() => location.reload(), 1500);
                }).fail(function() {
                    Swal.fire({
                        icon: 'error',
                        title: $('[data-locale="ar"]').length ? 'خطأ' : 'Error',
                        text: $('[data-locale="ar"]').length ? 'فشل في حذف الرسالة' : 'Failed to delete message'
                    });
                });
            }
        });
    });

    // Filter by status (keep search + other params via GET form)
    $('#statusFilter').change(function() {
        $('#messagesFiltersForm').submit();
    });
});
</script>
@endpush
@endsection
