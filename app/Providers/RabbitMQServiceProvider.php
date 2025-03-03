<?php

namespace App\Providers;

use App\Services\RabbitMQService;
use App\Traits\RabbitMQConnection;
use Illuminate\Support\Facades\Log;
use App\Services\RabbitMQFakeService;
use Illuminate\Support\ServiceProvider;
use App\Contracts\RabbitMQServiceInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQServiceProvider extends ServiceProvider
{
    use RabbitMQConnection;

    public function register()
    {
        if (!$this->isRabbitMQAvailable()) {
            $this->app->singleton(RabbitMQServiceInterface::class, function ($app) {
                Log::channel('rabbitmq')->warning(__CLASS__ . ": (isRabbitMQAvailable = False) RabbitMQ is not available. Running with a null connection.");
                return new RabbitMQFakeService();
            });
        } else {

            // This system must be able to handle RabbitMQ unavailability
            // by returning a null-safe service
            $this->app->singleton(RabbitMQServiceInterface::class, function ($app) {
                return $this->createRabbitMQService();
            });
        }
    }

    private function createRabbitMQService(): RabbitMQService
    {
        try {
            $connection = new AMQPStreamConnection(
                env('RABBITMQ_HOST', 'localhost'),
                env('RABBITMQ_PORT', 5672),
                env('RABBITMQ_USER', 'guest'),
                env('RABBITMQ_PASSWORD', 'guest'),
                env('RABBITMQ_VHOST', '/')
            );
            return new RabbitMQService($connection);
        } catch (\Throwable $e) {

            Log::channel('rabbitmq')->error(__METHOD__ . ': (Throwable) Failed to connect to RabbitMQ: ' . $e->getMessage());
            return new RabbitMQFakeService();
        }
    }

    public function boot()
    {
        //
    }
}
