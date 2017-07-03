<?php
declare(strict_types = 1);

namespace App;

class ReportPersistenceFactory extends PersistenceFactory
{
    public function getMapper(): Mapper
    {
        return new ReportMapper();
    }

    public function getDomainOjbectFactory(): ObjectFactory
    {
        return new ReportObjectFactory();
    }

    public function getCollection(array $raw): Collection
    {
        return new ReportCollection($raw, $this->getDomainObjectFactory());
    }
}
