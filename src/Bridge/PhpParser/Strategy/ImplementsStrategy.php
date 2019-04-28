<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser\Strategy;

use LambdaPackager\Dependency\ClassDependency;
use PhpParser\Node;

class ImplementsStrategy extends ClassStrategy
{
    public function supports(Node $node): bool
    {
        return
               $node instanceof Node\Stmt\Class_
            && count($node->implements) > 0;
    }

    public function extractDependencies(Node $node): array
    {
        return array_filter(array_map(function (Node\Name\FullyQualified $node) {
            return $this->isProcessable($node->toString())
                ? ClassDependency::fromClassName($node->toString())
                : null;
        }, $node->implements));
    }
}
