<?php
declare(strict_types = 1);

namespace App\DataMapper;

class UserPersistenceFactory extends PersistenceFactory
{
    public function getMapper(): Mapper
    {
        return new UserMapper();
    }

    public function getDomainObjectFactory(): DomainObjectFactory
    {
        return new UserObjectFactory();
    }

    public function getCollection(array $raw): Collection
    {
        return new UserCollection($raw, $this->getDomainObjectFactory());
    }

    public function getSelectionFactory(): SelectionFactory
    {
        return new UserSelectionFactory();
    }

    public function getUpdateFactory()
    {
        return new UserUpdateFactory();
    }

    public function getIdentityObject(): IdentityObject
    {
        return new UserIdentityObject();
    }
}
