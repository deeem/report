<?php
declare(strict_types = 1);

namespace App\DataMapper;

use App\DomainObject\Report;
use App\DomainObject\DomainObject;

class ReportObjectFactory extends DomainObjectFactory
{
    public function createObject(array $row): DomainObject
    {
        $old = $this->getFromMap(Report::class, $row['id']);

        if ($old) {
            return $old;
        }

        $obj = new Report(
            (int)$row['id'],
            $row['name'],
            json_decode($row['data'], true)
        );

        $this->addToMap($obj);

        $usermapper = new UserMapper();
        $user = $usermapper->find((int)$row['user']);
        $obj->setUser($user);
        $eventmapper = new EventMapper();
        $event = $eventmapper->find((int)$row['event']);
        $obj->setEvent($event);

        return $obj;
    }
}
