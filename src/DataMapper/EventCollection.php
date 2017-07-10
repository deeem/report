<?php
declare(strict_types = 1);

namespace App\DataMapper;

use App\DomainObject\Event;

class EventCollection extends Collection
{
    public function targetClass(): string
    {
        return Event::class;
    }
}
