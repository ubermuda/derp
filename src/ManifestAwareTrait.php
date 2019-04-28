<?php

declare(strict_types=1);

namespace Derp;

trait ManifestAwareTrait
{
    /** @var Manifest */
    private $manifest;

    public function setManifest(Manifest $manifest)
    {
        $this->manifest = $manifest;
    }
}
