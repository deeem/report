<?php
namespace App;

require_once '/app/vendor/autoload.php';

// - доделать иерархию классов Cell
// - базовый функционал класса Row
// - читать из xml

$row = [
    new Label('1'),
    new Label('Капитальный'),
    new Text(4),
    new Text(5),
    new Blank(),
    new Text(6),
    new Select('ТО 1', ['ТО1', 'ТО 2'])
];

foreach ($row as $cell) {
    echo " {$cell->getValue()} |";
}

echo "\n";
