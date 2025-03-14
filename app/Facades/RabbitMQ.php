<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RabbitMQ extends Facade
{
    public function handle()
    {
    }

    protected static function getFacadeAccessor()
    {
        return \App\Contracts\RabbitMQServiceInterface::class;
    }
}
