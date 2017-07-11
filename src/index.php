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

$factory = new ReportFactory('J0200119', 1, 1);
$report = $factory->make();
var_dump($report);


// $foo1 = new DomainObject\User(-1, 'foo1');
// $foo2 = new DomainObject\User(-1, 'foo2');
// $foo3 = new DomainObject\User(-1, 'foo3');
//
// $finder = new DataMapper\DomainObjectAssembler(DataMapper\PersistenceFactory::getFactory(DomainObject\User::class));
// $finder->insert($foo1);
// $finder->insert($foo2);
// $finder->insert($foo3);
