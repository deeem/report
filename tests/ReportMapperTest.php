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
                self::$pdo = new \PDO('sqlite::memory:');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');
        }
        self::initDatabase();

        return $this->conn;
    }

    public function getDataSet()
    {
        return $this->createXmlDataSet(dirname(__FILE__) . '/reportMapperDataSet.Empty.xml');
    }

    public static function initDatabase()
    {
        $query = 'CREATE TABLE IF NOT EXISTS "report" (
            "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL,
            "name" TEXT,
            "event" INTEGER,
            "data" TEXT)';

        self::$pdo->query($query);
    }

    public function testInsert()
    {
        $report = new Report(
            'J0200119',
            '3',
            (new YamlReader('/app/tests/J0200119.yml'))->parse()
        );

        $mapper = new ReportMapper(self::$pdo);
        $mapper->insert($report);

        $queryTable = $this->getConnection()
        ->createQueryTable('report', 'SELECT * FROM report');

        $expectedTable = $this->createXmlDataSet(dirname(__FILE__) . '/reportMapperDataSet.Expected1.xml')
                              ->getTable("report");

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testUpdate()
    {
        $report = new Report(
            'J0200119',
            '3',
            (new YamlReader('/app/tests/J0200119.yml'))->parse()
        );

        $report->data->find('1.1A')->setValue(50);

        $mapper = new ReportMapper(self::$pdo);
        $mapper->insert($report);

        $queryTable = $this->getConnection()
        ->createQueryTable('report', 'SELECT * FROM report');

        $expectedTable = $this->createXmlDataSet(dirname(__FILE__) . '/reportMapperDataSet.Expected2.xml')
                              ->getTable('report');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testFind()
    {
        $expectedReport = new Report(
            'J0200119',
            '3',
            (new YamlReader('/app/tests/J0200119.yml'))->parse()
        );

        $expectedReport->data->find('1.1A')->setValue(50);

        $mapper = new ReportMapper(self::$pdo);
        $mapper->insert($expectedReport);

        $report = $mapper->find(3);

        $this->assertEquals($expectedReport->getData(), $report->getData());
    }

    // public function testSelectStmt - fetch multiple reports
}
