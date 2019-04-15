<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

interface FileHandler
{
    public function extractFileNames(string $fileName): array;
}
