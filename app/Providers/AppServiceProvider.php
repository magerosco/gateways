<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Repositories\GatewayRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CrudRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CrudRepositoryInterface::class, GatewayRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
