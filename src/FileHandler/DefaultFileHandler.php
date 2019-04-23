<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

class DefaultFileHandler implements FileHandler
{
    public function extractDependencies(string $fileName): array
    {
        return [$fileName];
    }
}
