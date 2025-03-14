<?php

namespace App\Contracts;

interface TokenServiceInterface
{
    public function getTokenName();

    public function generateTokenForUser($user);

    public function revokeExistingTokens($user);
}
