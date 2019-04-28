<?php

declare(strict_types=1);

namespace Derp\Extension;

use Derp\Manifest;
use Derp\ManifestAware;

class Collection implements Extension, ManifestAware
{
    /** @var Extension[] */
    private $extensions = [];

    public function __construct(array $extensions)
    {
        foreach ($extensions as $extension) {
            $this->add($extension);
        }
    }

    public function add(Extension $extension)
    {
        $this->extensions[] = $extension;
    }

    public function beforeCopy(array $files): array
    {
        foreach ($this->extensions as $extension) {
            $files = $extension->beforeCopy($files);
        }

        return $files;
    }

    public function setManifest(Manifest $manifest)
    {
        foreach ($this->extensions as $extension) {
            if ($extension instanceof ManifestAware) {
                $extension->setManifest($manifest);
            }
        }
    }
}
