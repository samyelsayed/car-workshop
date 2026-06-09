<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Login Attempts
        RateLimiter::for('login-limiter', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip());
        });

        // Registration Attempts
        RateLimiter::for('register-limiter', function (Request $request) {
            return Limit::perMinute(3)
                ->by($request->ip());
        });

        // Forgot Password Flow
        RateLimiter::for('password-reset-limiter', function (Request $request) {
            return Limit::perMinute(6)
                 ->by($request->ip());
        });

        // Email Verification Flow
        RateLimiter::for('email-verification-limiter', function (Request $request) {
            return Limit::perMinute(6)
                 ->by($request->ip());
        });
    }
}