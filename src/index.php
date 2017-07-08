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

$foo1 = new User(-1, 'foo1');
$foo2 = new User(-1, 'foo2');
$foo3 = new User(-1, 'foo3');

$finder = new DomainObjectAssembler(PersistenceFactory::getFactory(User::class));
$finder->insert($foo1);
$finder->insert($foo2);
$finder->insert($foo3);
