<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Redirect;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
          api: __DIR__.'/../routes/api.php', // 🌟 SUNTIKAN SAKRAL: MEMAKSA LARAVEL MENGHIDUPKAN FILE API.PHP
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

       $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'premium' => \App\Http\Middleware\EnsureIsPremium::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // PINDAHKAN LOGIKA REDIRECT 419 KE SINI
        $exceptions->respond(function ($response) {
            if ($response->getStatusCode() === 419) {
                return Redirect::route('login')->with([
                    'message' => 'Sesi telah berakhir, silakan login kembali.',
                ]);
            }
            return $response;
        });
    })->create();