<?php
namespace App;

class Text extends Input
{
    private $value;

    public function __construct($val = null)
    {
        $this->value = $val;
    }

    public function setValue($val)
    {
        $this->value = $val;
    }

    public function getValue()
    {
        return $this->value;
    }
}
