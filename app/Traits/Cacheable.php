<?php

namespace App\Traits;

use Closure;
use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    private $time = 60;
    private $supportedCacheStores = ['redis', 'memcached'];

    public function cacheRemember($key, $time = null, $tag = null, $callback = null)
    {
        $time = is_numeric($time) && $time > 0 ? $time : $this->time;

        if (!empty($tag) &&  $this->useTag()) {
            return Cache::tags($tag)->remember($key, $time, $this->setCallbackDefault($callback));
        }

        return Cache::remember($key, $time, $this->setCallbackDefault($callback));
    }

    public function clearCache($key, $tag = null)
    {
        if (!empty($tag) && $this->useTag()) {
            return Cache::tags($tag)->flush();
        }
        return Cache::forget($key);
    }

    public function clearCacheByTagAndKey($tag, $key, $callback = null)
    {
        if (!empty($tag) && $this->useTag()) {
            return Cache::tags($tag)->forget($key, $this->setCallbackDefault($callback));
        }
        return Cache::forget($key);
    }

    private function useTag(): bool
    {
        return in_array(env('CACHE_DRIVER'), $this->supportedCacheStores);
    }
    private function setCallbackDefault(?Closure $callback = null): Closure
    {
        return $callback ?? fn() => true;
    }
}
