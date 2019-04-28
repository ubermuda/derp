<?php

declare(strict_types=1);

namespace Derp\Tree;

use ArrayIterator;
use RecursiveIterator;
use function count;

class TreeIterator extends ArrayIterator implements RecursiveIterator
{
    private $dependency;

    public function __construct(Node $dependency)
    {
        $this->dependency = $dependency;

        parent::__construct($dependency->all());
    }

    public function hasChildren(): bool
    {
        return count($this->current()->all()) > 0;
    }

    public function getChildren()
    {
        return new static($this->current());
    }
}
