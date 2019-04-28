<?php

declare(strict_types=1);

namespace Derp\Bridge\PhpParser\Strategy;

use Derp\Dependency\FileDependency;
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

    /**
     * @todo make a FunctionDependency object
     */
    public function extractDependencies(Node $node): array
    {
        $filePath = (new ReflectionFunction($node->toString()))->getFileName();

        return [new FileDependency($filePath)];
    }
}
