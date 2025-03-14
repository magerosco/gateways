<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Application;
use App\Services\Exception\ExceptionHandlerService;
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
            'api_version' => \App\Http\Middleware\APIVersionMiddleware::class,
            'RabbitMQ' => App\Facades\RabbitMQ::class,
            'scope' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
            'sanitize' => \App\Http\Middleware\SanitizeInputMiddleware::class,

            //Reference document: https://spatie.be/docs/laravel-permission/v6/basic-usage/middleware
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        /**
         * EXAMPLE: ExceptionHandlerService is a custom service that handles exceptions
         * and avoid excessive growth of this section of the app.
         */
        $exceptionHandler = new ExceptionHandlerService();
        $exceptionHandler->handleExceptions($exceptions);
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->call(function () {
           Log::info('Scheduled task executed');
        })->daily();
    })->create();
