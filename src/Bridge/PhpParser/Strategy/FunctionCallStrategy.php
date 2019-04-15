<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser\Strategy;

use PhpParser\Node;
use ReflectionFunction;

class FunctionCallStrategy implements Strategy
{
    public function supports(Node $node): bool
    {
        return
               $node instanceof Node\Name
            && function_exists($node->toString())
            && false === (new ReflectionFunction($node->toString()))->isInternal();
    }

    public function extractFileNames(Node $node): array
    {
        return [(new ReflectionFunction($node->toString()))->getFileName()];
    }
}
