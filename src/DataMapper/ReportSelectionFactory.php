<?php
declare(strict_types = 1);

namespace App\DataMapper;

class ReportSelectionFactory extends SelectionFactory
{
    public function newSelection(IdentityObject $obj): array
    {
        $fields = implode(',', $obj->getObjectFields());
        $core = "SELECT $fields FROM report";
        list($where, $values) = $this->buildWhere($obj);

        return [$core . " " . $where, $values];
    }
}
