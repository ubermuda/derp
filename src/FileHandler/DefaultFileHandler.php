<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

use LambdaPackager\Dependency;

class DefaultFileHandler implements FileHandler
{
    public function extractDependencies(Dependency $dependency): array
    {
        return [$dependency->getFilePath()];
    }
}
