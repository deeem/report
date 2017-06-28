<?php
declare(strict_types = 1);

namespace App;

class Registry
{
    private static $instance = null;
    private $conf = null;
    private $pdo = null;

    private function __construct()
    {
    }

    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setConf(Conf $conf)
    {
        $this->conf = $conf;
    }

    public function getConf(): Conf
    {
        if (is_null($this->conf)) {
            $this->conf = new Conf();
        }

        return $this->conf;
    }

    public function getPdo(): \PDO
    {
        if (is_null($this->pdo)) {
            $conf = $this->getConf();
            $dsn = $conf->get("DB_DSN");
            $user = $conf->get("DB_USER");
            $password = $conf->get("DB_PASSWD");

            if (is_null($dsn)) {
                throw new AppException("No DSN");
            }

            $this->pdo = new \PDO($dsn, $user, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return $this->pdo;
    }

    // mappers

    // collections
}
