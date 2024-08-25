<?php

namespace App\Services\ResponseStrategy;

use App\Facades\AdditionalDataRequest;

class ViewResponseStrategy implements ResponseStrategy
{
    public function getResponse(StrategyDataInterface $data = null)
    {
        $dataRequest = AdditionalDataRequest::getValue();

        $json_data = ['data' => $data !== null ? $data->getData()?->toJson() : null];

        return view($dataRequest['view'], ['data' => $json_data]);
    }
}
