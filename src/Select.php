<?php
namespace App;

class Select extends Primitive
{
    private $value;
    private $options = [];

    public function setValue($value): Primitive
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

    public function setOptions(array $options = []): Primitive
    {
        $this->options = $options;

        return $this;
    }

    public function serialize()
    {
        return [
            'name' => $this->name,
            'type' => 'select',
            'value' => $this->value,
            'options' => $this->options
        ];
    }
}
