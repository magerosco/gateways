<?php

namespace App\Providers;

use App\Repositories\GatewayRepository;
use App\Repositories\Decorators\GatewayRepositoryDecorator;
use Illuminate\Support\ServiceProvider;

class GatewayInterceptorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //Using the decorator to intercept method calls to the GatewayRepository.
        $this->app->extend(GatewayRepository::class, function ($repository) {
            return new GatewayRepositoryDecorator($repository);
        });
    }
}
