<?php

namespace App\Http\Middleware;

use Closure;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use App\Services\SanitizeData;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputMiddleware
{

    private SanitizeData $sanitizer;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', '');

        // SanitizeData expects a HTMLPurifier_Config object
        $this->sanitizer = new SanitizeData($config);
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->merge($this->sanitizer->sanitizeInputs($request->all()));

        return $next($request);
    }
}
