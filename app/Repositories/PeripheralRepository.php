<?php

namespace App\Repositories;

use App\Models\Peripheral;

class PeripheralRepository implements CrudRepositoryInterface
{
    public function all()
    {
        return Peripheral::all();
    }

    public function find($id)
    {
        return Peripheral::findOrFail($id);
    }

    public function create(array $data)
    {
        return Peripheral::create($data);
    }

    public function update($id, array $data)
    {
        $peripheral = $this->find($id);
        $peripheral->update($data);
        return $peripheral;
    }

    public function delete($peripheral)
    {
        $peripheral->delete();
    }
}
