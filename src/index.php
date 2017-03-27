<?php
namespace App;
require_once '/app/vendor/autoload.php';


// - 
// - читать из xml


$cell1 = new Input();
$cell1->setValue('Капитальный');
$cell2 = new Skip();
$cell3 = new Input();
$cell3->setValue('2');
$cell4 = new Input();
$cell4->setValue('4');

$row = new Row();
$row->addCell($cell1);
$row->addCell($cell2);
$row->addCell($cell3);
$row->addCell($cell4);

$cells = $row->getCells();

foreach($cells as $cell)
{
    echo "{$cell->getValue()} ";
}
