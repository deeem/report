<?php
declare(strict_types = 1);

namespace App;

abstract class Mapper
{
    protected $pdo = null;

    public function __construct()
    {
        $reg = Registry::instance();
        $this->pdo = $reg->getPdo();
    }

    private function getFromMap($id)
    {
        return ObjectWatcher::exists(
            $this->targetClass(),
            $id
        );
    }

    private function addToMap(DomainObject $obj): DomainObject
    {
        return ObjectWatcher::add($obj);
    }

    public function find(int $id)
    {
        $old = $this->getFromMap($id);

        if ($old) {
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
        $object->markClean();

        return $object;
    }

    public function findAll(): Collection
    {
        $this->selectAllStmt()->execute([]);

        return $this->getCollection($this->selectAllStmt()->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function getFactory(): PersistenceFactory
    {
        return PersistenceFactory::getFactory($this->targetClass());
    }

    public function createObject(array $row): DomainObject
    {
        $objectfactory = $this->getFactory()->getDomainObjectFactory();

        return $objectfactory->createObject($row);
    }

    public function getCollection(array $raw): Collection
    {
        return $this->getFactory()->getCollection($raw);
    }

    public function insert(DomainObject $obj)
    {
        $this->doInsert($obj);
        $this->addToMap($obj);
        $obj->markClean();
    }

    // abstract public function update(DomainObject $object);
    abstract protected function doInsert(DomainObject $object);
    abstract protected function selectStmt(): \PDOStatement;
    abstract protected function selectAllStmt(): \PDOStatement;
    abstract protected function targetClass(): string;
}
