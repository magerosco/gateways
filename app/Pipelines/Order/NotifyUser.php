<?php

namespace App\Pipelines\Order;

use Closure;
use App\Models\User;
use App\Services\Order\OrderLog;
use Illuminate\Support\Facades\Log;

class NotifyUser
{
    public function handle($request, Closure $next)
    {
        $user = User::find($request->user_id);

        if (empty($user)) {
            Log::error('User not found');
            return response()->json(['error' => 'User not found'], 400);
        }
        
        OrderLog::log($request, __CLASS__, 'Payment successful: Email sent to ' . $user->email);

        return $next($request);
    }
}
