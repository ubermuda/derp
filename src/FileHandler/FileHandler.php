<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

use LambdaPackager\Tree\Node;

interface FileHandler
{
    /** @return Node[] */
    public function extractDependencies(Node $dependency): array;
}
