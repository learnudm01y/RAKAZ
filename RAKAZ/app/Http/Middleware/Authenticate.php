<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // If accessing admin routes, redirect to admin login
            if ($request->is('admin/*') || $request->is('dashboard')) {
                return route('admin.login');
            }
            // Otherwise, redirect to user login
            return route('login');
        }

        return null;
    }
}
