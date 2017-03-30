<?php
namespace App;

abstract class Cell extends CellComponent
{
    public function add(CellComponent $component)
    {
        throw new CellException("Can't append child to Cell");
    }

    public function getCellByPath(string $path): Cell
    {
        throw new CellException("Can't get child from Cell");
    }
}
