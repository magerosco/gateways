<?php

namespace App\Providers;


use App\Models\User;
use App\Models\Gateway;

use Illuminate\Support\ServiceProvider;

class ObserverProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        User::observe(\App\Observers\UserObserver::class);
        Gateway::observe(\App\Observers\GatewayObserver::class);
    }
}
