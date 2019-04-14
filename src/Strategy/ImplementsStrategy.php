<?php

declare(strict_types=1);

namespace LambdaPackager\Strategy;

use PhpParser\Node;

class ImplementsStrategy extends ClassStrategy
{
    public function supports(Node $node): bool
    {
        return
            $node instanceof Node\Stmt\Class_
            && count($node->implements) > 0;
    }

    public function extractFileNames(Node $node): array
    {
        return array_filter(array_map(function(Node\Name\FullyQualified $node) {
            return $this->isProcessable($node->toString())
                ? $this->getClassFileName($node->toString())
                : null;
        }, $node->implements));
    }
}
