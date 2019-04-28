<?php

declare(strict_types=1);

namespace Derp\FileHandler;

use Derp\Dependency\FileDependency;

class DefaultFileHandler implements FileHandler
{
    public function extractDependencies(FileDependency $dependency): array
    {
        return [];
    }

    public function supports(FileDependency $dependency): bool
    {
        return true;
    }
}
