<?php
declare(strict_types = 1);

namespace App\DataMapper;

use App\DomainObject\Event;
use App\DomainObject\Report;
use App\DomainObject\User;
use App\AppException;

abstract class PersistenceFactory
{
    abstract public function getMapper(): Mapper;
    abstract public function getDomainObjectFactory(): DomainObjectFactory;
    abstract public function getCollection(array $raw): Collection;

    public static function getFactory($targetclass): PersistenceFactory
    {
        switch ($targetclass) {
            case Event::class:
                return new EventPersistenceFactory();
                break;
            case Report::class:
                return new ReportPersistenceFactory();
                break;
            case User::class:
                return new UserPersistenceFactory();
                break;
            default:
                throw new AppException("Unknown class {$targetclass}");
                break;
        }
    }
}
