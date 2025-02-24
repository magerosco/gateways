<?php

namespace App\Providers;

use App\Services\SanctumTokenService;
use App\Repositories\GatewayRepository;
use Illuminate\Support\ServiceProvider;
use App\Contracts\TokenServiceInterface;
use App\Services\Gateway\GatewayService;
use App\Contracts\TokenRepositoryInterface;
use App\Repositories\CrudRepositoryInterface;
use App\Services\Passport\PassportTokenService;
use App\Services\Gateway\GatewayServiceInterface;
use App\Services\Passport\PassportTokenRepository;
use App\Contracts\PersonalAccessTokenFactoryInterface;
use App\Services\Passport\PassportPersonalAccessTokenFactory;

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
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

        $this->app->bind(GatewayServiceInterface::class, GatewayService::class);


        /**
         * EXAMPLE: Created Laravel Packages: ServiceProvider
         * This example handless the multiple dependency classes that
         * need to be injected into the same class, ex: GatewayRepository
         */

         // GatewayRepository
        $gatewayRepositoryNeeds = [
            GatewayService::class,
            \App\Http\Controllers\Api\V2\GatewayController::class,
            \App\Http\Controllers\GatewayController::class
        ];
        foreach ($gatewayRepositoryNeeds as $service) {
            $this->app->when($service)
                ->needs(CrudRepositoryInterface::class)
                ->give(GatewayRepository::class);
        }

        // PeripheralRepository
        $peripheralRepositoryNeeds = [
            \App\Http\Controllers\PeripheralController::class
        ];
        foreach ($peripheralRepositoryNeeds as $service) {
            $this->app->when($service)
                ->needs(CrudRepositoryInterface::class)
                ->give(\App\Repositories\PeripheralRepository::class);
        }

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
