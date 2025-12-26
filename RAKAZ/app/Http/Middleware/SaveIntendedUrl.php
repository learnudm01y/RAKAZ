<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SaveIntendedUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't save intended URL for:
        // - Livewire requests
        // - Admin/Breeze auth pages
        // - User auth pages
        // - AJAX requests
        if (!$request->user()
            && !$request->is('livewire/*')
            && !$request->is('login')
            && !$request->is('register')
            && !$request->is('admin/login')
            && !$request->is('admin/register')
            && !$request->ajax()
            && !$request->wantsJson()
        ) {
            session(['url.intended' => $request->url()]);
        }

        return $next($request);
    }
}
