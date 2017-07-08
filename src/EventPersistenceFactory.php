<?php
declare(strict_types = 1);

namespace App;

class EventPersistenceFactory extends PersistenceFactory
{
    public function getMapper(): Mapper
    {
        return new EventMapper();
    }

    public function getDomainObjectFactory(): DomainObjectFactory
    {
        return new EventObjectFactory();
    }

    public function getCollection(array $raw): Collection
    {
        return new EventCollection($raw, $this->getDomainObjectFactory());
    }

    public function getSelectionFactory(): SelectionFactory
    {
        return new EventSelectionFactory();
    }

    public function getUpdateFactory()
    {
        return new EventUpdateFactory();
    }

    public function getIdentityObject(): IdentityObject
    {
        return new EventIdentityObject();
    }
}
