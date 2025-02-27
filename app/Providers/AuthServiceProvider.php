<?php

namespace App\Providers;

use App\Models\Gateway;
use Laravel\Passport\Passport;
use App\Policies\GatewayPolicy;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Gateway::class => GatewayPolicy::class,
    ];

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
        $this->registerPolicies();

        Config::set('passport.private_key', storage_path(env('PASSPORT_PRIVATE_KEY')));
        Config::set('passport.public_key', storage_path(env('PASSPORT_PUBLIC_KEY')));

        Passport::tokensCan([
            'view-profile' => 'View user profile',
            'manage-users' => 'Manage user accounts',
            'manage-posts' => 'Manage blog posts',
        ]);

        Passport::setDefaultScope([
            'view-profile',
        ]);
    }
}
