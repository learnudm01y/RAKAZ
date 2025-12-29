@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')

@section('page-title')
    <span class="ar-text">لوحة التحكم</span>
    <span class="en-text">Dashboard</span>
@endsection

@push('styles')
<style>
    /* Skeleton Loading Styles */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
        border-radius: 4px;
    }

    @keyframes skeleton-loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    .skeleton-text {
        height: 1em;
        margin-bottom: 0.5em;
    }

    .skeleton-text.large {
        height: 2rem;
        width: 60%;
    }

    .skeleton-text.medium {
        height: 1.5rem;
        width: 80%;
    }

    .skeleton-text.small {
        height: 1rem;
        width: 50%;
    }

    .skeleton-circle {
        border-radius: 50%;
    }

    .skeleton-box {
        border-radius: 8px;
    }

    /* Fade in animation for loaded content */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hide skeleton when loaded */
    .skeleton-container.loaded .skeleton-content {
        display: none;
    }

    .skeleton-container .real-content {
        display: none;
    }

    .skeleton-container.loaded .real-content {
        display: block;
    }

    /* Stats Skeleton */
    .stat-skeleton {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-skeleton .skeleton-value {
        height: 2rem;
        width: 80px;
        margin-bottom: 0.5rem;
    }

    .stat-skeleton .skeleton-label {
        height: 1rem;
        width: 100px;
    }

    .stat-skeleton .skeleton-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
    }

    /* Visitor Card Skeleton */
    .visitor-skeleton {
        padding: 1.5rem;
    }

    .visitor-skeleton-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .visitor-skeleton .skeleton-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
    }

    .visitor-skeleton-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .visitor-skeleton-stat {
        text-align: center;
        padding: 0.875rem;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .visitor-skeleton-stat .skeleton-number {
        height: 1.5rem;
        width: 50px;
        margin: 0 auto 0.5rem;
    }

    .visitor-skeleton-stat .skeleton-label {
        height: 0.8rem;
        width: 40px;
        margin: 0 auto;
    }

    /* Activity Skeleton */
    .activity-skeleton {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .activity-skeleton .skeleton-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .activity-skeleton-content {
        flex: 1;
    }

    .activity-skeleton .skeleton-title {
        height: 1rem;
        width: 200px;
        margin-bottom: 0.5rem;
    }

    .activity-skeleton .skeleton-time {
        height: 0.8rem;
        width: 100px;
    }

    /* Loading overlay for refresh */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: inherit;
        z-index: 10;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }

    .loading-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #e0e0e0;
        border-top-color: #667eea;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

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

    /* Visitor Stats Cards */
    .visitor-stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .visitor-stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .visitor-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .visitor-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        opacity: 0.1;
        transform: translate(30%, -30%);
    }

    .visitor-card.online::before {
        background: #48bb78;
    }

    .visitor-card.visits::before {
        background: #3182ce;
    }

    .visitor-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .visitor-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    .visitor-card.online .visitor-card-icon {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
    }

    .visitor-card.visits .visitor-card-icon {
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
        color: white;
    }

    .visitor-card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-color);
    }

    .visitor-card-subtitle {
        font-size: 0.875rem;
        color: #718096;
    }

    .online-stats {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .online-main {
        text-align: center;
    }

    .online-number {
        font-size: 3rem;
        font-weight: 700;
        color: #48bb78;
        line-height: 1;
    }

    .online-label {
        font-size: 0.875rem;
        color: #718096;
        margin-top: 0.5rem;
    }

    .online-pulse {
        width: 12px;
        height: 12px;
        background: #48bb78;
        border-radius: 50%;
        display: inline-block;
        margin-left: 8px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(72, 187, 120, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(72, 187, 120, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(72, 187, 120, 0);
        }
    }

    .online-breakdown {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding-right: 2rem;
        border-right: 1px solid var(--border-color);
    }

    [dir="rtl"] .online-breakdown {
        padding-right: 0;
        padding-left: 2rem;
        border-right: none;
        border-left: 1px solid var(--border-color);
    }

    .breakdown-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .breakdown-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .breakdown-dot.registered {
        background: #4299e1;
    }

    .breakdown-dot.guest {
        background: #ed8936;
    }

    .breakdown-value {
        font-weight: 600;
        font-size: 1.125rem;
        color: var(--text-color);
        min-width: 30px;
    }

    .breakdown-label {
        font-size: 0.875rem;
        color: #718096;
    }

    .visits-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .visit-item {
        text-align: center;
        padding: 1rem;
        background: var(--hover-bg);
        border-radius: 12px;
    }

    .visit-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #3182ce;
        line-height: 1;
    }

    .visit-label {
        font-size: 0.875rem;
        color: #718096;
        margin-top: 0.5rem;
    }

    .visit-item.today .visit-number {
        color: #48bb78;
    }

    .visit-item.month .visit-number {
        color: #ed8936;
    }

    .visit-item.year .visit-number {
        color: #9f7aea;
    }

    /* 3 Cards Layout */
    .visitor-stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    @media (max-width: 1200px) {
        .visitor-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .visitor-stats-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Page Views Card */
    .visitor-card.pageviews::before {
        background: #ed8936;
    }

    .visitor-card.pageviews .visitor-card-icon {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        color: white;
    }

    .pageviews-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .pageview-item {
        text-align: center;
        padding: 0.875rem;
        background: var(--hover-bg);
        border-radius: 10px;
    }

    .pageview-number {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .pageview-label {
        font-size: 0.8rem;
        color: #718096;
        margin-top: 0.375rem;
    }

    .pageview-item.today .pageview-number {
        color: #48bb78;
    }

    .pageview-item.month .pageview-number {
        color: #ed8936;
    }

    .pageview-item.year .pageview-number {
        color: #9f7aea;
    }

    /* Charts Modal Styles */
    .charts-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .charts-modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .charts-modal {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 1400px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .charts-modal-overlay.active .charts-modal {
        transform: scale(1);
    }

    .charts-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .charts-modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .charts-modal-title i {
        font-size: 1.75rem;
    }

    .charts-modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: all 0.2s;
    }

    .charts-modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .charts-modal-body {
        padding: 2rem;
        overflow-y: auto;
        max-height: calc(90vh - 80px);
    }

    /* Chart Legend */
    .chart-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .legend-item:hover {
        opacity: 0.7;
    }

    .legend-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary-color);
    }

    .legend-color {
        width: 24px;
        height: 4px;
        border-radius: 2px;
    }

    .legend-color.dashed {
        background: repeating-linear-gradient(
            to right,
            #3182ce,
            #3182ce 6px,
            transparent 6px,
            transparent 10px
        );
    }

    .legend-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #4a5568;
    }

    /* Chart Container */
    .chart-container {
        position: relative;
        height: 400px;
        background: white;
        border-radius: 16px;
        padding: 1rem;
        margin-bottom: 2rem;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }

    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .chart-card-header {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .chart-card-header i {
        font-size: 1.25rem;
        color: #667eea;
    }

    .chart-card-title {
        font-weight: 600;
        font-size: 1rem;
        color: #2d3748;
    }

    .chart-card-body {
        padding: 1.5rem;
        height: 300px;
        position: relative;
    }

    /* View Charts Button */
    .view-charts-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .view-charts-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }

    .view-charts-btn i {
        font-size: 1.25rem;
    }

    /* Summary Stats in Modal */
    .modal-summary-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .modal-summary-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .modal-stat-card {
        background: linear-gradient(135deg, #f6f8ff 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        border: 1px solid #e2e8f0;
    }

    .modal-stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .modal-stat-label {
        font-size: 0.85rem;
        color: #718096;
        margin-top: 0.25rem;
    }
</style>
@endpush

@section('content')
<!-- Visitor Statistics Cards - Skeleton -->
<div id="visitor-stats-skeleton" class="visitor-stats-grid">
    <!-- Online Users Skeleton -->
    <div class="visitor-card online">
        <div class="visitor-skeleton">
            <div class="visitor-skeleton-header">
                <div class="skeleton skeleton-icon"></div>
                <div style="flex: 1;">
                    <div class="skeleton skeleton-text medium"></div>
                    <div class="skeleton skeleton-text small"></div>
                </div>
            </div>
            <div class="visitor-skeleton-stats">
                <div class="visitor-skeleton-stat">
                    <div class="skeleton skeleton-number"></div>
                    <div class="skeleton skeleton-label"></div>
                </div>
                <div class="visitor-skeleton-stat">
                    <div class="skeleton skeleton-number"></div>
                    <div class="skeleton skeleton-label"></div>
                </div>
                <div class="visitor-skeleton-stat">
                    <div class="skeleton skeleton-number"></div>
                    <div class="skeleton skeleton-label"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Unique Visitors Skeleton -->
    <div class="visitor-card visits">
        <div class="visitor-skeleton">
            <div class="visitor-skeleton-header">
                <div class="skeleton skeleton-icon"></div>
                <div style="flex: 1;">
                    <div class="skeleton skeleton-text medium"></div>
                    <div class="skeleton skeleton-text small"></div>
                </div>
            </div>
            <div class="visitor-skeleton-stats">
                <div class="visitor-skeleton-stat">
                    <div class="skeleton skeleton-number"></div>
                    <div class="skeleton skeleton-label"></div>
                </div>
                <div class="visitor-skeleton-stat">
                    <div class="skeleton skeleton-number"></div>
                    <div class="skeleton skeleton-label"></div>
                </div>
                <div class="visitor-skeleton-stat">
                    <div class="skeleton skeleton-number"></div>
                    <div class="skeleton skeleton-label"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Views Skeleton -->
    <div class="visitor-card pageviews">
        <div class="visitor-skeleton">
            <div class="visitor-skeleton-header">
                <div class="skeleton skeleton-icon"></div>
                <div style="flex: 1;">
                    <div class="skeleton skeleton-text medium"></div>
                    <div class="skeleton skeleton-text small"></div>
                </div>
            </div>
            <div class="visitor-skeleton-stats">
                <div class="visitor-skeleton-stat">
                    <div class="skeleton skeleton-number"></div>
                    <div class="skeleton skeleton-label"></div>
                </div>
                <div class="visitor-skeleton-stat">
                    <div class="skeleton skeleton-number"></div>
                    <div class="skeleton skeleton-label"></div>
                </div>
                <div class="visitor-skeleton-stat">
                    <div class="skeleton skeleton-number"></div>
                    <div class="skeleton skeleton-label"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Visitor Statistics Cards - Real Content (Hidden Initially) -->
<div id="visitor-stats-real" class="visitor-stats-grid" style="display: none;">
    <!-- Online Users Card -->
    <div class="visitor-card online">
        <div class="visitor-card-header">
            <div class="visitor-card-icon"><i class="fas fa-users"></i></div>
            <div>
                <div class="visitor-card-title">
                    <span class="ar-text">المستخدمون المتصلون</span>
                    <span class="en-text">Online Users</span>
                    <span class="online-pulse"></span>
                </div>
                <div class="visitor-card-subtitle">
                    <span class="ar-text">متصلون الآن (آخر 5 دقائق)</span>
                    <span class="en-text">Currently Online (last 5 min)</span>
                </div>
            </div>
        </div>
        <div class="online-stats">
            <div class="online-breakdown">
                <div class="breakdown-item">
                    <span class="breakdown-dot registered"></span>
                    <span class="breakdown-value" id="registered-online">0</span>
                    <span class="breakdown-label">
                        <span class="ar-text">مسجلين</span>
                        <span class="en-text">Registered</span>
                    </span>
                </div>
                <div class="breakdown-item">
                    <span class="breakdown-dot guest"></span>
                    <span class="breakdown-value" id="guest-online">0</span>
                    <span class="breakdown-label">
                        <span class="ar-text">زوار</span>
                        <span class="en-text">Guests</span>
                    </span>
                </div>
            </div>
            <div class="online-main">
                <div class="online-number" id="total-online">0</div>
                <div class="online-label">
                    <span class="ar-text">إجمالي المتصلين</span>
                    <span class="en-text">Total Online</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Unique Visitors Card -->
    <div class="visitor-card visits">
        <div class="visitor-card-header">
            <div class="visitor-card-icon"><i class="fas fa-users"></i></div>
            <div>
                <div class="visitor-card-title">
                    <span class="ar-text">الزوار الفريدين</span>
                    <span class="en-text">Unique Visitors</span>
                </div>
                <div class="visitor-card-subtitle">
                    <span class="ar-text">زائر واحد لكل IP يومياً</span>
                    <span class="en-text">One visit per IP daily</span>
                </div>
            </div>
        </div>
        <div class="visits-grid">
            <div class="visit-item today">
                <div class="visit-number" id="visits-today">0</div>
                <div class="visit-label">
                    <span class="ar-text">اليوم</span>
                    <span class="en-text">Today</span>
                </div>
            </div>
            <div class="visit-item month">
                <div class="visit-number" id="visits-month">0</div>
                <div class="visit-label">
                    <span class="ar-text">هذا الشهر</span>
                    <span class="en-text">This Month</span>
                </div>
            </div>
            <div class="visit-item year">
                <div class="visit-number" id="visits-year">0</div>
                <div class="visit-label">
                    <span class="ar-text">هذه السنة</span>
                    <span class="en-text">This Year</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Views Card -->
    <div class="visitor-card pageviews">
        <div class="visitor-card-header">
            <div class="visitor-card-icon"><i class="fas fa-eye"></i></div>
            <div>
                <div class="visitor-card-title">
                    <span class="ar-text">مشاهدات الصفحات</span>
                    <span class="en-text">Page Views</span>
                </div>
                <div class="visitor-card-subtitle">
                    <span class="ar-text">كل تحميل للصفحة يُحتسب</span>
                    <span class="en-text">Every page load counts</span>
                </div>
            </div>
        </div>
        <div class="pageviews-grid">
            <div class="pageview-item today">
                <div class="pageview-number" id="pageviews-today">0</div>
                <div class="pageview-label">
                    <span class="ar-text">اليوم</span>
                    <span class="en-text">Today</span>
                </div>
            </div>
            <div class="pageview-item month">
                <div class="pageview-number" id="pageviews-month">0</div>
                <div class="pageview-label">
                    <span class="ar-text">هذا الشهر</span>
                    <span class="en-text">This Month</span>
                </div>
            </div>
            <div class="pageview-item year">
                <div class="pageview-number" id="pageviews-year">0</div>
                <div class="pageview-label">
                    <span class="ar-text">هذه السنة</span>
                    <span class="en-text">This Year</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Charts Button -->
<div style="text-align: center; margin-bottom: 2rem;">
    <button type="button" class="view-charts-btn" onclick="openChartsModal()">
        <i class="fas fa-chart-line"></i>
        <span class="ar-text">عرض الرسوم البيانية التفصيلية</span>
        <span class="en-text">View Detailed Charts</span>
    </button>
</div>

<!-- Charts Modal -->
<div class="charts-modal-overlay" id="chartsModal">
    <div class="charts-modal">
        <div class="charts-modal-header">
            <div class="charts-modal-title">
                <i class="fas fa-chart-area"></i>
                <span class="ar-text">الإحصائيات والرسوم البيانية</span>
                <span class="en-text">Statistics & Charts</span>
            </div>
            <button type="button" class="charts-modal-close" onclick="closeChartsModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="charts-modal-body">
            <!-- Summary Stats -->
            <div class="modal-summary-stats">
                <div class="modal-stat-card">
                    <div class="modal-stat-value" id="modal-total-orders">0</div>
                    <div class="modal-stat-label">
                        <span class="ar-text">إجمالي الطلبات</span>
                        <span class="en-text">Total Orders</span>
                    </div>
                </div>
                <div class="modal-stat-card">
                    <div class="modal-stat-value"><span id="modal-total-revenue">0</span> <small>د.إ</small></div>
                    <div class="modal-stat-label">
                        <span class="ar-text">إجمالي الإيرادات</span>
                        <span class="en-text">Total Revenue</span>
                    </div>
                </div>
                <div class="modal-stat-card">
                    <div class="modal-stat-value" id="modal-visits-month">0</div>
                    <div class="modal-stat-label">
                        <span class="ar-text">زوار هذا الشهر</span>
                        <span class="en-text">Visitors This Month</span>
                    </div>
                </div>
                <div class="modal-stat-card">
                    <div class="modal-stat-value" id="modal-pageviews-month">0</div>
                    <div class="modal-stat-label">
                        <span class="ar-text">مشاهدات هذا الشهر</span>
                        <span class="en-text">Page Views This Month</span>
                    </div>
                </div>
            </div>

            <!-- Main Chart with Legend -->
            <div class="chart-card" style="margin-bottom: 2rem;">
                <div class="chart-card-header">
                    <i class="fas fa-chart-line"></i>
                    <span class="chart-card-title">
                        <span class="ar-text">المبيعات والزيارات خلال الأشهر الماضية</span>
                        <span class="en-text">Sales & Visits Over Past Months</span>
                    </span>
                </div>
                <div class="chart-legend">
                    <label class="legend-item">
                        <input type="checkbox" checked id="toggleRevenue">
                        <span class="legend-color" style="background: #4299e1;"></span>
                        <span class="legend-label">
                            <span class="ar-text">الإيرادات</span>
                            <span class="en-text">Revenue</span>
                        </span>
                    </label>
                    <label class="legend-item">
                        <input type="checkbox" checked id="toggleOrders">
                        <span class="legend-color" style="background: #e53e3e;"></span>
                        <span class="legend-label">
                            <span class="ar-text">الطلبات</span>
                            <span class="en-text">Orders</span>
                        </span>
                    </label>
                    <label class="legend-item">
                        <input type="checkbox" checked id="toggleRatio">
                        <span class="legend-color dashed"></span>
                        <span class="legend-label">
                            <span class="ar-text">معدل النمو</span>
                            <span class="en-text">Growth Rate</span>
                        </span>
                    </label>
                    <label class="legend-item">
                        <input type="checkbox" id="toggleSelectAll">
                        <span class="legend-label" style="font-weight: 600;">
                            <span class="ar-text">تحديد الكل</span>
                            <span class="en-text">Select All</span>
                        </span>
                    </label>
                </div>
                <div class="chart-card-body" style="height: 350px;">
                    <canvas id="mainSalesChart"></canvas>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="charts-grid">
                <!-- Orders by Status Chart -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <i class="fas fa-pie-chart"></i>
                        <span class="chart-card-title">
                            <span class="ar-text">الطلبات حسب الحالة</span>
                            <span class="en-text">Orders by Status</span>
                        </span>
                    </div>
                    <div class="chart-card-body">
                        <canvas id="ordersStatusChart"></canvas>
                    </div>
                </div>

                <!-- Visitors Chart -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <i class="fas fa-users"></i>
                        <span class="chart-card-title">
                            <span class="ar-text">إحصائيات الزوار</span>
                            <span class="en-text">Visitor Statistics</span>
                        </span>
                    </div>
                    <div class="chart-card-body">
                        <canvas id="visitorsChart"></canvas>
                    </div>
                </div>

                <!-- Top Products Chart -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <i class="fas fa-trophy"></i>
                        <span class="chart-card-title">
                            <span class="ar-text">أفضل المنتجات مبيعاً</span>
                            <span class="en-text">Top Selling Products</span>
                        </span>
                    </div>
                    <div class="chart-card-body">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>

                <!-- Revenue vs Orders Chart -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <i class="fas fa-balance-scale"></i>
                        <span class="chart-card-title">
                            <span class="ar-text">مقارنة الإيرادات والطلبات</span>
                            <span class="en-text">Revenue vs Orders</span>
                        </span>
                    </div>
                    <div class="chart-card-body">
                        <canvas id="revenueOrdersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards - Skeleton -->
<div id="stats-skeleton" class="stats-grid">
    <div class="stat-card">
        <div class="stat-skeleton">
            <div>
                <div class="skeleton skeleton-value"></div>
                <div class="skeleton skeleton-label"></div>
            </div>
            <div class="skeleton skeleton-icon"></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-skeleton">
            <div>
                <div class="skeleton skeleton-value"></div>
                <div class="skeleton skeleton-label"></div>
            </div>
            <div class="skeleton skeleton-icon"></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-skeleton">
            <div>
                <div class="skeleton skeleton-value"></div>
                <div class="skeleton skeleton-label"></div>
            </div>
            <div class="skeleton skeleton-icon"></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-skeleton">
            <div>
                <div class="skeleton skeleton-value"></div>
                <div class="skeleton skeleton-label"></div>
            </div>
            <div class="skeleton skeleton-icon"></div>
        </div>
    </div>
</div>

<!-- Statistics Cards - Real Content -->
<div id="stats-real" class="stats-grid" style="display: none;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="stat-total-orders">0</div>
                <div class="stat-label">
                    <span class="ar-text">إجمالي الطلبات</span>
                    <span class="en-text">Total Orders</span>
                </div>
            </div>
            <div class="stat-icon blue"><i class="fas fa-shopping-bag"></i></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="stat-total-products">0</div>
                <div class="stat-label">
                    <span class="ar-text">المنتجات</span>
                    <span class="en-text">Products</span>
                </div>
            </div>
            <div class="stat-icon green"><i class="fas fa-box-open"></i></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="stat-new-orders">0</div>
                <div class="stat-label">
                    <span class="ar-text">طلبات جديدة</span>
                    <span class="en-text">New Orders</span>
                </div>
            </div>
            <div class="stat-icon yellow"><i class="fas fa-bolt"></i></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="stat-total-customers">0</div>
                <div class="stat-label">
                    <span class="ar-text">العملاء</span>
                    <span class="en-text">Customers</span>
                </div>
            </div>
            <div class="stat-icon purple"><i class="fas fa-user-friends"></i></div>
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

        <a href="#" class="action-btn" onclick="openChartsModal(); return false;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="ar-text">عرض التقارير</span>
            <span class="en-text">View Reports</span>
        </a>
    </div>
</div>

<!-- Recent Activity - Skeleton -->
<div id="activity-skeleton" class="recent-activity">
    <h3 class="section-title">
        <span class="ar-text">أحدث الطلبات</span>
        <span class="en-text">Recent Orders</span>
    </h3>
    <div class="activity-skeleton">
        <div class="skeleton skeleton-avatar"></div>
        <div class="activity-skeleton-content">
            <div class="skeleton skeleton-title"></div>
            <div class="skeleton skeleton-time"></div>
        </div>
    </div>
    <div class="activity-skeleton">
        <div class="skeleton skeleton-avatar"></div>
        <div class="activity-skeleton-content">
            <div class="skeleton skeleton-title"></div>
            <div class="skeleton skeleton-time"></div>
        </div>
    </div>
    <div class="activity-skeleton">
        <div class="skeleton skeleton-avatar"></div>
        <div class="activity-skeleton-content">
            <div class="skeleton skeleton-title"></div>
            <div class="skeleton skeleton-time"></div>
        </div>
    </div>
    <div class="activity-skeleton">
        <div class="skeleton skeleton-avatar"></div>
        <div class="activity-skeleton-content">
            <div class="skeleton skeleton-title"></div>
            <div class="skeleton skeleton-time"></div>
        </div>
    </div>
    <div class="activity-skeleton">
        <div class="skeleton skeleton-avatar"></div>
        <div class="activity-skeleton-content">
            <div class="skeleton skeleton-title"></div>
            <div class="skeleton skeleton-time"></div>
        </div>
    </div>
</div>

<!-- Recent Activity - Real Content -->
<div id="activity-real" class="recent-activity" style="display: none;">
    <h3 class="section-title">
        <span class="ar-text">أحدث الطلبات</span>
        <span class="en-text">Recent Orders</span>
    </h3>
    <div id="recent-orders-container"></div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
// Charts Modal Functions
function openChartsModal() {
    var modal = document.getElementById('chartsModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Initialize charts after modal opens
    setTimeout(function() {
        initializeCharts();
    }, 100);
}

function closeChartsModal() {
    var modal = document.getElementById('chartsModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

// Close modal on overlay click
document.getElementById('chartsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeChartsModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeChartsModal();
    }
});

// Chart instances
var mainChart = null;
var ordersStatusChart = null;
var visitorsChart = null;
var topProductsChart = null;
var revenueOrdersChart = null;

// Chart Data - will be populated via AJAX
var monthlySalesData = [];
var ordersByStatusData = {};
var topProductsData = [];
var visitorStatsData = {
    uniqueVisitors: {
        today: 0,
        month: 0,
        year: 0
    },
    pageViews: {
        today: 0,
        month: 0,
        year: 0
    }
};

function initializeCharts() {
    initMainSalesChart();
    initOrdersStatusChart();
    initVisitorsChart();
    initTopProductsChart();
    initRevenueOrdersChart();
    setupLegendToggles();
}

function initMainSalesChart() {
    var ctx = document.getElementById('mainSalesChart');
    if (!ctx) return;

    if (mainChart) {
        mainChart.destroy();
    }

    var labels = monthlySalesData.map(function(item) {
        return item.month;
    });

    var revenueData = monthlySalesData.map(function(item) {
        return parseFloat(item.total_sales) || 0;
    });

    var ordersData = monthlySalesData.map(function(item) {
        return parseInt(item.total_orders) || 0;
    });

    // Calculate growth rate (ratio of current to previous)
    var growthData = revenueData.map(function(val, idx) {
        if (idx === 0 || revenueData[idx - 1] === 0) return 1;
        return (val / revenueData[idx - 1]).toFixed(2);
    });

    mainChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'الإيرادات / Revenue',
                    data: revenueData,
                    borderColor: '#4299e1',
                    backgroundColor: 'rgba(66, 153, 225, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'الطلبات / Orders',
                    data: ordersData,
                    borderColor: '#e53e3e',
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'معدل النمو / Growth Rate',
                    data: growthData,
                    borderColor: '#3182ce',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    borderDash: [6, 4],
                    fill: false,
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 11 }
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    min: 0.8,
                    max: 1.4
                }
            }
        }
    });
}

