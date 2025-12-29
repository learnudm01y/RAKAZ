<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" data-locale="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('admin.dashboard')) - Rakaz Dashboard</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery - Load FIRST to ensure it's available for all scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS - Load EARLY to prevent Livewire errors -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Initialize toastr globally IMMEDIATELY -->
    <script>
        // Ensure toastr is defined globally to prevent Livewire errors
        window.toastr = window.toastr || {
            success: function(msg) { console.log('Success:', msg); },
            error: function(msg) { console.log('Error:', msg); },
            warning: function(msg) { console.log('Warning:', msg); },
            info: function(msg) { console.log('Info:', msg); }
        };

        // Global showToast wrapper
        window.showToast = function(type, message) {
            if (typeof toastr !== 'undefined' && toastr[type]) {
                toastr[type](message);
            } else {
                console.log(type.toUpperCase() + ': ' + message);
            }
        };

        // Configure toastr
        if (typeof toastr !== 'undefined' && typeof toastr.options !== 'undefined') {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "{{ app()->getLocale() == 'ar' ? 'toast-top-left' : 'toast-top-right' }}",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        }
    </script>

    <!-- Custom Select CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom-select.css') }}">

    <!-- Admin Menu Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/admin-menu.css') }}">

    <!-- Base Styles -->
    <style>
        * { box-sizing: border-box; }

        body { margin: 0; }

        :root {
            --primary-color: #3182ce;
            --secondary-color: #2d3748;
            --accent-color: #48bb78;
            --danger-color: #e53e3e;
            --warning-color: #ed8936;
            --bg-color: #f7fafc;
            --sidebar-bg: #2d3748;
            --text-color: #2d3748;
            --border-color: #e2e8f0;
            --hover-bg: #edf2f7;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Fix for modal close button alignment */
        .modal-header .btn-close {
            padding: calc(var(--bs-modal-header-padding-y) * .5) calc(var(--bs-modal-header-padding-x) * .5);
            margin: 0;
        }

        /* Language Support - Based on data-locale attribute */
        html[data-locale="ar"] .en-text {
            display: none !important;
        }

        html[data-locale="en"] .ar-text {
            display: none !important;
        }

        /* Backward compatibility with dir attribute */
        [dir="rtl"] .en-text {
            display: none !important;
        }

        [dir="ltr"] .ar-text {
            display: none !important;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: transform 0.3s ease, width 0.3s ease;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed .sidebar-logo span,
        .sidebar.collapsed .menu-title,
        .sidebar.collapsed .menu-item span,
        .sidebar.collapsed .sidebar-dropdown-icon {
            display: none;
        }

        .sidebar.collapsed .menu-item {
            justify-content: center;
            align-items: center;
            padding: 0.75rem;
            gap: 0;
        }

        .sidebar.collapsed .menu-item svg {
            margin: 0;
        }

        .sidebar.collapsed .sidebar-dropdown-menu {
            display: none;
        }

        .sidebar.collapsed .menu-dropdown .menu-item {
            justify-content: center;
            align-items: center;
        }

        .sidebar.collapsed .sidebar-header {
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        .sidebar.collapsed .sidebar-toggle-btn {
            margin: 0;
            position: static;
            transform: none;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }

        [dir="rtl"] .sidebar-toggle-btn {
            left: 1.5rem;
        }

        [dir="ltr"] .sidebar-toggle-btn {
            right: 1.5rem;
        }

        .sidebar-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-section {
            margin-bottom: 1.5rem;
        }

        .menu-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 600;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .menu-item.active {
            background: rgba(49, 130, 206, 0.2);
            color: white;
            border-right: 3px solid var(--primary-color);
        }

        [dir="rtl"] .menu-item.active {
            border-right: none;
            border-left: 3px solid var(--primary-color);
        }

        .menu-item svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        /* Sidebar Dropdown Menu Styles */
        .menu-dropdown {
            position: relative;
        }

        .sidebar-dropdown-toggle {
            cursor: pointer;
            position: relative;
            display: grid !important;
            grid-template-columns: 24px 1fr 20px;
            align-items: center;
            gap: 12px;
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .sidebar.collapsed .sidebar-dropdown-toggle {
            display: flex !important;
            justify-content: center;
            align-items: center;
            gap: 0;
            padding: 0.75rem !important;
        }

        .sidebar-dropdown-toggle > svg:first-child {
            grid-column: 1;
            width: 24px;
            height: 24px;
        }

        .sidebar-dropdown-toggle > span {
            grid-column: 2;
            text-align: right;
        }

        [dir="ltr"] .sidebar-dropdown-toggle > span {
            text-align: left;
        }

        .sidebar-dropdown-icon {
            grid-column: 3;
            transition: transform 0.3s ease;
            width: 12px;
            height: 12px;
            flex-shrink: 0;
            transform: rotate(-90deg);
            justify-self: end;
        }

        [dir="rtl"] .sidebar-dropdown-icon {
            transform: rotate(90deg);
        }

        .sidebar-dropdown-toggle.active .sidebar-dropdown-icon {
            transform: rotate(0deg);
        }

        .sidebar-dropdown-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgb(0 0 0 / 60%);
        }

        .sidebar-dropdown-menu.show {
            max-height: 500px;
        }

        .dropdown-item {
            display: block;
            padding: 0.75rem 1.5rem 0.75rem 3.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        [dir="rtl"] .dropdown-item {
            padding: 0.75rem 3.5rem 0.75rem 1.5rem;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .dropdown-item.active {
            background: rgba(49, 130, 206, 0.3);
            color: white;
        }

        /* User Dropdown Styles */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--hover-bg);
            border-radius: 8px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .user-dropdown-toggle:hover {
            background: var(--border-color);
        }

        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        [dir="rtl"] .user-dropdown-menu {
            right: auto;
            left: 0;
        }

        .user-dropdown.active .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.2s;
            border-bottom: 1px solid var(--border-color);
        }

        .user-dropdown-item:last-child {
            border-bottom: none;
        }

        .user-dropdown-item:hover {
            background: var(--hover-bg);
        }

        .user-dropdown-item svg {
            width: 18px;
            height: 18px;
        }

        .user-dropdown-item.danger {
            color: var(--danger-color);
        }

        .user-dropdown-item.danger:hover {
            background: rgba(229, 62, 62, 0.1);
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: 280px;
            transition: margin 0.3s ease;
        }

        [dir="rtl"] .main-content {
            margin-left: 0;
            margin-right: 280px;
        }

        .main-content.expanded {
            margin-left: 70px;
        }

        [dir="rtl"] .main-content.expanded {
            margin-left: 0;
            margin-right: 70px;
        }

        /* Topbar */
        .topbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            color: var(--text-color);
        }

        .menu-toggle svg {
            width: 24px;
            height: 24px;
        }

        .lang-switch {
            background: var(--hover-bg);
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .lang-switch:hover {
            background: var(--border-color);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--hover-bg);
            border-radius: 8px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .logout-btn {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: #c53030;
        }

        /* Content Area */
        .content {
            padding: 1rem !important;
            max-padding: 1rem !important;
        }

        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Responsive */
        /* Tablet (768px - 1024px) */
        @media (max-width: 1024px) and (min-width: 769px) {
            .sidebar-toggle-btn {
                position: static;
                transform: none;
            }

            .sidebar.collapsed .sidebar-toggle-btn {
                position: static;
            }
        }

        /* Mobile & Tablet (max 768px) */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            [dir="rtl"] .sidebar {
                transform: translateX(100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            [dir="rtl"] .main-content {
                margin-right: 0;
            }

            .menu-toggle {
                display: block;
            }

            .user-info span {
                display: none;
            }

            .sidebar-toggle-btn {
                position: static;
                transform: none;
            }

            .sidebar-header {
                justify-content: space-between;
            }
        }

        /* ============================================
           Quill Editor Global Styles - Strong Override
           ============================================ */

        /* Picker Icons */
        .ql-snow .ql-picker:not(.ql-color-picker):not(.ql-icon-picker) svg {
            position: absolute !important;
            margin-top: -9px !important;
            top: 50% !important;
            width: 18px !important;
        }

        /* RTL: Arabic - SVG on right */
        [dir="rtl"] .ql-snow .ql-picker:not(.ql-color-picker):not(.ql-icon-picker) svg {
            right: 66px !important;
            left: auto !important;
        }

        /* LTR: English - SVG on left */
        [dir="ltr"] .ql-snow .ql-picker:not(.ql-color-picker):not(.ql-icon-picker) svg {
            left: 56px !important;
            right: auto !important;
        }

        /* ============================================
           List Bullets Fix - Strong Override for RTL
           ============================================ */

        /* RTL Lists - Bullets/Numbers Positioning */
        [dir="rtl"] .ql-editor ol,
        [dir="rtl"] .ql-editor ul {
            padding-right: 1.5em !important;
            padding-left: 0 !important;
        }

        /* Unordered Lists - Bullet Spacing */
        [dir="rtl"] .ql-editor ul li:not(.ql-direction-rtl)::before {
            content: "•" !important;
        }

        /* RTL List Items - Before Pseudo Element (Bullets/Numbers) */
        [dir="rtl"] .ql-editor li:not(.ql-direction-rtl)::before {
            margin-left: -0.5em !important;
            margin-right: -1.5em !important;
            text-align: right !important;
            display: inline-block !important;
            white-space: nowrap !important;
            width: 1.2em !important;
        }

        /* LTR Lists */
        [dir="ltr"] .ql-editor ol,
        [dir="ltr"] .ql-editor ul {
            padding-left: 1.5em !important;
            padding-right: 0 !important;
        }

        [dir="ltr"] .ql-editor li::before {
            margin-right: 0.5em !important;
            margin-left: -1.5em !important;
            text-align: left !important;
            display: inline-block !important;
            white-space: nowrap !important;
            width: 1.2em !important;
        }

        /* General List Item Spacing - Prevent Text Sticking to Bullets */
        .ql-editor li {
            padding-right: 0.3em !important;
            padding-left: 0.3em !important;
        }

        [dir="rtl"] .ql-editor li {
            padding-right: 0.5em !important;
        }

        [dir="ltr"] .ql-editor li {
            padding-left: 0.5em !important;
        }

        /* Counter Reset for Nested Lists */
        .ql-editor ol {
            counter-reset: list-0 list-1 list-2 list-3 list-4 list-5 list-6 list-7 list-8 list-9 !important;
        }

        .ql-editor li {
            counter-reset: list-1 list-2 list-3 list-4 list-5 list-6 list-7 list-8 list-9 !important;
        }

        /* Preview Toggle Button Positioning */
        .preview-toggle-btn {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 0.75rem;
            cursor: pointer;
            z-index: 9998;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* RTL: Arabic - Button on left */
        [dir="rtl"] .preview-toggle-btn {
            left: 0;
            right: auto;
            border-radius: 0 8px 8px 0;
        }

        [dir="rtl"] .preview-toggle-btn:hover {
            padding-right: 1rem;
            padding-left: 0.75rem;
        }

        /* LTR: English - Button on right */
        [dir="ltr"] .preview-toggle-btn {
            right: 0;
            left: auto;
            border-radius: 8px 0 0 8px;
        }

        [dir="ltr"] .preview-toggle-btn:hover {
            padding-left: 1rem;
            padding-right: 0.75rem;
        }

        .preview-toggle-btn:hover {
            background: #2c5aa0;
        }

        .preview-toggle-btn svg {
            width: 24px;
            height: 24px;
        }
    </style>

    <!-- Page Specific Styles -->
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Include Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 style="font-size: 1.25rem; font-weight: 600;">
                        @hasSection('page-title')
                            @yield('page-title')
                        @else
                            <span class="ar-text">لوحة التحكم</span>
                            <span class="en-text">Dashboard</span>
                        @endif
                    </h1>
                </div>

                <div class="topbar-right">
                    <div class="user-dropdown" id="userDropdown">
                        <button class="user-dropdown-toggle" onclick="toggleUserDropdown()">
                            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="user-dropdown-menu">
                            <a href="#" class="user-dropdown-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="ar-text">الملف الشخصي</span>
                                <span class="en-text">Profile</span>
                            </a>
                            <a href="#" class="user-dropdown-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="ar-text">الإعدادات</span>
                                <span class="en-text">Settings</span>
                            </a>
                            <button class="user-dropdown-item" onclick="toggleLanguage()" style="width: 100%; text-align: inherit; background: none; border: none; cursor: pointer;" title="Change dashboard interface language">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                                </svg>
                                <span style="display: flex; flex-direction: column; align-items: start; gap: 2px;">
                                    <span>
                                        <span class="ar-text"> English</span>
                                        <span class="en-text"> عربي</span>
                                    </span>
                                    <span style="font-size: 10px; opacity: 0.7; font-weight: normal;">
                                        <span class="ar-text">لغة الواجهة</span>
                                        <span class="en-text">Interface Lang</span>
                                    </span>
                                </span>
                            </button>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" class="user-dropdown-item danger" style="width: 100%; text-align: inherit; background: none; border: none; cursor: pointer;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span class="ar-text">تسجيل الخروج</span>
                                    <span class="en-text">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Base Scripts -->
    <script>
        function toggleSidebarCollapse() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // Save state
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        function toggleDropdown(event, element) {
            event.preventDefault();
            const dropdown = element.nextElementSibling;
            const isOpen = dropdown.classList.contains('show');

            // Close all sidebar dropdowns
            document.querySelectorAll('.sidebar-dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
            document.querySelectorAll('.sidebar-dropdown-toggle').forEach(toggle => {
                toggle.classList.remove('active');
            });

            // Toggle current dropdown
            if (!isOpen) {
                dropdown.classList.add('show');
                element.classList.add('active');
            }
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('active');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.user-dropdown')) {
                document.querySelectorAll('.user-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        });

        function toggleLanguage() {
            const html = document.documentElement;
            const currentLang = html.getAttribute('lang');
            const newLang = currentLang === 'ar' ? 'en' : 'ar';
            const newDir = newLang === 'ar' ? 'rtl' : 'ltr';

            // Save to localStorage
            localStorage.setItem('language', newLang);
            localStorage.setItem('direction', newDir);

            // Send request to server to change locale
            fetch('/locale/' + newLang, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                // Reload page to apply language changes
                window.location.reload();
            });
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Language and direction are set by Laravel server-side
            // No need to manipulate from JavaScript on page load

            // Restore sidebar state
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.querySelector('.main-content');
                if (sidebar && mainContent) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                }
            }
        });
    </script>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Select JS -->
    <script src="{{ asset('assets/js/custom-select.js') }}"></script>

    <script>
        // Display session messages
        @if(session('success'))
            showToast('success', "{{ session('success') }}");
        @endif

        @if(session('error'))
            showToast('error', "{{ session('error') }}");
        @endif

        @if(session('info'))
            showToast('info', "{{ session('info') }}");
        @endif

        @if(session('warning'))
            showToast('warning', "{{ session('warning') }}");
        @endif

        // Initialize Custom Select for all select elements
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof CustomSelect !== 'undefined') {
                document.querySelectorAll('.form-select, select').forEach(select => {
                    if (!select.classList.contains('no-custom-select') &&
                        !select.parentElement.classList.contains('custom-select-wrapper')) {
                        new CustomSelect(select);
                    }
                });
            }
        });
    </script>

    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>
</html>
