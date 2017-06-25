<?php
declare(strict_types = 1);

namespace App;

require_once '/app/vendor/autoload.php';

error_reporting(E_ALL);

$pdo = new \PDO('mysql:dbname=report;host=db', 'report', 'secret');

$watcher = ObjectWatcher::instance();

$rmapper = new ReportMapper($pdo);
$emapper = new EventMapper($pdo);
$umapper = new UserMapper($pdo);

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
