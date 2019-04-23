<?php

declare(strict_types=1);

namespace LambdaPackager;

use ArrayIterator;
use RecursiveIterator;
use function count;

class DependencyIterator extends ArrayIterator implements RecursiveIterator
{
    private $dependency;

    public function __construct(Dependency $dependency)
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
        return new self($this->current());
    }
}
