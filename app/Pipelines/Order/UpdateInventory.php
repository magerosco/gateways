<?php

namespace App\Pipelines\Order;

use Closure;
use App\DTO\ProductDTO;
use App\Models\Product;
use App\Services\Order\OrderLog;

class UpdateInventory
{
    public function handle($request, Closure $next)
    {
        foreach ($request->products as $product) {

            $product = ProductDTO::fromArray($product);
            $dbProduct = Product::find($product->id);
            $dbProduct->stock -= $product->quantity;
            $dbProduct->save();
        }

        OrderLog::log($request, __CLASS__);
        
        return $next($request);
    }
}
