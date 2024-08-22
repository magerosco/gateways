<?php

namespace App\Services\ResponseStrategy;

use App\Facades\AdditionalDataRequest;
use Symfony\Component\HttpFoundation\Response;

class RedirectResponseStrategy implements ResponseStrategy
{
    public function getResponse($data = null, $message = null, $response = Response::HTTP_OK)
    {
        $dataRequest = AdditionalDataRequest::getValue();

        $json_data = ['data' => $data?->toJson()];

        if ($message) {
            $json_data['message'] = $message;
        }
        return redirect()
            ->route($dataRequest['route'])
            ->with($json_data, $response);
    }
}
