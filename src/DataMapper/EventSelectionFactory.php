<?php
declare(strict_types = 1);

namespace App\DataMapper;

class EventSelectionFactory extends SelectionFactory
{
    public function newSelection(IdentityObject $obj): array
    {
        $fields = implode(',', $obj->getObjectFields());
        $core = "SELECT $fields FROM event";
        list($where, $values) = $this->buildWhere($obj);

        return [$core . " " . $where, $values];
    }
}
