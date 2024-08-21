<?php

namespace App\Http\Middleware;

use App\Facades\AdditionalDataRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ResponseStrategy\ResponseContext;
use App\Services\ResponseStrategy\ApiResponseStrategy;
use App\Services\ResponseStrategy\ViewResponseStrategy;

class ApiOrWebMiddleware
{
    public function __construct(protected ResponseContext $responseContext, protected ApiResponseStrategy $apiStrategy, protected ViewResponseStrategy $viewStrategy)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('api/*')) {
            $strategy  = app(ApiResponseStrategy::class);
        } else {
            $strategy = app(ViewResponseStrategy::class);
        }
        $this->responseContext->setStrategy($strategy);

        $action = $request->route()->getAction();
        $controller = class_basename($action['controller']);
        [$controllerName, $methodName] = explode('@', $controller);

        AdditionalDataRequest::setValue([
            'controller' => $controllerName,
            'method' => $methodName,
            'view' => $action['as'],
            'resource' => 'App\\Http\\Resources\\' . ucwords(str_replace('Controller', 'Resource', $controllerName)),
        ]);

        return $next($request);
    }
}
