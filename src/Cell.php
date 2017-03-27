<?php
namespace App;

abstract class Cell
{
    abstract public function getValue();
    abstract public function isEditable();
}
