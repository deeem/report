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

    public function isComposite(): CellComponent
    {
        return null;
    }

}
