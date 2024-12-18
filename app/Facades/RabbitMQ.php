<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RabbitMQ extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Contracts\RabbitMQServiceInterface::class;
    }
}
