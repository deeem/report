<?php
declare(strict_types = 1);

namespace App;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

use App\DataMapper\PersistenceFactory;
use App\DomainObject\Event;
use App\DomainObject\Report;
use App\DomainObject\User;
use App\Unit\Accumulation;
use App\Unit\AccumulationFactory;

class ReportFactory
{
    private $name;
    private $event;
    private $user;

    public function __construct(string $name, int $event, int $user)
    {
        $this->name = $name;
        $this->event = $event;
        $this->user = $user;
    }

    public function make(): Report
    {
        $template = $this->parse(
            $this->getTemplatePath($this->name)
        );

        return new Report(
            -1,
            $this->name,
            $this->getAccumulation($template),
            $this->getEvent($this->event),
            $this->getUser($this->user)
        );
    }

    private function getTemplatePath(string $name)
    {
        $filename = "/app/templates/{$name}.yml";

        if (! file_exists($filename)) {
            throw new AppException("Template {$name}.yml not found");
        }

        return $filename;
    }

    private function parse(string $path): array
    {
        try {
            $array = Yaml::parse(file_get_contents($path));
        } catch (ParseException $e) {
            throw new AppException("Unable to parse the YAML string: %s", $e->getMessage());
        }

        return $array;
    }

    private function getEvent(int $id): Event
    {
        $event = PersistenceFactory::getFactory(Event::class)->getMapper()->find($id);

        if (! $event) {
            throw new AppException("Unable to find user with id equals {$id}");
        }

        return $event;
    }

    private function getUser(int $id): User
    {
        $user = PersistenceFactory::getFactory(User::class)->getMapper()->find($id);

        if (! $user) {
            throw new AppException("Unable to find user with id equals {$id}");
        }

        return $user;
    }

    private function getAccumulation(array $template): Accumulation
    {
        return (new AccumulationFactory())->make($template);
    }
}
