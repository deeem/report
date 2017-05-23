<?php
namespace App;

class Accumulation extends Composite
{
    public function isFixed(): bool
    {
        return true;
    }

    public function find($name): Component
    {
        for ($i = 0; $i < count($this->children); $i++) {
            if ($this->children[$i]->getName() == $name) {
                return $this->children[$i];
            }
        }

        throw new CellException('Child not found');
    }

    public function findNested(array $path): Component
    {
        $element = array_shift($path);

        try {
            $child = $this->find($element);
        } catch (CellException $e) {
            throw new CellException('Nested child not found');
        }

        return empty($path) ? $child : $child->findNested($path);
    }
}
