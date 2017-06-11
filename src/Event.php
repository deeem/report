<?php
declare(strict_types = 1);

namespace App;

class Event extends DomainObject
{
    protected $id;
    protected $name;
    protected $start;
    protected $end;
    protected $report;

    public function __construct($name, $start, $end, $report, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->start = $start;
        $this->end = $end;
        $this->report = $report;
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

    public function getStart()
    {
        return $this->start;
    }

    public function setStart($start)
    {
        $this->start = $start;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setEnd($end)
    {
        $this->end = $end;
    }

    public function getReport()
    {
        return $this->report;
    }

    public function setReport(string $report)
    {
        $this->report = $report;
    }
}
