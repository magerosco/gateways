<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;
use App\Contracts\RabbitMQServiceInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQFakeService implements RabbitMQServiceInterface
{

    public function sendMessage(string $message, string $queueName = 'default'): void
    {
        Log::channel('rabbitmq')->warning(__METHOD__ . ' : Cannot send message; RabbitMQ server is unavailable.');
    }

    public function receiveMessage(string $queueName = 'default'): void
    {
        Log::channel('rabbitmq')->warning(__METHOD__ . ': Cannot receive message; RabbitMQ server is unavailable.');
    }

    public function close(): void
    {
        Log::channel('rabbitmq')->warning(__METHOD__ . ': Cannot close connection; RabbitMQ server is unavailable.');
    }
}
