<?php
namespace App;

abstract class Primitive extends Component
{
    abstract public function getValue();

    public function __construct($name)
    {
        $this->name = $name;
    }
}
