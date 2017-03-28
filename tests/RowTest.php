<?php
declare(strict_types=1);

final class RowTest extends PHPUnit\Framework\TestCase
{
    public function testAddCell()
    {
        $cell = new App\Blank();
        $row = new App\Row();
        $row->addCell($cell);
        $cells = $row->getCells();
        $this->assertSame($cell, $cells[0]);
    }

    public function testGetCell()
    {
        $cell = new App\Blank();
        $row = new App\Row();
        $row->addCell($cell);
        $returned = $row->getCell(0);
        $this->assertSame($cell, $returned);
    }

    public function testGetCellException()
    {
        $cell = new App\Blank();
        $row = new App\Row();
        $row->addCell($cell);
        $this->expectException(App\CellException::class);
        $row->getCell(1);
    }
}
