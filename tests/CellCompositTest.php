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
        $composite->addChild($this->input);
        $this->assertEquals($this->input, $composite->getChild('5d84e373'));
    }

    public function testAddChildWithExistingIdToComposite()
    {
        $composite = new CellComposite('4b875873');
        $composite->addChild($this->factory->create('93836c77', 'input', ['value'=>'foo']));
        $composite->addChild($this->factory->create('93836c77', 'input', ['value'=>'bar']));
        $composite->addChild($this->factory->create('93836c77', 'input', ['value'=>'baz']));
        $this->assertEquals('foo', $composite->getChild('93836c77')->getValue());
        $this->assertEquals('bar', $composite->getChild('93836c771')->getValue());
        $this->assertEquals('baz', $composite->getChild('93836c772')->getValue());
    }

    public function testCanAppendCellsToCollectionFromTemplateAsArgument()
    {
        $collection = $this->factory->create('4b875873', 'composite');
        $template = [
            ['id' => '22305e2b', 'type' => 'input', 'options' => ['value' => 1]],
            ['id' => '7f2a2c2a', 'type' => 'select', 'options' => ['options' => ['foo', 'bar'], 'value' => 'foo']]
        ];
        $collection->append($template);

        $this->assertEquals($collection->getChild('22305e2b')->getValue(), 1);
        $this->assertEquals($collection->getChild('7f2a2c2a')->getValue(), 'foo');
    }

    public function testCanAppendCellsToCollectionFromRegisteredTemplate()
    {
        $collection = $this->factory->create('4b875873', 'composite');
        $template = [
            ['id' => '22305e2b', 'type' => 'input', 'options' => ['value' => 1]],
            ['id' => '7f2a2c2a', 'type' => 'select', 'options' => ['options' => ['foo', 'bar'], 'value' => 'foo']]
        ];

        $collection->registerTemplate('70660eda', $template);
        $collection->appendFromTemplate('70660eda');
        $collection->appendFromTemplate('70660eda');
        $this->assertEquals(1, $collection->getChild('22305e2b')->getValue());
        $this->assertEquals('foo', $collection->getChild('7f2a2c2a')->getValue());
        $this->assertEquals(1, $collection->getChild('22305e2b1')->getValue());
        $this->assertEquals('foo', $collection->getChild('7f2a2c2a1')->getValue());
    }

    public function testGetChildByPath()
    {
        $composite1 = new CellComposite('4b875873');
        $composite2 = new CellComposite('eeb9eda1');
        $composite2->addChild($this->input);
        $composite1->addChild($composite2);
        $this->assertEquals(
            $this->input,
            $composite1->getChildByPath('eeb9eda1_5d84e373')
        );
    }

    public function testGetChildByPathCellNotFoundException()
    {
        $composite = new CellComposite('4b875873');
        $composite->addChild($this->input);
        $this->expectException(CellException::class);
        $composite->getChildByPath('foo');
    }
}
