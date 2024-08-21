<?php

namespace App\Services\ResponseStrategy;

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

    public function executeStrategy($data)
    {
        return $this->strategy->getResponse($data);
    }
}
