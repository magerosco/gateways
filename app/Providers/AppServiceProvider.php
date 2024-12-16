<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Gateway;
use App\Policies\GatewayPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Gateway::class => GatewayPolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        User::observe(\App\Observers\UserObserver::class);
        Gateway::observe(\App\Observers\GatewayObserver::class);
    }
}
