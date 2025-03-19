<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // ...

    protected $routeMiddleware = [
        // ...
        'api' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'throttle:api' => \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ...
    ];

    protected $middlewareGroups = [
        'web' => [
            // ...
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    // ...
}