<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Supported locales
     */
    protected $supportedLocales = ['en', 'ar'];

    /**
     * Default locale
     */
    protected $defaultLocale = 'en';

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get locale from Accept-Language header
        $locale = $request->header('Accept-Language');

        // 2. Validate locale
        if (!$locale || !in_array($locale, $this->supportedLocales)) {
            $locale = $this->defaultLocale;
        }

        // 3. Set application locale
        app()->setLocale($locale);

        return $next($request);
    }
}
