<?php

namespace App\Pipelines\Order;

use Closure;
use App\Services\Order\OrderLog;

class ApplyDiscounts
{

    public function handle($request, Closure $next)
    {
        $request->merge(['discount' => 0]);
        if ($request->has('coupon')) {

            $discount = 10; //mocked discount
            $request->merge(['discount' => $discount]);
        }

        OrderLog::log($request, __CLASS__);

        return $next($request);
    }
}
