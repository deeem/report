<?php
namespace App;

class CollectionBuilder extends CompositeBuilder
{
    private $collection = null;

    public function __construct()
    {
        $this->collection = new Collection();
    }

    public function setName($name)
    {
        $this->collection->setName($name);
    }

    public function getResult()
    {
        return $this->collection;
    }

    public function appendInput($name, array $params)
    {
        $element = new Input($name);

        if (!empty($params)) {
            if (array_key_exists('value', $params)) {
                $element->setValue($params['value']);
            }
        }

        $this->collection->addChild($element);
    }

    public function appendSelect($name, array $params)
    {
        $element = new Select($name);

        if (!empty($params)) {
            if (array_key_exists('options', $params)) {
                $element->setOptions($params['options']);
            }

            if (array_key_exists('value', $params)) {
                $element->setValue($params['value']);
            }
        }

        $this->collection->addChild($element);
    }

    public function appendSummary($name, array $params)
    {
        $element = new Summary($name);

        if (!empty($params)) {
            if (array_key_exists('paths', $params)) {
                $element->setPaths($params['paths']);
            }
        }

        $this->collection->addChild($element);
    }

    public function appendPercentage($name, array $params)
    {
        $element = new Percentage($name);

        if (!empty($params)) {
            if (array_key_exists('part', $params) && array_key_exists('whole', $params)) {
                $element->setPaths($params['part'], $params['whole']);
            }
        }

        $this->collection->addChild($element);
    }

    public function appendCollection($name, $params)
    {
        $builder = new CollectionBuilder();
        $builder->setName($name);
        $director = new CollectionDirector();
        $element = $director->build($builder, $params['elements']);

        $this->collection->addChild($element);
    }

    public function appendTemplate($name, $params)
    {
        $element = new Template();
        $element->setName($name);
        $element->setTemplate($params['elements']);

        $this->collection->addChild($element);
    }
}
