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

$raw = [
    ['id' => -1, 'name' => 'ser1'],
    ['id' => -1, 'name' => 'ser2']
];

$collection = new UserCollection($raw, new UserObjectFactory());

foreach ($collection as $user) {
    var_dump($user);
}
