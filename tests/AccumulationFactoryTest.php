<?php
declare(strict_types=1);
namespace App;

final class AccumulationBuilderTest extends \PHPUnit\Framework\TestCase
{
    protected $accumulation;

    public function setUp()
    {
        $data = [
            'name' => '4b875873',
            'pile' => [
                ['name' => '22305e2b', 'type' => 'input', 'value' => 1],
                ['name' => '8f8709df', 'type' => 'input', 'value' => 2],
                ['name' => 'de85a90a', 'type' => 'summary', 'paths' => ['22305e2b','8f8709df']],
                ['name' => '07ff3925', 'type' => 'percentage', 'part' => '22305e2b', 'whole' => '8f8709df'],
                ['name' => 'cd9ac8a9', 'type' => 'select', 'options' => ['ТО-1', 'ТО-2'], 'value' => 'ТО-1'],
                ['name' => 'eeb9eda1', 'type' => 'accumulation', 'pile' => [
                    ['name' => '10ce46d7', 'type' => 'input', 'value' => 57],
                    ['name' => 'd38a1eb4', 'type' => 'input', 'value' => 54],
                ]],
                ['name' => '726522a4', 'type' => 'template', 'pile' => [
                    ['name' => 'fb03f80b', 'type' => 'input', 'value' => 100],
                    ['name' => '05ac5826', 'type' => 'input', 'value' => 101],
                ]],
            ]
        ];

        $this->accumulation = (new AccumulationFactory)->make($data);
    }

    public function testCanBuildAccumulation()
    {
        $this->assertInstanceOf(Accumulation::class, $this->accumulation);
    }

    public function testCanBuilderAddChildToAccumulation()
    {
        $children = $this->accumulation->getChildren();

        $this->assertInstanceOf(Component::class, $children[0]);
    }

    public function testCanBuilderAppendInput()
    {
        $this->assertInstanceOf(Input::class, $this->accumulation->find('22305e2b'));
    }

    public function testCanBuilderAppendSelect()
    {
        $this->assertInstanceOf(Select::class, $this->accumulation->find('cd9ac8a9'));
    }

    public function testCanBuilderAppendTemplate()
    {
        $this->assertInstanceOf(Template::class, $this->accumulation->find('726522a4'));
    }

    public function testCanBuilderAppendCollection()
    {
        $this->assertInstanceOf(Accumulation::class, $this->accumulation->find('eeb9eda1'));
    }

    public function testCanBuilderAppendInputInNestedAccumulation()
    {
        $nested = $this->accumulation->find('eeb9eda1')->getChildren();

        $this->assertInstanceOf(Input::class, $nested[0]);
    }

    public function testCanBuilderAppendSummary()
    {
        $this->assertInstanceOf(Summary::class, $this->accumulation->find('de85a90a'));
    }

    public function testCanBuilderAppendPercentage()
    {
        $this->assertInstanceOf(Percentage::class, $this->accumulation->find('07ff3925'));
    }

    public function testUnknownTypeException()
    {
        $name = '4b875873';
        $pile = [
            ['name' => '22305e2b', 'type' => 'foo']
        ];

        $this->expectException(AppException::class);

        $this->accumulation = (new AccumulationFactory)->make(['name' => $name, 'pile' => $pile]);
    }

    public function testNameRequiredException()
    {
        $name = '4b875873';
        $pile = [
            ['type' => 'input']
        ];

        $this->expectException(AppException::class);

        $this->accumulation = (new AccumulationFactory)->make(['name' => $name, 'pile' => $pile]);
    }
}
