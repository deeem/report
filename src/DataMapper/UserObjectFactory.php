<?php
declare(strict_types = 1);

namespace App\DataMapper;

use App\DomainObject\DomainObject;
use App\DomainObject\User;

class UserObjectFactory extends DomainObjectFactory
{
    public function createObject(array $row): DomainObject
    {
        $old = $this->getFromMap(User::class, $row['id']);

        if ($old) {
            return $old;
        }

        $obj = new User((int)$row['id'], $row['name']);

        $this->addToMap($obj);

        $reportmapper = new ReportMapper();
        $reportcollection = $reportmapper->findByUser($row['id']);
        $obj->setReports($reportcollection);

        return $obj;
    }
}
