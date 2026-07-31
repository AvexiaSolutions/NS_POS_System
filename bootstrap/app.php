<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        $middleware->trustProxies(at: '*');

        // 🔴 කලින් තිබූ validateCsrfTokens (except: ['login']) කොටස සම්පූර්ණයෙන්ම අයින් කරන්න.
        // ඒ වෙනුවට මේවා පරීක්ෂා කරන්න:
        
        $middleware->web(append: [
            \App\Http\Middleware\UserMonitoringMiddleware::class,
        ]);

        // Mobile browsers වල Cookie ගැටලු වළක්වා ගැනීමට මෙය එක් කරන්න
        $middleware->statefulApi();

    })
    ->withExceptions(function (Exceptions $exceptions): void {
    })->create();
