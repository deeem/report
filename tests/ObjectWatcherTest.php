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

    public function testAddToMap()
    {
        ObjectWatcher::reset();

        $mapper = new ReportMapper();
        $report = $mapper->find(1);

        $watcher = ObjectWatcher::instance();
        $this->assertInstanceOf(User::class, $watcher::exists(User::class, 1));
        $this->assertInstanceOf(Event::class, $watcher::exists(Event::class, 1));
        $this->assertInstanceOf(Report::class, $watcher::exists(Report::class, 1));
    }

    public function testGetFromMap()
    {
        ObjectWatcher::reset();

        $mapper1 = new ReportMapper();
        $reportFromMapper1 = $mapper1->find(1);
        $reportFromMapper1->data->find('1.1A')->setValue(50);

        $mapper2 = new ReportMapper();
        $reportFromMapper2 = $mapper2->find(1);

        $this->assertEquals(
            $reportFromMapper1->data->find('1.1A')->getValue(),
            $reportFromMapper2->data->find('1.1A')->getValue()
        );
    }

    public function testInsertNewObjects()
    {
        ObjectWatcher::reset();

        $eventmapper = new EventMapper();
        $usermapper = new UserMapper();

        $report = new Report(
            -1,
            'J0200119',
            (new YamlReader('/app/tests/J0200119.yml'))->parse(),
            $eventmapper->find(1),
            $usermapper->find(1)
        );

        ObjectWatcher::instance()->performOperations();

        $queryTable = $this->getConnection()
        ->createQueryTable('report', 'SELECT * FROM report');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/dataset.report.insert.xml'
        )->getTable('report');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testUpdateExistingObjects()
    {
        ObjectWatcher::reset();

        $newUser = new User(-1, 'user2');
        ObjectWatcher::instance()->performOperations();

        $reportMapper = new ReportMapper();
        $report = $reportMapper->find(1);
        $report->setUser($newUser);
        ObjectWatcher::instance()->performOperations();

        $queryReportTable = $this->getConnection()
        ->createQueryTable('report', 'SELECT * FROM report');

        $expectedReportTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/dataset.watcher.update.xml'
        )->getTable('report');

        $this->assertTablesEqual($expectedReportTable, $queryReportTable);

        $queryUserTable = $this->getConnection()
        ->createQueryTable('user', 'SELECT * FROM user');

        $expectedUserTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/dataset.watcher.update.xml'
        )->getTable('user');

        $this->assertTablesEqual($expectedUserTable, $queryUserTable);
    }

    public function testInsertNewObjects1()
    {
        ObjectWatcher::reset();

        $foo1 = new User(-1, 'foo1');
        $foo2 = new User(-1, 'foo2');
        $foo3 = new User(-1, 'foo3');

        $finder = new DomainObjectAssembler(PersistenceFactory::getFactory(User::class));
        $finder->insert($foo1);
        $finder->insert($foo2);
        $finder->insert($foo3);

        $queryTable = $this->getConnection()
        ->createQueryTable('user', 'SELECT * FROM user');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/dataset.watcher.insert.xml'
        )->getTable('user');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testInsertNewObjects2()
    {
        ObjectWatcher::reset();

        $foo1 = new User(-1, 'foo1');
        $foo2 = new User(-1, 'foo2');
        $foo3 = new User(-1, 'foo3');

        ObjectWatcher::instance()->performOperations();

        $queryTable = $this->getConnection()
        ->createQueryTable('user', 'SELECT * FROM user');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/dataset.watcher.insert.xml'
        )->getTable('user');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }
}
