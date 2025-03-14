<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        Log::channel('registered_user')->info('Sending welcome email to ', ['user_email' => $event->user->email, 'created_at' => $event->user->created_at]);
    }
}