function initOrdersStatusChart() {
    var ctx = document.getElementById('ordersStatusChart');
    if (!ctx) return;

    if (ordersStatusChart) {
        ordersStatusChart.destroy();
    }

    var statusLabels = {
        'pending': 'قيد الانتظار',
        'processing': 'قيد المعالجة',
        'shipped': 'تم الشحن',
        'delivered': 'تم التسليم',
        'cancelled': 'ملغي'
    };

    var statusColors = {
        'pending': '#ecc94b',
        'processing': '#4299e1',
        'shipped': '#9f7aea',
        'delivered': '#48bb78',
        'cancelled': '#e53e3e'
    };

    var labels = [];
    var data = [];
    var colors = [];

    for (var status in ordersByStatusData) {
        labels.push(statusLabels[status] || status);
        data.push(ordersByStatusData[status]);
        colors.push(statusColors[status] || '#718096');
    }

    ordersStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                }
            }
        }
    });
}

function initVisitorsChart() {
    var ctx = document.getElementById('visitorsChart');
    if (!ctx) return;

    if (visitorsChart) {
        visitorsChart.destroy();
    }

    visitorsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['اليوم / Today', 'هذا الشهر / Month', 'هذه السنة / Year'],
            datasets: [
                {
                    label: 'الزوار الفريدين / Unique Visitors',
                    data: [
                        visitorStatsData.uniqueVisitors.today,
                        visitorStatsData.uniqueVisitors.month,
                        visitorStatsData.uniqueVisitors.year
                    ],
                    backgroundColor: '#4299e1',
                    borderRadius: 8
                },
                {
                    label: 'مشاهدات الصفحات / Page Views',
                    data: [
                        visitorStatsData.pageViews.today,
                        visitorStatsData.pageViews.month,
                        visitorStatsData.pageViews.year
                    ],
                    backgroundColor: '#ed8936',
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 11 }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    beginAtZero: true
                }
            }
        }
    });
}

