<?php

namespace App\Contracts;

interface TokenRepositoryInterface
{
    public function forUser($userId);

    public function revokeAccessToken($tokenId);
}
