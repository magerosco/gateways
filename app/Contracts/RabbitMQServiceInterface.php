<?php

namespace App\Contracts;

interface RabbitMQServiceInterface
{
    public function sendMessage(string $message, string $queueName = 'default'): void;

    public function receiveMessage(string $queueName = 'default'): void;

    public function close(): void;
}
