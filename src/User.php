<?php
declare(strict_types = 1);

namespace App;

class User extends DomainObject
{
    protected $name;
    private $reports;

    public function __construct(int $id, $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setReports(ReportCollection $reports)
    {
        $this->reports = $reports;
    }

    public function getReports():ReportCollection
    {
        if (is_null($this->reports)) {
            // $reg = Registry::instance();
            // $this->reports = $reg->getReportCollection();
            $this->reports = new ReportCollection();
        }
        return $this->reports;
    }

    public function addReport(Report $report)
    {
        $this->getReports()->add($report);
        $report->setEvent($this);
    }
}
