<?php
namespace App;

class Row
{
    private $cells = [];

    public function getCells(): array
    {
        return $this->cells;
    }

    public function addCell(Cell $cell)
    {
        $this->cells[] = $cell;
    }
}
