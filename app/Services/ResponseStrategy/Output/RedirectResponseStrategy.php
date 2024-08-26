<?php

namespace App\Services\ResponseStrategy;

use App\Facades\AdditionalDataRequest;
use Symfony\Component\HttpFoundation\Response;

class RedirectResponseStrategy implements ResponseStrategy
{
    public function getResponse(StrategyDataInterface $data = null)
    {
        $dataRequest = AdditionalDataRequest::getValue();

        $json_data = ['data' => $data !== null ? $data->getData()?->toJson() : null];

        if ($data !== null && !empty($data->getMessage())) {
            $result['message'] = $data->getMessage();
        }

        $statusCode = $data !== null ? $data->getHttpResponse() : Response::HTTP_OK;

        return redirect()
            ->route($dataRequest['route'])
            ->with($json_data, $statusCode);
    }
}
