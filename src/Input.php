<?php
namespace App;

class Input extends Cell
{
    private $value;

    public function setValue($value): Cell
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }
}
