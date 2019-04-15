<?php

declare(strict_types=1);

namespace LambdaPackager\Autoload;

/**
 * Handles a particular Autoload strategy.
 */
interface Autoload
{
    public function initialize(): void;

    public function extractFileNames(): array;
}
