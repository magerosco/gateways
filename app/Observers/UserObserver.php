<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Log::channel('registered_user')->info('Sending welcome email to ', ['user_email' => $user->email, 'created_at' => $user->created_at]);
        $user->createToken('Personal Access Token')->plainTextToken;
    }
}
