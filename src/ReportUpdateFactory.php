<?php
declare(strict_types = 1);

namespace App;

class ReportUpdateFactory extends UpdateFactory
{
    public function newUpdate(DomainObject $obj): array
    {
        // note type checking removed
        $id = $obj->getId();
        $cond = null;
        $values = [
            'name' => $obj->getName(),
            'event' => $obj->getEvent()->getId(),
            'user' => $obj->getUser()->getId(),
            'data' => $obj->getData(),
        ];

        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("report", $values, $cond);
    }
}
