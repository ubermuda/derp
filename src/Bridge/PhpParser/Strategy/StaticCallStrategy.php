<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser\Strategy;

use LambdaPackager\Dependency\ClassDependency;
use PhpParser\Node;

class StaticCallStrategy extends ClassStrategy
{
    public function supports(Node $node): bool
    {
        return
               $node instanceof Node\Expr\StaticCall
            && $node->class instanceof Node\Name\FullyQualified
            && $this->isProcessable($node->class->toString());
    }

    public function extractDependencies(Node $node): array
    {
        return [ClassDependency::fromClassName($node->class->toString())];
    }
}
