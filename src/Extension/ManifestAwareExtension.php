<?php

declare(strict_types=1);

namespace LambdaPackager\Extension;

use LambdaPackager\Manifest;

interface ManifestAwareExtension
{
    public function setManifest(Manifest $manifest);
}
