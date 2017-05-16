<?php
declare(strict_types = 1);

namespace App;

use \PHPUnit\Framework\TestCase;
use \PHPUnit\DbUnit\TestCaseTrait;

class DBTest extends TestCase
{
    use TestCaseTrait;

    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO('sqlite::memory:');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');

            self::initDatabase();
        }

        return $this->conn;
    }

    public function getDataSet()
    {
        return $this->createFlatXmlDataSet(dirname(__FILE__) . '/reports.xml');
    }

    public static function initDatabase()
    {
        $query = 'CREATE TABLE "reports" (
            "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL ,
            "data" TEXT)';

        self::$pdo->query($query);
    }

    public function testDataSet()
    {
        $queryTable = $this->getConnection()
        ->createQueryTable('reports', 'SELECT * FROM reports');

         $expectedTable = $this->createFlatXmlDataSet(dirname(__FILE__) . '/reports.xml')
                               ->getTable("reports");
         $this->assertTablesEqual($expectedTable, $queryTable);
    }
}
