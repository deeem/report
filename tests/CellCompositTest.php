<?php
declare(strict_types=1);
namespace App;

final class CellCompositTest extends \PHPUnit\Framework\TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = new CellFactory();
        $this->input = $this->factory->create('5d84e373', 'input');
        $this->select = $this->factory->create('c1f80055', 'select');
    }

    public function testCompositeGetId()
    {
        $composite = new CellComposite('93836c77');
        $this->assertEquals('93836c77', $composite->getId());
    }

    public function testCanCreateCellComposite()
    {
        $composite = new CellComposite('93836c77');
        $this->assertInstanceOf(CellComposite::class, $composite);
    }

    public function testAddChildToComposite()
    {
        $composite = new CellComposite('4b875873');
        $composite->add($this->input);
        $this->assertEquals($this->input, $composite->getCellByPath('5d84e373'));
    }

    public function testGetCellByPath()
    {
        $composite1 = new CellComposite('4b875873');
        $composite2 = new CellComposite('eeb9eda1');
        $composite2->add($this->input);
        $composite1->add($composite2);
        $this->assertEquals(
            $this->input,
            $composite1->getCellByPath('eeb9eda1_5d84e373')
        );
    }

    public function testGetCellByPathCellNotFoundException()
    {
        $composite = new CellComposite('4b875873');
        $composite->add($this->input);
        $this->expectException(CellException::class);
        $composite->getCellByPath('foo');
    }
}
