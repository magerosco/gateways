<?php

namespace App\Services\ResponseStrategy;

interface ResponseStrategy
{
    public function getResponse(StrategyDataInterface $data);
}
