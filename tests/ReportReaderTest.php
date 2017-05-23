<?php
declare(strict_types=1);
namespace App;

final class ReportReaderTest extends \PHPUnit\Framework\TestCase
{
    public function testReaderFileNotFoundException()
    {
        $filename = '/foo.yaml';
        $this->expectException(AppException::class);
        $reader = new YamlReader($filename);
    }

    public function testCanBuildFromParsedFile()
    {
        $report = (new YamlReader('/app/tests/report.yml'))->parse();
        $collection = (new AccumulationFactory)->make(['name' => $report['name'], 'pile' => $report['pile']]);

        $this->assertInstanceOf(Accumulation::class, $collection);
    }
}
