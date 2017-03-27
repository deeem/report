<?php
namespace App;

class Label extends Populated
{
    private $value;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function getValue()
    {
        return $this->value;
    }
}
