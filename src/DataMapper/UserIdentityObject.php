<?php
declare(strict_types = 1);

namespace App\DataMapper;

class UserIdentityObject extends IdentityObject
{
    public function __construct(string $field = null)
    {
        parent::__construct(
            $field,
            ['id', 'name']
        );
    }
}
