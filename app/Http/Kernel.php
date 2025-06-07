<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;



class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     * Middleware di sini akan dipanggil untuk setiap request ke aplikasi kamu.
     */
    protected $middleware = [
        // contoh middleware global
        \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
    ];

    /**
     * Middleware groups (untuk web & api).
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Route middleware (alias).
     * Gunakan ini untuk middleware yang dipanggil secara eksplisit di route.
     */
    protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'checkrole' => \App\Http\Middleware\CheckRole::class, 
];
}
