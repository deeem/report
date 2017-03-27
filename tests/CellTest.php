<?php
declare(strict_types=1);
namespace App;

final class CellTest extends PHPUnit\Framework\TestCase
{
    public function testLabelGetValue()
    {
        $cell = new App\Label('foo');
        $this->assertEquals('foo', $cell->getValue());
    }

    public function testTextSetValue()
    {
        $cell = new App\Text();
        $cell->setValue(5);
        $this->assertEquals(5, $cell->getValue());
    }

    public function testBlankGetValue()
    {
        $cell = new App\Blank();
        $this->assertNull($cell->getValue());
    }

    public function testSelectSetValue()
    {
        $cell = new App\Select('foo', ['foo', 'bar']);
        $this->assertEquals('foo', $cell->getValue());
    }

    public function testSelectSetValueException()
    {
        $this->expectException(App\CellException::class);
        $cell = new App\Select('buzz', ['foo', 'bar']);
    }
}
