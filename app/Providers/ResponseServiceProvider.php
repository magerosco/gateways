<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Anasa\ResponseStrategy\{ResponseContext,ResponseContextInterface};
use Anasa\ResponseStrategy\OutputDataFormat\{StrategyData,StrategyDataInterface};
use Anasa\ResponseStrategy\Output\{ApiResponseStrategy, ViewResponseStrategy, RedirectResponseStrategy};

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
        $this->app->bind(StrategyDataInterface::class, function ($app) {
            return new StrategyData();
        });

        $this->app->singleton(ResponseContextInterface::class, function ($app) {
            return new ResponseContext();
        });
    }
}
