<?php

namespace App\Repositories;

use App\Models\Gateway;
use Illuminate\Support\Facades\Cache;

class GatewayRepository implements CrudRepositoryInterface
{
    protected $cacheTime = 600;

    public function clearCacheForGateway($key)
    {
        Cache::forget($key);
    }
    public function cacheRemember($key, $time = null, $callback)
    {
        return Cache::remember($key, $time ?? $this->cacheTime, $callback);
    }

    public function all()
    {
        return $this->cacheRemember(__METHOD__, $this->cacheTime, function () {
            return Gateway::all();
        });
    }

    public function find($id)
    {
        return $this->cacheRemember(__METHOD__ . $id, $this->cacheTime, function () use ($id) {
            return Gateway::findOrFail($id);
        });
    }

    public function create(array $data)
    {
        return Gateway::create($data);
    }

    public function update($id, array $data)
    {
        $gateway = $this->find($id);
        $gateway->update($data);
        return $gateway;
    }
    /**
     * This is a basic update example, and it was implemented to test the GatewayRepositoryDecorator
     * The idea is create an integration to listen an specific function from the repository instead of update
     * function of the crud actions.
     */
    public function updateGateway($id, array $data)
    {
        $gateway = $this->find($id);
        $gateway->update($data);
        return $gateway;
    }

    public function delete($gateway)
    {
        $id = $gateway->id;
        $this->clearCacheForGateway(str_replace('delete', 'find', __METHOD__ . $id));
        $gateway->delete();
    }
}
