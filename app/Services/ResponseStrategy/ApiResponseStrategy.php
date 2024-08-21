<?php

namespace App\Services\ResponseStrategy;

use App\Facades\AdditionalDataRequest;

class ApiResponseStrategy implements ResponseStrategy
{
    public function getResponse($data)
    {
        $dataRequest = AdditionalDataRequest::getValue();
        return $dataRequest['resource']::collection($data);
    }
}
