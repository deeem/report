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

    public function serialize()
    {
        return [
            'name' => $this->name,
            'type' => 'input',
            'value' => $this->value
        ];
    }
}
