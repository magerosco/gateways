<?php

namespace App\Services\Passport;

use App\Contracts\TokenRepositoryInterface;
use Laravel\Passport\TokenRepository;

class PassportTokenRepository implements TokenRepositoryInterface
{
    protected $repository;

    public function __construct(TokenRepository $repository)
    {
        $this->repository = $repository;
    }

    public function forUser($userId)
    {
        return $this->repository->forUser($userId);
    }

    public function revokeAccessToken($tokenId)
    {
        return $this->repository->revokeAccessToken($tokenId);
    }
}
