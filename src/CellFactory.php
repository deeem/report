<?php
namespace App;

class CellFactory
{
    public function create($id, string $type, array $options = []): CellComponent
    {
        switch ($type) {
            case 'input':
                $input = new Input($id);
                if (empty($options)) {
                    return $input;
                }
                if (array_key_exists('value', $options)) {
                    $input->setValue($options['value']);
                }
                return $input;
            case 'select':
                $select = new Select($id);
                if (empty($options)) {
                    return $select;
                }
                if (array_key_exists('options', $options)) {
                    $select->setOptions($options['options']);
                }
                if (array_key_exists('value', $options)) {
                    $select->setValue($options['value']);
                }
                return $select;
            case 'composite':
                return new CellComposite($id);
            case 'summary':
                $summary = new Summary($id);
                if (empty($options)) {
                    return $summary;
                }
                if (array_key_exists('paths', $options)) {
                    $summary->setPaths($options['paths']);
                }
                return $summary;
            case 'percentage':
                $percentage = new Percentage($id);
                if (empty($options)) {
                    return $percentage;
                }
                if (array_key_exists('part', $options) &&
                    array_key_exists('whole', $options)
                ) {
                        $percentage->setPaths($options['part'], $options['whole']);
                }
                return $percentage;
            default:
                throw new CellException('Invalid Cell type');
        }
    }

    /*
     * $template = [
     *  ['id' => '1', 'type' => 'input', 'options' => ['value' => 1]],
     *  ['id' => '2', 'type' => 'select', 'options' => ['options' => ['foo', 'bar'], 'value' => 'foo']],
     * ]
     */
    public function append(CellComposite $collection, array $template = [])
    {
        if (empty($template)) {
            return;
        }

        foreach ($template as $cell) {
            $collection->add($this->create($cell['id'], $cell['type'], $cell['options']));
        }
    }
}
