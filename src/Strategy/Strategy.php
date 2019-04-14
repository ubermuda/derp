<?php

declare(strict_types=1);

namespace LambdaPackager\Strategy;

use PhpParser\Node;

interface Strategy
{
    public function supports(Node $node): bool;

    /**
     * @return string[]
     */
    public function extractFileNames(Node $node): array;
}