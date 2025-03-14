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
        /**
         * Deny all.. Always returns false, which implies that it denies any ability
        * (action) not defined in the policy. This ensures that any action not explicitly
        * allowed will be denied.
         */
        return false;
    }
}
