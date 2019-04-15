<?php

declare(strict_types=1);

namespace LambdaPackager\Autoload;

use LambdaPackager\Manifest;

/**
 * Creates Autoload handlers.
 */
class AutoloadFactory
{
    public function createForManifest(Manifest $manifest): Autoload
    {
        if ($manifest->getAutoload() === 'composer') {
            return new ComposerAutoload($manifest);
        }

        throw new \RuntimeException(sprintf('Unsupported autoload strategy "%s"', $manifest->getAutoload()));
    }
}
