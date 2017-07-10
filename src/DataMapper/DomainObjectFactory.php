<?php
declare(strict_types = 1);

namespace App\DataMapper;

use App\DomainObject\DomainObject;

abstract class DomainObjectFactory
{
    abstract public function createObject(array $row): DomainObject;

    protected function getFromMap($class, $id)
    {
        return ObjectWatcher::exists($class, $id);
    }

    protected function addToMap(DomainObject $obj): DomainObject
    {
        return ObjectWatcher::add($obj);
    }
}
