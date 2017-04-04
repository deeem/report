<?php
namespace App;

abstract class CellComponent
{
    protected $id;

    public function __construct($id)
    {
        $this->setId($id);
    }

    protected function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getComposite(): CellComponent
    {
        return null;
    }
}
