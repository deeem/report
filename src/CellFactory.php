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
