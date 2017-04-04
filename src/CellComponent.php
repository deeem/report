<?php
namespace App;

abstract class CellComponent
{
    protected $id;
    private $parent;

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

    protected function setParent(CellComposite $parent)
    {
        $this->parent = $parent;
    }

    protected function getParent(): CellComposite
    {
        return $this->parent;
    }

    public function getComposite(): CellComponent
    {
        return null;
    }
}
