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
        return $this->createXmlDataSet(dirname(__FILE__) . '/objectWatcherDataSet.xml');
    }

    public function testObjectWatcherAddToMap()
    {
        ObjectWatcher::reset();

        $mapper = new ReportMapper();
        $mapper->find(1);

        $watcher = ObjectWatcher::instance();
        $this->assertInstanceOf(User::class, $watcher::exists(User::class, 1));
        $this->assertInstanceOf(Event::class, $watcher::exists(Event::class, 1));
        $this->assertInstanceOf(Report::class, $watcher::exists(Report::class, 1));
    }

    public function testObjectWatcherGetFromMap()
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

        $reg = Registry::instance();

        $report = new Report(
            -1,
            'J0200119',
            (new YamlReader('/app/tests/J0200119.yml'))->parse(),
            $reg->getEventMapper()->find(1),
            $reg->getUserMapper()->find(1)
        );

        ObjectWatcher::instance()->performOperations();

        $queryTable = $this->getConnection()
        ->createQueryTable('report', 'SELECT * FROM report');

        $expectedTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/objectWatcherDataSet.Insert.xml'
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
            dirname(__FILE__) . '/objectWatcherDataSet.Update.xml'
        )->getTable('report');

        $this->assertTablesEqual($expectedReportTable, $queryReportTable);

        $queryUserTable = $this->getConnection()
        ->createQueryTable('user', 'SELECT * FROM user');

        $expectedUserTable = $this->createXmlDataSet(
            dirname(__FILE__) . '/objectWatcherDataSet.Update.xml'
        )->getTable('user');

        $this->assertTablesEqual($expectedUserTable, $queryUserTable);
    }
}
