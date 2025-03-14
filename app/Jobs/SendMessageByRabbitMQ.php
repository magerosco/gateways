<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMessageByRabbitMQ implements ShouldQueue
{
    use Queueable;


    public function __construct(public User $user) {}

    public function handle(): void
    {

        Log::channel('rabbitmq')
            ->info(
                __CLASS__ . ': Sending welcome email to ',
                [
                    'user_email' => $this->user->email,
                    'created_at' => $this->user->created_at
                ]
            );
    }
}
