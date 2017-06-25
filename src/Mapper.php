<?php
declare(strict_types = 1);

namespace App;

abstract class Mapper
{
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find(int $id): DomainObject
    {
        $old = $this->getFromMap($id);

        if (! is_null($old)) {
            return $old;
        }

        $this->selectstmt()->execute([$id]);
        $row = $this->selectstmt()->fetch();
        $this->selectstmt()->closeCursor();

        if (! is_array($row)) {
            return null;
        }

        if (! isset($row['id'])) {
            return null;
        }

        $object = $this->createObject($row);

        return $object;
    }

    public function findAll(): Collection
    {
        $this->selectAllStmt()->execute([]);

        return $this->getCollection($this->selectAllStmt()->fetchAll());
    }

    public function insert(DomainObject $obj)
    {
        $this->doInsert($obj);
        $this->addToMap($obj);
    }

    private function getFromMap($id)
    {
        return ObjectWatcher::exists(
            $this->targetClass(),
            $id
        );
    }

    private function addToMap(DomainObject $obj)
    {
        ObjectWatcher::add($obj);
    }

    public function createObject($raw)
    {
        $old = $this->getFromMap($raw['id']);

        if (! is_null($old)) {
            return $old;
        }

        $obj = $this->doCreateObject($raw);
        $this->addToMap($obj);

        return $obj;
    }

    abstract public function update(DomainObject $object);
    abstract protected function doCreateObject(array $raw): DomainObject;
    abstract protected function doInsert(DomainObject $object);
    abstract protected function selectStmt(): \PDOStatement;
    abstract protected function selectAllStmt(): \PDOStatement;
    abstract protected function getCollection(array $raw): Collection;
    abstract protected function targetClass(): string;
}
