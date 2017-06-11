<?php
declare(strict_types = 1);

namespace App;

class UserCollection extends Collection
{
    public function targetClass(): string
    {
        return User::class;
    }
}
