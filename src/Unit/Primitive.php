<?php
declare(strict_types = 1);

namespace App\Unit;

abstract class Primitive extends Component
{
    abstract public function getValue();
}
