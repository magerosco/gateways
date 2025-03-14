<?php

namespace App\Repositories;

use App\Models\Gateway;
use App\Traits\Cacheable;

class GatewayRepository implements CrudRepositoryInterface
{
    use Cacheable; // EXAMPLE: This is a trait to manage the cache.

    protected $cacheTime = 30;
    protected $cacheTag = 'gateway.';

    public function all()
    {
        return $this->cacheRemember($this->cacheTag . 'list', $this->cacheTime, null, function () {
            return Gateway::all();
        });
    }

    public function find($id)
    {
        return $this->cacheRemember(
            $this->cacheTag . 'find' . $id,
            $this->cacheTime,
            $this->cacheTag,
            fn () => Gateway::findOrFail($id)
        );
    }

    public function create(array $data)
    {
        $result = Gateway::create($data);
        return $result;
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
        $gateway->delete();
    }
}
