<?php

namespace App\Contracts;

interface PersonalAccessTokenFactoryInterface
{
    public function make($userId, $name, array $scopes = []);
}
