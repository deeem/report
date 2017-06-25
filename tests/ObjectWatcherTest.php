<?php
namespace App;

use \PHPUnit\Framework\TestCase;
use \PHPUnit\DbUnit\TestCaseTrait;

class ObjectWatcherTest extends TestCase
{
    use TestCaseTrait;

    static private $pdo = null;

    private $conn = null;

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    public function getDataSet()
    {
        return $this->createXmlDataSet(dirname(__FILE__) . '/objectWatcherDataSet.xml');
    }

    public function testObjectWatcherAddToMap()
    {
        $mapper = new ReportMapper(self::$pdo);
        $mapper->find(1);

        $watcher = ObjectWatcher::instance();
        $this->assertInstanceOf(User::class, $watcher::exists(User::class, 1));
        $this->assertInstanceOf(Event::class, $watcher::exists(Event::class, 1));
        $this->assertInstanceOf(Report::class, $watcher::exists(Report::class, 1));
    }

    public function testObjectWatcherGetFromMap()
    {
        $mapper1 = new ReportMapper(self::$pdo);
        $reportFromMapper1 = $mapper1->find(1);
        $reportFromMapper1->data->find('1.1A')->setValue(50);

        $mapper2 = new ReportMapper(self::$pdo);
        $reportFromMapper2 = $mapper2->find(1);

        $this->assertEquals(
            $reportFromMapper1->data->find('1.1A')->getValue(),
            $reportFromMapper2->data->find('1.1A')->getValue()
        );
    }
}
