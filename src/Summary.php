<?php
namespace App;

class Summary extends Primitive
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
            return $carry + $parent->find($item)->getValue();
        }, 0);
    }

    public function serialize()
    {
        return [
            'name' => $this->name,
            'type' => 'summary',
            'paths' => $this->paths
        ];
    }
}
