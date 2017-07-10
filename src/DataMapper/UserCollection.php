<?php
declare(strict_types = 1);

namespace App\DataMapper;

use App\DomainObject\User;

class UserCollection extends Collection
{
    public function targetClass(): string
    {
        return User::class;
    }
}
