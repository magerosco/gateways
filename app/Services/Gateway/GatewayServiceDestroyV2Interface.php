<?php

namespace App\Services\Gateway;

use App\Models\Gateway;

interface GatewayServiceDestroyV2Interface
{
    public function destroyV2(Gateway $gateway);
}
