<?php
declare(strict_types=1);

namespace App;

class Report extends DomainObject
{
    private $id;
    private $name;
    private $event;
    public $data;

    public function __construct($name, $event, array $data, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->event = $event;
        $this->data = (new AccumulationFactory())->make($data);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getData()
    {
        return json_encode($this->data->serialize());
    }
}
