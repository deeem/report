<?php
namespace App;

class CellComposite extends CellComponent
{
    protected $children = [];

    public function add(CellComponent $component)
    {
        $this->children[$component->getId()] = $component;
    }

    public function getCellByPath(string $path): Cell
    {
        $route = explode('_', $path);
        $id = array_shift($route);

        if (array_key_exists($id, $this->children)) {
            if (count($route)) {
                return $this->children[$id]->getCellByPath(implode('_', $route));
            }

            return $this->children[$id];
        }

        throw new CellException('Cell not found');
    }

    public function getValue()
    {
        throw new CellException("Can't get value from Composite");
    }
}
