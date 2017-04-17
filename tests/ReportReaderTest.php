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

    public function testCanBuildParsedFile()
    {
        $report = (new YamlReader('/app/tests/report.yml'))->parse();
        $builder = new CollectionBuilder();
        $builder->setName('4b875873');
        $collection = (new CollectionDirector())->build($builder, $report['elements']);

        $this->assertInstanceOf(Collection::class, $collection);
    }
}
