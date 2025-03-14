<?php

namespace App\Services\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderLog
{
    public static function log(Request $request, string $class, string $message = '')	
    {
        $orderData = $request->only(['user_id', 'payment_method', 'coupon', 'products', 'discount']);
        \Illuminate\Support\Facades\Log::channel('order_processing')->info($class . ': '. $message, ['order' => $orderData]);
    }
}
