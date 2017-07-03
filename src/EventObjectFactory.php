<?php
declare(strict_types = 1);

namespace App;

class EventObjectFactory extends DomainObjectFactory
{
    public function createObect(array $row): DomainObject
    {
        $old = $this->getFromMap(Event::class, $row['id']);

        if ($old) {
            return $old;
        }

        $obj = new Event(
            (int)$row['id'],
            $raw['name'],
            (int)$row['start'],
            (int)$row['end'],
            $row['report']
        );

        $this->addToMap($obj);

        // $reportmapper = new ReportMapper();
        // $reportcollection = $reportmapper->findByEvent($raw['id']);
        // $obj->setReports($reportcollection);

        return $obj;
    }
}
