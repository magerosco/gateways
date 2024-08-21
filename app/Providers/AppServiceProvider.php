<?php

namespace App\Providers;

use App\Repositories\GatewayRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\GatewayRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GatewayRepositoryInterface::class, GatewayRepository::class);
        $this->app->singleton('additionalDataRequest', function ($app) {
            return new \App\Services\AdditionalDataRequest;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
