<?php
namespace App;

class Row
{
    private $cells;

    public function addCell(Cell $cell)
    {
        $this->cells[] = $cell;
    }

    public function getCells(): array
    {
        return $this->cells;
    }

    public function getCell($num): Cell
    {
        if ($num < count($this->cells)) {
            return $this->cells[$num];
        } else {
            throw new CellException('Cell number out of range');
        }
    }
}
