<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    public function register(): void
    {
        // ...existing code...
    }

    public function boot(): void
    {
        // Define limiter "api" used by throttle:api
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Prevent fatal errors when Vite manifest is missing in production deployments
        // This safely no-ops the @vite directive if no dev server or build output is present.
        if (! file_exists(public_path('build/manifest.json')) && ! file_exists(public_path('hot'))) {
            Blade::directive('vite', function ($expression) {
                return '';
            });
        }
    }
}
