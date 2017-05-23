<?php
declare(strict_types=1);
namespace App;

final class CompositeTest extends \PHPUnit\Framework\TestCase
{
    protected $collection;

    public function setUp()
    {
        $this->collection = new Accumulation('4b875873');
    }

    public function testCollectionSetGetId()
    {
        $this->assertEquals('4b875873', $this->collection->getName());
    }

    public function testCheckCollectionIsFixedAttribute()
    {
        $this->assertTrue($this->collection->isFixed());
    }

    public function testCheckTemplateIsFixedAttribute()
    {
        $template = new Template('4b875873');

        $this->assertFalse($template->isFixed());
    }

    public function testCollectionAddChild()
    {
        $this->collection->addChild(new Input('22305e2b'));

        $this->assertInstanceOf(Input::class, $this->collection->find('22305e2b'));
    }

    public function testCollectionFind()
    {
        $child = new Input('22305e2b');
        $this->collection->addChild($child);

        $this->assertEquals($child, $this->collection->find('22305e2b'));
    }

    public function testCollectionFindNested()
    {
        $inner = new Accumulation('eeb9eda1');
        $inner->addChild(new Input('22305e2b'));
        $this->collection->addChild($inner);

        $this->assertInstanceOf(Input::class, $this->collection->findNested(['eeb9eda1', '22305e2b']));
    }

    public function testCollectionFindNestedException()
    {
        $inner = new Accumulation('eeb9eda1');
        $inner->addChild(new Input('22305e2b'));
        $this->collection->addChild($inner);

        $this->expectException(CellException::class);
        $this->collection->findNested(['eeb9eda1', 'foo']);
    }

    public function testCollectionGetChildren()
    {
        $this->collection->addChild(new Input('22305e2b'));
        $this->collection->addChild(new Select('22305e2c'));
        $children = $this->collection->getChildren();

        $this->assertInstanceOf(Input::class, $children[0]);
        $this->assertInstanceOf(Select::class, $children[1]);
    }

    public function testCollectionGetParent()
    {
        $collection = new Accumulation('4b875873');
        $collection->addChild(new Input('22305e2b'));
        $subCollection = new Accumulation('eeb9eda1');
        $collection->addChild($subCollection);
        $nested = $collection->find('eeb9eda1');
        $nested->addChild(new Select('10ce46d7'));

        $parent = $collection;
        $child = $collection->find('22305e2b');
        $this->assertEquals($parent->getName(), $child->getParent()->getName());

        $parent2 = $collection->find('eeb9eda1');
        $child2 = $collection->find('eeb9eda1')->find('10ce46d7');
        $this->assertEquals($parent2->getName(), $child2->getParent()->getName());
    }

    public function testCanTemplateGenerateCollection()
    {
        $elements = [
            ['name' => 'fb03f80b', 'type' => 'input', 'params' => ['value' => 100]],
            ['name' => '05ac5826', 'type' => 'input', 'params' => ['value' => 101]],
        ];

        $template = new Template('726522a4');
        $template->setTemplate($elements);

        $this->assertEmpty($template->getChildren());

        $generated = $template->generate();

        $this->assertNotEmpty($generated->getChildren());
        $this->assertEquals($elements[0]['name'], $generated->find($elements[0]['name'])->getName());
        $this->assertEquals($elements[1]['name'], $generated->find($elements[1]['name'])->getName());
    }
}
