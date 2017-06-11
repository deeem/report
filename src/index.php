<?php
namespace App;

require_once '/app/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

error_reporting(E_ALL);

// // INIT
// $r1 = new Report(
//     'J0200119',
//     '1',
//     (new YamlReader('/app/tests/J0200119.yml'))->parse()
// );
//
// $r2 = new Report(
//     'J0200119',
//     '2',
//     (new YamlReader('/app/tests/J0200119.yml'))->parse()
// );
//
// $r3 = new Report(
//     'J0200119',
//     '3',
//     (new YamlReader('/app/tests/J0200119.yml'))->parse()
// );
//
// //eval(\Psy\sh());
// $collection = new ReportCollection();
// $collection->add($r1);
// $collection->add($r2);
// $collection->add($r3);
//
// foreach($collection as $report) {
//     print $report->getName() . ' ' . $report->getEvent() . "\n";
// }

$pdo = new \PDO('mysql:dbname=report;host=db', 'report', 'secret');

$eventMapper = new EventMapper($pdo);

$event = $eventMapper->find(1);
$event->setName('daily 18.06');

$eventMapper->update($event);

//$eventMapper->insert($event);

//$mapper = new ReportMapper($pdo);

// $report = $mapper->find(2);

// $name = 'J0200119';
// $event = '3';
// $data = (new YamlReader('/app/tests/J0200119.yml'))->parse();
//
// $report = new Report($name, $event, $data);
//
// $mapper = new ReportMapper($pdo);
// $mapper->insert($report);
//
// // INPUT
//
// $report->data->find('1.1A')->setValue(1566790);
// $report->data->find('1.1B')->setValue(313358);
// $report->data->find('3A')->setValue(150186);
// $report->data->find('4.1A')->setValue(471253);
// $report->data->find('4.1B')->setValue(942451);
// $report->data->find('7A')->setValue(-280446);
// $report->data->find('7B')->setValue(-56089);
// $report->data->find('8B')->setValue(1199720);
// $report->data->find('10.1A')->setValue(5978284);
// $report->data->find('10.1B')->setValue(1195657);
// $report->data->find('10.2A')->setValue(1035);
// $report->data->find('10.2B')->setValue(72);
// $report->data->find('18')->setValue(3991);
//
// // var_dump($report->data->find('9B')->getValue(), $report->data->find("17B")->getValue());
// var_dump($report->data->serialize());
// $new = $report->data->find('dop')->generate();
// $new->find("dopitem")->setValue('AAAAAA');
// var_dump(Yaml::dump($report->data->serialize()), $report->serialize());
