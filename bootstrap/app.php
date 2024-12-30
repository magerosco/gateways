<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'api_or_web' => \App\Http\Middleware\ApiOrWebMiddleware::class,
            'gateway_action' => \App\Http\Middleware\GatewayActionMiddleware::class,
            'role_or_permission' => \App\Http\Middleware\RoleOrPermissionMiddleware::class,
            'api_version' => \App\Http\Middleware\APIVersionMiddleware::class,
            'RabbitMQ' => App\Facades\RabbitMQ::class,
            'scope' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
            'sanitize' => \App\Http\Middleware\SanitizeInputMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            return response()->json([
                'error' => 'Resource not found',
                'message' => 'The requested resource was not found.',
            ], 404);
        });

        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            return response()->json([
                'error' => 'Not Found',
                'message' => '',
            ], 404);
        });
    })->create();
