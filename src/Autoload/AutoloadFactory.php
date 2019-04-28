<?php

declare(strict_types=1);

namespace Derp\Autoload;

use Derp\Manifest;

/**
 * Creates Autoload handlers.
 */
class AutoloadFactory
{
    public function createForManifest(Manifest $manifest): Autoload
    {
        if ('composer' === $manifest->getAutoload()) {
            return new ComposerAutoload($manifest);
        }

        throw new \RuntimeException(sprintf('Unsupported autoload strategy "%s"', $manifest->getAutoload()));
    }
}
