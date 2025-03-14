<?php

namespace App\Helpers;

use Illuminate\Http\Response;

class AuthorizationResponse
{
    /**
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function denied(
        string $message = 'You are not authorized.',
        int $statusCode = Response::HTTP_FORBIDDEN
    ) {
        return response()->json([
            'message' => $message,
        ], $statusCode);
    }
}
