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
     * IMPORTANT: This middleware sets the DASHBOARD INTERFACE language from session.
     * It does NOT use the 'locale' query parameter from URLs like /admin/home/edit?locale=en
     * That 'locale' parameter is only for selecting which CONTENT version to edit.
     *
     * Dashboard Language (this middleware): Controlled by session, changed via user menu
     * Content Language (query param): Controlled by URL parameter in content edit pages
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if locale is stored in session (for dashboard interface language)
        if (session()->has('locale')) {
            $locale = session('locale');
        } else {
            // Default to Arabic
            $locale = 'ar';
            session(['locale' => $locale]);
        }

        // Set application locale for dashboard interface
        app()->setLocale($locale);

        return $next($request);
    }
}
