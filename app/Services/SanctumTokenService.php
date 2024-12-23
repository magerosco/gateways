<?php

namespace App\Services;

use App\Traits\RoleScopeMapper;
use App\Contracts\TokenServiceInterface;
use App\Contracts\TokenRepositoryInterface;
use App\Contracts\PersonalAccessTokenFactoryInterface;

class SanctumTokenService implements TokenServiceInterface
{
    use RoleScopeMapper;

    protected $tokenFactory;
    protected $tokenRepository;

    public function __construct(PersonalAccessTokenFactoryInterface $tokenFactory, TokenRepositoryInterface $tokenRepository)
    {
        $this->tokenFactory = $tokenFactory;
        $this->tokenRepository = $tokenRepository;
    }

    public function revokeExistingTokens($user)
    {
        $user->tokens()->where('name', 'Personal Access Token')->delete();
    }

    public function generateTokenForUser($user)
    {
        $this->revokeExistingTokens($user);
        return $user->createToken('Personal Access Token')->plainTextToken;

    }
}
