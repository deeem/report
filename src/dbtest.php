<?php
namespace App;

$host = 'db';
$dbname = 'report';
$user = 'report';
$pass = 'secret';

try {
    $dbh = new \PDO("mysql:host={$host};dbname={$dbname}", $user, $pass);
} catch (\PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
}
