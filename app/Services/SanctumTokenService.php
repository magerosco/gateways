<?php

namespace App\Services;

use App\Traits\RoleScopeMapper;
use App\Contracts\TokenServiceInterface;
use App\Contracts\TokenRepositoryInterface;
use App\Contracts\PersonalAccessTokenFactoryInterface;

class SanctumTokenService implements TokenServiceInterface
{
    use RoleScopeMapper;

    private $tokenName = 'User Personal Token';

    public function __construct(
        protected PersonalAccessTokenFactoryInterface $tokenFactory,
        protected TokenRepositoryInterface $tokenRepository
    ) {
        $this->tokenFactory = $tokenFactory;
        $this->tokenRepository = $tokenRepository;
    }

    public  function getTokenName()
    {
        return $this->tokenName;
    }

    public function revokeExistingTokens($user)
    {
        $user->tokens()->where('name', $this->tokenName)->delete();
    }

    public function generateTokenForUser($user)
    {
        $this->revokeExistingTokens($user);
        return $user->createToken($this->tokenName)->plainTextToken;
    }
}
