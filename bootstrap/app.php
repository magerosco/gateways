<?php

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

        $middleware->alias([
            'api_or_web' => \App\Http\Middleware\ApiOrWebMiddleware::class,
            'gateway_action' => \App\Http\Middleware\GatewayActionMiddleware::class,
            'role_or_permission' => \App\Http\Middleware\RoleOrPermissionMiddleware::class,
            'api_version' => \App\Http\Middleware\APIVersionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
