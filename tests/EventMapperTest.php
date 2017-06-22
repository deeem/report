<?php
namespace App;

use \PHPUnit\Framework\TestCase;
use \PHPUnit\DbUnit\TestCaseTrait;

class EventMapperTest extends TestCase
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
        return $this->createXmlDataSet(dirname(__FILE__) . '/eventMapperDataSet.Empty.xml');
    }

    public function testInsert()
    {
        $mapper = new EventMapper(self::$pdo);
        $object = new Event(-1, 'daily 18.06', '1806', '1906', 'A00201');
        $mapper->insert($object);

        $queryTable = $this->getConnection()
        ->createQueryTable('event', 'SELECT * FROM event');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/eventMapperDataSet.Insert.xml'
        )->getTable('event');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testFind()
    {
        $mapper = new EventMapper(self::$pdo);

        $mapper->insert(new Event(-1, 'daily 18.06', '1806', '1906', 'A00201'));
        $object = $mapper->find(1);

        $this->assertEquals('A00201', $object->getReport());
    }

    public function testUpdate()
    {
        $mapper = new EventMapper(self::$pdo);

        $mapper->insert(new Event(-1, 'daily 18.06', '1806', '1906', 'A00201'));
        $object = $mapper->find(1);
        $object->setReport('B00201');
        $mapper->update($object);

        $queryTable = $this->getConnection()
        ->createQueryTable('event', 'SELECT * FROM event');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/eventMapperDataSet.Update.xml'
        )->getTable('event');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }
}
