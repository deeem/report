<?php
declare(strict_types = 1);

namespace App;

class ReportMapper extends Mapper
{
    private $selectAllStmt;
    private $selectStmt;
    private $updateStmt;
    private $insertStmt;
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

        $this->insertStmt = $this->pdo->prepare(
            "INSERT INTO report(name, event, user, data) VALUES(?, ?, ?, ?)"
        );

        $this->updateStmt = $this->pdo->prepare(
            "UPDATE report SET id=?, name=?, event=?, user=?, data=? WHERE id=?"
        );
    }

    protected function doInsert(DomainObject $object)
    {
        $event = $object->getEvent();

        if (! $event) {
            throw new AppException("cannot save without Event");
        }

        $user = $object->getUser();

        if (! $user) {
            throw new AppException("cannot save without User");
        }

        $values = [
            $object->getName(),
            $object->getEvent()->getId(),
            $object->getUser()->getId(),
            $object->getData()
        ];

        $this->insertStmt->execute($values);
        $id = $this->pdo->lastInsertId();
        $object->setId((int)$id);
    }

    public function update(DomainObject $object)
    {
        $values = [
            $object->getId(),
            $object->getName(),
            $object->getEvent()->getId(),
            $object->getUser()->getId(),
            $object->getData(),
            $object->getId()
        ];

        $this->updateStmt->execute($values);
    }

    public function findByEvent($eid): Collection
    {
        return new DefferedReportCollection(
            $this,
            $this->findByEventStmt,
            [$eid]
        );
    }

    public function findByUser($uid): Collection
    {
        return new DefferedReportCollection(
            $this,
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
