<?php
declare(strict_types = 1);

namespace App;

class ReportMapper extends Mapper
{
    private $selectStmt;
    private $updateStmt;
    private $insertStmt;

    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);
        $this->selectStmt = $this->pdo->prepare(
            "SELECT * FROM report WHERE id=?"
        );
        $this->updateStmt = $this->pdo->prepare(
            "UPDATE report SET name=?, event=?, data=? WHERE id=?"
        );
        $this->insertStmt = $this->pdo->prepare(
            "INSERT INTO report(name, event, data) VALUES(?, ?, ?)"
        );
    }

    public function getCollection(array $raw): Collection
    {
        return new ReportCollection($raw, $this);
    }

    protected function doCreateObject(array $raw): DomainObject
    {
        $obj = new Report(
            $raw['name'],
            (int)$raw['event'],
            json_decode($raw['data'], true),
            (int)$raw['id']
        );
        return $obj;
    }

    protected function doInsert(DomainObject $object)
    {
        $values = [
            $object->getName(),
            $object->getEvent(),
            $object->getData()
        ];

        $this->insertStmt->execute($values);
        $id = $this->pdo->lastInsertId();
        $object->setId((int)$id);
    }

    public function update(DomainObject $object)
    {
        $values = [
            $object->getName(),
            $object->getEvent(),
            $object->getData()
        ];

        $this->updateStmt->execute($values);
    }

    public function selectStmt(): \PDOStatement
    {
        return $this->selectStmt;
    }
}
