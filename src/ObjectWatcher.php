<?php
declare(strict_types = 1);

namespace App;

class ObjectWatcher
{
    private static $instance = null;
    private $all = [];
    /**
     * Part of "Unit of Work" pattern implementation
     * @var array Objects desribed as "dirty" when they have been
     */
    private $dirty = [];
    /**
     * Part of "Unit of Work" pattern implementation
     * @var array Stores newly created objects
     */
    private $new = [];
    /**
     * Part of "Unit of Work" pattern implementation
     * @var array unused in this example
     */
    private $delete = [];

    private function __construct()
    {
    }

    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new ObjectWatcher();
        }

        return self::$instance;
    }

    public static function reset()
    {
        self::$instance = null;
    }

    public function globalKey(DomainObject $obj): string
    {
        $key = get_class($obj) . "." . $obj->getId();

        return $key;
    }

    public static function add(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->all[$inst->globalKey($obj)] = $obj;

        return $obj;
    }

    public static function exists($classname, $id)
    {
        $inst = self::instance();
        $key = "{$classname}.{$id}";

        if (isset($inst->all[$key])) {
            return $inst->all[$key];
        }

        return null;
    }

    /**
     * Mark object to "delete"
     * Part of "Unit of Work" pattern implementation
     * @param DomainObject $obj delete object
     */
    public static function addDelete(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->delete[$self->globalKey($obj)] = $obj;
    }

    /**
     * Mark object as "dirty"
     * Part of "Unit of Work" pattern implementation
     * @param DomainObject $obj "dirty" object
     */
    public static function addDirty(DomainObject $obj)
    {
        $inst = self::instance();

        if (! in_array($obj, $inst->new, true)) {
            $inst->dirty[$inst->globalKey($obj)] = $obj;
        }
    }

    /**
     * Mark object as "new"
     * Part of "Unit of Work" pattern implementation
     * @param DomainObject $obj "new" object
     */
    public static function addNew(DomainObject $obj)
    {
        $inst = self::instance();
        // we don't yet have an id
        $inst->new[] = $obj;
    }

    /**
     * Mark object as "clean"
     * Client code may decide that a dirty object should not undergo update for its own reasons
     * Part of "Unit of Work" pattern implementation
     * @param DomainObject $obj object to "clean"
     */
    public static function addClean(DomainObject $obj)
    {
        $inst = self::instance();
        unset($inst->delete[$inst->globalKey($obj)]);
        unset($inst->dirty[$inst->globalKey($obj)]);

        $inst->new = array_filter(
            $inst->new,
            function ($a) use ($obj) {
                return !($a === $obj);
            }
        );
    }

    /**
     * This method add, update or delete pending objects
     * Part of "Unit of Work" pattern implementation
     */
    public function performOperations()
    {
        foreach ($this->dirty as $key => $obj) {
            $obj->getFinder()->update($obj);
        }

        foreach ($this->new as $key => $obj) {
            $obj->getFinder()->insert($obj);
        }

        $this->dirty = [];
        $this->new = [];
    }
}
