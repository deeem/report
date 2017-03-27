<?php
namespace App;

class Rows
{
    private $rows = [];

    public function getRows(): array
    {
        return $this->rows;
    }

    public function addRow(Row $row)
    {
        $this->rows[] = $row;
    }
}
