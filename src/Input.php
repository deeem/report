<?php
namespace App;

abstract class Input implements Cell
{
    abstract public function setValue($val);

    public function getValue()
    {
        return $this->value;
    }
}
