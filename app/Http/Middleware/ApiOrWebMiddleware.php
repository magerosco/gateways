<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use SebastianBergmann\Type\Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ResponseStrategy\ResponseStrategyFactory;
use App\Services\ResponseStrategy\ResponseContextInterface;
use App\Services\ResponseStrategy\Facades\AdditionalDataRequest;

class ApiOrWebMiddleware
{
    public function __construct(protected ResponseContextInterface $responseContext)
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

        /**
         * facades, the intentions is to have a dinamic data abaliable for all app
         *  without need to create dependencies
         **/

        AdditionalDataRequest::setMethod($request->is('api/*') ? 'API' : $methodName);
        AdditionalDataRequest::setView($request->route()->getName());
        AdditionalDataRequest::setRoute($request->route()->getName());
    }

    /**
     * Used factory design patterns to create the response strategy.
     */
    public function defineResponseStrategy()
    {
        try {
            //Factory Method, returns a concrete instance based on the ResponseStrategy interface.
            $strategy = ResponseStrategyFactory::createStrategy(AdditionalDataRequest::getMethod());
        } catch (Exception $e) {
            throw new Exception('Unknown method');
        }

        $this->responseContext->setStrategy($strategy);
    }
}
