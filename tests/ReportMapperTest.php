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
        return $this->createXmlDataSet(dirname(__FILE__) . '/reportMapperDataSet.Empty.xml');
    }

    public function testInsert()
    {
        $eventmapper = new EventMapper(self::$pdo);
        $usermapper = new UserMapper(self::$pdo);

        $report = new Report(
            -1,
            'J0200119',
            (new YamlReader('/app/tests/J0200119.yml'))->parse()
        );

        $report->setEvent($eventmapper->find(1));
        $report->setUser($usermapper->find(1));

        $mapper = new ReportMapper(self::$pdo);
        $mapper->insert($report);

        $queryTable = $this->getConnection()
        ->createQueryTable('report', 'SELECT * FROM report');

        $expectedTable = $this->createXmlDataSet(dirname(__FILE__) . '/reportMapperDataSet.Insert.xml')
        ->getTable("report");

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testFind()
    {
        $usermapper = new UserMapper(self::$pdo);
        $eventmapper = new EventMapper(self::$pdo);
        $reportmapper = new ReportMapper(self::$pdo);

        $expectedReport = new Report(
            -1,
            'J0200119',
            (new YamlReader('/app/tests/J0200119.yml'))->parse()
        );
        $expectedReport->setEvent($eventmapper->find(1));
        $expectedReport->setUser($usermapper->find(1));

        $expectedReport->data->find('1.1A')->setValue(50);

        $reportmapper->insert($expectedReport);
        $report = $reportmapper->find(1);

        $this->assertEquals($expectedReport->getData(), $report->getData());
    }

    public function testUpdate()
    {
        $report = new Report(
            -1,
            'J0200119',
            (new YamlReader('/app/tests/J0200119.yml'))->parse(),
            new Event(3, 'daily 18.06', '1806', '1906', 'A00201'),
            new User(7, 'user1')
        );

        $mapper = new ReportMapper(self::$pdo);
        $mapper->insert($report);

        $report->data->find('1.1A')->setValue(50);

        $mapper->update($report);

        $queryTable = $this->getConnection()
        ->createQueryTable('report', 'SELECT * FROM report');

        $expectedTable = $this->createXmlDataSet(dirname(__FILE__) . '/reportMapperDataSet.Update.xml')
        ->getTable('report');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testCollection()
    {
        $eventmapper = new EventMapper(self::$pdo);
        $usermapper = new UserMapper(self::$pdo);

        $report = new Report(
            -1,
            'J0200119',
            (new YamlReader('/app/tests/J0200119.yml'))->parse()
        );

        $report->setEvent($eventmapper->find(1));
        $report->setUser($usermapper->find(1));

        $mapper = new ReportMapper(self::$pdo);
        $mapper->insert($report);
        $id = self::$pdo->lastInsertId();

        $report2 = $mapper->find($id);

        $this->assertInstanceOf(Event::class, $report2->getEvent());
        $this->assertInstanceOf(User::class, $report2->getUser());
        $this->assertInstanceOf(ReportCollection::class, $report2->getEvent()->getReports());
        $this->assertInstanceOf(ReportCollection::class, $report2->getUser()->getReports());
    }
}
