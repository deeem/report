<?php
namespace App;

require_once '/app/vendor/autoload.php';

error_reporting(E_ALL);

// DATA

$report = (new YamlReader('/app/tests/report.yml'))->parse();

// INIT

$collection = (new AccumulationFactory())->make($report);

// INPUT

$collection->find('22305e2b')->setValue(5);
$collection->find('8f8709df')->setValue(20);
$collection->find('cd9ac8a9')->setValue('ТО-2');
$collection->find('eeb9eda1')->find('10ce46d7')->setValue(15);
$collection->find('eeb9eda1')->find('d38a1eb4')->setValue(150);

$generated = $collection->find('726522a4')->generate();
$generated->find('fb03f80b')->setValue(40);
$generated->find('05ac5826')->setValue(42);

// OUTPUT

echo "Показатели текущего периода\n";
echo 'План: ' . $collection->find('22305e2b')->getValue() . " | ";
echo 'Факт: ' . $collection->find('8f8709df')->getValue() . " | ";
echo 'Сумм: ' . $collection->find('summa')->getValue() . " | ";
echo 'в %:  ' . $collection->find('07ff3925')->getValue() . " | ";
echo 'Тип:  ' . $collection->find('cd9ac8a9')->getValue() . "\n\n";
echo 'Задействовано сотрудников: ' .
$collection->findNested(['eeb9eda1', '10ce46d7'])->getValue() . "\n";
echo 'Человекочасов: ' .
$collection->findNested(['eeb9eda1', 'd38a1eb4'])->getValue() . "\n";

echo "Стоимость по дням:\n";
$daily = $collection->find('726522a4')->getChildren();
foreach ($daily as $day) {
    foreach ($day->getChildren() as $item) {
        echo $item->getValue() . " у.е.\n";
    }
}
