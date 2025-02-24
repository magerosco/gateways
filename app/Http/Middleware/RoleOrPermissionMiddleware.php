<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AuthorizationResponse;
use Symfony\Component\HttpFoundation\Response;

class RoleOrPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        $route = $request->route()->getName();
        $user = $request->user();

        if (!empty($role) && ($user->hasRole($role) || $user->can($route))) {
            return $next($request);
        }

        return AuthorizationResponse::denied();
    }
}
