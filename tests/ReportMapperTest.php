<?php
namespace App;

use \PHPUnit\Framework\TestCase;
use \PHPUnit\DbUnit\TestCaseTrait;

class ReportMapperTest extends TestCase
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

    public function testCreateObject()
    {
        $object = PersistenceFactory::getFactory(Report::class)->getMapper()->find(1);

        $this->assertInstanceOf(Report::class, $object);
        $this->assertInstanceOf(User::class, $object->getUser());
        $this->assertInstanceOf(Event::class, $object->getEvent());
    }

    public function testFindById()
    {
        $object = PersistenceFactory::getFactory(Report::class)->getMapper()->find(1);

        $this->assertEquals('J0200119', $object->getName());
    }

    public function testFind()
    {
        $factory = PersistenceFactory::getFactory(Report::class);
        $finder = new DomainObjectAssembler($factory);

        $idobj = $factory->getIdentityObject()->field('name')->eq('J0200119');
        $results = $finder->find($idobj);

        $this->assertEquals(1, iterator_count($results));
    }

    public function testFindOne()
    {
        $factory = PersistenceFactory::getFactory(Report::class);
        $finder = new DomainObjectAssembler($factory);

        $idobj = $factory->getIdentityObject()->field('name')->eq('J0200119');
        $result = $finder->findOne($idobj);

        $this->assertEquals('J0200119', $result->getName());
    }

    public function testInsert()
    {
        ObjectWatcher::reset();

        $user = PersistenceFactory::getFactory(User::class)->getMapper()->find(1);
        $event = PersistenceFactory::getFactory(Event::class)->getMapper()->find(1);

        $rfactory = PersistenceFactory::getFactory(Report::class);
        $rfinder = new DomainObjectAssembler($rfactory);

        $report = new Report(
            -1,
            'J0200119',
            (new YamlReader('/app/tests/J0200119.yml'))->parse(),
            $event,
            $user
        );

        $rfinder->insert($report);

        $queryTable = $this->getConnection()
        ->createQueryTable('report', 'SELECT * FROM report');

        $expectedTable = $this->createXmlDataSet(dirname(__FILE__) . '/dataset.report.insert.xml')
        ->getTable("report");

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testUpdate()
    {
        ObjectWatcher::reset();

        $factory = PersistenceFactory::getFactory(Report::class);
        $finder = new DomainObjectAssembler($factory);

        $report = $factory->getMapper()->find(1);

        $report->data->find('1.1A')->setValue(50);

        $finder->insert($report);

        $queryTable = $this->getConnection()
        ->createQueryTable('report', 'SELECT * FROM report');

        $expectedTable = $this->createXmlDataSet(dirname(__FILE__) . '/dataset.report.update.xml')
        ->getTable("report");

        $this->assertTablesEqual($expectedTable, $queryTable);
    }
}
