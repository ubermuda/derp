<?php

declare(strict_types=1);

namespace Derp\Bridge\PhpParser\Strategy;

use Derp\Dependency\ClassDependency;
use PhpParser\Node;

class NewClassStrategy extends ClassStrategy
{
    public function supports(Node $node): bool
    {
        return
               $node instanceof Node\Expr\New_
            && $node->class instanceof Node\Name\FullyQualified
            && $this->isProcessable($node->class->toString());
    }

    public function extractDependencies(Node $node): array
    {
        return [ClassDependency::fromClassName($node->class->toString())];
    }
}
