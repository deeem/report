<?php
namespace App;

abstract class CompositeDirector
{
    abstract public function build(CompositeBuilder $builder, array $elements);
}
