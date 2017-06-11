<?php
namespace App;

use \PHPUnit\Framework\TestCase;
use \PHPUnit\DbUnit\TestCaseTrait;

class UserMapperTest extends TestCase
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
        return $this->createXmlDataSet(dirname(__FILE__) . '/userMapperDataSet.Empty.xml');
    }

    public function testInsert()
    {
        $mapper = new UserMapper(self::$pdo);
        $object = new User('user1');
        $mapper->insert($object);

        $queryTable = $this->getConnection()
        ->createQueryTable('user', 'SELECT * FROM user');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/userMapperDataSet.Insert.xml'
        )->getTable('user');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testFind()
    {
        $mapper = new UserMapper(self::$pdo);
        $mapper->insert(new User('user1'));
        $object = $mapper->find(1);

        $this->assertEquals('user1', $object->getName());
    }

    public function testUpdate()
    {
        $mapper = new UserMapper(self::$pdo);
        $mapper->insert(new User('user1'));
        $object = $mapper->find(1);
        $object->setName('user2');
        $mapper->update($object);

        $queryTable = $this->getConnection()
        ->createQueryTable('user', 'SELECT * FROM user');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/userMapperDataSet.Update.xml'
        )->getTable('user');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }
}
