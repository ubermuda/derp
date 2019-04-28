<?php

declare(strict_types=1);

namespace Derp\Bridge\PhpParser\Strategy;

use Derp\Dependency\ClassDependency;
use PhpParser\Node;

class UseStrategy extends ClassStrategy
{
    public function supports(Node $node): bool
    {
        return $node instanceof Node\Stmt\Use_ || $node instanceof Node\Stmt\GroupUse;
    }

    public function extractDependencies(Node $node): array
    {
        if ($node instanceof Node\Stmt\Use_) {
            $className = $node->uses[0]->name->toString();

            if ($this->isProcessable($className)) {
                return [ClassDependency::fromClassName($className)];
            }
        }

        if ($node instanceof Node\Stmt\GroupUse) {
            $prefix = $node->prefix->toString();

            return array_filter(array_map(function (Node\Stmt\UseUse $use) use ($prefix) {
                $className = $prefix.'\\'.$use->name->toString();

                return $this->isProcessable($className)
                    ? ClassDependency::fromClassName($className)
                    : null;
            }, $node->uses));
        }

        return [];
    }
}
