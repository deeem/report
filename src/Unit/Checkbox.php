<?php
declare(strict_types = 1);

namespace App\Unit;

class Checkbox extends Primitive
{
    private $value = false;

    public function setValue(bool $checked): Primitive
    {
        $this->value = $checked;

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
            'type' => 'checkbox',
            'value' => $this->value
        ];
    }
}
