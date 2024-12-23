<?php

namespace App\Services\Passport;

use App\Traits\RoleScopeMapper;
use App\Contracts\TokenServiceInterface;
use App\Contracts\TokenRepositoryInterface;
use App\Contracts\PersonalAccessTokenFactoryInterface;

class PassportTokenService implements TokenServiceInterface
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
        foreach ($this->tokenRepository->forUser($user->getKey()) as $token) {
            $this->tokenRepository->revokeAccessToken($token->id);
        }
    }

    public function generateTokenForUser($user)
    {
        $this->revokeExistingTokens($user);

        $scopes = $this->determineScopesBasedOnRole($user->getRoleNames()->all());
        $token = $this->tokenFactory->make($user->getKey(), 'User Personal Token', $scopes);

        return [
            'access_token' => $token->accessToken,
            'expires_at' => $token->token->expires_at,
        ];
    }
}
