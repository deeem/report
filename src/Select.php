<?php
namespace App;

class Select extends Input
{
    private $value;
    private $options;

    public function __construct($val = null, $options = [])
    {
        $this->options = $options;
        $this->setValue($val);
    }

    public function setValue($value)
    {
        if (in_array($value, $this->options)) {
            $this->value = $value;
        } else {
            throw new CellException('Select type: value not in options list');
        }
    }

    public function getValue()
    {
        return $this->value;
    }
}
