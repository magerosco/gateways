<?php

namespace App\Services\ResponseStrategy;

use Symfony\Component\HttpFoundation\Response;
use App\Facades\AdditionalDataRequest;

class ApiResponseStrategy implements ResponseStrategy
{
    public function getResponse($data = null, $message = null, $response = Response::HTTP_OK)
    {
        $result = ['data' => $data];

        if ($message) {
            $result['message'] = $message;
        }

        return response()->json($result, $response);
    }
}
