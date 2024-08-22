<?php

namespace App\Services;

use SebastianBergmann\Type\Exception;
use App\Services\ResponseStrategy\ResponseStrategy;
use App\Services\ResponseStrategy\ApiResponseStrategy;
use App\Services\ResponseStrategy\ViewResponseStrategy;
use App\Services\ResponseStrategy\RedirectResponseStrategy;

class ResponseStrategyFactory
{
    public static function createStrategy(string $method): ResponseStrategy
    {
        switch ($method) {
            case 'API':
                return new ApiResponseStrategy();
            case 'index':
            case 'show':
            case 'edit':
                return new ViewResponseStrategy();
            case 'store':
            case 'update':
            case 'destroy':
                return new RedirectResponseStrategy();
            default:
                throw new Exception('Unknown method');
        }
    }
}
