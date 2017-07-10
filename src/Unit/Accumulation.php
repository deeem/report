<?php
declare(strict_types = 1);

namespace App\Unit;

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

        throw new UnitException('Child not found');
    }

    public function findNested(array $path): Component
    {
        $element = array_shift($path);

        try {
            $child = $this->find($element);
        } catch (UnitException $e) {
            throw new UnitException('Nested child not found');
        }

        return empty($path) ? $child : $child->findNested($path);
    }

    public function serialize()
    {
        $pile = [];

        foreach ($this->children as $item) {
            $pile[] = $item->serialize();
        }

        return [
            'name' => $this->name,
            'type' => 'accumulation',
            'pile' => $pile
        ];
    }
}