function initTopProductsChart() {
    var ctx = document.getElementById('topProductsChart');
    if (!ctx) return;

    if (topProductsChart) {
        topProductsChart.destroy();
    }

    var labels = topProductsData.map(function(item) {
        var name = item.name;
        // Handle JSON name object (multilingual)
        if (typeof name === 'object' && name !== null) {
            name = name.ar || name.en || 'منتج';
        } else if (typeof name === 'string') {
            // Try to parse if it's a JSON string
            try {
                var parsed = JSON.parse(name);
                name = parsed.ar || parsed.en || name;
            } catch(e) {
                // It's a regular string, use as is
            }
        }
        name = name || 'منتج';
        return name.length > 25 ? name.substring(0, 25) + '...' : name;
    });

    var quantities = topProductsData.map(function(item) {
        return parseInt(item.total_quantity) || 0;
    });

    topProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'الكمية المباعة / Quantity Sold',
                data: quantities,
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#4299e1',
                    '#48bb78',
                    '#ed8936'
                ],
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    beginAtZero: true
                },
                y: {
                    grid: { display: false }
                }
            }
        }
    });
}

function initRevenueOrdersChart() {
    var ctx = document.getElementById('revenueOrdersChart');
    if (!ctx) return;

    if (revenueOrdersChart) {
        revenueOrdersChart.destroy();
    }

    var labels = monthlySalesData.map(function(item) {
        return item.month;
    });

    var revenueData = monthlySalesData.map(function(item) {
        return parseFloat(item.total_sales) || 0;
    });

    var ordersData = monthlySalesData.map(function(item) {
        return parseInt(item.total_orders) || 0;
    });

    revenueOrdersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'الإيرادات / Revenue',
                    data: revenueData,
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderRadius: 6,
                    yAxisID: 'y'
                },
                {
                    label: 'الطلبات / Orders',
                    data: ordersData,
                    type: 'line',
                    borderColor: '#e53e3e',
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 11 }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });
}

