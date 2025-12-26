<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SiteVisit;
use App\Models\OnlineUser;
use App\Models\PageView;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تجاهل الـ API requests و assets
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $sessionId = session()->getId();
        $ipAddress = $request->ip();
        $userId = auth()->id();

        // تحديث حالة المستخدم المتصل
        OnlineUser::updateActivity($sessionId, $ipAddress, $userId);

        // تسجيل الزيارة الفريدة (مرة واحدة لكل IP في اليوم)
        $this->recordUniqueVisit($request, $ipAddress, $userId);

        // تسجيل مشاهدة الصفحة (كل تحميل للصفحة)
        $this->recordPageView($request, $ipAddress, $sessionId, $userId);

        // تنظيف السجلات القديمة (مرة كل 100 request تقريباً)
        if (rand(1, 100) === 1) {
            OnlineUser::cleanOld(1);
        }

        return $next($request);
    }

    /**
     * تسجيل الزيارة الفريدة (مستخدم واحد = زيارة واحدة في اليوم)
     */
    protected function recordUniqueVisit(Request $request, $ipAddress, $userId)
    {
        $today = today();

        // التحقق من عدم تسجيل زيارة من هذا الـ IP اليوم
        $exists = SiteVisit::where('ip_address', $ipAddress)
            ->where('visit_date', $today)
            ->exists();

        if (!$exists) {
            SiteVisit::create([
                'ip_address' => $ipAddress,
                'user_agent' => substr($request->userAgent(), 0, 255),
                'page_url' => $request->path(),
                'user_id' => $userId,
                'visit_date' => $today,
            ]);
        }
    }

    /**
     * تسجيل مشاهدة الصفحة (كل تحميل للصفحة يُسجل)
     */
    protected function recordPageView(Request $request, $ipAddress, $sessionId, $userId)
    {
        PageView::create([
            'ip_address' => $ipAddress,
            'session_id' => $sessionId,
            'page_url' => $request->path(),
            'user_id' => $userId,
            'view_date' => today(),
        ]);
    }

    /**
     * تحديد ما إذا كان يجب تخطي هذا الـ request
     */
    protected function shouldSkip(Request $request): bool
    {
        $path = $request->path();

        // تخطي الـ API
        if (str_starts_with($path, 'api/')) {
            return true;
        }

        // تخطي الـ admin routes (اختياري)
        // if (str_starts_with($path, 'admin/')) {
        //     return true;
        // }

        // تخطي الـ assets
        $skipExtensions = ['.js', '.css', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.ico', '.woff', '.woff2'];
        foreach ($skipExtensions as $ext) {
            if (str_ends_with($path, $ext)) {
                return true;
            }
        }

        // تخطي Livewire requests
        if (str_starts_with($path, 'livewire/')) {
            return true;
        }

        return false;
    }
}
