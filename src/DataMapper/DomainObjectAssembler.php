<?php
declare(strict_types = 1);

namespace App\DataMapper;

use App\Registry;
use App\DomainObject\DomainObject;

class DomainObjectAssembler
{
    protected $pdo = null;

    public function __construct(PersistenceFactory $factory)
    {
        $this->factory = $factory;
        $reg = Registry::instance();
        $this->pdo = $reg->getPdo();
    }

    public function getStatement(string $str): \PDOStatement
    {
        if (! isset($this->statements[$str])) {
            $this->statements[$str] = $this->pdo->prepare($str);
        }

        return $this->statements[$str];
    }

    public function findOne(IdentityObject $idobj): DomainObject
    {
        $collection = $this->find($idobj);

        return $collection->current();
    }

    public function find(IdentityObject $idobj): Collection
    {
        $selfact = $this->factory->getSelectionFactory();
        list ($selection, $values) = $selfact->newSelection($idobj);
        $stmt = $this->getStatement($selection);
        $stmt->execute($values);
        $raw = $stmt->fetchAll();

        return $this->factory->getCollection($raw);
    }

    public function insert(DomainObject $obj)
    {
        $upfact = $this->factory->getUpdateFactory();
        list($update, $values) = $upfact->newUpdate($obj);
        $stmt = $this->getStatement($update);
        $stmt->execute($values);

        if ($obj->getId() < 0) {
            $obj->setId((int)$this->pdo->lastInsertId());
        }

        $obj->markClean();
    }
}
