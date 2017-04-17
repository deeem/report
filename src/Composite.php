<?php
namespace App;

abstract class Composite extends Component
{
    protected $children = [];

    abstract public function isFixed(): bool;

    public function getComposite(): Component
    {
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function addChild(Component $component)
    {
        $component->setParent($this);
        $this->children[] = $component;
    }

    public function getChildren()
    {
        return $this->children;
    }
}
