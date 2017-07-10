<?php
declare(strict_types = 1);

namespace App\Unit;

class Template extends Composite
{
    protected $schema;

    public function isFixed(): bool
    {
        return false;
    }

    public function setSchema(array $schema)
    {
        $this->schema = $schema;
    }

    public function generate()
    {
        $name = base_convert(hash('crc32b', strval(time() + random_int(0, 100))), 16, 36);

        $accumulation = (new AccumulationFactory())->make(['name' => $name, 'pile' => $this->schema]);
        $this->addChild($accumulation);

        return $accumulation;
    }

    public function serialize()
    {
        $pile = [];

        foreach ($this->children as $item) {
            $pile[] = $item->serialize();
        }

        return [
            'name' => $this->name,
            'type' => 'template',
            'schema' => $this->schema,
            'pile' => $pile
        ];
    }
}
