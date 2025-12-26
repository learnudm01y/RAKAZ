<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle CSRF token mismatch (419)
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            // If it's a logout request, just logout and redirect
            if ($request->is('user/logout') || $request->is('logout')) {
                auth()->logout();
                return redirect()->route('home')->with('message', 'تم تسجيل الخروج بنجاح');
            }

            // For other requests, redirect to appropriate login page
            if ($request->is('admin/*') || $request->is('dashboard')) {
                return redirect()->route('admin.login')
                    ->with('error', 'انتهت صلاحية الجلسة. يرجى تسجيل الدخول مرة أخرى.');
            }

            return redirect()->route('login')
                ->with('error', 'انتهت صلاحية الجلسة. يرجى تسجيل الدخول مرة أخرى.');
        }

        return parent::render($request, $exception);
    }
}
