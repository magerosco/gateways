<?php

namespace App\Observers;

use App\Models\Gateway;
use App\Traits\Cacheable;

class GatewayObserver
{
    use Cacheable;

    protected $cacheTag = 'gateway.';
    /**
     * Handle the Gateway "created" event.
     */
    public function created(Gateway $gateway): void
    {
        $this->clearCacheByTagAndKey($this->cacheTag . 'list', $this->cacheTag);
    }

    /**
     * Handle the Gateway "updated" event.
     */
    public function updated(Gateway $gateway): void
    {
        $this->clearCacheByTagAndKey($this->cacheTag . 'list', $this->cacheTag);
        $this->clearCacheByTagAndKey($this->cacheTag . 'find' . $gateway->id, $this->cacheTag, fn() => $gateway);
    }

    /**
     * Handle the Gateway "deleted" event.
     */
    public function deleted(Gateway $gateway): void
    {
        $this->clearCacheByTagAndKey($this->cacheTag . 'list', $this->cacheTag);
        $this->clearCacheByTagAndKey($this->cacheTag . 'find' . $gateway->id, $this->cacheTag, fn() => $gateway);
    }

    /**
     * Handle the Gateway "restored" event.
     */
    public function restored(Gateway $gateway): void
    {
        $this->clearCacheByTagAndKey($this->cacheTag . 'list', $this->cacheTag);
    }

    /**
     * Handle the Gateway "force deleted" event.
     */
    public function forceDeleted(Gateway $gateway): void
    {
        $this->clearCacheByTagAndKey($this->cacheTag . 'list', $this->cacheTag);
        $this->clearCacheByTagAndKey($this->cacheTag . 'find' . $gateway->id, $this->cacheTag, fn() => $gateway);
    }
}
