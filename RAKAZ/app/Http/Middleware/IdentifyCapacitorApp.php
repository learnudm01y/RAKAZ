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

        // Check if the request comes from Rakaz Capacitor Android App
        $isCapacitor = str_contains($userAgent, 'RakazApp-Android-Capacitor');

        // Share the variable with all views
        view()->share('isCapacitor', $isCapacitor);

        // Also store in session for other uses
        session(['isCapacitor' => $isCapacitor]);

        return $next($request);
    }
}
