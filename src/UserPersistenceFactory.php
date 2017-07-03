<?php
declare(strict_types = 1);

namespace App;

class UserPersistenceFactory extends PersistenceFactory
{
    public function getMapper(): Mapper
    {
        return new UserMapper();
    }

    public function getDomainOjbectFactory(): ObjectFactory
    {
        return new UserObjectFactory();
    }

    public function getCollection(array $raw): Collection
    {
        return new UserCollection($raw, $this->getDomainObjectFactory());
    }
}
