<?php

namespace App\Repositories\Decorators;

use App\Facades\RabbitMQ;
use App\Events\GatewayUpdated;
use App\Repositories\GatewayRepository;

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

        // Example: Trigger an event from the decorated repository
        event(new GatewayUpdated($gateway));

        // Example: Send a message to RabbitMQ
        RabbitMQ::sendMessage(json_encode($gateway));

        return $result;
    }
}
