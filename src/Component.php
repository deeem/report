<?php
namespace App;

abstract class Component
{
    protected $name;
    private $parent;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function setParent(Composite $parent)
    {
        $this->parent = $parent;
    }

    public function getParent(): Composite
    {
        return $this->parent;
    }

    public function getComposite(): Component
    {
        return null;
    }

    abstract public function serialize();
}
