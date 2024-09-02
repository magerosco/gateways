<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Repositories\GatewayRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CrudRepositoryInterface;
use Anasa\ResponseStrategy\AdditionalDataRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CrudRepositoryInterface::class, GatewayRepository::class);
        $this->app->singleton('additionalDataRequest', function ($app) {
            return new AdditionalDataRequest;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
