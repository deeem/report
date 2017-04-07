<?php
namespace App;

class CellComposite extends CellComponent
{
    protected $children = [];
    protected $templates = [];

    public function getComposite(): CellComponent
    {
        return $this;
    }

    public function addChild(CellComponent $component)
    {
        $this->add($component);
    }

    public function append(array $template)
    {
        $factory = new CellFactory();

        if (empty($template)) {
            throw new CellException('Template is empty');
        }

        foreach ($template as $item) {
            if (isset($item['options']) && is_array($item['options'])) {
                $this->add($factory->create($item['id'], $item['type'], $item['options']));
            } else {
                $this->add($factory->create($item['id'], $item['type']));
            }
        }
    }

    public function registerTemplate($id, array $template)
    {
        $this->templates[$id] = $template;
    }

    public function appendFromTemplate($id)
    {
        $this->append($this->templates[$id]);
    }

    protected function add(CellComponent $component)
    {
        $component->setParent($this);
        $id = $component->getId();

        if ($this->hasChild($id)) {
            $count = array_reduce($this->children, function ($carry, $item) use ($id) {
                return preg_match('/^' . $id . '[0-9]+$/', $item->getId()) ? ++$carry : $carry;
            }, 1);
            $component->setId($id.$count);
        }

        $this->children[] = $component;
    }

    protected function findChild($id)
    {
        for ($i = 0; $i < count($this->children); $i++) {
            if ($this->children[$i]->getId() == $id) {
                return $i;
            }
        }
    }

    public function hasChild($id): bool
    {
        return (null !== $this->findChild($id)) ? true : false;
    }

    public function getChild($id): CellComponent
    {
        if ($this->hasChild($id)) {
            return $this->children[$this->findChild($id)];
        } else {
            throw new CellException('Child not found');
        }
    }

    public function getChildByPath(string $path): Cell
    {
        $route = explode('_', $path);
        $id = array_shift($route);

        if ($this->hasChild($id)) {
            $child = $this->getChild($id);
            if (count($route)) {
                return $child->getChildByPath(implode('_', $route));
            }

            return $child;
        }

        throw new CellException('Child not found');
    }
}
