<?php
declare(strict_types = 1);

namespace App;

require_once '/app/vendor/autoload.php';

error_reporting(E_ALL);

/*
$reg = Registry::instance();
$reg->setConf(new Conf(
    [
        'DB_DSN' => 'mysql:dbname=report;host=db',
        'DB_USER' => 'report',
        'DB_PASSWD' => 'secret'
    ]
));
*/

$idobj = new IdentityObject();
$idobj->field("name")
    ->eq("The Good Show")
    ->field("start")
    ->gt(time())
    ->lt(time() + (24 * 60 * 60));
var_dump($idobj->getComps());

try {
    $idobj = new EventIdentityObject();
    $idobj->field("banana")
        ->eq("The Good Show")
        ->field("start")
        ->gt(time())
        ->lt(time() + (24 * 60 * 60));

    print $idobj;
} catch (\Exception $e) {
    print $e->getMessage();
}
