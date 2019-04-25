<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser\Strategy;

use LambdaPackager\Manifest;

trait ManifestAwareStrategyTrait
{
    /** @var Manifest */
    private $manifest;

    public function setManifest(Manifest $manifest): void
    {
        $this->manifest = $manifest;
    }

    protected function getManifest(): Manifest
    {
        return $this->manifest;
    }
}
