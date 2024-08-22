<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use SebastianBergmann\Type\Exception;
use App\Facades\AdditionalDataRequest;
use App\Services\ResponseStrategyFactory;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ResponseStrategy\ResponseContext;

class ApiOrWebMiddleware
{

    public function __construct(protected ResponseContext $responseContext)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * Set additional data request:
         * this will add the controller, method, view and resource. It's
         * some dinamic data to be used in the strategy to identify and build
         * the response. A facade will be used.
         */
        $this->setAdditionalDataRequest($request);

        $this->defineResponseStrategy();

        return $next($request);
    }

    private function setAdditionalDataRequest(Request $request): void
    {
        $action = $request->route()->getAction();
        $controller = class_basename($action['controller']);
        [, $methodName] = explode('@', $controller);

        //facades
        AdditionalDataRequest::setValue([
            'method' => $request->is('api/*') ? 'API' : $methodName,
            'view' => $request->route()->getName(),
            'route' => $request->route()->getName(),
        ]);
    }

    /**
     * Used factory design patterns to create the response strategy.
     */
    public function defineResponseStrategy()
    {
        $dataRequest = AdditionalDataRequest::getValue();

        try {
            $strategy = ResponseStrategyFactory::createStrategy($dataRequest['method']);
        } catch (Exception $e) {
            throw new Exception('Unknown method');
        }

        $this->responseContext->setStrategy($strategy);
    }
}
