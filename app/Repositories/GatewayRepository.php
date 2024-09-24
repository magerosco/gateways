<?php

namespace App\Repositories;

use App\Models\Gateway;

class GatewayRepository implements CrudRepositoryInterface
{
    public function all()
    {
        return Gateway::all();
    }

    public function find($id)
    {
        return Gateway::findOrFail($id);
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

    public function delete($gateway)
    {
        $gateway->delete();
    }
}
