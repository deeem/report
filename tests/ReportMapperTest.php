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
                self::$pdo = new \PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
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
        $report = new Report(
            'J0200119',
            '3',
            (new YamlReader('/app/tests/J0200119.yml'))->parse()
        );

        $mapper = new ReportMapper(self::$pdo);
        $mapper->insert($report);

        $queryTable = $this->getConnection()
        ->createQueryTable('report', 'SELECT * FROM report');

        $expectedTable = $this->createXmlDataSet(dirname(__FILE__) . '/reportMapperDataSet.Insert.xml')
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

        $expectedTable = $this->createXmlDataSet(dirname(__FILE__) . '/reportMapperDataSet.Update.xml')
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

        $report = $mapper->find(1);

        $this->assertEquals($expectedReport->getData(), $report->getData());
    }

    // public function testSelectStmt - fetch multiple reports
}
