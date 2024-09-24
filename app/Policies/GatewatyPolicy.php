<?php

namespace App\Policies;

use App\Models\Gateway;
use App\Models\User;

class GatewatyPolicy
{
    //A mocked logic to apply the policy.
    public function delete(User $user, Gateway $gateway): bool
    {
        return str_contains($gateway->name, 'policy') || $user->email == 'tester@example.com';
    }
}
