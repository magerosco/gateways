<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ResponseStrategy\ApiResponseStrategy;
use App\Services\ResponseStrategy\ViewResponseStrategy;
use App\Services\ResponseStrategy\RedirectResponseStrategy;
use App\Services\ResponseStrategy\ResponseContextInterface;

class ResponseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ApiResponseStrategy::class, function ($app) {
            return new ApiResponseStrategy();
        });

        $this->app->bind(ViewResponseStrategy::class, function ($app) {
            return new ViewResponseStrategy();
        });

        $this->app->bind(RedirectResponseStrategy::class, function ($app) {
            return new RedirectResponseStrategy();
        });


        $this->app->singleton(ResponseContextInterface::class, function ($app) {
            return new ResponseContextInterface();
        });
    }
}
