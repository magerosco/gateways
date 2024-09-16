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
            return Gateway::find($id);
        });
    }

    public function create(array $data)
    {
        return Gateway::create($data);
    }

    public function update($id, array $data)
    {
        $Gateway = $this->find($id);
        $Gateway->update($data);
        return $Gateway;
    }

    public function delete($id)
    {
        $Gateway = $this->find($id);
        $this->clearCacheForGateway(str_replace('delete', 'find', __METHOD__ . $id));
        $Gateway->delete();
    }
}
