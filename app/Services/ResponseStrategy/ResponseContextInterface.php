<?php

namespace App\Services\ResponseStrategy;

use Symfony\Component\HttpFoundation\Response;

class ResponseContextInterface
{
    protected $strategy;

    public function __construct()
    {
        $this->strategy = null;
    }

    public function setStrategy(ResponseStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function executeStrategy($data, $message = null, $response = Response::HTTP_OK)
    {
        return $this->strategy->getResponse($data, $message, $response);
    }
}
