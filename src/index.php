<?php
namespace App;

require_once '/app/vendor/autoload.php';

$factory = new CellFactory();

// INIT

$collection = $factory->create('4b875873', 'composite');
$collection->add($factory->create('22305e2b', 'input', ['value' => 1]));
$collection->add($factory->create('8f8709df', 'input', ['value' => 2]));
$collection->add(
    $factory->create('7f2a2c2a', 'select', ['options' => ['ТО-1', 'ТО-2'], 'value' => 'ТО-1'])
);
$template = [
    ['id' => '22305e2b', 'type' => 'input', 'options' => ['value' => 1]],
    ['id' => '8f8709df', 'type' => 'input', 'options' => ['value' => 2]],
    ['id' => '7f2a2c2a', 'type' => 'select', 'options' => ['options' => ['ТО-1', 'ТО-2'], 'value' => 'ТО-1']]
];
$factory->append($collection, $template);

$subCollection = $factory->create('eeb9eda1', 'composite');
$subCollection->add($factory->create('93836c77', 'input'));
$subCollection->add($factory->create('ee0bf9d6', 'input'));
$subCollection->add(
    $factory->create('04ca8335', 'select', ['options' => ['ТО-1', 'ТО-2']])
);

$collection->add($subCollection);

// INPUT

$collection->getCellByPath('eeb9eda1_93836c77')->setValue(3);
$collection->getCellByPath('eeb9eda1_ee0bf9d6')->setValue(4);
$collection->getCellByPath('eeb9eda1_04ca8335')->setValue('ТО-2');

// OUTPUT

echo "Показатели прошлого периода\n";
echo 'План: ' . $collection->getCellByPath('22305e2b')->getValue() . " | ";
echo 'Факт: ' . $collection->getCellByPath('8f8709df')->getValue() . " | ";
echo 'Тип: ' . $collection->getCellByPath('7f2a2c2a')->getValue() . "\n";
echo 'План: ' . $collection->getCellByPath('22305e2b1')->getValue() . " | ";
echo 'Факт: ' . $collection->getCellByPath('8f8709df1')->getValue() . " | ";
echo 'Тип: ' . $collection->getCellByPath('7f2a2c2a1')->getValue() . "\n";
echo "Показатели текущего периода\n";
echo 'План: ' . $collection->getCellByPath('eeb9eda1_93836c77')->getValue() . " | ";
echo 'Факт: ' . $collection->getCellByPath('eeb9eda1_ee0bf9d6')->getValue() . " | ";
echo 'Тип: ' . $collection->getCellByPath('eeb9eda1_04ca8335')->getValue() . "\n";
