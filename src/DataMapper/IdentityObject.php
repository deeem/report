<?php
declare(strict_types = 1);

namespace App\DataMapper;

use App\AppException;

class IdentityObject
{
    protected $currentfield = null;
    protected $fields = [];
    private $enforce = [];

    // and identity object can start off empty, or with a field
    public function __construct(string $field = null, array $enforce = null)
    {
        if (! is_null($enforce)) {
            $this->enforce = $enforce;
        }

        if (! is_null($field)) {
            $this->field($field);
        }
    }

    // field names to which this is constrained
    public function getObjectFields()
    {
        return $this->enforce;
    }

    // kick off a new field.
    // will throw error if a current field is not complete
    // (ie age rather than age > 40)
    // this method returns a referense to the current object
    // allow for fluent syntax
    public function field(string $fieldname): self
    {
        if (! $this->isVoid() && $this->currentfield->isIncomplete()) {
            throw new AppException("Incomplete field");
        }

        $this->enforceField($fieldname);

        if (isset($this->fields[$fieldname])) {
            $this->currentfield = $this->fields[$fieldname];
        } else {
            $this->currentfield = new Field($fieldname);
            $this->fields[$fieldname] = $this->currentfield;
        }

        return $this;
    }

    // does the identity object have any fields yet
    public function isVoid(): bool
    {
        return empty($this->fields);
    }

    // is the given fieldname legal?
    public function enforceField(string $fieldname)
    {
        if (! in_array($fieldname, $this->enforce) && ! empty($this->enforce)) {
            $forcelist = implode(', ', $this->enforce);
            throw new AppException("{$fieldname} not a legal field ($forcelist)");
        }
    }

    // add an equality operator to the current field
    // ie 'age' becomes age=40
    // return a reference to the current object (via operator())
    public function eq($value): self
    {
        return $this->operator("=", $value);
    }

    // less then
    public function lt($value): self
    {
        return $this->operator("<", $value);
    }

    // greater than
    public function gt($value): self
    {
        return $this->operator(">", $value);
    }

    // does the work for the operator methods
    // gets the current field and adds the operator and test value
    // to it
    private function operator(string $symbol, $value): self
    {
        if ($this->isVoid()) {
            throw new AppException("no object field defined");
        }

        $this->currentfield->addTest($symbol, $value);

        return $this;
    }

    // return all comparsions built up so far in an associative array
    public function getComps(): array
    {
        $ret = [];

        foreach ($this->fields as $field) {
            $ret = array_merge($ret, $field->getComps());
        }

        return $ret;
    }
}
