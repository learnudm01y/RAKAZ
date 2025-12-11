@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')

@section('page-title')
    <span class="ar-text">لوحة التحكم</span>
    <span class="en-text">Dashboard</span>
@endsection

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s;
        box-shadow: var(--shadow);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.blue { background: #e6f2ff; color: #3182ce; }
    .stat-icon.green { background: #e6ffed; color: #48bb78; }
    .stat-icon.yellow { background: #fff6e6; color: #ed8936; }
    .stat-icon.purple { background: #f3e6ff; color: #9f7aea; }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: var(--text-color);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #718096;
        font-size: 0.875rem;
    }

    .quick-actions {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-color);
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: var(--hover-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-color);
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: translateX(-2px);
    }

    [dir="rtl"] .action-btn:hover {
        transform: translateX(2px);
    }

    .action-btn svg {
        width: 24px;
        height: 24px;
    }

    .recent-activity {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        font-size: 0.875rem;
        color: #718096;
    }

    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .welcome-banner h2 {
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }

    .welcome-banner p {
        opacity: 0.9;
    }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="welcome-banner">
    <h2>
        <span class="ar-text">مرحباً، {{ Auth::user()->name }}! 👋</span>
        <span class="en-text">Welcome, {{ Auth::user()->name }}! 👋</span>
    </h2>
    <p>
        <span class="ar-text">إليك نظرة سريعة على نشاط موقعك اليوم</span>
        <span class="en-text">Here's a quick overview of your site's activity today</span>
    </p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">1,234</div>
                <div class="stat-label">
                    <span class="ar-text">إجمالي الطلبات</span>
                    <span class="en-text">Total Orders</span>
                </div>
            </div>
            <div class="stat-icon blue">📦</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">567</div>
                <div class="stat-label">
                    <span class="ar-text">المنتجات</span>
                    <span class="en-text">Products</span>
                </div>
            </div>
            <div class="stat-icon green">🛍️</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">89</div>
                <div class="stat-label">
                    <span class="ar-text">طلبات جديدة</span>
                    <span class="en-text">New Orders</span>
                </div>
            </div>
            <div class="stat-icon yellow">⚡</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">2,456</div>
                <div class="stat-label">
                    <span class="ar-text">العملاء</span>
                    <span class="en-text">Customers</span>
                </div>
            </div>
            <div class="stat-icon purple">👥</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <h3 class="section-title">
        <span class="ar-text">إجراءات سريعة</span>
        <span class="en-text">Quick Actions</span>
    </h3>
    <div class="actions-grid">
        <a href="{{ route('admin.pages.create') }}" class="action-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="ar-text">إضافة صفحة جديدة</span>
            <span class="en-text">Add New Page</span>
        </a>

        <a href="{{ route('admin.about.edit') }}" class="action-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <span class="ar-text">تحرير صفحة من نحن</span>
            <span class="en-text">Edit About Page</span>
        </a>

        <a href="{{ route('home') }}" target="_blank" class="action-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            <span class="ar-text">زيارة الموقع</span>
            <span class="en-text">Visit Website</span>
        </a>

        <a href="#" class="action-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="ar-text">عرض التقارير</span>
            <span class="en-text">View Reports</span>
        </a>
    </div>
</div>

<!-- Recent Activity -->
<div class="recent-activity">
    <h3 class="section-title">
        <span class="ar-text">النشاط الأخير</span>
        <span class="en-text">Recent Activity</span>
    </h3>

    <div class="activity-item">
        <div class="activity-icon" style="background: #e6f2ff; color: #3182ce;">
            📝
        </div>
        <div class="activity-content">
            <div class="activity-title">
                <span class="ar-text">تم إنشاء صفحة جديدة</span>
                <span class="en-text">New page created</span>
            </div>
            <div class="activity-time">
                <span class="ar-text">منذ 5 دقائق</span>
                <span class="en-text">5 minutes ago</span>
            </div>
        </div>
    </div>

    <div class="activity-item">
        <div class="activity-icon" style="background: #e6ffed; color: #48bb78;">
            ✓
        </div>
        <div class="activity-content">
            <div class="activity-title">
                <span class="ar-text">تم تحديث صفحة من نحن</span>
                <span class="en-text">About page updated</span>
            </div>
            <div class="activity-time">
                <span class="ar-text">منذ ساعة</span>
                <span class="en-text">1 hour ago</span>
            </div>
        </div>
    </div>

    <div class="activity-item">
        <div class="activity-icon" style="background: #fff6e6; color: #ed8936;">
            🛍️
        </div>
        <div class="activity-content">
            <div class="activity-title">
                <span class="ar-text">طلب جديد #1234</span>
                <span class="en-text">New order #1234</span>
            </div>
            <div class="activity-time">
                <span class="ar-text">منذ 3 ساعات</span>
                <span class="en-text">3 hours ago</span>
            </div>
        </div>
    </div>
</div>
@endsection
