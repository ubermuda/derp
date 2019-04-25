<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser\Strategy;

use LambdaPackager\Dependency;
use PhpParser\Node;

interface Strategy
{
    public function supports(Node $node): bool;

    /** @return Dependency[] */
    public function extractDependencies(Node $node): array;
}
