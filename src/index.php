<?php
namespace App;

require_once '/app/vendor/autoload.php';

$factory = new CellFactory();

// DATA

$sheet = [
    ['id' => '22305e2b', 'type' => 'input'],
    ['id' => '8f8709df', 'type' => 'input'],
    ['id' => '7f2a2c2a', 'type' => 'select', 'options' => ['options' => ['ТО-1', 'ТО-2']]],
    ['id' => '3ea48e03', 'type' => 'percentage', 'options' => ['part' => '22305e2b', 'whole' => '8f8709df']]
];

$addTemplate = [
    ['id' => '22305e2b', 'type' => 'input'],
    ['id' => '8f8709df', 'type' => 'input'],
    ['id' => '7f2a2c2a', 'type' => 'select', 'options' => ['options' => ['ТО-1', 'ТО-2']]]
];

$report = [
    ['id' => '22305e2b', 'type' => 'input', 'options' => ['value' => 1]],
    ['id' => '8f8709df', 'type' => 'input', 'options' => ['value' => 2]],
    ['id' => 'cd9ac8a9', 'type' => 'select', 'options' => ['options' => ['ТО-1', 'ТО-2'], 'value' => 'ТО-1']],
    ['id' => '3ea48e03', 'type' => 'percentage', 'options' => ['part' => '22305e2b', 'whole' => '8f8709df']]
];

// INIT

$previousReport = $factory->create('4b875873', 'composite');
$previousReport->append($report);

$currentReport = $factory->create('eeb9eda1', 'composite');
$currentReport->append($sheet);
$currentReport->registerTemplate('feb1ac70', $addTemplate);

$reports = $factory->create('56729d6f', 'composite');
$reports->addChild($previousReport);
$reports->addChild($currentReport);

// INPUT

$reports->getChildByPath('eeb9eda1_22305e2b')->setValue(3);
$reports->getChildByPath('eeb9eda1_8f8709df')->setValue(4);
$reports->getChildByPath('eeb9eda1_7f2a2c2a')->setValue('ТО-2');
$reports->getChild('eeb9eda1')->appendFromTemplate('feb1ac70');
$reports->getChildByPath('eeb9eda1_22305e2b1')->setValue(5);
$reports->getChildByPath('eeb9eda1_8f8709df1')->setValue(6);
$reports->getChildByPath('eeb9eda1_7f2a2c2a1')->setValue('ТО-1');

// OUTPUT

echo "Показатели прошлого периода\n";
echo 'План: ' . $reports->getChildByPath('4b875873_22305e2b')->getValue() . " | ";
echo 'Факт: ' . $reports->getChildByPath('4b875873_8f8709df')->getValue() . " | ";
echo 'Тип: ' . $reports->getChildByPath('4b875873_cd9ac8a9')->getValue() . " | ";
echo '%: ' . $reports->getChildByPath('4b875873_3ea48e03')->getValue(). "\n";

echo "Показатели текущего периода\n";
echo 'План: ' . $reports->getChildByPath('eeb9eda1_22305e2b')->getValue() . " | ";
echo 'Факт: ' . $reports->getChildByPath('eeb9eda1_8f8709df')->getValue() . " | ";
echo 'Тип: ' . $reports->getChildByPath('eeb9eda1_7f2a2c2a')->getValue() . "\n";
echo 'План: ' . $reports->getChildByPath('eeb9eda1_22305e2b1')->getValue() . " | ";
echo 'Факт: ' . $reports->getChildByPath('eeb9eda1_8f8709df1')->getValue() . " | ";
echo 'Тип: ' . $reports->getChildByPath('eeb9eda1_7f2a2c2a1')->getValue() . "\n";
