<?php
declare(strict_types=1);
namespace App;

final class CollectionBuilderTest extends \PHPUnit\Framework\TestCase
{
    protected $root;
    protected $collection;

    public function setUp()
    {
        $this->root = [
            'name' => '4b875873',
            'elements' => [
                ['name' => '22305e2b', 'type' => 'input', 'params' => ['value' => 1]],
                ['name' => '8f8709df', 'type' => 'input', 'params' => ['value' => 2]],
                ['name' => 'de85a90a', 'type' => 'summary', 'params' => ['paths' => ['22305e2b','8f8709df']]],
                ['name' => '07ff3925', 'type' => 'percentage',
                    'params' => ['part' => '22305e2b', 'whole' => '8f8709df']
                ],
                ['name' => 'cd9ac8a9', 'type' => 'select', 'params' => [
                    'options' => ['ТО-1', 'ТО-2'],
                    'value' => 'ТО-1']
                ],
                ['name' => 'eeb9eda1', 'type' => 'collection', 'params' => ['elements' => [
                    ['name' => '10ce46d7', 'type' => 'input', 'params' => ['value' => 57]],
                    ['name' => 'd38a1eb4', 'type' => 'input', 'params' => ['value' => 54]],
                    ]
                ]],
                ['name' => '726522a4', 'type' => 'template', 'params' => ['elements' => [
                    ['name' => 'fb03f80b', 'type' => 'input', 'params' => ['value' => 100]],
                    ['name' => '05ac5826', 'type' => 'input', 'params' => ['value' => 101]],
                    ]
                ]],
            ]
        ];

        $builder = new CollectionBuilder();
        $builder->setName($this->root['name']);
        $this->collection = (new CollectionDirector())->build($builder, $this->root['elements']);
    }

    public function testCanBuildCollection()
    {
        $this->assertInstanceOf(Collection::class, $this->collection);
    }

    public function testCanBuilderAddChildToCollection()
    {
        $children = $this->collection->getChildren();

        $this->assertInstanceOf(Component::class, $children[0]);
    }

    public function testCanBuilderAppendInput()
    {
        $this->assertInstanceOf(Input::class, $this->collection->find('22305e2b'));
    }

    public function testCanBuilderAppendSelect()
    {
        $this->assertInstanceOf(Select::class, $this->collection->find('cd9ac8a9'));
    }

    public function testCanBuilderAppendTemplate()
    {
        $this->assertInstanceOf(Template::class, $this->collection->find('726522a4'));
    }

    public function testCanBuilderAppendCollection()
    {
        $this->assertInstanceOf(Collection::class, $this->collection->find('eeb9eda1'));
    }

    public function testCanBuilderAppendInputInNestedCollection()
    {
        $nested = $this->collection->find('eeb9eda1')->getChildren();

        $this->assertInstanceOf(Input::class, $nested[0]);
    }

    public function testCanBuilderAppendSummary()
    {
        $this->assertInstanceOf(Summary::class, $this->collection->find('de85a90a'));
    }

    public function testCanBuilderAppendPercentage()
    {
        $this->assertInstanceOf(Percentage::class, $this->collection->find('07ff3925'));
    }

    public function testUnknownTypeException()
    {
        $id = '4b875873';
        $elements = [
            ['name' => '22305e2b', 'type' => 'foo']
        ];

        $builder = new CollectionBuilder();
        $builder->setName($id);

        $this->expectException(CellException::class);

        $this->collection = (new CollectionDirector())->build($builder, $elements);
    }
}
