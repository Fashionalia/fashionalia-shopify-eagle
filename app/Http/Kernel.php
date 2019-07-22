<?php

namespace Eagle\Http;

use Eagle\Http\Middleware\EagleBasicAuthMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [];

    protected $middlewareGroups = [
        'api' => [
            'throttle:60,1',
            'auth.basic',
        ],
    ];

    protected $routeMiddleware = [
        'throttle'   => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'auth.basic' => EagleBasicAuthMiddleware::class,
    ];
}
