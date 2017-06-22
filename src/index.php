<?php
namespace App;

require_once '/app/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

error_reporting(E_ALL);

$pdo = new \PDO('mysql:dbname=report;host=db', 'report', 'secret');

$reportmapper = new ReportMapper($pdo);
$report = $reportmapper->find(1);
