<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use App\Pipelines\Order\CheckStock;
use App\Pipelines\Order\NotifyUser;
use App\Http\Controllers\Controller;
use App\Pipelines\Order\ApplyDiscounts;
use App\Pipelines\Order\ProcessPayment;
use App\Pipelines\Order\UpdateInventory;

class OrderController extends Controller
{
    // EXAMPLE: Using Chain of Responsibility Pattern to create
    // a pipeline to process an order
    public function processOrder(Request $request)
    {
        try {
            $result = app(Pipeline::class)
                ->send($request)
                ->through([
                    CheckStock::class,
                    ApplyDiscounts::class,
                    ProcessPayment::class,
                    UpdateInventory::class,
                    NotifyUser::class
                ])
                ->thenReturn();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['message' => 'Payment successful'], Response::HTTP_CREATED);
    }
}
