<?php

namespace App\Observers;

use App\Models\User;
use App\Traits\RabbitMQConnection;
use App\Jobs\SendMessageByRabbitMQ;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    use RabbitMQConnection;
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Log::channel('registered_user')->info('Sending welcome email to ', ['user_email' => $user->email, 'created_at' => $user->created_at]);
        $user->createToken('Personal Access Token')->plainTextToken;


        if ($this->isRabbitMQAvailable()) {
            dispatch(new SendMessageByRabbitMQ($user));
        }else{
            Log::channel('rabbitmq')->warning(__METHOD__ . ": RabbitMQ is not available.");
        }
    }
}
