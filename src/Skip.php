<?php
namespace App;

class Skip extends Cell
{
    public function getValue()
    {
        return null;
    }

    public function isEditable()
    {
        return false;
    }
}
