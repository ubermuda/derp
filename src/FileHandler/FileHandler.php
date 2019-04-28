<?php

declare(strict_types=1);

namespace Derp\FileHandler;

use Derp\Dependency\FileDependency;

interface FileHandler
{
    public function supports(FileDependency $dependency): bool;

    /** @return FileDependency[] */
    public function extractDependencies(FileDependency $dependency): array;
}
