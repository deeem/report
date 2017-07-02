<?php
declare(strict_types = 1);

namespace App;

require_once '/app/vendor/autoload.php';

error_reporting(E_ALL);

$reg = Registry::instance();
$reg->setConf(new Conf(
    [
        'DB_DSN' => 'mysql:dbname=report;host=db',
        'DB_USER' => 'report',
        'DB_PASSWD' => 'secret'
    ]
));

$fooEventObject = $reg->getEventMapper()->find(1);

$report = new Report(
    -1,
    'J0200119',
    (new YamlReader('/app/tests/J0200119.yml'))->parse(),
    $reg->getEventMapper()->find(1),
    $reg->getUserMapper()->find(1)
);

$barEventObject = $reg->getEventMapper()->find(1);

ObjectWatcher::instance()->performOperations();

$reports1 = $fooEventObject->getReports();
$reports2 = $barEventObject->getReports();

$reports1->notifyAccess();
$reports2->notifyAccess();

$count = function ($collection) {
    $i = 0;
    foreach ($collection as $item) {
        $i++;
    }
    return $i;
};

var_dump($count($reports1), $count($reports2));
