<?php

declare(strict_types=1);

namespace Derp\Tree;

use RecursiveIteratorIterator;

class RecursiveTreeIterator extends RecursiveIteratorIterator
{
    public function __construct(Node $node)
    {
        parent::__construct(new TreeIterator($node), RecursiveIteratorIterator::SELF_FIRST);
    }
}
