<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use \App\Http\Middleware\ApikeyAuth;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\OptionalAuth;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ngrok/rev-proxy: percaya X-Forwarded-Proto biar asset() generate https://
        $middleware->trustProxies(at: '*');
        // Header keamanan di SEMUA response (web + api).
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
        $middleware->alias([
            'auth.apikey' => ApikeyAuth::class,
            'admin' => AdminMiddleware::class,
            'auth.optional' => OptionalAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Detail error (stack trace, isi env) HANYA untuk akses lokal (localhost/127.0.0.1).
        // Publik (ngrok) dapat halaman error polos — cegah bocor kredensial via stack trace.
        $exceptions->shouldDisplayExceptions(fn (\Illuminate\Http\Request $request) => in_array(
            $request->ip(), ['127.0.0.1', '::1', 'localhost', '192.168.56.1'], true
        ));
    })->create();

return $app;
