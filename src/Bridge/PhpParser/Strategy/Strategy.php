<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser\Strategy;

use PhpParser\Node;

interface Strategy
{
    public function supports(Node $node): bool;

    /** @return Node[] */
    public function extractDependencies(Node $node): array;
}
