<?php

namespace App\Providers;

use App\Services\RabbitMQService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use App\Contracts\RabbitMQServiceInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQServiceProvider extends ServiceProvider
{
    public function register()
    {

        // This system must be able to handle RabbitMQ unavailability
        // by returning a null-safe service
        $this->app->singleton(RabbitMQServiceInterface::class, function ($app) {

            try {
                $connection = new AMQPStreamConnection(
                    env('RABBITMQ_HOST', 'localhost'),
                    env('RABBITMQ_PORT', 5672),
                    env('RABBITMQ_USER', 'guest'),
                    env('RABBITMQ_PASSWORD', 'guest'),
                    env('RABBITMQ_VHOST', '/')
                );

                return new RabbitMQService($connection);
            } catch (\Exception $e) {
                // Log the error and return a null-safe service
                Log::error('Failed to connect to RabbitMQ: ' . $e->getMessage());
                return new RabbitMQService(null); // Pass null to handle RabbitMQ unavailability
            }
        });
    }

    public function boot()
    {
        //
    }
}
