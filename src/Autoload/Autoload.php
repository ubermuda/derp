<?php

declare(strict_types=1);

namespace Derp\Autoload;

use Derp\Tree\Node;

/**
 * Handles a particular Autoload strategy.
 */
interface Autoload
{
    public function initialize(): void;

    /** @return Node[] */
    public function extractDependencies(): array;
}
