<?php

namespace App\Services\Passport;

use App\Traits\RoleScopeMapper;
use App\Contracts\TokenServiceInterface;
use App\Contracts\TokenRepositoryInterface;
use App\Contracts\PersonalAccessTokenFactoryInterface;

class PassportTokenService implements TokenServiceInterface
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
        foreach ($this->tokenRepository->forUser($user->getKey()) as $token) {
            $this->tokenRepository->revokeAccessToken($token->id);
        }
    }

    public function generateTokenForUser($user)
    {
        $this->revokeExistingTokens($user);

        $scopes = $this->determineScopesBasedOnRole($user->getRoleNames()->all());
        $token = $this->tokenFactory->make($user->getKey(), $this->tokenName, $scopes);

        return [
            'accessToken' => $token->accessToken,
            'expiresAt' => $token->token->expires_at,
        ];
    }
}
