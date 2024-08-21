<?php

namespace App\Repositories;

use App\Models\Gateway;

class GatewayRepository implements GatewayRepositoryInterface
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
        $Gateway = $this->find($id);
        $Gateway->update($data);
        return $Gateway;
    }

    public function delete($id)
    {
        $Gateway = $this->find($id);
        $Gateway->delete();
    }
}
