<?php
declare(strict_types = 1);

namespace App\DataMapper;

use App\DomainObject\Report;

class ReportCollection extends Collection
{
    public function targetClass(): string
    {
        return Report::class;
    }
}
