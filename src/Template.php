<?php
namespace App;

class Template extends Composite
{
    protected $template;

    public function isFixed(): bool
    {
        return false;
    }

    public function setTemplate(array $elements)
    {
        $this->template = $elements;
    }

    public function generate()
    {
        $name = base_convert(hash('crc32b', microtime() + rand()), 16, 36);

        $builder = new CollectionBuilder();
        $builder->setName($name);
        $collection = (new CollectionDirector())->build($builder, $this->template);
        $this->addChild($collection);

        return $collection;
    }
}