function setupLegendToggles() {
    var toggleRevenue = document.getElementById('toggleRevenue');
    var toggleOrders = document.getElementById('toggleOrders');
    var toggleRatio = document.getElementById('toggleRatio');
    var toggleSelectAll = document.getElementById('toggleSelectAll');

    if (toggleRevenue) {
        toggleRevenue.addEventListener('change', function() {
            if (mainChart) {
                mainChart.data.datasets[0].hidden = !this.checked;
                mainChart.update();
            }
        });
    }

    if (toggleOrders) {
        toggleOrders.addEventListener('change', function() {
            if (mainChart) {
                mainChart.data.datasets[1].hidden = !this.checked;
                mainChart.update();
            }
        });
    }

    if (toggleRatio) {
        toggleRatio.addEventListener('change', function() {
            if (mainChart) {
                mainChart.data.datasets[2].hidden = !this.checked;
                mainChart.update();
            }
        });
    }

    if (toggleSelectAll) {
        toggleSelectAll.checked = true;
        toggleSelectAll.addEventListener('change', function() {
            var checked = this.checked;
            if (toggleRevenue) toggleRevenue.checked = checked;
            if (toggleOrders) toggleOrders.checked = checked;
            if (toggleRatio) toggleRatio.checked = checked;

            if (mainChart) {
                mainChart.data.datasets[0].hidden = !checked;
                mainChart.data.datasets[1].hidden = !checked;
                mainChart.data.datasets[2].hidden = !checked;
                mainChart.update();
            }
        });
    }
}

