<?php

declare(strict_types=1);

namespace LambdaPackager\Autoload;

use LambdaPackager\Dependency;

/**
 * Handles a particular Autoload strategy.
 */
interface Autoload
{
    public function initialize(): void;

    /** @return Dependency[] */
    public function extractDependencies(): array;
}
