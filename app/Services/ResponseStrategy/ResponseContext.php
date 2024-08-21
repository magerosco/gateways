<?php

namespace App\Services\ResponseStrategy;

class ResponseContext
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
