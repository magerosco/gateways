<?php

namespace App\Policies;

use App\Models\Gateway;
use App\Models\User;

class GatewayPolicy
{
    //A mocked logic to apply the policy.
    public function delete(User $user, Gateway $gateway): bool
    {
        return str_contains($gateway->name, 'policy') || $user->email == 'admin@admin.com';
    }

    public function __call($ability, $arguments)
    {
        return false; // deny all
    }
}
