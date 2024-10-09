<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Anasa\ResponseStrategy\ResponseContextInterface;
use Anasa\ResponseStrategy\OutputDataFormat\StrategyDataInterface;

class GatewayActionMiddleware
{
    public function __construct(protected ResponseContextInterface $responseContext, protected StrategyDataInterface $strategyData)
    {
    }

    public function handle(Request $request, Closure $next, $ability)
    {
        $gateway = $request->route('gateway');

        if (!Gate::allows($ability, $gateway)) {
            return $this->responseContext->executeStrategy($this->strategyData->setStrategyData([], 'You are not authorized.', Response::HTTP_FORBIDDEN));
        }

        return $next($request);
    }
}
