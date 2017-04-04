<?php
namespace App;

class CellComposite extends CellComponent
{
    protected $children = [];

    public function isComposite(): CellComponent
    {
        return $this;
    }

    public function add(CellComponent $component)
    {
        $id = $component->getId();
        if (!array_key_exists($id, $this->children)) {
            $this->children[$component->getId()] = $component;
        } else {
            $num = array_reduce(array_keys($this->children), function ($carry, $item) use ($id) {
                return preg_match('/^' . $id . '[0-9]+$/', $item) ? ++$carry : $carry;
            }, 1);
            $this->children[$id.$num] = $component;
        }
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
}
