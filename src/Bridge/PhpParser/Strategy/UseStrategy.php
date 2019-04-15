<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser\Strategy;

use PhpParser\Node;

class UseStrategy extends ClassStrategy
{

    public function supports(Node $node): bool
    {
        return $node instanceof Node\Stmt\Use_ || $node instanceof Node\Stmt\GroupUse;
    }

    public function extractFileNames(Node $node): array
    {
        if ($node instanceof Node\Stmt\Use_) {
            $className = $node->uses[0]->name->toString();

            if ($this->isProcessable($className)) {
                return [$this->getClassFileName($className)];
            }
        }

        if ($node instanceof Node\Stmt\GroupUse) {
            $prefix = $node->prefix->toString();

            return array_filter(array_map(function (Node\Stmt\UseUse $use) use ($prefix) {
                $className = $prefix . '\\' . $use->name->toString();

                return $this->isProcessable($className)
                    ? $this->getClassFileName($className)
                    : null;
            }, $node->uses));
        }

        return [];
    }
}
