<?php
declare(strict_types = 1);

namespace App\DataMapper;

use App\AppException;

abstract class Collection implements \Iterator
{
    protected $dofact = null;
    protected $total = 0;
    protected $raw = [];

    private $result;
    private $pointer = 0;
    private $objects = [];

    public function __construct(array $raw = [], DomainObjectFactory $dofact = null)
    {
        if (count($raw) && ! is_null($dofact)) {
            $this->raw = $raw;
            $this->total = count($raw);
        }

        $this->dofact = $dofact;
    }

    public function add(DomainObject $object)
    {
        $class = $this->targetClass();

        if (! ($object instanceof $class)) {
            throw new AppException("This is a {$class} collection");
        }

        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }

    abstract public function targetClass(): string;

    protected function notifyAccess()
    {
        // deliberately left blank!
    }

    private function getRow(int $num)
    {
        $this->notifyAccess();

        if ($num >= $this->total || $num < 0) {
            return null;
        }

        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }

        if (isset($this->raw[$num])) {
            $this->objects[$num] = $this->dofact->createObject($this->raw[$num]);

            return $this->objects[$num];
        }
    }

    public function rewind()
    {
        $this->pointer = 0;
    }

    public function current()
    {
        return $this->getRow($this->pointer);
    }

    public function key()
    {
        return $this->pointer;
    }

    public function next()
    {
        $row = $this->getRow($this->pointer);

        if (! is_null($row)) {
            $this->pointer++;
        }
    }

    public function valid()
    {
        return (! is_null($this->current()));
    }
}
