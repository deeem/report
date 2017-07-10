<?php
declare(strict_types = 1);

namespace App\DataMapper;

class ReportPersistenceFactory extends PersistenceFactory
{
    public function getMapper(): Mapper
    {
        return new ReportMapper();
    }

    public function getDomainObjectFactory(): DomainObjectFactory
    {
        return new ReportObjectFactory();
    }

    public function getCollection(array $raw): Collection
    {
        return new ReportCollection($raw, $this->getDomainObjectFactory());
    }

    public function getSelectionFactory(): SelectionFactory
    {
        return new ReportSelectionFactory();
    }

    public function getUpdateFactory()
    {
        return new ReportUpdateFactory();
    }

    public function getIdentityObject(): IdentityObject
    {
        return new ReportIdentityObject();
    }
}
