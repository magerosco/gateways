<?php

namespace App\Services\ResponseStrategy;

/**
 * Class ResponseContext
 * it's a context that will be used to execute the strategy
 *
 */
class ResponseContext
{
    protected $strategy;

    public function __construct()
    {
        $this->strategy = null;
    }

    /**
     * Used by the middleware to set a concrete response strategy.
     */
    public function setStrategy(ResponseStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * It is used by the controller to execute the strategy as a concrete instance of the response.
     */
    public function executeStrategy(StrategyDataInterface $data = null): mixed
    {
        return $this->strategy->getResponse($data);
    }
}
