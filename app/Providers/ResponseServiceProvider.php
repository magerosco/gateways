<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ResponseStrategy\ResponseContext;
use App\Services\ResponseStrategy\Output\ApiResponseStrategy;
use App\Services\ResponseStrategy\Output\ViewResponseStrategy;
use App\Services\ResponseStrategy\Output\RedirectResponseStrategy;

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


        $this->app->singleton(ResponseContext::class, function ($app) {
            return new ResponseContext();
        });
    }
}
