<?php

namespace App\Services\ResponseStrategy;

use App\Facades\AdditionalDataRequest;
use App\Http\Resources\GatewayResource;

class ViewResponseStrategy implements ResponseStrategy
{
    public function getResponse($data)
    {
        $dataRequest = AdditionalDataRequest::getValue();

        $json_data = $dataRequest['resource']::collection($data)->toJson();
        return view($dataRequest['view'], ['data' => $json_data]);
    }
}
