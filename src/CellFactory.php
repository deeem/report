<?php
namespace App;

class CellFactory
{
    public function create($id, $type): CellComponent
    {
        switch ($type) {
            case 'input':
                return new Input($id);
            case 'select':
                return new Select($id);
            case 'composite':
                return new CellComposite($id);
            default:
                throw new CellException('Invalid Cell type');
        }
    }
}
