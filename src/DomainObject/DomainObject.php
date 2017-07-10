<?php
declare(strict_types = 1);

namespace App\DomainObject;

use App\DataMapper\ObjectWatcher;

abstract class DomainObject
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;

        if ($id < 0) {
            $this->markNew();
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Mark object as newly created
     * Part of "Unit of Work" pattern implementation
     */
    public function markNew()
    {
        ObjectWatcher::addNew($this);
    }

    /**
     * Mark object for deletion
     * Part of "Unit of Work" pattern implementation
     */
    public function markDeleted()
    {
        ObjectWatcher::addDelete($this);
    }

    /**
     * Mark object as "changed"
     * Part of "Unit of Work" pattern implementation
     */
    public function markDirty()
    {
        ObjectWatcher::addDirty($this);
    }

    /**
     * Mark object as "clean" (do not update this object data in DB)
     * Part of "Unit of Work" pattern implementation
     */
    public function markClean()
    {
        ObjectWatcher::addClean($this);
    }
}
