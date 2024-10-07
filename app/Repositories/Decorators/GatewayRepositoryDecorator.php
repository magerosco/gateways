<?php

namespace App\Repositories\Decorators;

use App\Repositories\GatewayRepository;
use App\Events\GatewayUpdated;

class GatewayRepositoryDecorator extends GatewayRepository
{
    protected $repository;

    public function __construct(GatewayRepository $repository)
    {
        $this->repository = $repository;
    }

    public function updateGateway($id, $data)
    {
        // Call the original updateGateway method
        $result = $this->repository->updateGateway($id, $data);
        $gateway = $this->find($id);

        event(new GatewayUpdated($gateway));

        return $result;
    }
}
