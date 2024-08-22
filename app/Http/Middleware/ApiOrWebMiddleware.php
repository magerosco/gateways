<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use SebastianBergmann\Type\Exception;
use App\Facades\AdditionalDataRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ResponseStrategy\ApiResponseStrategy;
use App\Services\ResponseStrategy\ViewResponseStrategy;
use App\Services\ResponseStrategy\RedirectResponseStrategy;
use App\Services\ResponseStrategy\ResponseContextInterface;

class ApiOrWebMiddleware
{
    private static $map = [
        'API' => ApiResponseStrategy::class,
        'index' => ViewResponseStrategy::class,
        'store' => RedirectResponseStrategy::class,
        'show' => ViewResponseStrategy::class,
        'edit' => ViewResponseStrategy::class,
        'update' => RedirectResponseStrategy::class,
        'destroy' => RedirectResponseStrategy::class,
    ];

    public function __construct(protected ResponseContextInterface $responseContext, protected ApiResponseStrategy $apiStrategy, protected ViewResponseStrategy $viewStrategy)
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

    public function defineResponseStrategy()
    {
        $dataRequest = AdditionalDataRequest::getValue();

        if (!array_key_exists($dataRequest['method'], self::$map)) {
            throw new Exception('Unknown method');
        }

        $className = self::$map[$dataRequest['method']];
        $strategy = new $className();

        $this->responseContext->setStrategy($strategy);
    }
}
