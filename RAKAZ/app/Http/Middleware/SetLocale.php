<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * CRITICAL: This middleware sets the DASHBOARD INTERFACE language from session ONLY.
     * It NEVER uses the 'locale' query parameter from URLs like /admin/home/edit?locale=en
     * That 'locale' parameter is ONLY for selecting which CONTENT version to edit.
     *
     * Dashboard Language (this middleware): Controlled ONLY by session, changed via user menu toggle
     * Content Language (query param): Controlled by URL parameter in content edit pages
     *
     * STRICT SEPARATION: Query parameters MUST NEVER affect dashboard language!
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // CRITICAL: ONLY use session for dashboard language, IGNORE URL parameters
        // The 'locale' query parameter is for CONTENT selection, NOT interface language
        if (session()->has('locale')) {
            $locale = session('locale');
        } else {
            // Default to Arabic for dashboard interface
            $locale = 'ar';
            session(['locale' => $locale]);
        }

        // Set application locale for dashboard interface ONLY
        app()->setLocale($locale);

        // SECURITY: Log if there's a mismatch to detect potential issues
        $urlLocale = $request->query('locale');
        $dashboardLocale = session('locale', 'ar');

        if ($urlLocale && $urlLocale !== $dashboardLocale && $request->is('admin/*')) {
            \Log::info('Dashboard locale separation working correctly', [
                'dashboard_locale' => $dashboardLocale,
                'content_locale_param' => $urlLocale,
                'note' => 'These should be different - this is correct behavior'
            ]);
        }

        return $next($request);
    }
}
