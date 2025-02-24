<?php

namespace App\Providers;

use App\Traits\Cacheable;
use App\Models\AllowedOrigin;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class CORSServiceProvider extends ServiceProvider
{
    use Cacheable;

    private $allowedOrigins = [];

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
        $this->allowedOrigins = $this->cacheRemember('allowed_origins', 3600, null, function () {
            return Schema::hasTable('allowed_origins') ? AllowedOrigin::pluck('origin')->toArray() : [];
        });

        Config::set('cors.allowed_origins', $this->allowedOrigins ?: ['*']);
    }
}
