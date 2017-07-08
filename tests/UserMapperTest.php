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
        return $this->createXmlDataSet(dirname(__FILE__) . '/dataset.initial.xml');
    }

    public function testGetReports()
    {
        $object = PersistenceFactory::getFactory(User::class)->getMapper()->find(1);

        $this->assertInstanceOf(User::class, $object);
        $this->assertInstanceOf(DefferedReportCollection::class, $object->getReports());
    }

    public function testFindById()
    {
        $object = PersistenceFactory::getFactory(User::class)->getMapper()->find(1);

        $this->assertEquals('user1', $object->getName());
    }

    public function testFind()
    {
        $factory = PersistenceFactory::getFactory(User::class);
        $finder = new DomainObjectAssembler($factory);

        $idobj = $factory->getIdentityObject()->field('name')->eq('user1');
        $results = $finder->find($idobj);

        $this->assertEquals(1, iterator_count($results));
    }

    public function testFindOne()
    {
        $factory = PersistenceFactory::getFactory(User::class);
        $finder = new DomainObjectAssembler($factory);

        $idobj = $factory->getIdentityObject()->field('name')->eq('user1');
        $result = $finder->findOne($idobj);

        $this->assertEquals('user1', $result->getName());
    }

    public function testInsert()
    {
        $factory = PersistenceFactory::getFactory(User::class);
        $finder = new DomainObjectAssembler($factory);

        $object = new User(-1, 'user2');
        $finder->insert($object);

        $queryTable = $this->getConnection()
        ->createQueryTable('user', 'SELECT * FROM user');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/dataset.user.insert.xml'
        )->getTable('user');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testUpdate()
    {
        $factory = PersistenceFactory::getFactory(User::class);
        $finder = new DomainObjectAssembler($factory);

        $idobj = $factory->getIdentityObject()->field('name')->eq('user1');
        $obj = $finder->findOne($idobj);
        $obj->setName('userOne');
        $finder->insert($obj);

        $queryTable = $this->getConnection()
        ->createQueryTable('user', 'SELECT * FROM user');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/dataset.user.update.xml'
        )->getTable('user');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testNotFoundById()
    {
        $object = PersistenceFactory::getFactory(User::class)->getMapper()->find(3);

        $this->assertNull($object);
    }

    public function testNotFound()
    {
        $factory = PersistenceFactory::getFactory(User::class);
        $finder = new DomainObjectAssembler($factory);

        $idobj = $factory->getIdentityObject()->field('name')->eq('foo');
        $results = $finder->find($idobj);

        $this->assertEquals(0, iterator_count($results));
    }

    public function testBadSelectionQuery()
    {
        $this->expectException(AppException::class);

        $idobj = new UserIdentityObject();
        $idobj->field('name')->eq('1')->field('foo')->gt(time());
    }
}