// ============================================
// AJAX Statistics Loading System
// ============================================

var dashboardData = null;
var chartsInitialized = false;

// Format number with commas
function formatNumber(num) {
    if (num === null || num === undefined) return '0';
    return Number(num).toLocaleString('en-US');
}

// Load all statistics via AJAX
function loadStatistics() {
    console.log('🔄 Loading dashboard statistics...');

    // Get CSRF token
    var csrfToken = '';
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (csrfMeta) {
        csrfToken = csrfMeta.getAttribute('content');
        console.log('🔑 CSRF Token found');
    } else {
        console.warn('⚠️ CSRF Token meta tag not found!');
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/admin/api/statistics/all', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Accept', 'application/json');
    if (csrfToken) {
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    }
    xhr.withCredentials = true;

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            console.log('📡 Response received - Status:', xhr.status);
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    console.log('✅ Statistics loaded successfully:', response);
                    if (response.success) {
                        dashboardData = response.data;
                        updateDashboardUI(response.data);
                    } else {
                        console.error('❌ Failed to load statistics:', response.error);
                        showErrorState();
                    }
                } catch (e) {
                    console.error('❌ Error parsing response:', e);
                    showErrorState();
                }
            } else {
                console.error('❌ HTTP Error:', xhr.status, xhr.statusText);
                console.log('📄 Response text:', xhr.responseText);
                if (xhr.status === 401) {
                    console.error('🔒 Unauthorized - Session may have expired');
                }
                showErrorState();
            }
        }
    };

    xhr.onerror = function() {
        console.error('❌ Network error occurred');
        showErrorState();
    };

    console.log('📤 Sending request to /admin/api/statistics/all');
    xhr.send();
}

