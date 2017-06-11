<?php
declare(strict_types = 1);

namespace App;

class EventCollection extends Collection
{
    public function targetClass(): string
    {
        return Event::class;
    }
}
