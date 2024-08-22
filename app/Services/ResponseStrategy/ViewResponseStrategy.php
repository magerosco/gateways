<?php

namespace App\Services\ResponseStrategy;

use App\Facades\AdditionalDataRequest;

class ViewResponseStrategy implements ResponseStrategy
{
    public function getResponse($data = null)
    {
        $dataRequest = AdditionalDataRequest::getValue();

        $json_data = $data?->toJson();
        return view($dataRequest['view'], ['data' => $json_data]);
    }
}
