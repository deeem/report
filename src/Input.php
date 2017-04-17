<?php
namespace App;

class Input extends Primitive
{
    private $value;

    public function setValue($value): Primitive
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }
}
