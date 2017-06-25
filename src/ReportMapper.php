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

    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);

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

    public function getCollection(array $raw): Collection
    {
        return new ReportCollection($raw, $this);
    }

    protected function doCreateObject(array $raw): DomainObject
    {
        $obj = new Report(
            (int)$raw['id'],
            $raw['name'],
            json_decode($raw['data'], true)
        );

        $usermapper = new UserMapper($this->pdo);
        $user = $usermapper->find((int)$raw['user']);
        $obj->setUser($user);
        $eventmapper = new EventMapper($this->pdo);
        $event = $eventmapper->find((int)$raw['event']);
        $obj->setEvent($event);

        return $obj;
    }

    protected function doInsert(DomainObject $object)
    {
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
        $this->findByEventStmt->execute([$eid]);
        return new ReportCollection($this->findByEventStmt->fetchAll(), $this);
    }

    public function findByUser($uid): Collection
    {
        $this->findByUserStmt->execute([$uid]);
        return new ReportCollection($this->findByUserStmt->fetchAll(), $this);
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
