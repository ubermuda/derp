<?php

declare(strict_types=1);

namespace LambdaPackager\Autoload;

use LambdaPackager\Dependency;
use LambdaPackager\Manifest;

class ComposerAutoload implements Autoload
{
    /** @var string */
    private $projectRoot;

    public function __construct(Manifest $manifest)
    {
        $this->projectRoot = $manifest->getProjectRoot();
    }

    public function initialize(): void
    {
        require_once $this->projectRoot.'/vendor/autoload.php';
    }

    /** @return Dependency[] */
    public function extractDependencies(): array
    {
        $root = new Dependency($this->projectRoot.'/vendor/autoload.php');

        foreach (glob($this->projectRoot.'/vendor/composer/*.php') as $filePath) {
            $root->createChild($filePath);
        }

        if (count($deps = $root->findInChildren($this->projectRoot.'/vendor/composer/autoload_files.php')) > 0) {
            $autoloadFiles = $deps[0];

            foreach (array_values(include($autoloadFiles->getFilePath())) as $filePath) {
                $autoloadFiles->createChild($filePath);
            }
        }

        return [$root];
    }
}
