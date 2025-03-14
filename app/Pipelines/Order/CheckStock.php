<?php

namespace App\Pipelines\Order;

use Closure;
use App\DTO\ProductDTO;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\Order\OrderLog;

class CheckStock
{
    public function handle(Request $request, Closure $next)
    {
        foreach ($request->products as $product) {
            $product = ProductDTO::fromArray($product);
            $dbProduct = Product::findOrFail($product->id);
            if ($dbProduct->stock < $product->quantity) {
                return response()->json(['error' => "Insufficient stock for  {$dbProduct->name}"], 400);
            }
        }

        OrderLog::log($request, __CLASS__);

        return $next($request);
    }
}