// Update all dashboard UI elements
function updateDashboardUI(data) {
    // Update visitor stats
    updateVisitorStats(data.visitorStats);

    // Update main stats
    updateMainStats(data.stats);

    // Update recent orders
    updateRecentOrders(data.recentOrders);

    // Update modal stats
    updateModalStats(data.stats, data.visitorStats);

    // Store chart data globally
    monthlySalesData = data.monthlySales || [];
    ordersByStatusData = data.ordersByStatus || {};
    topProductsData = data.topProducts || [];
    visitorStatsData = {
        uniqueVisitors: {
            today: data.visitorStats.visits_today || 0,
            month: data.visitorStats.visits_month || 0,
            year: data.visitorStats.visits_year || 0
        },
        pageViews: {
            today: data.visitorStats.pageviews_today || 0,
            month: data.visitorStats.pageviews_month || 0,
            year: data.visitorStats.pageviews_year || 0
        }
    };

    // Show real content, hide skeletons
    showRealContent();
}

// Update visitor statistics
function updateVisitorStats(stats) {
    if (!stats) return;

    // Online users
    var totalOnline = document.getElementById('total-online');
    var registeredOnline = document.getElementById('registered-online');
    var guestOnline = document.getElementById('guest-online');

    if (totalOnline) totalOnline.textContent = formatNumber(stats.online_users || 0);
    if (registeredOnline) registeredOnline.textContent = formatNumber(stats.registered_online || 0);
    if (guestOnline) guestOnline.textContent = formatNumber(stats.guest_online || 0);

    // Unique visitors
    var visitsToday = document.getElementById('visits-today');
    var visitsMonth = document.getElementById('visits-month');
    var visitsYear = document.getElementById('visits-year');

    if (visitsToday) visitsToday.textContent = formatNumber(stats.visits_today || 0);
    if (visitsMonth) visitsMonth.textContent = formatNumber(stats.visits_month || 0);
    if (visitsYear) visitsYear.textContent = formatNumber(stats.visits_year || 0);

    // Page views
    var pageviewsToday = document.getElementById('pageviews-today');
    var pageviewsMonth = document.getElementById('pageviews-month');
    var pageviewsYear = document.getElementById('pageviews-year');

    if (pageviewsToday) pageviewsToday.textContent = formatNumber(stats.pageviews_today || 0);
    if (pageviewsMonth) pageviewsMonth.textContent = formatNumber(stats.pageviews_month || 0);
    if (pageviewsYear) pageviewsYear.textContent = formatNumber(stats.pageviews_year || 0);
}

