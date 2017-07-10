<?php
declare(strict_types=1);

namespace App;

use App\Unit\UnitException;
use App\Unit\Accumulation;
use App\Unit\Input;
use App\Unit\Select;
use App\Unit\Summary;
use App\Unit\Percentage;

final class PrimitiveTest extends \PHPUnit\Framework\TestCase
{
    public function testCellGetId()
    {
        $primitive = new Input('5d84e373');
        $this->assertEquals('5d84e373', $primitive->getName());
    }

    public function testInputSetGetValue()
    {
        $input = new Input('5d84e373');
        $input->setValue(5);
        $this->assertEquals(5, $input->getValue());
    }

    public function testSelectSetGetValue()
    {
        $select = new Select('5d84e373');
        $select->setOptions(['foo', 'bar']);
        $select->setValue('foo');
        $this->assertEquals('foo', $select->getValue());
    }

    public function testSelectNotInOptionsException()
    {
        $select = new Select('5d84e373');
        $select->setOptions(['foo', 'bar']);
        $this->expectException(UnitException::class);
        $select->setValue('baz');
    }

    public function testSummaryGetValue()
    {
        $input1 = new Input('22305e2b');
        $input1->setValue(2);
        $input2 = new Input('8f8709df');
        $input2->setValue(3);
        $summary = new Summary('summa');
        $summary->setPaths(['22305e2b', '8f8709df']);
        $collection = new Accumulation('4b875873');
        $collection->addChild($input1);
        $collection->addChild($input2);
        $collection->addChild($summary);

        $this->assertEquals(5, $collection->find('summa')->getValue());
    }

    public function testPercentageGetValue()
    {
        $input1 = new Input('22305e2b');
        $input1->setValue(2);
        $input2 = new Input('8f8709df');
        $input2->setValue(4);
        $summary = new Percentage('pct');
        $summary->setPaths('22305e2b', '8f8709df');
        $collection = new Accumulation('4b875873');
        $collection->addChild($input1);
        $collection->addChild($input2);
        $collection->addChild($summary);

        $this->assertEquals(50, $collection->find('pct')->getValue());
    }
}
