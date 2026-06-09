<?php

use Illuminate\Http\Request; // 👈 تأكد إن السطر ده كدا بالظبط مش Facade
use Illuminate\Http\Exceptions\ThrottleRequestsException; // 👈 سطر الـ Exception الافتراضي
use App\Exceptions\Auth\TooManyRequestsException;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\LocaleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;




return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. تعريف الـ Aliases
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
        ]);

        // 2. إضافة ميدل وير اللغة في بداية الـ Stack
        // استخدمنا prepend عشان نضمن إن اللغة تتحدد "قبل" أي عملية تانية
        $middleware->prepend(\App\Http\Middleware\LocaleMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
            
            // 🎯 المفرمة السحرية: أول ما لارافل يرمي خطأ الـ Rate Limit على الـ API
            $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
                if ($request->is('api/*')) {
                    // بنخطف الخطأ ونرمي الـ Custom Exception بتاعنا اللي بيرث من الـ BaseException
                    throw new TooManyRequestsException();
                }
            });

    })->create();
