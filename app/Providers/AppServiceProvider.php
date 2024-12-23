<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Gateway;
use App\Policies\GatewayPolicy;
use App\Services\SanctumTokenService;
use App\Contracts\TokenServiceInterface;
use App\Contracts\TokenRepositoryInterface;
use App\Services\Passport\PassportTokenService;
use App\Services\Passport\PassportTokenRepository;
use App\Contracts\PersonalAccessTokenFactoryInterface;
use App\Services\Passport\PassportPersonalAccessTokenFactory;
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
        $this->app->bind(TokenServiceInterface::class, function ($app) {
            $headerValue = request()->header('X-Auth-Service');

            if (strtolower($headerValue) === 'oauth') {
                return $app->make(PassportTokenService::class);
            }

            return $app->make(SanctumTokenService::class);
        });

        $this->app->bind(PersonalAccessTokenFactoryInterface::class, PassportPersonalAccessTokenFactory::class);
        $this->app->bind(TokenRepositoryInterface::class, PassportTokenRepository::class);
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
