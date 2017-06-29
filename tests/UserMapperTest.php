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
                Registry::reset();
                $reg = Registry::instance();
                $reg->setConf(new Conf(
                    [
                    'DB_DSN' => $GLOBALS['DB_DSN'],
                    'DB_USER' =>  $GLOBALS['DB_USER'],
                    'DB_PASSWD' => $GLOBALS['DB_PASSWD']
                    ]
                ));

                self::$pdo = $reg->getPdo();
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
        ObjectWatcher::reset();

        $mapper = new UserMapper();
        $object = new User(-1, 'user1');
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
        ObjectWatcher::reset();

        $mapper = new UserMapper();
        $mapper->insert(new User(-1, 'user1'));
        $object = $mapper->find(1);

        $this->assertEquals('user1', $object->getName());
    }

    public function testUpdate()
    {
        ObjectWatcher::reset();

        $mapper = new UserMapper();
        $mapper->insert(new User(-1, 'user1'));
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
