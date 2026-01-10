<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyCapacitorApp
{
    /**
     * Handle an incoming request.
     *
     * Check if the request comes from the Capacitor Android app
     * by looking for the custom User-Agent string.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = $request->header('User-Agent', '');

        // Check if the request comes from Rakaz Capacitor App (Android or iOS)
        // Support multiple User-Agent formats:
        // - RakazApp-Capacitor-Android (Android app)
        // - RakazApp-Android-Capacitor (old format)
        // - RakazApp-Capacitor (general Capacitor)
        // - Capacitor (any Capacitor app)
        $isCapacitor = str_contains($userAgent, 'RakazApp-Capacitor-Android') ||
                       str_contains($userAgent, 'RakazApp-Android-Capacitor') ||
                       str_contains($userAgent, 'RakazApp-Capacitor') ||
                       str_contains($userAgent, 'Capacitor');

        // Log the detection for debugging
        \Log::info('Capacitor Detection', [
            'user_agent' => $userAgent,
            'isCapacitor' => $isCapacitor,
            'ip' => $request->ip(),
        ]);

        // Share the variable with all views
        view()->share('isCapacitor', $isCapacitor);

        // Also store in session for other uses
        session(['isCapacitor' => $isCapacitor]);

        return $next($request);
    }
}
