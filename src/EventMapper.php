<?php
declare(strict_types = 1);

namespace App;

class EventMapper extends Mapper
{
    private $selectStmt;
    private $updateStmt;
    private $insertStmt;

    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);

        $this->selectStmt = $this->pdo->prepare(
            "SELECT * FROM event WHERE id=?"
        );

        $this->updateStmt = $this->pdo->prepare(
            "UPDATE event SET name=?, start=?, end=?, report=?, id=? WHERE id=?"
        );

        $this->insertStmt = $this->pdo->prepare(
            "INSERT INTO event(name, start, end, report) VALUES(?, ?, ?, ?)"
        );
    }

    public function getCollection(array $raw): Collection
    {
        return new EventCollection($raw, $this);
    }

    protected function doCreateObject(array $raw): DomainObject
    {
        $obj = new Event(
            (int)$raw['id'],
            $raw['name'],
            (int)$raw['start'],
            (int)$raw['end'],
            $raw['report']
        );

        $reportmapper = new ReportMapper($this->pdo);
        $reportcollection = $reportmapper->findByEvent($raw['id']);
        $obj->setReports($reportcollection);

        return $obj;
    }

    protected function doInsert(DomainObject $object)
    {
        $values = [
            $object->getName(),
            $object->getStart(),
            $object->getEnd(),
            $object->getReport()
        ];

        $this->insertStmt->execute($values);
        $id = $this->pdo->lastInsertId();
        $object->setId((int)$id);
    }

    public function update(DomainObject $object)
    {
        $values = [
            $object->getName(),
            $object->getStart(),
            $object->getEnd(),
            $object->getReport(),
            $object->getId(),
            $object->getId()
        ];

        $this->updateStmt->execute($values);
    }

    public function selectStmt(): \PDOStatement
    {
        return $this->selectStmt;
    }

    public function selectAllStmt(): \PDOStatement
    {
        return $this->selectAllStmt;
    }
}
