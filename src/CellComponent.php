<?php
namespace App;

abstract class CellComponent
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    abstract public function add(CellComponent $component);
    abstract public function getCellByPath(string $path): Cell;
    abstract public function setValue($value): Cell;
    abstract public function getValue();
}
