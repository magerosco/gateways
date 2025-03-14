<?php

namespace App\Traits;


trait RabbitMQConnection
{
    public function isRabbitMQAvailable(): bool
    {
        $host = env('RABBITMQ_HOST', 'localhost');
        $port = env('RABBITMQ_PORT', 5672);

        $connection = @fsockopen($host, $port, $errno, $errstr, 1); // Timeout 1s

        if ($connection) {
            fclose($connection);
            return true;
        }

        return false;
    }
}
