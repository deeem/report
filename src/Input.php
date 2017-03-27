<?php
namespace App;

class Input extends Cell
{
    private $value = null;
    private $editable = false;

    public function setValue($val)
    {
        $this->value = $val;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setEditable()
    {
        $this->editable = true;
    }

    public function isEditable()
    {
        return $this->editable;
    }
}
