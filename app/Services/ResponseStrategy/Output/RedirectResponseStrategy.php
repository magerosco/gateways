<?php

namespace App\Services\ResponseStrategy\Output;

use Throwable;
// use App\Facades\AdditionalDataRequest;
use App\Services\ResponseStrategy\AdditionalDataRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ResponseStrategy\ResponseStrategyInterface;
use App\Services\ResponseStrategy\OutputDataFormat\StrategyDataInterface;

class RedirectResponseStrategy implements ResponseStrategyInterface
{
    public function getResponse(StrategyDataInterface $data = null)
    {
        try {

            $service = AdditionalDataRequest::getInstance();
            $json_data = ['data' => $data !== null ? $data->getData()?->toJson() : null];

            if ($data !== null && !empty($data->getMessage())) {
                $result['message'] = $data->getMessage();
            }

            $statusCode = $data !== null ? $data->getHttpResponse() : Response::HTTP_OK;

            return redirect()->route($service->getRoute())->with($json_data, $statusCode);
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}
