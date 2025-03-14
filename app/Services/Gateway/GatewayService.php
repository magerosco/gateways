<?php

namespace App\Services\Gateway;

use App\Models\Gateway;
use App\Repositories\GatewayRepository;
use App\Services\Gateway\GatewayServiceInterface;

class GatewayService implements GatewayServiceInterface
{
    public function __construct(protected GatewayRepository $repository) {}

    public function destroyV2(Gateway $gateway): void
    {
        $this->repository->delete($gateway);
    }
}
