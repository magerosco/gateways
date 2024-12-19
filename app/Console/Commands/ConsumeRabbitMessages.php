<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Facades\RabbitMQ;

class ConsumeRabbitMessages extends Command
{
    protected $signature = 'rabbit:consume {queues=default}';
    protected $description = 'Consume messages from one or multiple RabbitMQ queues';

    public function handle()
    {
        $queueNames = explode(',', $this->argument('queues'));

        foreach ($queueNames as $queueName) {
            $queueName = trim($queueName);
            $this->info("Starting to consume messages from queue: $queueName");

            RabbitMQ::receiveMessage($queueName);
        }

        $this->info('Stopped consuming messages from all queues: ' . implode(', ', $queueNames));
    }
}
