<?php
namespace App;

class Percentage extends Cell
{
    protected $part;
    protected $whole;

    public function setPaths($part, $whole)
    {
        $this->part = $part;
        $this->whole = $whole;
    }

    public function getValue()
    {
        $parent = $this->getParent();

        return $parent->getChild($this->part)->getValue() /
        $parent->getChild($this->whole)->getValue() * 100;
    }
}
