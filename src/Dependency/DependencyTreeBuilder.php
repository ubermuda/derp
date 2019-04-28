<?php

declare(strict_types=1);

namespace LambdaPackager\Dependency;

use LambdaPackager\Autoload\AutoloadFactory;
use LambdaPackager\FileHandler\FileHandlerRegistry;
use LambdaPackager\Manifest;
use LambdaPackager\Tree\Node;

class DependencyTreeBuilder
{
    private $manifest;

    private $handlerRegistry;

    private $seenFilePaths = [];

    public function __construct(Manifest $manifest)
    {
        $this->manifest = $manifest;
        $this->handlerRegistry = new FileHandlerRegistry($manifest);
    }

    public static function buildFromManifestPath(string $manifestPath): Node
    {
        return (new self(new Manifest($manifestPath)))->build();
    }

    public function build(): Node
    {
        $this->manifest->getAutoloadManager()->initialize();

        $root = new Node($this->manifest->getManifestPath());

        $autoload = (new AutoloadFactory())->createForManifest($this->manifest);
        $root->addAll($autoload->extractDependencies());

        foreach ($this->manifest as $filePath) {
            $this->processDependency($root->createChild($filePath));
        }

        return $root;
    }

    private function processDependency(Node $dependency)
    {
        $this->markAsSeen($dependency);

        $handler = $this->handlerRegistry->getHandler($dependency);
        $children = $handler->extractDependencies($dependency);

        $dependency->addAll($children);

        foreach ($children as $child) {
            if (!$this->isCircularDependency($child)) {
                $this->processDependency($child);
            }
        }
    }

    private function markAsSeen(Node $dependency)
    {
        $this->seenFilePaths[$dependency->getValue()] = true;
    }

    private function isCircularDependency(Node $dependency): bool
    {
        return isset($this->seenFilePaths[$dependency->getValue()]);
    }
}
