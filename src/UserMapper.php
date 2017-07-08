<?php
declare(strict_types = 1);

namespace App;

class UserMapper extends Mapper
{
    private $selectStmt;
    private $selectAllStmt;

    public function __construct()
    {
        parent::__construct();

        $this->selectAllStmt = $this->pdo->prepare(
            "SELECT * FROM user"
        );

        $this->selectStmt = $this->pdo->prepare(
            "SELECT * FROM user WHERE id=?"
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
        return User::class;
    }
}
