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

/*
// Update Factory pattern test

$uuf = new UserUpdateFactory();
print_r($uuf->newUpdate(new User(334, "The Happy Hairband")));


// Select Factory pattern test
$uio = new UserIdentityObject();
$uio->field('name')->eq('The Happy Hairband');

$usf = new UserSelectionFactory();
print_r($usf->newSelection($uio));
*/

/*
// Identity Object

// first test
$idobj = new IdentityObject();
$idobj->field("name")
    ->eq("The Good Show")
    ->field("start")
    ->gt(time())
    ->lt(time() + (24 * 60 * 60));
var_dump($idobj->getComps());

// second test
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
*/
