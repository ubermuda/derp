<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

use LambdaPackager\Dependency;

interface FileHandler
{
    /** @return Dependency[] */
    public function extractDependencies(string $fileName): array;
}
