<?php
declare(strict_types = 1);

namespace App\Unit;

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

    public function serialize()
    {
        return [
            'name' => $this->name,
            'type' => 'percentage',
            'part' => $this->part,
            'whole' => $this->whole
        ];
    }
}
