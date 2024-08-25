<?php

namespace App\Services\ResponseStrategy;

use Symfony\Component\HttpFoundation\Response;
use App\Services\ResponseStrategy\StrategyDataInterface;

class ApiResponseStrategy implements ResponseStrategy
{
    public function getResponse(StrategyDataInterface $data = null)
    {
        $result = ['data' => $data !== null ? $data->getData() : null];

        if ($data !== null && !empty($data->getMessage())) {
            $result['message'] = $data->getMessage();
        }

        $statusCode = $data !== null ? $data->getHttpResponse() : Response::HTTP_OK;

        return response()->json($result, $statusCode);
    }
}
