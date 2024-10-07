<?php

namespace App\Events;

use App\Models\Gateway;
use Illuminate\Foundation\Events\Dispatchable;

class GatewayUpdated
{
    use Dispatchable;

    public $gateway;

    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }
}
