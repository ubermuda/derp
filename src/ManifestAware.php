<?php

declare(strict_types=1);

namespace LambdaPackager;

interface ManifestAware
{
    public function setManifest(Manifest $manifest);
}
