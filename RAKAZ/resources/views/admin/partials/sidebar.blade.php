<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <span class="ar-text">ركاز</span>
            <span class="en-text">Rakaz</span>
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
                <a href="#" class="menu-item sidebar-dropdown-toggle {{ request()->routeIs('admin.pages.*') || request()->routeIs('admin.home.*') || request()->routeIs('admin.about.*') || request()->routeIs('admin.privacy.*') || request()->routeIs('admin.contact.*') || request()->routeIs('admin.perfect-gift-section.*') || request()->routeIs('admin.featured-section.*') || request()->routeIs('admin.footer.*') ? 'active' : '' }}" onclick="toggleDropdown(event, this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="ar-text">الصفحات</span>
                    <span class="en-text">Pages</span>
                    <svg class="sidebar-dropdown-icon" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <div class="sidebar-dropdown-menu">
                    <a href="{{ route('admin.pages.index') }}" class="dropdown-item {{ request()->routeIs('admin.pages.index') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <span class="ar-text">جميع الصفحات</span>
                        <span class="en-text">All Pages</span>
                    </a>
                    <a href="{{ route('admin.home.edit') }}" class="dropdown-item {{ request()->routeIs('admin.home.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="ar-text">الصفحة الرئيسية</span>
                        <span class="en-text">Home Page</span>
                    </a>
                    <a href="{{ route('admin.about.edit') }}" class="dropdown-item {{ request()->routeIs('admin.about.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ar-text">صفحة من نحن</span>
                        <span class="en-text">About Page</span>
                    </a>
                    <a href="{{ route('admin.privacy.edit') }}" class="dropdown-item {{ request()->routeIs('admin.privacy.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span class="ar-text">سياسة الخصوصية</span>
                        <span class="en-text">Privacy Policy</span>
                    </a>
                    <a href="{{ route('admin.contact.edit') }}" class="dropdown-item {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="ar-text">تواصل معنا</span>
                        <span class="en-text">Contact Us</span>
                    </a>
                    <a href="{{ route('admin.featured-section.index') }}" class="dropdown-item {{ request()->routeIs('admin.featured-section.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <span class="ar-text">المنتجات المميزة</span>
                        <span class="en-text">Featured Products</span>
                    </a>
                    <a href="{{ route('admin.perfect-gift-section.index') }}" class="dropdown-item {{ request()->routeIs('admin.perfect-gift-section.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                        <span class="ar-text">الهدية المثالية</span>
                        <span class="en-text">Perfect Gift</span>
                    </a>
                    <a href="{{ route('admin.footer.index') }}" class="dropdown-item {{ request()->routeIs('admin.footer.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        <span class="ar-text">إدارة الفوتر</span>
                        <span class="en-text">Footer Management</span>
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
                <a href="#" class="menu-item sidebar-dropdown-toggle {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" onclick="toggleDropdown(event, this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="ar-text">البريد الإلكتروني</span>
                    <span class="en-text">Contact Messages</span>
                    <svg class="sidebar-dropdown-icon" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <div class="sidebar-dropdown-menu">
                    <a href="{{ route('admin.customers.messages.index') }}" class="dropdown-item {{ request()->routeIs('admin.customers.messages.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <span class="ar-text">جميع الرسائل</span>
                        <span class="en-text">All Messages</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- إدارة المستخدمين -->
        <div class="menu-section">
            <div class="menu-title">
                <span class="ar-text">إدارة المستخدمين</span>
                <span class="en-text">Users Management</span>
            </div>

            <div class="has-dropdown">
                <a href="{{ route('admin.users.index') }}" class="menu-item sidebar-dropdown-toggle {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" onclick="toggleDropdown(event, this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="ar-text">المستخدمين</span>
                    <span class="en-text">Users</span>
                    <svg class="sidebar-dropdown-icon" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <div class="sidebar-dropdown-menu">
                    <a href="{{ route('admin.users.index') }}" class="dropdown-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <span class="ar-text">جميع المستخدمين</span>
                        <span class="en-text">All Users</span>
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="dropdown-item {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="ar-text">إضافة مستخدم</span>
                        <span class="en-text">Add User</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('admin.customers.index') }}" class="menu-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span class="ar-text">العملاء</span>
                <span class="en-text">Customers</span>
            </a>

            <a href="{{ route('admin.administrators.index') }}" class="menu-item {{ request()->routeIs('admin.administrators.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span class="ar-text">المسؤولين</span>
                <span class="en-text">Administrators</span>
            </a>
        </div>

        <!-- إدارة المنتجات -->
        <div class="menu-section">
            <div class="menu-title">
                <span class="ar-text">إدارة المنتجات</span>
                <span class="en-text">Products Management</span>
            </div>

            <div class="has-dropdown">
                <a href="{{ route('admin.products.index') }}" class="menu-item sidebar-dropdown-toggle {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" onclick="toggleDropdown(event, this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="ar-text">المنتجات</span>
                    <span class="en-text">Products</span>
                    <svg class="sidebar-dropdown-icon" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <div class="sidebar-dropdown-menu">
                    <a href="{{ route('admin.products.index') }}" class="dropdown-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <span class="ar-text">جميع المنتجات</span>
                        <span class="en-text">All Products</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="dropdown-item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="ar-text">إضافة منتج</span>
                        <span class="en-text">Add Product</span>
                    </a>
                </div>
            </div>

            <div class="has-dropdown">
                <a href="{{ route('admin.categories.index') }}" class="menu-item sidebar-dropdown-toggle {{ request()->routeIs('admin.categories.*', 'admin.sizes.*', 'admin.colors.*', 'admin.shoe-sizes.*') ? 'active' : '' }}" onclick="toggleDropdown(event, this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="ar-text">التصنيفات</span>
                    <span class="en-text">Categories</span>
                    <svg class="sidebar-dropdown-icon" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <div class="sidebar-dropdown-menu">
                    <a href="{{ route('admin.categories.index') }}" class="dropdown-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <span class="ar-text">جميع التصنيفات</span>
                        <span class="en-text">All Categories</span>
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="dropdown-item {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="ar-text">إضافة تصنيف</span>
                        <span class="en-text">Add Category</span>
                    </a>
                    <a href="{{ route('admin.sizes.index') }}" class="dropdown-item {{ request()->routeIs('admin.sizes.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="ar-text">المقاسات</span>
                        <span class="en-text">Sizes</span>
                    </a>
                    <a href="{{ route('admin.colors.index') }}" class="dropdown-item {{ request()->routeIs('admin.colors.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                        <span class="ar-text">الألوان</span>
                        <span class="en-text">Colors</span>
                    </a>
                    <a href="{{ route('admin.shoe-sizes.index') }}" class="dropdown-item {{ request()->routeIs('admin.shoe-sizes.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span class="ar-text">مقاسات الأحذية</span>
                        <span class="en-text">Shoe Sizes</span>
                    </a>
                    <a href="{{ route('admin.brands.index') }}" class="dropdown-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span class="ar-text">البراندات</span>
                        <span class="en-text">Brands</span>
                    </a>
                </div>
            </div>



            <div class="has-dropdown">
                <a href="{{ route('admin.menus.index') }}" class="menu-item sidebar-dropdown-toggle {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}" onclick="toggleDropdown(event, this)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span class="ar-text">إدارة القوائم</span>
                    <span class="en-text">Menu Management</span>
                    <svg class="sidebar-dropdown-icon" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <div class="sidebar-dropdown-menu">
                    <a href="{{ route('admin.menus.index') }}" class="dropdown-item {{ request()->routeIs('admin.menus.index') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <span class="ar-text">جميع القوائم</span>
                        <span class="en-text">All Menus</span>
                    </a>
                    <a href="{{ route('admin.menus.create') }}" class="dropdown-item {{ request()->routeIs('admin.menus.create') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
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

            <a href="{{ route('admin.orders.index') }}" class="menu-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
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

            <a href="{{ route('admin.settings.general') }}" class="menu-item {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
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
