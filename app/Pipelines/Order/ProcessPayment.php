<?php

namespace App\Pipelines\Order;

use Closure;
use App\Services\Order\OrderLog;

class ProcessPayment
{
    public function handle($request, Closure $next)
    {

        if ($request->payment_method !== 'valid_card') {
            return response()->json(["error" => "The payment method is not valid."], 400);
        }

        OrderLog::log($request, __CLASS__);
        
        return $next($request);
    }
}
