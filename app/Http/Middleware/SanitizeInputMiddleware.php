<?php

namespace App\Http\Middleware;

use Closure;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputMiddleware
{
    private HTMLPurifier $purifier;
    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', '');
        $this->purifier = new HTMLPurifier($config);
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->merge($this->sanitizeInputs($request->all()));

        return $next($request);
    }

    public function sanitizeInputs(array $inputs): mixed
    {
        $sanitizedInputs = [];

        foreach ($inputs as $key => $value) {
            $sanitizedInputs[$key] = $this->sanitizeValue($key, $value);
        }

        return $sanitizedInputs;
    }

    private function sanitizeValue(string $key, mixed $value): mixed
    {
        switch (true) {
            case is_string($value):
                return $this->purifier->purify($value);

            // use is_numeric could accept strings like "123" or scientific notation like "1.2e3"
            case is_int($value) || is_float($value) || is_bool($value) || is_null($value):
                return $value;

            case is_array($value):
                return $this->sanitizeInputs($value);

            case is_object($value):
                try {
                    return $this->sanitizeInputs(get_object_vars($value));
                } catch (\Throwable $e) {
                    Log::error("Failed to sanitize object at key: $key", ['exception' => $e]);
                    throw new \UnexpectedValueException("Invalid object input for key: $key");
                }

            default:
                // At this point the value is not Null, Object, Array, or Scalar. Then I consider it invalid
                Log::warning("Sanitization skipped for non-scalar value at key: $key", ['value' => $value]);
                throw new \UnexpectedValueException("Invalid input type for key $key");
        }
    }
}
