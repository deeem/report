<?php
namespace App;

class Percentage extends Primitive
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

        return $parent->find($this->part)->getValue() /
        $parent->find($this->whole)->getValue() * 100;
    }
}
