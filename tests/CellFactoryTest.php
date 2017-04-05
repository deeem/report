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

    public function testCanFactoryCreateInputWithOptions()
    {
        $input = $this->factory->create('5d84e373', 'input', ['value'=>'foo']);
        $this->assertEquals('foo', $input->getValue());
    }

    public function testCanFactoryCreateSelect()
    {
        $this->assertInstanceOf(
            Select::class,
            $this->factory->create('c1f80055', 'select')
        );
    }

    public function testCanFactoryCreateSelectWithOptions()
    {
        $select = $this->factory->create(
            'c1f80055',
            'select',
            ['options'=>['foo', 'bar'], 'value'=>'foo']
        );
        $this->assertEquals('foo', $select->getValue());
    }

    public function testCanFactoryCreateSummary()
    {
        $this->assertInstanceOf(
            Summary::class,
            $this->factory->create('c1f80055', 'summary')
        );
    }

    public function testCanFactoryCreatePercentage()
    {
        $this->assertInstanceOf(
            Percentage::class,
            $this->factory->create('c1f80055', 'percentage')
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

    public function testCanAppendCellsToCollectionFromTemplate()
    {
        $collection = $this->factory->create('4b875873', 'composite');
        $template = [
            ['id' => '22305e2b', 'type' => 'input', 'options' => ['value' => 1]],
            ['id' => '7f2a2c2a', 'type' => 'select', 'options' => ['options' => ['foo', 'bar'], 'value' => 'foo']]
        ];
        $this->factory->append($collection, $template);

        $this->assertEquals($collection->getChild('22305e2b')->getValue(), 1);
        $this->assertEquals($collection->getChild('7f2a2c2a')->getValue(), 'foo');
    }
}
