<?php
declare(strict_types = 1);

namespace App\DataMapper;

use App\DomainObject\Event;
use App\DomainObject\DomainObject;

class EventObjectFactory extends DomainObjectFactory
{
    public function createObject(array $row): DomainObject
    {
        $old = $this->getFromMap(Event::class, $row['id']);

        if ($old) {
            return $old;
        }

        $obj = new Event(
            (int)$row['id'],
            $row['name'],
            (int)$row['start'],
            (int)$row['end'],
            $row['report']
        );

        $this->addToMap($obj);

        $reportmapper = new ReportMapper();
        $reportcollection = $reportmapper->findByEvent($row['id']);
        $obj->setReports($reportcollection);

        return $obj;
    }
}
