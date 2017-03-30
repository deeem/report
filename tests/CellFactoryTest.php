<?php
declare(strict_types=1);
namespace App;

final class CellFactoryTest extends \PHPUnit\Framework\TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = new CellFactory();
    }

    public function testCanFactoryCreateInput()
    {
        $this->assertInstanceOf(
            Input::class,
            $this->factory->create('5d84e373', 'input')
        );
    }

    public function testCanFactoryCreateSelect()
    {
        $this->assertInstanceOf(
            Select::class,
            $this->factory->create('c1f80055', 'select')
        );
    }

    public function testCanFactoryCreateComposite()
    {
        $this->assertInstanceOf(
            CellComposite::class,
            $this->factory->create('93836c77', 'composite')
        );
    }

    public function testFactoryInvalidTypeException()
    {
        $this->expectException(CellException::class);
        $this->factory->create('5d84e373', 'foo');
    }
}
