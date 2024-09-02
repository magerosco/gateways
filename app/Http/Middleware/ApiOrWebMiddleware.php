<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use SebastianBergmann\Type\Exception;
// use App\Facades\AdditionalDataRequest;
use App\Services\ResponseStrategy\AdditionalDataRequest;
use App\Services\ResponseStrategy\ResponseStrategyFactory;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ResponseStrategy\ResponseContextInterface;

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
        $service = AdditionalDataRequest::getInstance();
        $this->setAdditionalDataRequest($request,$service);

        $this->defineResponseStrategy($service);

        return $next($request);
    }

    private function setAdditionalDataRequest(Request $request,$service): void
    {
        $action = $request->route()->getAction();
        $controller = class_basename($action['controller']);
        [, $methodName] = explode('@', $controller);

        /**
         * facades, the intentions is to have a dinamic data abaliable for all app
         *  without need to create dependencies
         **/



         $service->setMethod($request->is('api/*') ? 'API' : $methodName);
         $service->setView($request->route()->getName());
         $service->setRoute($request->route()->getName());
    }

    /**
     * Used factory design patterns to create the response strategy.
     */
    public function defineResponseStrategy($service)
    {
        try {
            //Factory Method, returns a concrete instance based on the ResponseStrategy interface.
            $strategy = ResponseStrategyFactory::createStrategy($service->getMethod());
        } catch (Exception $e) {
            throw new Exception('Unknown method');
        }

        $this->responseContext->setStrategy($strategy);
    }
}
