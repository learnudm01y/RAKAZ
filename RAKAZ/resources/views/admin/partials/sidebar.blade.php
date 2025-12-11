<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <span>ركاز Rakaz</span>
        </a>
        <button class="sidebar-toggle-btn" onclick="toggleSidebarCollapse()" title="طي/فتح القائمة">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg>
        </button>
    </div>

    <nav class="sidebar-menu">
        <!-- Dashboard -->
        <div class="menu-section">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="ar-text">لوحة التحكم</span>
                <span class="en-text">Dashboard</span>
            </a>
        </div>

        <!-- الصفحة الرئيسية -->
        <div class="menu-section">
            <a href="{{ route('home') }}" class="menu-item" target="_blank">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                </svg>
                <span class="ar-text">زيارة الموقع</span>
                <span class="en-text">Visit Website</span>
            </a>
        </div>

        <!-- إدارة المحتوى -->
        <div class="menu-section">
            <div class="menu-dropdown">
                <a href="#" class="menu-item dropdown-toggle {{ request()->routeIs('admin.pages.*') || request()->routeIs('admin.home.*') || request()->routeIs('admin.about.*') || request()->routeIs('admin.privacy.*') || request()->routeIs('admin.contact.*') ? 'active' : '' }}" onclick="toggleDropdown(event, this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="ar-text">الصفحات</span>
                    <span class="en-text">Pages</span>
                    <svg class="dropdown-icon" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('admin.pages.index') }}" class="dropdown-item {{ request()->routeIs('admin.pages.index') ? 'active' : '' }}">
                        <span class="ar-text">جميع الصفحات</span>
                        <span class="en-text">All Pages</span>
                    </a>
                    <a href="{{ route('admin.home.edit') }}" class="dropdown-item {{ request()->routeIs('admin.home.*') ? 'active' : '' }}">
                        <span class="ar-text">الصفحة الرئيسية</span>
                        <span class="en-text">Home Page</span>
                    </a>
                    <a href="{{ route('admin.about.edit') }}" class="dropdown-item {{ request()->routeIs('admin.about.*') ? 'active' : '' }}">
                        <span class="ar-text">صفحة من نحن</span>
                        <span class="en-text">About Page</span>
                    </a>
                    <a href="{{ route('admin.privacy.edit') }}" class="dropdown-item {{ request()->routeIs('admin.privacy.*') ? 'active' : '' }}">
                        <span class="ar-text">سياسة الخصوصية</span>
                        <span class="en-text">Privacy Policy</span>
                    </a>
                    <a href="{{ route('admin.contact.edit') }}" class="dropdown-item {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                        <span class="ar-text">تواصل معنا</span>
                        <span class="en-text">Contact Us</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- إدارة العملاء -->
        <div class="menu-section">
            <div class="menu-title">
                <span class="ar-text">إدارة العملاء</span>
                <span class="en-text">Customers Management</span>
            </div>

            <div class="has-dropdown">
                <a href="#" class="menu-item dropdown-toggle {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" onclick="toggleDropdown(event, this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="ar-text">البريد الإلكتروني</span>
                    <span class="en-text">Contact Messages</span>
                    <svg class="dropdown-icon" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('admin.customers.messages.index') }}" class="dropdown-item {{ request()->routeIs('admin.customers.messages.*') ? 'active' : '' }}">
                        <span class="ar-text">جميع الرسائل</span>
                        <span class="en-text">All Messages</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- إدارة المنتجات -->
        <div class="menu-section">
            <div class="menu-title">
                <span class="ar-text">إدارة المنتجات</span>
                <span class="en-text">Products Management</span>
            </div>

            <div class="has-dropdown">
                <a href="{{ route('admin.products.index') }}" class="menu-item dropdown-toggle {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" onclick="toggleDropdown(event, this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="ar-text">المنتجات</span>
                    <span class="en-text">Products</span>
                    <svg class="dropdown-icon" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('admin.products.index') }}" class="dropdown-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                        <span class="ar-text">جميع المنتجات</span>
                        <span class="en-text">All Products</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="dropdown-item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        <span class="ar-text">إضافة منتج</span>
                        <span class="en-text">Add Product</span>
                    </a>
                </div>
            </div>

            <div class="has-dropdown">
                <a href="{{ route('admin.categories.index') }}" class="menu-item dropdown-toggle {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" onclick="toggleDropdown(event, this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="ar-text">التصنيفات</span>
                    <span class="en-text">Categories</span>
                    <svg class="dropdown-icon" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('admin.categories.index') }}" class="dropdown-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                        <span class="ar-text">جميع التصنيفات</span>
                        <span class="en-text">All Categories</span>
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="dropdown-item {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                        <span class="ar-text">إضافة تصنيف</span>
                        <span class="en-text">Add Category</span>
                    </a>
                </div>
            </div>

            <div class="has-dropdown">
                <a href="{{ route('admin.menus.index') }}" class="menu-item dropdown-toggle {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}" onclick="toggleDropdown(event, this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span class="ar-text">إدارة القوائم</span>
                    <span class="en-text">Menu Management</span>
                    <svg class="dropdown-icon" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('admin.menus.index') }}" class="dropdown-item {{ request()->routeIs('admin.menus.index') ? 'active' : '' }}">
                        <span class="ar-text">جميع القوائم</span>
                        <span class="en-text">All Menus</span>
                    </a>
                    <a href="{{ route('admin.menus.create') }}" class="dropdown-item {{ request()->routeIs('admin.menus.create') ? 'active' : '' }}">
                        <span class="ar-text">إضافة قائمة</span>
                        <span class="en-text">Add Menu</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- إدارة الطلبات -->
        <div class="menu-section">
            <div class="menu-title">
                <span class="ar-text">إدارة الطلبات</span>
                <span class="en-text">Orders Management</span>
            </div>

            <a href="#" class="menu-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span class="ar-text">الطلبات</span>
                <span class="en-text">Orders</span>
            </a>

            <a href="#" class="menu-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="ar-text">المدفوعات</span>
                <span class="en-text">Payments</span>
            </a>
        </div>

        <!-- إدارة المستخدمين -->
        <div class="menu-section">
            <div class="menu-title">
                <span class="ar-text">إدارة المستخدمين</span>
                <span class="en-text">Users Management</span>
            </div>

            <a href="#" class="menu-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="ar-text">العملاء</span>
                <span class="en-text">Customers</span>
            </a>

            <a href="#" class="menu-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="ar-text">المسؤولين</span>
                <span class="en-text">Admins</span>
            </a>
        </div>

        <!-- الإعدادات -->
        <div class="menu-section">
            <div class="menu-title">
                <span class="ar-text">الإعدادات</span>
                <span class="en-text">Settings</span>
            </div>

            <a href="#" class="menu-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="ar-text">إعدادات عامة</span>
                <span class="en-text">General Settings</span>
            </a>
        </div>
    </nav>
</aside>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
