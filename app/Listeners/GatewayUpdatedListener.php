<?php

namespace App\Listeners;

use App\Events\GatewayUpdated;
use Illuminate\Support\Facades\Log;

class GatewayUpdatedListener
{
    public function __construct()
    {
        //
    }

    public function handle(GatewayUpdated $event): void
    {

        $gateway = $event->gateway;

        // Log listener triggered using decorator repository logic to uncoupled code
        Log::info('GatewayUpdatedListener triggered: ', ['gateway' => $gateway]);
    }
}
