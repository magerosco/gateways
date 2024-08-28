<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ResponseStrategy\ResponseContext;
use App\Services\ResponseStrategy\ResponseContextInterface;
use App\Services\ResponseStrategy\Output\ApiResponseStrategy;
use App\Services\ResponseStrategy\Output\ViewResponseStrategy;
use App\Services\ResponseStrategy\OutputDataFormat\StrategyData;
use App\Services\ResponseStrategy\Output\RedirectResponseStrategy;
use App\Services\ResponseStrategy\OutputDataFormat\StrategyDataInterface;

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
