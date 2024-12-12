<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class APIVersionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get version from headers, query parameters, or  set a default version if none
        $apiVersion = $request->header('Accept-Version') ?? ($request->query('version') ?? 'v1');

        if ($apiVersion === 'v2') {
            $newPath = str_replace('api/', 'api/v2/', $request->getRequestUri());
            return redirect($newPath);
        }

        if ($apiVersion && $apiVersion !== 'v1') {
            return response()->json(['error' => 'Unsupported API version'], 400);
        }

        return $next($request);
    }
}
