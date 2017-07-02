<?php
declare(strict_types = 1);

namespace App;

class Event extends DomainObject
{
    protected $name;
    protected $start;
    protected $end;
    protected $report;
    private $reports;

    public function __construct(int $id, $name, $start, $end, $report)
    {
        parent::__construct($id);
        $this->name = $name;
        $this->start = $start;
        $this->end = $end;
        $this->report = $report;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        $this->markDirty();
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setStart($start)
    {
        $this->start = $start;
        $this->markDirty();
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setEnd($end)
    {
        $this->end = $end;
        $this->markDirty();
    }

    public function getReport()
    {
        return $this->report;
    }

    public function setReport(string $report)
    {
        $this->report = $report;
        $this->markDirty();
    }

    public function getReports(): ReportCollection
    {
        if (is_null($this->reports)) {
            $reg = Registry::instance();
            $finder = $reg->getReportMapper();
            $this->reports = $finder->findByUser($this->getId());
        }
        return $this->reports;
    }

    public function setReports(ReportCollection $reports)
    {
        $this->reports = $reports;
    }

    public function addReport(Report $report)
    {
        $this->getReports()->add($report);
        $report->setEvent($this);
    }

    public function getFinder(): Mapper
    {
        $reg = Registry::instance();
        return $reg->getEventMapper();
    }
}
