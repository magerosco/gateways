<?php

namespace App\Services\ResponseStrategy;

use Illuminate\Http\Request;
use App\Facades\AdditionalDataRequest;
use App\Http\Resources\GatewayResource;

class ApiResponseStrategy implements ResponseStrategy
{
    public function getResponse($data)
    {
        $dataRequest = AdditionalDataRequest::getValue();

        return $dataRequest['resource']::collection($data);
    }
}