// Update main statistics cards
function updateMainStats(stats) {
    if (!stats) return;

    var totalOrders = document.getElementById('stat-total-orders');
    var totalProducts = document.getElementById('stat-total-products');
    var newOrders = document.getElementById('stat-new-orders');
    var totalCustomers = document.getElementById('stat-total-customers');

    if (totalOrders) totalOrders.textContent = formatNumber(stats.total_orders || 0);
    if (totalProducts) totalProducts.textContent = formatNumber(stats.total_products || 0);
    if (newOrders) newOrders.textContent = formatNumber(stats.new_orders || 0);
    if (totalCustomers) totalCustomers.textContent = formatNumber(stats.total_customers || 0);
}

// Update modal statistics
function updateModalStats(stats, visitorStats) {
    var modalTotalOrders = document.getElementById('modal-total-orders');
    var modalTotalRevenue = document.getElementById('modal-total-revenue');
    var modalVisitsMonth = document.getElementById('modal-visits-month');
    var modalPageviewsMonth = document.getElementById('modal-pageviews-month');

    if (modalTotalOrders) modalTotalOrders.textContent = formatNumber(stats.total_orders || 0);
    if (modalTotalRevenue) modalTotalRevenue.textContent = formatNumber(stats.total_revenue || 0);
    if (modalVisitsMonth) modalVisitsMonth.textContent = formatNumber(visitorStats.visits_month || 0);
    if (modalPageviewsMonth) modalPageviewsMonth.textContent = formatNumber(visitorStats.pageviews_month || 0);
}

