<?php
declare(strict_types = 1);

namespace App;

require_once '/app/vendor/autoload.php';

error_reporting(E_ALL);

$options = [
    'DB_DSN' => 'mysql:dbname=report;host=db',
    'DB_USER' => 'report',
    'DB_PASSWD' => 'secret'
];

$conf = new Conf($options);

$reg = Registry::instance();
$reg->setConf($conf);

$watcher = ObjectWatcher::instance();

$rmapper = new ReportMapper($reg->getPdo());
$emapper = new EventMapper($reg->getPdo());
$umapper = new UserMapper($reg->getPdo());

$report = new Report(
    -1,
    'J0200119',
    (new YamlReader('/app/tests/J0200119.yml'))->parse()
);

$report->setEvent($emapper->find(1));
$report->setUser($umapper->find(1));

if ($user = $watcher::exists(User::class, 1)) {
    var_dump($user->getReports());
}
