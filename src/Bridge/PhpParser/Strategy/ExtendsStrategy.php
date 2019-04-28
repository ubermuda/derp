<?php

declare(strict_types=1);

namespace Derp\Bridge\PhpParser\Strategy;

use Derp\Dependency\ClassDependency;
use PhpParser\Node;

class ExtendsStrategy extends ClassStrategy
{
    public function supports(Node $node): bool
    {
        return
               $node instanceof Node\Stmt\Class_
            && $node->extends instanceof Node\Name\FullyQualified
            && $this->isProcessable($node->extends->toString());
    }

    public function extractDependencies(Node $node): array
    {
        return [ClassDependency::fromClassName($node->extends->toString())];
    }
}
