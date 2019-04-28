<?php

declare(strict_types=1);

namespace Derp;

interface ManifestAware
{
    public function setManifest(Manifest $manifest);
}