// Update recent orders list
function updateRecentOrders(orders) {
    var container = document.getElementById('recent-orders-container');
    if (!container) return;

    if (!orders || orders.length === 0) {
        container.innerHTML = '<div class="activity-item"><div class="activity-content"><div class="activity-title" style="text-align: center; color: #999;"><span class="ar-text">لا توجد طلبات بعد</span><span class="en-text">No orders yet</span></div></div></div>';
        return;
    }

    var html = '';
    for (var i = 0; i < orders.length; i++) {
        var order = orders[i];
        html += '<div class="activity-item">';
        html += '<div class="activity-icon" style="background: #e6f2ff; color: #3182ce;">📦</div>';
        html += '<div class="activity-content">';
        html += '<div class="activity-title">';
        html += '<span class="ar-text">طلب #' + order.order_number + '</span>';
        html += '<span class="en-text">Order #' + order.order_number + '</span>';
        html += ' - <span style="font-size: 0.875rem; color: #666;">' + (order.customer_name || '-') + '</span>';
        html += ' - <strong>' + formatNumber(order.total) + ' د.إ</strong>';
        html += '</div>';
        html += '<div class="activity-time">' + (order.created_at_human || order.created_at) + '</div>';
        html += '</div>';
        html += '<a href="/admin/orders/' + order.id + '" style="color: #3182ce; text-decoration: none;">';
        html += '<svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>';
        html += '</svg></a>';
        html += '</div>';
    }

    container.innerHTML = html;
}

// Show real content and hide skeletons
function showRealContent() {
    console.log('✨ Showing real content, hiding skeletons');

    // Visitor stats
    var visitorSkeleton = document.getElementById('visitor-stats-skeleton');
    var visitorReal = document.getElementById('visitor-stats-real');
    if (visitorSkeleton) visitorSkeleton.style.display = 'none';
    if (visitorReal) {
        visitorReal.style.display = 'grid';
        visitorReal.classList.add('fade-in');
    }

    // Main stats
    var statsSkeleton = document.getElementById('stats-skeleton');
    var statsReal = document.getElementById('stats-real');
    if (statsSkeleton) statsSkeleton.style.display = 'none';
    if (statsReal) {
        statsReal.style.display = 'grid';
        statsReal.classList.add('fade-in');
    }

    // Activity/Recent orders
    var activitySkeleton = document.getElementById('activity-skeleton');
    var activityReal = document.getElementById('activity-real');
    if (activitySkeleton) activitySkeleton.style.display = 'none';
    if (activityReal) {
        activityReal.style.display = 'block';
        activityReal.classList.add('fade-in');
    }

    console.log('✅ Real content displayed successfully');
}

// Show error state
function showErrorState() {
    console.warn('⚠️ Showing error state with empty data');
    // Still show real content but with zeros/empty
    updateVisitorStats({});
    updateMainStats({});
    updateRecentOrders([]);
    showRealContent();
}

// Initialize dashboard on page load
function initDashboard() {
    console.log('🚀 Dashboard initialized');
    console.log('📍 Document ready state:', document.readyState);
    console.log('⏰ Waiting 2 seconds before loading data...');

    // Wait 2 seconds before loading data (as per requirement)
    setTimeout(function() {
        console.log('⏳ Starting data load...');
        loadStatistics();
    }, 2000);
}

// Run immediately if document already loaded, otherwise wait for DOMContentLoaded
if (document.readyState === 'loading') {
    console.log('⏳ Waiting for DOM to be ready...');
    document.addEventListener('DOMContentLoaded', initDashboard);
} else {
    console.log('✅ DOM already ready, initializing now');
    initDashboard();
}

// Refresh statistics every 5 minutes
setInterval(function() {
    console.log('🔄 Auto-refresh: Reloading statistics...');
    loadStatistics();
}, 300000);
</script>
@endsection
