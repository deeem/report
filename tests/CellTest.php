<?php
declare(strict_types=1);
namespace App;

final class CellTest extends \PHPUnit\Framework\TestCase
{
    protected $factory;
    protected $input;
    protected $select;

    public function setUp()
    {
        $this->factory = new CellFactory();
        $this->input = $this->factory->create('5d84e373', 'input');
        $this->select = $this->factory->create('c1f80055', 'select');
    }

    public function testCellGetId()
    {
        $this->assertEquals('5d84e373', $this->input->getId());
    }

    public function testInputSetGetValue()
    {
        $this->input->setValue(5);
        $this->assertEquals(5, $this->input->getValue());
    }

    public function testSelectSetGetValue()
    {
        $this->select->setOptions(['foo', 'bar']);
        $this->select->setValue('foo');
        $this->assertEquals('foo', $this->select->getValue());
    }

    public function testSelectInvalidValueException()
    {
        $this->select->setOptions(['foo', 'bar']);
        $this->expectException(CellException::class);
        $this->select->setValue('baz');
    }
}
