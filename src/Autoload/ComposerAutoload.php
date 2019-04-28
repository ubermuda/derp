<?php

declare(strict_types=1);

namespace LambdaPackager\Autoload;

use LambdaPackager\Dependency\FileDependency;
use LambdaPackager\Tree\Node;
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

    /** @return Node[] */
    public function extractDependencies(): array
    {
        $root = new FileDependency($this->projectRoot.'/vendor/autoload.php');

        foreach (glob($this->projectRoot.'/vendor/composer/*.php') as $filePath) {
            $root->createChild($filePath);
        }

        $autoloadFilesDependencies = $root->filterChildren(function (FileDependency $dependency) {
            return $dependency->getValue() === $this->projectRoot.'/vendor/composer/autoload_files.php';
        });

        if (count($autoloadFilesDependencies) > 0) {
            $autoloadFiles = $autoloadFilesDependencies[0];

            foreach (array_values(include($autoloadFiles->getValue())) as $filePath) {
                $autoloadFiles->createChild($filePath);
            }
        }

        return [$root];
    }
}
