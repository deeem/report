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

/*
$report = new Report(
    -1,
    'Z0200119',
    (new YamlReader('/app/tests/J0200119.yml'))->parse()
);

$report->setEvent($reg->getEventMapper()->find(1));
$report->setUser($reg->getUserMapper()->find(1));
*/
$newUser = new User(-1, 'user333');

$report = $reg->getReportMapper()->find(1);
$report->setUser($newUser);

ObjectWatcher::instance()->performOperations();
