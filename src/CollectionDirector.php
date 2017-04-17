<?php
namespace App;

class CollectionDirector extends CompositeDirector
{
    private $builder;

    public function build(CompositeBuilder $builder, array $elements)
    {
        $this->builder = $builder;

        foreach ($elements as $item) {
            $params = isset($item['params']) ? $item['params'] : [];

            switch ($item['type']) {
                case 'input':
                    $this->builder->appendInput($item['name'], $params);
                    break;
                case 'select':
                    $this->builder->appendSelect($item['name'], $params);
                    break;
                case 'summary':
                    $this->builder->appendSummary($item['name'], $params);
                    break;
                case 'percentage':
                    $this->builder->appendPercentage($item['name'], $params);
                    break;
                case 'collection':
                    $this->builder->appendCollection($item['name'], $params);
                    break;
                case 'template':
                    $this->builder->appendTemplate($item['name'], $params);
                    break;
                default:
                    throw new CellException('Unknown type');
            }
        }

        return $this->builder->getResult();
    }
}
