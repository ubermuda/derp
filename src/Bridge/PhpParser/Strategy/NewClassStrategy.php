<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser\Strategy;

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

    public function extractFileNames(Node $node): array
    {
        return [$this->getClassFileName($node->class->toString())];
    }
}
