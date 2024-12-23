<?php
namespace App\Services\Passport;

use App\Contracts\PersonalAccessTokenFactoryInterface;
use Laravel\Passport\PersonalAccessTokenFactory;

class PassportPersonalAccessTokenFactory implements PersonalAccessTokenFactoryInterface
{
    protected $factory;

    public function __construct(PersonalAccessTokenFactory $factory)
    {
        $this->factory = $factory;
    }

    public function make($userId, $name, array $scopes = [])
    {
        return $this->factory->make($userId, $name, $scopes);
    }
}
