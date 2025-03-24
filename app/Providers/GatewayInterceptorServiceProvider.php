<?php

namespace App\Providers;

use App\Traits\ApiVersion;
use Illuminate\Http\Request;
use App\Repositories\GatewayRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Decorators\GatewayRepositoryDecorator;

class GatewayInterceptorServiceProvider extends ServiceProvider
{

    use ApiVersion;

    public function register()
    {
        $this->app->extend(GatewayRepository::class, function ($repository, $app) {


            $apiVersion = $this->getApiVersion($app->make(Request::class));

            if ($apiVersion === 'v1') {
                return new GatewayRepositoryDecorator($repository);
            }

            return $repository;
        });
    }
}
