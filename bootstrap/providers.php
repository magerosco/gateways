<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\CORSServiceProvider::class,
    App\Providers\GatewayInterceptorServiceProvider::class,
    App\Providers\GatewayProvider::class,
    App\Providers\InterfaceServiceProvider::class,
    App\Providers\RabbitMQServiceProvider::class,
    App\Providers\ObserverProvider::class,
    App\Providers\ResponseServiceProvider::class,
    App\Providers\VoltServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    Barryvdh\DomPDF\ServiceProvider::class,

];
