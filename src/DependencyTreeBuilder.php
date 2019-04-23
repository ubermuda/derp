<?php

declare(strict_types=1);

namespace LambdaPackager;

use LambdaPackager\FileHandler\FileHandlerRegistry;

class DependencyTreeBuilder
{
    private $manifest;

    private $handlerRegistry;

    private $seenFilePaths = [];

    public function __construct(Manifest $manifest)
    {
        $this->manifest = $manifest;
        $this->handlerRegistry = new FileHandlerRegistry();
    }

    public function build(): Dependency
    {
        $this->manifest->getAutoloadManager()->initialize();

        $root = new Dependency($this->manifest->getManifestPath());

        foreach ($this->manifest as $filePath) {
//            echo '>>> Processing manifest dependency '.$filePath.PHP_EOL;
            $this->processDependency($root->createChild($filePath));
        }

        return $root;
    }

    private function processDependency(Dependency $dependency)
    {
        $this->markAsSeen($dependency);

        $handler = $this->handlerRegistry->getHandler($dependency);
        $children = $handler->extractDependencies($dependency);

        $dependency->addAll($children);

        foreach ($children as $child) {
            if (!$this->isCircularDependency($child)) {
//                echo '>>> Processing child dependency '.$child->getFilePath().PHP_EOL;
                $this->processDependency($child);
            }
        }
    }

    private function markAsSeen(Dependency $dependency)
    {
        $this->seenFilePaths[$dependency->getFilePath()] = true;
    }

    private function isCircularDependency(Dependency $dependency): bool
    {
        return isset($this->seenFilePaths[$dependency->getFilePath()]);
    }
}
