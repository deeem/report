<?php
declare(strict_types = 1);

namespace App;

class EventUpdateFactory extends UpdateFactory
{
    public function newUpdate(DomainObject $obj): array
    {
        // note type checking removed
        $id = $obj->getId();
        $cond = null;
        $values = [
            'name' => $obj->getName(),
            'start' => $obj->getStart(),
            'end' => $obj->getEnd(),
            'report' => $obj->getReport()
        ];

        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("event", $values, $cond);
    }
}
