<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser\Strategy;

use LambdaPackager\Manifest;

interface ManifestAwareStrategy
{
    public function setManifest(Manifest $manifest): void;
}
