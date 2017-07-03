<?php
declare(strict_types = 1);

namespace App;

class UserMapper extends Mapper
{
    private $selectStmt;
    private $selectAllStmt;
    private $updateStmt;
    private $insertStmt;

    public function __construct()
    {
        parent::__construct();

        $this->selectAllStmt = $this->pdo->prepare(
            "SELECT * FROM user"
        );

        $this->selectStmt = $this->pdo->prepare(
            "SELECT * FROM user WHERE id=?"
        );

        $this->updateStmt = $this->pdo->prepare(
            "UPDATE user SET name=?, id=? WHERE id=?"
        );

        $this->insertStmt = $this->pdo->prepare(
            "INSERT INTO user(name) VALUES(?)"
        );
    }

    public function doInsert(DomainObject $object)
    {
        $values = [
            $object->getName()
        ];

        $this->insertStmt->execute($values);
        $id = $this->pdo->lastInsertId();
        $object->setId((int)$id);
    }

    public function update(DomainObject $object)
    {
        $values = [
            $object->getName(),
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

    protected function targetClass(): string
    {
        return User::class;
    }
}
