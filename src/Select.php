<?php
namespace App;

class Select extends Cell
{
    private $value;
    private $options = [];

    public function setValue($value): Cell
    {
        if (! in_array($value, $this->options)) {
            throw new CellException('Select type: value not in options list');
        }

        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setOptions(array $options = []): Cell
    {
        $this->options = $options;

        return $this;
    }
}
