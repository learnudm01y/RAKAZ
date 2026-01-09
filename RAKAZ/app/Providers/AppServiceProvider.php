<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\MenuComposer;
use App\Models\Order;
use App\Observers\OrderObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set SSL certificate path for cURL (only on local Windows/Laragon environment)
        if (app()->environment('local')) {
            $cacertPath = 'C:\laragon\bin\php\php-8.3.26-Win32-vs16-x64\extras\ssl\cacert.pem';
            if (file_exists($cacertPath)) {
                ini_set('curl.cainfo', $cacertPath);
                ini_set('openssl.cafile', $cacertPath);
            }
        }

        // Force HTTPS in production
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        View::composer('layouts.app', MenuComposer::class);

        // Register Order Observer
        Order::observe(OrderObserver::class);
    }
}
