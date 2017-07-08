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
        $object = PersistenceFactory::getFactory(Event::class)->getMapper()->find(1);

        $this->assertInstanceOf(Event::class, $object);
        $this->assertInstanceOf(DefferedReportCollection::class, $object->getReports());
    }

    public function testFindById()
    {
        $object = PersistenceFactory::getFactory(Event::class)->getMapper()->find(1);

        $this->assertEquals('J0200119', $object->getReport());
    }

    public function testFind()
    {
        $factory = PersistenceFactory::getFactory(Event::class);
        $finder = new DomainObjectAssembler($factory);

        $idobj = $factory->getIdentityObject()->field('report')->eq('J0200119');
        $results = $finder->find($idobj);

        $this->assertEquals(1, iterator_count($results));
    }

    public function testFindOne()
    {
        $factory = PersistenceFactory::getFactory(Event::class);
        $finder = new DomainObjectAssembler($factory);

        $idobj = $factory->getIdentityObject()->field('name')->eq('milestone (18.06)');
        $result = $finder->findOne($idobj);

        $this->assertEquals('milestone (18.06)', $result->getName());
    }

    public function testInsert()
    {
        $factory = PersistenceFactory::getFactory(Event::class);
        $finder = new DomainObjectAssembler($factory);

        $object = new Event(-1, 'milestone (18.06)', '1499595597793', '1499595653657', 'A0200119');
        $finder->insert($object);

        $queryTable = $this->getConnection()
        ->createQueryTable('event', 'SELECT * FROM event');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/dataset.event.insert.xml'
        )->getTable('event');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testUpdate()
    {
        $factory = PersistenceFactory::getFactory(Event::class);
        $finder = new DomainObjectAssembler($factory);

        $idobj = $factory->getIdentityObject()->field('report')->eq('J0200119');
        $obj = $finder->findOne($idobj);
        $obj->setReport('A0200119');
        $finder->insert($obj);

        $queryTable = $this->getConnection()
        ->createQueryTable('event', 'SELECT * FROM event');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/dataset.event.update.xml'
        )->getTable('event');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }
}
