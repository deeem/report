<?php
declare(strict_types = 1);

namespace App;

class ReportMapper extends Mapper
{
    private $selectAllStmt;
    private $selectStmt;
    private $findByEventStmt;
    private $findByUserStmt;

    public function __construct()
    {
        parent::__construct();

        $this->selectAllStmt = $this->pdo->prepare(
            "SELECT * FROM report"
        );

        $this->selectStmt = $this->pdo->prepare(
            "SELECT * FROM report WHERE id=?"
        );

        $this->findByEventStmt = $this->pdo->prepare(
            "SELECT * FROM report WHERE event=?"
        );

        $this->findByUserStmt = $this->pdo->prepare(
            "SELECT * FROM report WHERE user=?"
        );
    }

    public function findByEvent($eid): Collection
    {
        return new DefferedReportCollection(
            PersistenceFactory::getFactory(Report::class)->getDomainObjectFactory(),
            $this->findByEventStmt,
            [$eid]
        );
    }

    public function findByUser($uid): Collection
    {
        return new DefferedReportCollection(
            PersistenceFactory::getFactory(Report::class)->getDomainObjectFactory(),
            $this->findByUserStmt,
            [$uid]
        );
    }

    public function selectStmt(): \PDOStatement
    {
        return $this->selectStmt;
    }

    public function selectAllStmt(): \PDOStatement
    {
        return $this->selectAllStmt;
    }

    protected function targetClass(): string
    {
        return Report::class;
    }
}
