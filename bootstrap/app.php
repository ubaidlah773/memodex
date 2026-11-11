<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// On Vercel serverless, filesystem is read-only outside /tmp
if (env('VERCEL') || env('LARAVEL_STORAGE_PATH')) {
    $storagePath = env('LARAVEL_STORAGE_PATH', '/tmp/storage');
    if (!is_dir($storagePath)) {
        @mkdir($storagePath, 0755, true);
    }
    $app->useStoragePath($storagePath);
}

return $app;
