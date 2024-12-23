<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;
use App\Contracts\RabbitMQServiceInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQService implements RabbitMQServiceInterface
{
    private $connection;
    private $channel;

    public function __construct(?AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        $this->channel = $connection ? $connection->channel() : null;
    }
    public function handle(): void{}

    public function isServerAvailable(): bool
    {
        return $this->connection !== null && $this->channel !== null;
    }

    public function sendMessage(string $message, string $queueName = 'default'): void
    {
        if (!$this->isServerAvailable()) {
            Log::warning('Cannot send message; RabbitMQ server is unavailable.');
            return;
        }

        try {
            // Declare the queue if it doesn't exist
            $this->channel->queue_declare($queueName, false, true, false, false);

            // Create and publish the message
            $msg = new AMQPMessage($message);
            $this->channel->basic_publish($msg, '', $queueName);

            Log::info("Message sent to queue '$queueName': $message");
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
        }
    }

    public function receiveMessage(string $queueName = 'default'): void
    {
        if (!$this->isServerAvailable()) {
            Log::warning('Cannot receive message; RabbitMQ server is unavailable.');
            return;
        }

        try {
            // Declare the queue if it doesn't exist
            $this->channel->queue_declare($queueName, false, true, false, false);

            // Consume messages from the queue
            Log::info("Waiting for messages in '$queueName'. To exit press CTRL+C");

            $callback = function ($msg) {
                Log::info('Received message: ' . $msg->body);

                $data = json_decode($msg->body, true);
                if ($data && isset($data['action'])) {
                    $this->processMessageAction($data['action'], $data['payload'] ?? []);
                }
            };

            // Start consuming
            $this->channel->basic_consume($queueName, '', false, true, false, false, $callback);

            // Wait for incoming messages
            while ($this->channel->is_consuming()) {
                $this->channel->wait();
            }
        } catch (\Exception $e) {
            Log::error('Error receiving message: ' . $e->getMessage());
        }
    }

    private function processMessageAction(string $action, array $payload): void
    {
        switch ($action) {
            case 'notify_user':
                // Implement a notification logic here
                Log::info('Notifying user with payload: ' . json_encode($payload));
                break;
            case 'update_database':
                // Example: Update a record in the database
                Log::info('Updating database with payload: ' . json_encode($payload));
                break;
            default:
                Log::warning("Unknown action: $action");
        }
    }

    public function close(): void
    {
        if ($this->channel !== null) {
            $this->channel->close();
        }
        if ($this->connection !== null) {
            $this->connection->close();
        }
    }
}
