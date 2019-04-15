<?php

declare(strict_types=1);

namespace LambdaPackager\Extension;

use LambdaPackager\Manifest;

trait ManifestAwareExtensionTrait
{
    /** @var Manifest */
    private $manifest;

    public function setManifest(Manifest $manifest)
    {
        $this->manifest = $manifest;
    }
}
