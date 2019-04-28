<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

use LambdaPackager\Tree\Node;

class DefaultFileHandler implements FileHandler
{
    public function extractDependencies(Node $dependency): array
    {
        return [$dependency->getValue()];
    }
}
