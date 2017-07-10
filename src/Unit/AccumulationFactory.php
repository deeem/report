<?php
declare(strict_types = 1);

namespace App\Unit;

use App\AppException;

class AccumulationFactory
{
    public function make(array $data)
    {
        return $this->makeAccumulation(['name' => $data['name'], 'pile' => $data['pile']]);
    }

    private function makeInput(array $item)
    {
        $this->isNameSet($item);
        $element = new Input($item['name']);

        if (array_key_exists('value', $item)) {
            $element->setValue($item['value']);
        }

        return $element;
    }

    private function makeSelect(array $item)
    {
        $this->isNameSet($item);
        $element = new Select($item['name']);

        if (array_key_exists('options', $item)) {
            $element->setOptions($item['options']);
        }

        if (array_key_exists('value', $item)) {
            $element->setValue($item['value']);
        }

        return $element;
    }

    private function makeSummary(array $item)
    {
        $this->isNameSet($item);
        $element = new Summary($item['name']);

        if (array_key_exists('paths', $item)) {
            $element->setPaths($item['paths']);
        }

        return $element;
    }

    private function makePercentage(array $item)
    {
        $this->isNameSet($item);
        $element = new Percentage($item['name']);

        if (array_key_exists('part', $item) && array_key_exists('whole', $item)) {
            $element->setPaths($item['part'], $item['whole']);
        }

        return $element;
    }

    private function makeTemplate(array $item)
    {
        $this->isNameSet($item);
        $element = new Template($item['name']);
        if (array_key_exists('schema', $item)) {
            $element->setSchema($item['schema']);
        }

        if (array_key_exists('pile', $item)) {
            foreach ($item['pile'] as $accum) {
                $element->addChild($this->makeAccumulation(['name' => $accum['name'], 'pile' => $accum['pile']]));
            }
        }

        return $element;
    }

    private function makeAccumulation(array $item)
    {
        $this->isNameSet($item);

        $accumulation = new Accumulation($item['name']);

        foreach ($item['pile'] as $element) {
            switch ($element['type']) {
                case 'input':
                    $accumulation->addChild($this->makeInput($element));
                    break;
                case 'select':
                    $accumulation->addChild($this->makeSelect($element));
                    break;
                case 'summary':
                    $accumulation->addChild($this->makeSummary($element));
                    break;
                case 'percentage':
                    $accumulation->addChild($this->makePercentage($element));
                    break;
                case 'accumulation':
                    $accumulation->addChild($this->makeAccumulation($element));
                    break;
                case 'template':
                    $accumulation->addChild($this->makeTemplate($element));
                    break;
                default:
                    throw new AppException('Unknow type');
                    break;
            }
        }

        return $accumulation;
    }

    private function isNameSet(array $item)
    {
        if (! array_key_exists('name', $item)) {
            throw new AppException('AccumulationFactory: "name" required');
        }
    }
}
