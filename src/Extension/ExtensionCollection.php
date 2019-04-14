<?php

declare(strict_types=1);

namespace LambdaPackager\Extension;

use LambdaPackager\Manifest;

class ExtensionCollection implements Extension, ManifestAwareExtension
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
            if ($extension instanceof ManifestAwareExtension) {
                $extension->setManifest($manifest);
            }
        }
    }
}
