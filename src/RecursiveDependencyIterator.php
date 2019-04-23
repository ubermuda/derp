<?php

declare(strict_types=1);

namespace LambdaPackager;

use RecursiveIteratorIterator;

class RecursiveDependencyIterator extends RecursiveIteratorIterator
{
    public function __construct(Dependency $dependency)
    {
        parent::__construct(new DependencyIterator($dependency), RecursiveIteratorIterator::SELF_FIRST);
    }
}
