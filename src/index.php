<?php
namespace App;

require_once '/app/vendor/autoload.php';

error_reporting(E_ALL);

// DATA

$report = [
    'name' => '4b875873',
    'elements' => [
        ['name' => '22305e2b', 'type' => 'input', 'params' => ['value' => 1]],
        ['name' => '8f8709df', 'type' => 'input', 'params' => ['value' => 2]],
        ['name' => 'cd9ac8a9', 'type' => 'select', 'params' => [
            'options' => ['ТО-1', 'ТО-2'],
            'value' => 'ТО-1']
        ],
        ['name' => 'summa' , 'type' => 'summary', 'params' => ['paths' => ['22305e2b', '8f8709df']]],
        ['name' => '07ff3925', 'type' => 'percentage', 'params' => ['part' => '22305e2b', 'whole' => '8f8709df']],
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

// INIT

$builder = new CollectionBuilder();
$builder->setName('4b875873');
$collection = (new CollectionDirector())->build($builder, $report['elements']);

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
