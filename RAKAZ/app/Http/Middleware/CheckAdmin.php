<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     * Only allow users with 'admin' role to access admin routes
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // Check if user has admin role
        if (auth()->user()->role !== 'admin') {
            abort(403, 'غير مصرح لك بالدخول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
