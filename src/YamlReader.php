<?php
namespace App;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class YamlReader extends ReportReader
{
    public function parse()
    {
        try {
            $value = Yaml::parse(file_get_contents($this->filename));
        } catch (ParseException $e) {
            throw new AppException ("Unable to parse the YAML string: %s", $e->getMessage());
        }

        return $value;
    }
}
