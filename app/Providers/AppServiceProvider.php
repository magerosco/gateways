<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Gateway;
use App\Observers\UserObserver;
use App\Policies\GatewatyPolicy;
use App\Repositories\GatewayRepository;
use App\Repositories\CrudRepositoryInterface;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Gateway::class => GatewatyPolicy::class,
    ];
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
        $this->registerPolicies();
        User::observe(UserObserver::class);
    }
}
