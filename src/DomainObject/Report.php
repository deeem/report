<?php
declare(strict_types = 1);

namespace App\DomainObject;

use App\Unit\AccumulationFactory;

class Report extends DomainObject
{
    private $name;
    private $event;
    private $user;
    public $data;

    public function __construct(int $id, $name, array $data, Event $event = null, User $user = null)
    {
        parent::__construct($id);
        $this->name = $name;
        $this->event = $event;
        $this->user = $user;
        $this->data = (new AccumulationFactory())->make($data);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEvent(Event $event)
    {
        $this->event = $event;
        $this->markDirty();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $this->markDirty();
    }

    public function getData()
    {
        return json_encode($this->data->serialize());
    }
}
