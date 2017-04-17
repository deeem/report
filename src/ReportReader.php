<?php
namespace App;

abstract class ReportReader
{
    protected $filename;

    public function __construct($filename)
    {
        if (file_exists($filename)) {
            $this->filename = $filename;
        } else {
            throw new AppException('File not found');
        }
    }

    abstract public function parse();
}
