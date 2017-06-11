<?php
declare(strict_types = 1);

namespace App;

class User extends DomainObject
{
    protected $id;
    protected $name;

    public function __construct($name, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
