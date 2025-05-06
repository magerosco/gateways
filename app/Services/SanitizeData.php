<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Log;

class SanitizeData
{
    private HTMLPurifier $purifier;

    public function __construct(HTMLPurifier_Config $config)
    {
        $this->purifier = new HTMLPurifier($config);
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
                    return $this->sanitizeInputs(json_decode(json_encode($value), true));
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
