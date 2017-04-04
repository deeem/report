<?php
namespace App;

class Summary extends Cell
{
    protected $paths = [];

    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    public function getValue()
    {
        $parent = $this->getParent();

        return array_reduce($this->paths, function ($carry, $item) use ($parent) {
            return $carry + $parent->getChild($item)->getValue();
        }, 0);
    }
}
