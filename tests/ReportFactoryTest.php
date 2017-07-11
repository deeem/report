<?php
declare(strict_types=1);

namespace App;

use App\AppException;
use App\ReportFactory;
use App\Unit\Accumulation;
use App\DomainObject\Event;
use App\DomainObject\Report;
use App\DomainObject\User;

final class ReportFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCanFactoryCreateReport()
    {
        $factory = new ReportFactory('J0200119', 1, 1);
        $report = $factory->make();

        $this->assertInstanceOf(Event::class, $report->getEvent());
        $this->assertInstanceOf(Report::class, $report);
        $this->assertInstanceOf(User::class, $report->getUser());
        $this->assertInstanceOf(Accumulation::class, $report->data);
    }

    public function testTemplateNotFound()
    {
        $this->expectException(AppException::class);
        (new ReportFactory('foo', 1, 1))->make();
    }

    public function testEventNotFound()
    {
        $this->expectException(AppException::class);
        (new ReportFactory('foo', 3, 1))->make();
    }

    public function testUserNotFound()
    {
        $this->expectException(AppException::class);
        (new ReportFactory('foo', 1, 3))->make();
    }
}
