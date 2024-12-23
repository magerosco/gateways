<?php

namespace App\Contracts;

interface TokenServiceInterface
{
    public function generateTokenForUser($user);
}
