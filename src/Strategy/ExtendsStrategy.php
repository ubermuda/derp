<?php

declare(strict_types=1);

namespace LambdaPackager\Strategy;

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

    public function extractFileNames(Node $node): array
    {
        return [$this->getClassFileName($node->extends->toString())];
    }
}
